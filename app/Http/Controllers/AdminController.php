<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Dispute;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSales = Order::sum('total_price');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $recentOrders = Order::with('user')->latest()->limit(5)->get();
        $lowStockProducts = Product::where('stock', '<', 10)->limit(5)->get();
        $totalCustomers = User::where('role', 'customer')->count();

        return view('admin.dashboard', compact('totalSales', 'totalOrders', 'totalProducts', 'totalCustomers', 'recentOrders', 'lowStockProducts'));
    }

    public function products()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('admin.products', compact('products', 'categories'));
    }

    public function productStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_url' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Product::create($request->all());

        return back()->with('success', 'Product added successfully!');
    }

    public function productDestroy($id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('success', 'Product deleted!');
    }

    public function productEdit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.edit-product', compact('product', 'categories'));
    }

    public function productUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_url' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function orders()
    {
        $orders = Order::with('user')->latest()->get();
        return view('admin.orders', compact('orders'));
    }

    public function orderUpdateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        Order::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated!');
    }

    public function disputes()
    {
        $disputes = Dispute::with(['order', 'user'])->latest()->get();
        return view('admin.disputes', compact('disputes'));
    }

    public function disputeUpdateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        Dispute::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Dispute status updated!');
    }

    public function disputeReply(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        \App\Models\DisputeMessage::create([
            'dispute_id' => $id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Reply sent successfully!');
    }

    public function users()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }

    public function userDestroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the only administrator.');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function categories()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name']);
        Category::create(['name' => $request->name]);
        return back()->with('success', 'Category created successfully!');
    }

    public function categoryDestroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category that has products assigned.');
        }
        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }

    public function coupons()
    {
        $coupons = \App\Models\Coupon::latest()->get();
        return view('admin.coupons', compact('coupons'));
    }

    public function couponStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        \App\Models\Coupon::create($request->all());
        return back()->with('success', 'Coupon created successfully!');
    }

    public function couponDestroy($id)
    {
        \App\Models\Coupon::findOrFail($id)->delete();
        return back()->with('success', 'Coupon deleted successfully.');
    }
}
