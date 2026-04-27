<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        
        if ($request->wantsJson() || $request->is('api/*')) {
            return CategoryResource::collection($categories);
        }

        return view('categories.index', compact('categories'));
    }
}
