<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index(Request $request)
    {
        $cart = Cart::with(['items.product.brand'])->firstOrCreate(['user_id' => Auth::id()]);
        
        $subtotal = $cart->subtotal();
        $discount = 0;
        $couponCode = session('coupon_code');

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid()) {
                if ($coupon->discount_type === Coupon::TYPE_PERCENTAGE) {
                    $discount = $subtotal * ($coupon->discount_value / 100);
                } else {
                    $discount = $coupon->discount_value;
                }
            }
        }

        $shipping = $subtotal >= 50 ? 0 : 5.00;
        $total = $subtotal - $discount + $shipping;

        if ($request->wantsJson() || $request->is('api/*')) {
            $cart->load('items.product.images');
            return new \App\Http\Resources\CartResource($cart);
        }

        return view('cart.index', compact('cart', 'subtotal', 'discount', 'shipping', 'total', 'couponCode'));
    }

    /**
     * Add a die-cast car to the cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Stock validation
        if ($product->stock_quantity < $request->quantity) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => "Only {$product->stock_quantity} left in stock!"], 400);
            }
            return back()->with('error', "Only {$product->stock_quantity} left in stock!");
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $newQuantity = $item->quantity + $request->quantity;
            if ($product->stock_quantity < $newQuantity) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json(['message' => "Cannot add more. Max stock reached."], 400);
                }
                return back()->with('error', "Cannot add more. Max stock reached.");
            }
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price_at_time' => $product->price,
            ]);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return $this->index($request);
        }

        return redirect()->route('cart.index')->with('success', 'Car added to your garage!');
    }

    /**
     * Update item quantity.
     */
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $item = CartItem::whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))->findOrFail($id);

        if ($item->product->stock_quantity < $request->quantity) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => "Insufficient stock."], 400);
            }
            return back()->with('error', "Insufficient stock.");
        }

        $item->update(['quantity' => $request->quantity]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return $this->index($request);
        }

        return back()->with('success', 'Quantity updated.');
    }

    /**
     * Remove item from cart.
     */
    public function destroy(Request $request, $id)
    {
        $item = CartItem::whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))->findOrFail($id);
        $item->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Item removed from cart']);
        }

        return back()->with('success', 'Item removed from cart.');
    }
}
