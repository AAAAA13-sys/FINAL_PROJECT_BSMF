<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Scale;
use App\Models\Series;
use Illuminate\Http\Request;

final class ProductController extends Controller
{
    /**
     * Display a listing of the die-cast products with filters.
     */
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['category', 'brand', 'scale', 'series', 'reviews'])
            ->active();
        
        // Search by car name, casting name, or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('casting_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by Category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        // Filter by Brand
        if ($request->filled('brand')) {
            $query->whereIn('brand_id', (array) $request->brand);
        }

        // Filter by Scale
        if ($request->filled('scale')) {
            $query->whereIn('scale_id', (array) $request->scale);
        }

        // Filter by Series
        if ($request->filled('series')) {
            $query->whereIn('series_id', (array) $request->series);
        }

        // Filter by Rarity
        if ($request->filled('rarity')) {
            $rarity = (array) $request->rarity;
            $query->where(function($q) use ($rarity) {
                if (in_array('sth', $rarity)) $q->orWhere('is_super_treasure_hunt', true);
                if (in_array('th', $rarity)) $q->orWhere('is_treasure_hunt', true);
                if (in_array('chase', $rarity)) $q->orWhere('is_chase', true);
                if (in_array('rlc', $rarity)) $q->orWhere('is_rlc_exclusive', true);
            });
        }

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        switch ($request->get('sort')) {
            case 'price_low': $query->orderBy('price', 'asc'); break;
            case 'price_high': $query->orderBy('price', 'desc'); break;
            case 'newest': $query->latest(); break;
            case 'oldest': $query->oldest(); break;
            default: $query->latest(); break;
        }

        $products = $query->paginate(24)->withQueryString();
        
        $categories = Category::all();
        $brands = Brand::all();
        $scales = Scale::all();
        $series = Series::all();

        return view('products.index', compact('products', 'categories', 'brands', 'scales', 'series'));
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'scale', 'series', 'reviews.user', 'images'])
            ->findOrFail($id);
            
        // Increment views
        $product->increment('views');

        return view('products.show', compact('product'));
    }

    /**
     * Get search suggestions for the dynamic dropdown.
     */
    public function suggestions(Request $request)
    {
        $query = $request->query('query');
        if (empty($query)) {
            return response()->json([]);
        }

        $products = Product::where('name', 'like', "%{$query}%")
            ->active()
            ->limit(5)
            ->get(['id', 'name', 'main_image', 'price']);

        return response()->json($products);
    }
}
