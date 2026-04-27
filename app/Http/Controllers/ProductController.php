<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
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
            ->with(['brand', 'scale', 'series', 'reviews'])
            ->active();

        // Search by car name, casting name, or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('casting_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
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
            $query->where(function ($q) use ($rarity) {
                if (in_array('sth', $rarity))
                    $q->orWhere('is_super_treasure_hunt', true);
                if (in_array('th', $rarity))
                    $q->orWhere('is_treasure_hunt', true);
                if (in_array('chase', $rarity))
                    $q->orWhere('is_chase', true);
                if (in_array('rlc', $rarity))
                    $q->orWhere('is_rlc_exclusive', true);
            });
        }

        // Filter by Carded / Loose
        if ($request->filled('is_carded')) {
            $query->where('is_carded', true);
        }
        if ($request->filled('is_loose')) {
            $query->where('is_loose', true);
        }

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');

        $query->reorder(); // Clear any existing orders

        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc')->orderBy('name', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc')->orderBy('name', 'asc');
                break;
            case 'alpha_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'alpha_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
                break;
        }

        $products = $query->paginate(24)->withQueryString();

        if ($request->wantsJson() || $request->is('api/*')) {
            return \App\Http\Resources\ProductResource::collection($products);
        }

        $brands = Brand::all();
        $scales = Scale::all();
        $series = Series::all();

        return view('products.index', compact('products', 'brands', 'scales', 'series'));
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::with(['brand', 'scale', 'series', 'reviews.user', 'images'])
            ->findOrFail($id);

        // Increment views
        $product->increment('views');

        if (request()->wantsJson() || request()->is('api/*')) {
            return new \App\Http\Resources\ProductResource($product);
        }

        // Product details view
        return view('products.show', compact('product'));
    }

    /**
     * Get search suggestions for the dynamic dropdown.
     */
    public function suggestions(Request $request)
    {
        $query = $request->query('q') ?? $request->query('query');
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('name', 'like', "%{$query}%")
            ->active()
            ->limit(5)
            ->get(['id', 'name', 'main_image', 'price']);

        return response()->json($products);
    }
}
