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
        // Check if the order belongs to the user
        $order = Order::with(['items.product'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        // If not authorized, redirect to their most recent order instead of showing an error
        if (!$order) {
            $latestOrder = Order::where('user_id', Auth::id())->latest()->first();
            if ($latestOrder) {
                return redirect()->route('orders.show', $latestOrder->id)
                    ->with('warning', 'You do not have access to that acquisition. Showing your most recent one.');
            }
            return redirect()->route('orders.index')->with('error', 'Unauthorized access.');
        }
            
        if (request()->wantsJson() || request()->is('api/*')) {
            return new \App\Http\Resources\OrderResource($order);
        }
            
        return view('orders.show', compact('order'));
    }

    /**
     * Cancel a pending order (Collector-side).
     */
    public function cancel(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        
        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Only pending orders can be cancelled from your garage.');
        }

        try {
            DB::statement('CALL sp_CancelOrder(?, ?, ?)', [
                $id, 
                Auth::id(), 
                $request->cancellation_reason ?? 'No reason provided'
            ]);
            return redirect()->route('orders.index')->with('success', 'Order cancelled successfully. Items returned to stock.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show checkout page with collector options.
     */
    public function checkout(Request $request)
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty.');
        }

        $selectedItemIds = $request->input('selected_items', session('selected_cart_items', []));
        $items = $cart->items->whereIn('id', $selectedItemIds);
        
        // If still empty (session/request lost), process whole cart
        if ($items->isEmpty()) {
            $items = $cart->items;
            $selectedItemIds = $items->pluck('id')->toArray();
        }

        session(['selected_cart_items' => $selectedItemIds]);

        $subtotal = $items->sum(fn($item) => ($item->price_at_time ?? $item->product->price) * $item->quantity);
        
        $discount = 0;
        $couponCode = session('coupon_code');

        if ($couponCode) {
            $coupon = Coupon::where('coupon_code', $couponCode)->first();
            if ($coupon && $coupon->isValid()) {
                if ($coupon->discount_type === Coupon::TYPE_PERCENTAGE) {
                    $discount = $subtotal * ($coupon->discount_value / 100);
                } elseif ($coupon->discount_type === Coupon::TYPE_FIXED) {
                    $discount = $coupon->discount_value;
                } else {
                    $discount = 0;
                }
            }
        }

        $regions = \App\Services\ShippingService::getRegions();
        $regionalCities = \App\Services\ShippingService::getRegionalCities();
        $mmDistances = \App\Services\ShippingService::getMetroManilaDistances();
        $shipping = 0; 
        $total = $subtotal - $discount;

        return view('checkout.index', compact('cart', 'items', 'subtotal', 'discount', 'shipping', 'total', 'couponCode', 'mmDistances', 'regions', 'regionalCities'));
    }

    /**
     * Process the die-cast order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:Cash on Delivery',
            'shipping_region' => 'required|string',
            'shipping_city' => 'required|string',
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
            $regions = \App\Services\ShippingService::getRegions();
            
            // Calculate components using selected items from request/session
            $selectedItemIds = $request->input('selected_items', session('selected_cart_items', []));
            $items = $cart->items->whereIn('id', $selectedItemIds);
            
            // If still empty, process whole cart
            if ($items->isEmpty()) {
                $items = $cart->items;
                $selectedItemIds = $items->pluck('id')->toArray();
            }

            $subtotal = $items->sum(fn($item) => ($item->price_at_time ?? $item->product->price) * $item->quantity);
            
            $discount = 0;
            $couponCode = session('coupon_code');

            if ($couponCode) {
                $coupon = Coupon::where('coupon_code', $couponCode)->first();
                if ($coupon && $coupon->isValid()) {
                    if ($coupon->discount_type === Coupon::TYPE_PERCENTAGE) {
                        $discount = $subtotal * ($coupon->discount_value / 100);
                    } elseif ($coupon->discount_type === Coupon::TYPE_FIXED) {
                        $discount = $coupon->discount_value;
                    } else {
                        // Free shipping or other types: no monetary discount on items
                        $discount = 0;
                    }
                }
            }

            // Calculate Shipping
            $region = $request->shipping_region;
            $city = $request->shipping_city;
            $distance = 0;
            
            if ($region === 'NCR') {
                $distances = \App\Services\ShippingService::getMetroManilaDistances();
                $distance = $distances[$city] ?? 0;
            }
            
            $shipping = \App\Services\ShippingService::calculate($region, (float)$distance);

            // Combine components into a full shipping address
            $fullAddress = $request->shipping_address;
            if ($request->shipping_city) {
                $fullAddress .= ", " . $request->shipping_city;
            }
            $fullAddress .= ", " . ($regions[$request->shipping_region] ?? $request->shipping_region);

            // Call the Stored Procedure with isolation logic
            $selectedItemIds = session('selected_cart_items', []);
            
            // 1. Remove unselected items temporarily (This is auto-committed)
            $unselectedItems = [];
            if (!empty($selectedItemIds)) {
                $unselectedItems = DB::table('cart_items')
                    ->where('cart_id', $cart->id)
                    ->whereNotIn('id', $selectedItemIds)
                    ->get()
                    ->map(function($item) {
                        return (array)$item;
                    })->toArray();

                DB::table('cart_items')
                    ->where('cart_id', $cart->id)
                    ->whereNotIn('id', $selectedItemIds)
                    ->delete();
            }

            try {
                // 2. Call the Stored Procedure (It handles its own transaction)
                DB::statement("SET @p_order_id = 0;");
                DB::statement("CALL sp_ProcessOrder(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @p_order_id)", [
                    $user->id,
                    $fullAddress,
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
            } finally {
                // 3. Restore the unselected items (Always run this, even if SP fails)
                if (!empty($unselectedItems)) {
                    foreach ($unselectedItems as $item) {
                        unset($item['id']); 
                        DB::table('cart_items')->insert($item);
                    }
                }
            }

            if (!$orderId) {
                throw new \Exception("Order processing failed at database level.");
            }

            session()->forget('coupon_code');

            if ($request->wantsJson() || $request->is('api/*')) {
                $order = Order::find($orderId);
                return (new \App\Http\Resources\OrderResource($order))->response()->setStatusCode(201);
            }

            $order = Order::find($orderId);
            $order->update(['courier_name' => \App\Services\ShippingService::getCarrier($region)]);
            
            defer(fn() => \Illuminate\Support\Facades\Mail::to($order->customer_email)
                ->send(new \App\Mail\OrderConfirmation($order)));

            return redirect()->route('orders.confirmation', $orderId)->with('success', 'Order placed successfully! A confirmation receipt has been sent to your email.');
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

        $coupon = Coupon::where('coupon_code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return back()->with('error', 'Invalid or expired promotional code.');
        }

        // Enforce 1-time use per user for specific coupons like WELCOME10
        if ($coupon->isUsedByUser(Auth::id())) {
            return back()->with('error', 'You have already used this promotional code on a previous acquisition.');
        }

        session(['coupon_code' => $coupon->coupon_code]);

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
        // Check if the order belongs to the user
        $order = Order::with(['items.product'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        // If not authorized, redirect to their most recent confirmation instead of showing an error
        if (!$order) {
            $latestOrder = Order::where('user_id', Auth::id())->latest()->first();
            if ($latestOrder) {
                return redirect()->route('orders.confirmation', $latestOrder->id)
                    ->with('warning', 'You do not have access to that acquisition. Showing your most recent one.');
            }
            return redirect()->route('orders.index')->with('error', 'Unauthorized access.');
        }

        return view('orders.confirmation', compact('order'));
    }
}
