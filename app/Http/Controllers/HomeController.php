<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')->limit(3)->get();
        $categories = Category::all();
        return view('home', compact('featuredProducts', 'categories'));
    }
}
