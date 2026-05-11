<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Quote;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Services\GeminiService;

class AdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_orders' => \App\Models\Order::count(),
                'total_quotes' => \App\Models\Quote::count(),
                'total_products' => \App\Models\Product::count(),
                'total_users' => User::count(),
                'total_blog_posts' => \App\Models\BlogPost::count(),
                'revenue' => \App\Models\Order::sum('total'),
            ],
            'recent_orders' => Order::latest()->limit(5)->get(),
        ]);
    }

    // Categories
    public function categories()
    {
        return Inertia::render('Admin/Categories/Index', [
            'categories' => Category::withCount('products')->get(),
        ]);
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);
        return back()->with('success', 'Category created.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);
        return back()->with('success', 'Category updated.');
    }

    public function deleteCategory(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products.');
        }
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    // Service Categories
    public function serviceCategories()
    {
        return Inertia::render('Admin/ServiceCategories/Index', [
            'categories' => ServiceCategory::withCount('services')->get(),
        ]);
    }

    public function storeServiceCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:service_categories',
        ]);

        ServiceCategory::create($validated);
        return back()->with('success', 'Service category created.');
    }

    public function updateServiceCategory(Request $request, ServiceCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:service_categories,slug,' . $category->id,
        ]);

        $category->update($validated);
        return back()->with('success', 'Service category updated.');
    }

    public function deleteServiceCategory(ServiceCategory $category)
    {
        if ($category->services()->count() > 0) {
            return back()->with('error', 'Cannot delete category with services.');
        }
        $category->delete();
        return back()->with('success', 'Service category deleted.');
    }

    // Services
    public function services()
    {
        return Inertia::render('Admin/Services/Index', [
            'services' => Service::with('category')->get(),
        ]);
    }

    public function createService()
    {
        return Inertia::render('Admin/Services/Create', [
            'categories' => ServiceCategory::all(),
        ]);
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services',
            'description' => 'required|string',
            'features' => 'required|array|min:3|max:3',
            'icon' => 'required|string',
            'is_featured' => 'boolean',
            'service_category_id' => 'required|exists:service_categories,id',
        ]);

        Service::create($validated);
        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }

    public function editService(Service $service)
    {
        return Inertia::render('Admin/Services/Edit', [
            'service' => $service,
            'categories' => ServiceCategory::all(),
        ]);
    }

    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,' . $service->id,
            'description' => 'required|string',
            'features' => 'required|array|min:3|max:3',
            'icon' => 'required|string',
            'is_featured' => 'boolean',
            'service_category_id' => 'required|exists:service_categories,id',
        ]);

        $service->update($validated);
        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }

    public function deleteService(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Service deleted.');
    }

    // Products
    public function products()
    {
        return Inertia::render('Admin/Products/Index', [
            'products' => Product::with('category')->get(),
        ]);
    }

    public function createProduct()
    {
        return Inertia::render('Admin/Products/Create', [
            'categories' => Category::all(),
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
            'image_file' => 'nullable|image|max:2048',
            'image' => 'nullable|string',
            'specifications' => 'nullable|array',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        // Convert specifications array to associative array
        if ($request->has('specifications') && is_array($request->specifications)) {
            $specs = [];
            foreach ($request->specifications as $spec) {
                if (isset($spec['key']) && isset($spec['value']) && $spec['key'] !== '') {
                    $specs[$spec['key']] = $spec['value'];
                }
            }
            $validated['specifications'] = $specs;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function editProduct(Product $product)
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => Category::all(),
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
            'image_file' => 'nullable|image|max:2048',
            'image' => 'nullable|string',
            'specifications' => 'nullable|array',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        // Convert specifications array to associative array
        if ($request->has('specifications') && is_array($request->specifications)) {
            $specs = [];
            foreach ($request->specifications as $spec) {
                if (isset($spec['key']) && isset($spec['value']) && $spec['key'] !== '') {
                    $specs[$spec['key']] = $spec['value'];
                }
            }
            $validated['specifications'] = $specs;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted successfully.');
    }

    // Blog Categories
    public function blogCategories()
    {
        return Inertia::render('Admin/Blog/Categories/Index', [
            'categories' => \App\Models\BlogCategory::withCount('posts')->get(),
        ]);
    }

    public function storeBlogCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories',
            'description' => 'nullable|string',
        ]);

        \App\Models\BlogCategory::create($validated);

        return redirect()->back()->with('success', 'Blog category created.');
    }

    public function updateBlogCategory(Request $request, \App\Models\BlogCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->back()->with('success', 'Blog category updated.');
    }

    public function deleteBlogCategory(\App\Models\BlogCategory $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Blog category deleted.');
    }

    // Blog Posts
    public function blogPosts()
    {
        return Inertia::render('Admin/Blog/Posts/Index', [
            'posts' => \App\Models\BlogPost::with('category')->latest()->get(),
        ]);
    }

    public function createBlogPost()
    {
        return Inertia::render('Admin/Blog/Posts/Create', [
            'categories' => \App\Models\BlogCategory::all(),
        ]);
    }

    public function storeBlogPost(Request $request)
    {
        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image_file' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('blog', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        if ($validated['is_published'] ?? false) {
            $validated['published_at'] = now();
        }

        \App\Models\BlogPost::create($validated);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post created.');
    }

    public function editBlogPost(\App\Models\BlogPost $post)
    {
        return Inertia::render('Admin/Blog/Posts/Edit', [
            'post' => $post,
            'categories' => \App\Models\BlogCategory::all(),
        ]);
    }

    public function updateBlogPost(Request $request, \App\Models\BlogPost $post)
    {
        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,' . $post->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image_file' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('blog', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        if ($validated['is_published'] && !$post->is_published) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post updated.');
    }

    public function generateBlogPost(Request $request, GeminiService $gemini)
    {
        $request->validate(['topic' => 'required|string|max:255']);
        
        $content = $gemini->generateBlogPost($request->topic);
        
        if (!$content) {
            return back()->with('error', 'Failed to generate content. Please check your API key.');
        }
        
        return back()->with([
            'success' => 'Content generated successfully!',
            'generated_content' => $content
        ]);
    }

    public function deleteBlogPost(\App\Models\BlogPost $post)
    {
        $post->delete();
        return redirect()->back()->with('success', 'Blog post deleted.');
    }

    // Orders & Quotes
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
}
