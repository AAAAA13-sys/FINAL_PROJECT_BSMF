<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Dispute;
use App\Models\Brand;
use App\Models\Scale;
use App\Models\Series;
use App\Models\Coupon;
use App\Models\DisputeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class AdminController extends Controller
{
    /**
     * Display the admin dashboard with collector-specific widgets.
     */
    public function dashboard()
    {
        $totalSales = Order::where('status', '!=', Order::STATUS_CANCELLED)->sum('total_amount');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $recentOrders = Order::with('user')->latest()->limit(5)->get();
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->limit(5)->get();
        $totalCustomers = User::where('is_admin', false)->count();
        $recentDisputes = Dispute::where('status', 'open')->limit(5)->get();

        // Dynamic Sales for Chart
        $filter = request('revenue_filter', 'month');
        $query = Order::where('status', '!=', Order::STATUS_CANCELLED);

        if ($filter === 'week') {
            $salesData = $query->where('created_at', '>=', now()->subDays(7))
                ->select(DB::raw('SUM(total_amount) as total'), DB::raw("DATE_FORMAT(created_at, '%a') as label"))
                ->groupBy('label')
                ->orderBy('created_at')
                ->get();
        } elseif ($filter === 'year') {
            $salesData = $query->where('created_at', '>=', now()->subYears(5))
                ->select(DB::raw('SUM(total_amount) as total'), DB::raw("DATE_FORMAT(created_at, '%Y') as label"))
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        } else {
            $salesData = $query->where('created_at', '>=', now()->subMonths(6))
                ->select(DB::raw('SUM(total_amount) as total'), DB::raw("DATE_FORMAT(created_at, '%m') as month_num"))
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->get()
                ->map(function($item) {
                    $item->label = date("M", mktime(0, 0, 0, (int)$item->month_num, 1));
                    return $item;
                });
        }

        return view('admin.dashboard', compact(
            'totalSales', 
            'totalOrders', 
            'totalProducts', 
            'totalCustomers', 
            'recentOrders', 
            'lowStockProducts',
            'recentDisputes',
            'salesData',
            'filter'
        ));
    }

    /**
     * Display product list for management.
     */
    public function products()
    {
        $products = Product::with(['brand', 'scale', 'series'])->latest()->paginate(10);
        $brands = Brand::all();
        $scales = Scale::all();
        $series = Series::all();
        
        return view('admin.products', compact('products', 'brands', 'scales', 'series'));
    }

    /**
     * Store a new die-cast product.
     */
    public function productStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'casting_name' => 'required|string|max:200',
            'brand_id' => 'required|exists:brands,id',
            'scale_id' => 'required|exists:scales,id',
            'series_id' => 'nullable|exists:series,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'year' => 'nullable|integer',
            'description' => 'nullable|string',
            // ... other fields
        ]);

        $validated['slug'] = Str::slug($request->name . '-' . Str::random(5));
        
        Product::create($validated);

        return back()->with('success', 'Product added successfully!');
    }

    /**
     * Show order management list.
     */
    public function orders()
    {
        $orders = Order::with('user')->latest()->paginate(20);
        return view('admin.orders', compact('orders'));
    }

    /**
     * Show detailed order view for admin.
     */
    public function orderShow($id)
    {
        $order = Order::with(['items.product', 'user', 'disputes.messages'])->findOrFail($id);
        return view('admin.order-details', compact('order'));
    }

    /**
     * Update order status and record timestamps.
     */
    public function orderUpdateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        $order = Order::findOrFail($id);
        
        $status = $request->status;
        $updateData = ['status' => $status];

        if ($status === Order::STATUS_PROCESSING) $updateData['processed_at'] = now();
        if ($status === Order::STATUS_OUT_FOR_DELIVERY) $updateData['out_for_delivery_at'] = now();
        if ($status === Order::STATUS_DELIVERED) $updateData['delivered_at'] = now();

        $order->update($updateData);
        
        return back()->with('success', 'Order status updated!');
    }

    /**
     * Dispute management.
     */
    public function disputes()
    {
        $disputes = Dispute::with(['order', 'user', 'messages.user'])->latest()->paginate(20);
        return view('admin.disputes', compact('disputes'));
    }

    public function disputeUpdateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        $dispute = Dispute::findOrFail($id);
        
        $updateData = ['status' => $request->status];

        $dispute->update($updateData);
        return back()->with('success', 'Dispute status updated!');
    }

    /**
     * Brand management (Deliverable Phase 2).
     */
    public function brands()
    {
        $brands = Brand::withCount('products')->get();
        return view('admin.brands', compact('brands'));
    }

    public function brandStore(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:brands,name']);
        Brand::create(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Brand created successfully!');
    }
    /**
     * Show the product edit form.
     */
    public function productEdit($id)
    {
        $product = Product::findOrFail($id);
        $brands = Brand::all();
        $scales = Scale::all();
        $series = Series::all();
        return view('admin.edit-product', compact('product', 'brands', 'scales', 'series'));
    }

    /**
     * Update an existing product.
     */
    public function productUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'casting_name' => 'required|string|max:200',
            'brand_id' => 'required|exists:brands,id',
            'scale_id' => 'required|exists:scales,id',
            'series_id' => 'nullable|exists:series,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products')->with('success', 'Model updated in the garage!');
    }

    /**
     * Remove a product.
     */
    public function productDestroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return back()->with('success', 'Model ejected from the garage.');
    }

    /**
     * User management list.
     */
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Remove a user.
     */
    public function userDestroy($id)
    {
        if ($id == auth()->id()) {
            return back()->with('error', 'You cannot eject yourself!');
        }
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Collector ejected from the garage.');
    }


    /**
     * Coupon management.
     */
    public function coupons()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons', compact('coupons'));
    }

    public function couponStore(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'name' => 'nullable|string',
            'discount_type' => 'required|string',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date',
        ]);

        $validated['name'] = $validated['name'] ?? $validated['code'];
        Coupon::create($validated);
        return back()->with('success', 'Promo code activated!');
    }

    public function couponDestroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return back()->with('success', 'Promo code deactivated.');
    }

    /**
     * Admin reply to dispute.
     */
    public function disputeReply(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);
        
        DisputeMessage::create([
            'dispute_id' => $id,
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        return back()->with('success', 'Reply dispatched.');
    }
}
