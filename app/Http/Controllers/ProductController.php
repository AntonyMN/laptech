<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with('category');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        return Inertia::render('Products/Index', [
            'products' => $query->get(),
            'categories' => Category::all(),
            'filters' => $request->only(['search', 'category', 'min_price', 'max_price']),
        ]);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with('category')->firstOrFail();
        
        return Inertia::render('Products/Show', [
            'product' => $product,
            'relatedProducts' => Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->limit(3)
                ->get(),
        ]);
    }
}

