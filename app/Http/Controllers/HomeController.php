<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

final class HomeController extends Controller
{
    /**
     * Display the premium speed shop home page.
     */
    public function index()
    {
        // Fetch featured products (e.g. STH or high views)
        $featuredProducts = Product::with(['category', 'brand', 'scale'])
            ->active()
            ->orderByDesc('is_super_treasure_hunt')
            ->orderByDesc('views')
            ->limit(6)
            ->get();
            
        $categories = Category::all();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
