<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Verify if user bought the product and it was delivered
        $hasBought = \App\Models\Order::where('user_id', $user->id)
            ->where('status', \App\Models\Order::STATUS_DELIVERED)
            ->whereHas('items', function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })->exists();

        if (!$hasBought) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'You can only review products you have purchased.'], 403);
            }
            return back()->with('error', 'You can only review products you have purchased.');
        }

        // Check if already reviewed
        if (Review::where('user_id', $user->id)->where('product_id', $request->product_id)->exists()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'You have already reviewed this product.'], 400);
            }
            return back()->with('error', 'You have already reviewed this product.');
        }

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return new \App\Http\Resources\ReviewResource($review);
        }

        return back()->with('success', 'Thank you for your review!');
    }
}
