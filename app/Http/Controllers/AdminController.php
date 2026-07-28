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
use App\Services\HtmlSanitizer;
use App\Services\ProductBulkService;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use Spatie\Permission\Models\Role;
use Spatie\Activitylog\Models\Activity;

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
    public function products(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $products = Product::with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'filters' => ['search' => $search],
        ]);
    }

    public function createProduct()
    {
        return Inertia::render('Admin/Products/Create', [
            'categories' => Category::all(),
        ]);
    }

    public function storeProduct(Request $request, HtmlSanitizer $sanitizer)
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
            'status' => 'required|string|in:Brand new,Ex-UK,Certified Refurbished',
        ]);

        $validated['description'] = $sanitizer->clean($validated['description']);

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

    public function updateProduct(Request $request, Product $product, HtmlSanitizer $sanitizer)
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
            'status' => 'required|string|in:Brand new,Ex-UK,Certified Refurbished',
        ]);

        $validated['description'] = $sanitizer->clean($validated['description']);

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

    // Bulk product export / import

    public function exportProducts(ProductBulkService $service)
    {
        $spreadsheet = $service->buildExport();
        $writer = new XlsxWriter($spreadsheet);
        $filename = 'products-' . now()->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function previewProductImport(Request $request, ProductBulkService $service)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $rows = $service->rowsFromFile($request->file('file'));
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Could not read that file. Please upload the exported .xlsx spreadsheet.',
            ], 422);
        }

        return response()->json($service->analyze($rows));
    }

    public function importProducts(Request $request, ProductBulkService $service)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $rows = $service->rowsFromFile($request->file('file'));
            $result = $service->commit($rows);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage() ?: 'The import could not be completed.',
            ], 422);
        }

        activity('bulk-import')
            ->causedBy($request->user())
            ->withProperties($result)
            ->log("Bulk product import — {$result['updated']} updated, {$result['created']} created, {$result['categoriesCreated']} new categories");

        return response()->json([
            'message' => "Import complete — {$result['updated']} updated, {$result['created']} created, {$result['categoriesCreated']} new categories.",
            'result' => $result,
        ]);
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

    // Users & Roles (RBAC)
    public function users(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $users = User::with('roles')
            ->when($search !== '', fn ($q) => $q->where(fn ($sub) => $sub
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'is_admin' => (bool) $u->is_admin,
                'role' => $u->roles->pluck('name')->first(),
                'created_at' => $u->created_at?->toDateString(),
            ]);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => Role::orderBy('name')->pluck('name'),
            'filters' => ['search' => $search],
        ]);
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => 'nullable|string|in:admin,manager,staff',
        ]);

        // Prevent locking yourself out of the admin console.
        if ($user->id === $request->user()->id && ($data['role'] ?? null) !== 'admin') {
            return back()->with('error', 'You cannot change your own admin role.');
        }

        $oldRole = $user->roles->pluck('name')->first();
        $newRole = $data['role'] ?? null;

        $user->syncRoles(array_filter([$newRole]));
        $user->is_admin = $newRole === 'admin';
        $user->save();

        // Role changes live on a pivot, so log them explicitly for the audit trail.
        if ($oldRole !== $newRole) {
            activity('roles')
                ->causedBy($request->user())
                ->performedOn($user)
                ->withProperties(['old_role' => $oldRole ?: 'Customer', 'new_role' => $newRole ?: 'Customer'])
                ->log("Changed {$user->name}'s role to " . ($newRole ?: 'Customer'));
        }

        return back()->with('success', "Updated {$user->name}'s access.");
    }

    // Audit trail
    public function audit()
    {
        $activities = Activity::with('causer')
            ->latest()
            ->paginate(25)
            ->through(fn (Activity $a) => [
                'id' => $a->id,
                'log_name' => $a->log_name,
                'description' => $a->description,
                'event' => $a->event,
                'subject_type' => $a->subject_type ? class_basename($a->subject_type) : null,
                'subject_id' => $a->subject_id,
                'causer' => $a->causer?->name ?? 'System',
                'changes' => $a->changes(),
                'created_at' => $a->created_at?->diffForHumans(),
                'created_at_full' => $a->created_at?->toDayDateTimeString(),
            ]);

        return Inertia::render('Admin/Audit/Index', [
            'activities' => $activities,
        ]);
    }
}
