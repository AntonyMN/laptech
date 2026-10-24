<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * List all services with category relations.
     */
    public function index(Request $request): JsonResponse
    {
        $categoryId = $request->input('category_id');
        $search = trim((string) $request->input('search', ''));

        $query = Service::with('category');

        if ($categoryId) {
            $query->where('service_category_id', $categoryId);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $services = $query->orderBy('name')->get();

        return response()->json([
            'services' => $services,
        ]);
    }

    /**
     * Get single service details.
     */
    public function show(int $id): JsonResponse
    {
        $service = Service::with('category')->findOrFail($id);

        return response()->json([
            'service' => $service,
        ]);
    }

    /**
     * Create a new service.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug',
            'description' => 'required|string',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'features' => 'nullable',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Service::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        if (isset($validated['features']) && is_string($validated['features'])) {
            $decoded = json_decode($validated['features'], true);
            $validated['features'] = is_array($decoded) ? $decoded : [$validated['features']];
        }

        $service = Service::create($validated);
        $service->load('category');

        return response()->json([
            'message' => 'Service created successfully.',
            'service' => $service,
        ], 201);
    }

    /**
     * Update an existing service.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255|unique:services,slug,' . $service->id,
            'description' => 'sometimes|required|string',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'features' => 'nullable',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        if (isset($validated['features']) && is_string($validated['features'])) {
            $decoded = json_decode($validated['features'], true);
            $validated['features'] = is_array($decoded) ? $decoded : [$validated['features']];
        }

        $service->update($validated);
        $service->load('category');

        return response()->json([
            'message' => 'Service updated successfully.',
            'service' => $service,
        ]);
    }

    /**
     * Delete a service.
     */
    public function destroy(int $id): JsonResponse
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json([
            'message' => 'Service deleted successfully.',
        ]);
    }

    /**
     * List all service categories.
     */
    public function categories(): JsonResponse
    {
        $categories = ServiceCategory::withCount('services')->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    /**
     * Create a new service category.
     */
    public function storeCategory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:service_categories,slug',
            'description' => 'nullable|string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category = ServiceCategory::create($validated);

        return response()->json([
            'message' => 'Service category created successfully.',
            'category' => $category,
        ], 201);
    }
}
