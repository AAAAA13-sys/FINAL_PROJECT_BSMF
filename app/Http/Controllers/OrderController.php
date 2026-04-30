<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class OrderController extends Controller
{
    /**
     * Display customer order history.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product'])
            ->latest()
            ->paginate(10);
            
        if (request()->wantsJson() || request()->is('api/*')) {
            return \App\Http\Resources\OrderResource::collection($orders);
        }
            
        return view('orders.index', compact('orders'));
    }

    /**
     * Display specific order details.
     */
    public function show($id)
    {
        $order = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        if (request()->wantsJson() || request()->is('api/*')) {
            return new \App\Http\Resources\OrderResource($order);
        }
            
        return view('orders.show', compact('order'));
    }

    /**
     * Show checkout page with collector options.
     */
    public function checkout()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cart->subtotal();
        $discount = 0;
        $couponCode = session('coupon_code');

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid()) {
                // Simplified discount calculation
                if ($coupon->discount_type === Coupon::TYPE_PERCENTAGE) {
                    $discount = $subtotal * ($coupon->discount_value / 100);
                } else {
                    $discount = $coupon->discount_value;
                }
            }
        }

        $shipping = $subtotal >= 50 ? 0 : 5.00; // Sample shipping logic
        $total = $subtotal - $discount + $shipping;

        return view('checkout.index', compact('cart', 'subtotal', 'discount', 'shipping', 'total', 'couponCode'));
    }

    /**
     * Process the die-cast order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Cart is empty'], 400);
            }
            return redirect()->route('products.index')->with('error', 'Your cart is empty.');
        }

        try {
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

            // Call the Stored Procedure
            DB::statement("SET @p_order_id = 0;");
            DB::statement("CALL sp_ProcessOrder(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @p_order_id)", [
                $user->id,
                $request->shipping_address,
                $request->payment_method,
                $request->customer_name ?? $user->name,
                $request->customer_email ?? $user->email,
                $request->customer_phone,
                $couponCode,
                $discount,
                $shipping,
                $request->notes,
                (int) $request->has('extra_packaging')
            ]);

            $result = DB::selectOne("SELECT @p_order_id as id");
            $orderId = $result->id;

            if (!$orderId) {
                throw new \Exception("Order processing failed at database level.");
            }

            session()->forget('coupon_code');

            if ($request->wantsJson() || $request->is('api/*')) {
                $order = Order::find($orderId);
                return (new \App\Http\Resources\OrderResource($order))->response()->setStatusCode(201);
            }

            return redirect()->route('orders.confirmation', $orderId)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Apply a promotional code to the session.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return back()->with('error', 'Invalid or expired promotional code.');
        }

        // Enforce 1-time use per user for specific coupons like WELCOME10
        if ($coupon->isUsedByUser(Auth::id())) {
            return back()->with('error', 'You have already used this promotional code on a previous acquisition.');
        }

        session(['coupon_code' => $coupon->code]);

        return back()->with('success', 'Promo code applied to your order!');
    }

    /**
     * Remove the promotional code from the session.
     */
    public function removeCoupon()
    {
        session()->forget('coupon_code');
        return back()->with('success', 'Promo code removed.');
    }

    /**
     * Display order confirmation page.
     */
    public function confirmation($id)
    {
        $order = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.confirmation', compact('order'));
    }
}
