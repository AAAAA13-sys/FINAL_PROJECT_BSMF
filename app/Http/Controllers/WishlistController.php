<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlistItems = Wishlist::with('product')->where('user_id', Auth::id())->latest()->get();
        
        if ($request->wantsJson() || $request->is('api/*')) {
            return \App\Http\Resources\WishlistResource::collection($wishlistItems);
        }

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            $wishlist->load('product');
            return new \App\Http\Resources\WishlistResource($wishlist);
        }

        return back()->with('success', 'Added to your dream garage!');
    }

    public function destroy(Request $request, $id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlist->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Removed from wishlist']);
        }

        return back()->with('success', 'Removed from your garage.');
    }
}
