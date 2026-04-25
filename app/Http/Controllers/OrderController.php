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
            
        return view('orders.index', compact('orders'));
    }

    /**
     * Display specific order details.
     */
    public function show($id)
    {
        $order = Order::with(['items.product', 'disputes'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
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
    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty.');
        }

        return DB::transaction(function () use ($request, $cart) {
            // Calculate totals
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
                    $coupon->increment('times_used');
                }
            }

            $shipping = $subtotal >= 50 ? 0 : 5.00;
            $total = $subtotal - $discount + $shipping;

            // Generate BSG Order Number: BSG-{YYYYMMDD}-{5-digit}
            $orderNumber = 'BSG-' . date('Ymd') . '-' . str_pad((string) (Order::whereDate('created_at', now())->count() + 1), 5, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'status' => Order::STATUS_PENDING,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_fee' => $shipping,
                'total_amount' => $total,
                'coupon_code' => $couponCode,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'extra_packaging_requested' => $request->has('extra_packaging'),
                'notes' => $request->notes,
                'placed_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_brand' => $item->product->brand->name ?? 'Unknown',
                    'product_price' => $item->price_at_time,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->price_at_time * $item->quantity,
                    'product_image' => $item->product->main_image,
                ]);

                // Deduct stock
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            // Clear cart
            $cart->items()->delete();
            session()->forget('coupon_code');

            return redirect()->route('orders.confirmation', $order->id)->with('success', 'Order placed successfully!');
        });
    }
}
