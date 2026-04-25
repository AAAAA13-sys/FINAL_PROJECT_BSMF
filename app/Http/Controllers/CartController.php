<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = Cart::with(['items.product.brand'])->firstOrCreate(['user_id' => Auth::id()]);
        return view('cart.index', compact('cart'));
    }

    /**
     * Add a die-cast car to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Stock validation
        if ($product->stock_quantity < $request->quantity) {
            return back()->with('error', "Only {$product->stock_quantity} left in stock!");
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $newQuantity = $item->quantity + $request->quantity;
            if ($product->stock_quantity < $newQuantity) {
                return back()->with('error', "Cannot add more. Max stock reached.");
            }
            $item->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price_at_time' => $product->price,
            ]);
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
            return back()->with('error', "Insufficient stock.");
        }

        $item->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Quantity updated.');
    }

    /**
     * Remove item from cart.
     */
    public function remove($id)
    {
        $item = CartItem::whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))->findOrFail($id);
        $item->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
