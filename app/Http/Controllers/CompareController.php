<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;

class CompareController extends Controller
{
    public function index()
    {
        $compareIds = session()->get('compare', []);
        $products = Product::whereIn('id', $compareIds)->with('category')->get();

        return Inertia::render('Compare/Index', [
            'products' => $products
        ]);
    }

    public function add(Product $product)
    {
        $compare = session()->get('compare', []);

        if (count($compare) >= 4) {
            return back()->with('error', 'You can only compare up to 4 products.');
        }

        if (!in_array($product->id, $compare)) {
            $compare[] = $product->id;
            session()->put('compare', $compare);
        }

        return back()->with('success', 'Added to comparison.');
    }

    public function remove(Product $product)
    {
        $compare = session()->get('compare', []);
        
        if (($key = array_search($product->id, $compare)) !== false) {
            unset($compare[$key]);
            session()->put('compare', array_values($compare));
        }

        return back()->with('success', 'Removed from comparison.');
    }

    public function clear()
    {
        session()->forget('compare');
        return back()->with('success', 'Comparison cleared.');
    }
}
