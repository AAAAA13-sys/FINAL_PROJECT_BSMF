<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

final class HomeController extends Controller
{
    /**
     * Display the premium speed shop home page.
     */
    public function index()
    {
        // Fetch featured products (e.g. Super Treasure Hunt or high views)
        $featuredProducts = Product::with(['brand', 'scale'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->active()
            ->orderByDesc('is_super_treasure_hunt')
            ->latest()
            ->limit(3)
            ->get();
            

        return view('home', compact('featuredProducts'));
    }
}
