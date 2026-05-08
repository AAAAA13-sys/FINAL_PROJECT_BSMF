<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Brand;
use App\Models\Scale;
use App\Models\Series;
use App\Models\Coupon;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\LogsActions;

final class AdminController extends Controller
{
    use LogsActions;

    /**
     * Display the admin dashboard with collector-specific widgets.
     */
    public function dashboard()
    {
        // Use the new stored procedure for consolidated stats retrieval
        DB::statement('CALL sp_GetAdminDashboardStats(@total_rev, @total_ord, @total_prod, @total_cust, @low_stock, @pending)');
        $stats = DB::selectOne('SELECT @total_rev as totalSales, @total_ord as totalOrders, @total_prod as totalProducts, @total_cust as totalCustomers, @low_stock as lowStockCount, @pending as pendingOrdersCount');

        $totalSales = $stats->totalSales;
        $totalOrders = $stats->totalOrders;
        $totalProducts = $stats->totalProducts;
        $totalCustomers = $stats->totalCustomers;
        
        $recentOrders = Order::with('user')->latest()->limit(5)->get();
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->limit(5)->get();

        // Dynamic Sales for Chart
        $filter = request('revenue_filter', 'week');
        $query = Order::where('status', '!=', Order::STATUS_CANCELLED);

        if ($filter === 'week') {
            // Start from Monday of the current week
            $startOfWeek = now()->startOfWeek();
            $data = $query->where('created_at', '>=', $startOfWeek)
                ->select(DB::raw('SUM(total_amount) as total'), DB::raw("DATE_FORMAT(created_at, '%a') as label"))
                ->groupBy('label')
                ->get()
                ->pluck('total', 'label');

            $salesData = collect();
            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            foreach ($days as $day) {
                $salesData->push((object)[
                    'label' => $day,
                    'total' => $data[$day] ?? 0
                ]);
            }
        } else {
            $data = $query->where('created_at', '>=', now()->subMonths(5))
                ->select(DB::raw('SUM(total_amount) as total'), DB::raw("DATE_FORMAT(created_at, '%m') as month_num"))
                ->groupBy('month_num')
                ->get()
                ->pluck('total', 'month_num');

            $salesData = collect();
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthNum = $month->format('m');
                $salesData->push((object)[
                    'label' => $month->format('M'),
                    'total' => $data[$monthNum] ?? 0
                ]);
            }
        }

