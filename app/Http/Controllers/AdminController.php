<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Order;
use App\Models\Quote;
use App\Models\User;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_orders' => Order::count(),
                'total_quotes' => Quote::count(),
                'total_products' => Product::count(),
                'total_users' => User::count(),
                'revenue' => Order::sum('total'),
            ],
            'recent_orders' => Order::latest()->limit(5)->get(),
        ]);
    }

    public function products()
    {
        return Inertia::render('Admin/Products/Index', [
            'products' => Product::with('category')->get(),
        ]);
    }

    public function orders()
    {
        return Inertia::render('Admin/Orders/Index', [
            'orders' => Order::latest()->get(),
        ]);
    }

    public function quotes()
    {
        return Inertia::render('Admin/Quotes/Index', [
            'quotes' => Quote::with('service')->latest()->get(),
        ]);
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string']);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated.');
    }

    public function updateQuoteStatus(Request $request, Quote $quote)
    {
        $request->validate(['status' => 'required|string']);
        $quote->update(['status' => $request->status]);
        return back()->with('success', 'Quote status updated.');
    }

    public function createProduct()
    {
        return Inertia::render('Admin/Products/Create', [
            'categories' => \App\Models\Category::all(),
        ]);
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function editProduct(Product $product)
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => \App\Models\Category::all(),
        ]);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted successfully.');
    }
}


