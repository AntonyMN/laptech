<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * List products with pagination, search, category filter, and archive status.
     */
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->input('search', ''));
        $categoryId = $request->input('category_id');
        $archiveFilter = $request->input('archive_filter', 'active'); // 'active', 'archived', 'all'
        $lowStock = $request->boolean('low_stock');
        $perPage = min(max((int) $request->input('per_page', 15), 5), 100);

        $query = Product::with('category');

        if ($archiveFilter === 'active') {
            $query->active();
        } elseif ($archiveFilter === 'archived') {
            $query->archived();
        }

        if ($lowStock) {
            $query->where('stock', '<=', 3);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        $products = $query->latest()->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Get a single product details.
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json([
            'product' => $product,
        ]);
    }

    /**
     * Create a new product.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|string',
            'is_featured' => 'boolean',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|max:5120',
            'specifications' => 'nullable',
        ]);

        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        if (isset($validated['specifications']) && is_string($validated['specifications'])) {
            $decoded = json_decode($validated['specifications'], true);
            $validated['specifications'] = is_array($decoded) ? $decoded : null;
        }

        unset($validated['image_file']);

        $product = Product::create($validated);
        $product->load('category');

        return response()->json([
            'message' => 'Product created successfully.',
            'product' => $product,
        ], 201);
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255|unique:products,slug,' . $product->id,
            'category_id' => 'sometimes|required|exists:categories,id',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'status' => 'sometimes|required|string',
            'is_featured' => 'boolean',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|max:5120',
            'specifications' => 'nullable',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        if (isset($validated['specifications']) && is_string($validated['specifications'])) {
            $decoded = json_decode($validated['specifications'], true);
            $validated['specifications'] = is_array($decoded) ? $decoded : null;
        }

        unset($validated['image_file']);

        $product->update($validated);
        $product->load('category');

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $product,
        ]);
    }

    /**
     * Delete a product permanently.
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }

    /**
     * Archive product.
     */
    public function archive(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->update([
            'is_archived' => true,
            'archived_at' => now(),
        ]);

        return response()->json([
            'message' => 'Product archived.',
            'product' => $product,
        ]);
    }

    /**
     * Restore archived product.
     */
    public function restore(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->update([
            'is_archived' => false,
            'archived_at' => null,
        ]);

        return response()->json([
            'message' => 'Product restored.',
            'product' => $product,
        ]);
    }

    /**
     * Get all categories.
     */
    public function categories(): JsonResponse
    {
        $categories = Category::withCount('activeProducts')->orderBy('name')->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    /**
     * Create a new category.
     */
    public function storeCategory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Category created successfully.',
            'category' => $category,
        ], 201);
    }
}