        return view('admin.dashboard', compact(
            'totalSales', 
            'totalOrders', 
            'totalProducts', 
            'totalCustomers', 
            'recentOrders', 
            'lowStockProducts',
            'salesData',
            'filter'
        ));
    }

    /**
     * Display product list for management.
     */
    public function products()
    {
        $products = Product::with(['brand', 'scale', 'series', 'gallery'])->latest()->paginate(10);
        $brands = Brand::all();
        $scales = Scale::all();
        $series = Series::all();
        
        return view('admin.products', compact('products', 'brands', 'scales', 'series'));
    }

    public function productStore(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Staff cannot add new products.');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'casting_name' => 'required|string|max:200',
            'brand_id' => 'required|exists:brands,id',
            'scale_id' => 'required|exists:scales,id',
            'series_id' => 'nullable|exists:series,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|max:2048',
            'additional_images' => 'nullable|array|max:4',
            'additional_images.*' => 'image|max:2048',
        ]);

        $validated['slug'] = Str::slug($request->name . '-' . Str::random(5));

        // Extract files before creation to avoid JSON storage issues
        $mainImage = $request->file('main_image');
        $secondaryImages = $request->file('additional_images');
        
        // Remove from validated array
        unset($validated['main_image'], $validated['additional_images']);

        // Handle Main Image
        if ($mainImage) {
            $path = $mainImage->store('products/main', 'public');
            $validated['main_image'] = 'storage/' . $path;
        }
        
        $product = Product::create($validated);


        // Handle Secondary Images (Gallery Table)
        if ($secondaryImages) {
            foreach ($secondaryImages as $image) {
                $path = $image->store('products/gallery', 'public');
                $product->gallery()->create([
                    'image_path' => 'storage/' . $path,
                    'type' => 'gallery'
                ]);
            }
        }

        return back()->with('success', 'Product added to the garage!');
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
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        return view('admin.order-details', compact('order'));
    }

    /**
     * Update order status with strict sequential enforcement.
     */
    public function orderUpdateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string', 'reason' => 'nullable|string']);
        $order = Order::findOrFail($id);
        $newStatus = $request->status;
        $oldStatus = $order->status;

        // If cancelling, refer to the dedicated cancellation route
        if ($newStatus === Order::STATUS_CANCELLED) {
            return back()->with('error', 'Please use the dedicated Cancellation workflow to provide a reason for the collector.');
        }


        // Define strict sequence for other transitions
        $allowedTransitions = [
            Order::STATUS_PENDING => [Order::STATUS_PROCESSING, Order::STATUS_CANCELLED],
            Order::STATUS_PROCESSING => [Order::STATUS_SHIPPED, Order::STATUS_CANCELLED],
            Order::STATUS_SHIPPED => [Order::STATUS_DELIVERED, Order::STATUS_CANCELLED],
            Order::STATUS_DELIVERED => [Order::STATUS_REFUNDED],
            Order::STATUS_CANCELLED => [], // Dead end
            Order::STATUS_REFUNDED => [], // Dead end
        ];

        // 1. Skip check (No skipping gears)
        if ($oldStatus !== $newStatus && !in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
            $msg = "Illegal Shift! Orders in '{$oldStatus}' status can only move to: " . implode(', ', $allowedTransitions[$oldStatus] ?? ['None']);
            return back()->with('error', $msg);
        }

        // 2. Prepare Update Data
        $updateData = ['status' => $newStatus];
        if ($newStatus === Order::STATUS_PROCESSING) $updateData['processed_at'] = now();
        
        if ($newStatus === Order::STATUS_SHIPPED) {
            $updateData['shipped_at'] = now();
            $updateData['courier_name'] = $request->courier_name;
            $updateData['tracking_number'] = $request->tracking_number;
            $updateData['tracking_link'] = $request->tracking_link;
        }
        
        if ($newStatus === Order::STATUS_DELIVERED) $updateData['delivered_at'] = now();

        // 3. Save
        $order->update($updateData);

        // 4. Send Email if Shipped
        if ($newStatus === Order::STATUS_SHIPPED) {
            try {
                defer(fn() => \Illuminate\Support\Facades\Mail::to($order->customer_email)
                    ->send(new \App\Mail\OrderShipped($order)));
            } catch (\Exception $e) {
                // Log the error but don't stop the flow
                \Illuminate\Support\Facades\Log::error("Failed to send shipping email: " . $e->getMessage());
            }
        }

        return back()->with('success', "Order #{$order->order_number} shifted to {$newStatus}.");
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

    public function productUpdate(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Staff cannot modify products.');
        }
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
            'main_image' => 'nullable|image|max:2048',
            'additional_images' => 'nullable|array|max:4',
            'additional_images.*' => 'image|max:2048',
        ]);

        // Extract files
        $mainImage = $request->file('main_image');
        $secondaryImages = $request->file('additional_images');
        
        // Remove from validated array
        unset($validated['main_image'], $validated['additional_images']);

        // Handle Deferred Deletions
        if ($request->has('delete_main_image') && $product->main_image) {
            $filePath = str_replace('storage/', '', $product->main_image);
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $validated['main_image'] = null;
        }

        // Handle New Main Image Upload
        if ($mainImage) {
            $path = $mainImage->store('products/main', 'public');
            $validated['main_image'] = 'storage/' . $path;
        }

        if ($request->has('delete_gallery')) {
            foreach ($request->delete_gallery as $imageId) {
                $img = \App\Models\ProductImage::find($imageId);
                if ($img) {
                    $filePath = str_replace('storage/', '', $img->image_path);
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                    $img->delete();
                }
            }
        }

        $product->update($validated);


        // Handle Secondary Images (Append to Gallery)
        if ($secondaryImages) {
            $currentGalleryCount = $product->gallery()->count();
            $newCount = count($secondaryImages);
            
            if (($currentGalleryCount + $newCount) > 4) {
                return back()->with('error', "Garage limit exceeded! A grail can only have 4 gallery images total. (Currently has {$currentGalleryCount})");
            }

            foreach ($secondaryImages as $image) {
                $path = $image->store('products/gallery', 'public');
                $product->gallery()->create([
                    'image_path' => 'storage/' . $path,
                    'type' => 'gallery'
                ]);
            }
        }

        return back()->with('success', 'Model updated in the garage!');
    }

    /**
     * Delete a gallery image.
     */
    public function productImageDestroy($id)
    {
        try {
            $image = ProductImage::findOrFail($id);
            
            // Delete physical file
            $filePath = str_replace('storage/', '', $image->image_path);
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            
            $image->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete the main product image.
     */
    public function productMainImageDestroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            if ($product->main_image) {
                // Delete physical file
                $filePath = str_replace('storage/', '', $product->main_image);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                
                $product->main_image = null;
                $product->save();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove a product.
     */
    public function productDestroy($id)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Staff cannot delete products.');
        }
        $product = Product::findOrFail($id);
        $product->delete();
        
        return redirect()->route('admin.products')->with('success', 'Model ejected from the garage.');
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
     * Show detailed user view for admin.
     */
    public function userShow($id)
    {
        $user = User::with(['orders', 'cart'])->findOrFail($id);
        return view('admin.user-details', compact('user'));
    }

    /**
     * Promote a user.
     */
    public function userPromote($id)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Staff cannot promote users.');
        }

        $user = User::findOrFail($id);

        if ($user->role === 'customer') {
            $user->role = 'staff';
        } elseif ($user->role === 'staff') {
            $user->role = 'admin';
        } else {
            return back()->with('error', 'Collector is already an Admin.');
        }

        $user->save();

        return back()->with('success', "Collector promoted to " . strtoupper($user->role) . ".");
    }

    /**
     * Demote a user.
     */
    public function userDemote($id)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Staff cannot demote users.');
        }

        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot demote an Admin.');
        } elseif ($user->role === 'staff') {
            $user->role = 'customer';
        } else {
            return back()->with('error', 'User is already a Collector.');
        }

        $user->save();

        return back()->with('success', "User demoted to " . strtoupper($user->role) . ".");
    }

    /**
     * Remove a user.
     */
    public function userDestroy($id)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Staff cannot eject collectors.');
        }
        if ($id == auth()->id()) {
            return back()->with('error', 'You cannot eject yourself!');
        }
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Collector ejected from the garage.');
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
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Staff cannot create coupons.');
        }
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,coupon_code',
            'name' => 'nullable|string',
            'discount_type' => 'required|string',
            'discount_value' => 'required_unless:discount_type,free_shipping|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date',
        ]);

        $validated['coupon_code'] = $validated['code'];
        $validated['name'] = $validated['name'] ?? $validated['coupon_code'];
        unset($validated['code']);
        $coupon = Coupon::create($validated);
        return back()->with('success', 'Promo code activated!');
    }

    public function couponDestroy($id)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Staff cannot delete coupons.');
        }
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('success', 'Promo code deactivated.');
    }

    /**
     * Display the audit log list for the Owner.
     */
    public function auditLogs(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access to Audit Logs.');
        }

        $query = \App\Models\AuditLog::with('user');

        if ($request->filled('action_filter')) {
            $query->where('action', $request->action_filter);
        }

        if ($request->filled('user_filter')) {
            $query->where('user_id', $request->user_filter);
        }

        $logs = $query->latest()->paginate(50)->appends($request->all());
        
        $actions = \App\Models\AuditLog::select('action')->distinct()->pluck('action');
        $users = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();

        return view('admin.audit-logs', compact('logs', 'actions', 'users'));
    }


}
