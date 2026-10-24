<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of blog articles and categories.
     */
    public function index(Request $request): JsonResponse
    {
        $query = BlogPost::with('category')->latest();

        if ($request->has('is_published')) {
            $isPublished = filter_var($request->query('is_published'), FILTER_VALIDATE_BOOLEAN);
            $query->where('is_published', $isPublished);
        }

        if ($request->filled('category_id')) {
            $query->where('blog_category_id', $request->query('category_id'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->get();

        $stats = [
            'total' => BlogPost::count(),
            'published' => BlogPost::where('is_published', true)->count(),
            'drafts' => BlogPost::where('is_published', false)->count(),
            'categories_count' => BlogCategory::count(),
        ];

        return response()->json([
            'posts' => $posts,
            'stats' => $stats,
        ]);
    }

    /**
     * Display the specified blog post.
     */
    public function show($id): JsonResponse
    {
        $post = BlogPost::with('category')->findOrFail($id);

        return response()->json([
            'post' => $post,
        ]);
    }

    /**
     * Store a new blog post.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        }

        if (! empty($validated['is_published'])) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blog', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        $post = BlogPost::create($validated);
        $post->load('category');

        return response()->json([
            'message' => 'Blog article published successfully.',
            'post' => $post,
        ], 201);
    }

    /**
     * Update an existing blog post.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $post = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'blog_category_id' => 'sometimes|required|exists:blog_categories,id',
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $post->id,
            'excerpt' => 'nullable|string',
            'content' => 'sometimes|required|string',
            'featured_image' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blog', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        if (isset($validated['is_published']) && $validated['is_published'] && ! $post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);
        $post->load('category');

        return response()->json([
            'message' => 'Blog article updated successfully.',
            'post' => $post,
        ]);
    }

    /**
     * Toggle publication status.
     */
    public function togglePublish($id): JsonResponse
    {
        $post = BlogPost::with('category')->findOrFail($id);
        $post->is_published = ! $post->is_published;
        if ($post->is_published && ! $post->published_at) {
            $post->published_at = now();
        }
        $post->save();

        return response()->json([
            'message' => $post->is_published ? 'Article published.' : 'Article reverted to draft.',
            'post' => $post,
        ]);
    }

    /**
     * Delete blog article.
     */
    public function destroy($id): JsonResponse
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        return response()->json([
            'message' => 'Blog article deleted successfully.',
        ]);
    }

    /**
     * List blog categories.
     */
    public function categories(): JsonResponse
    {
        $categories = BlogCategory::withCount('posts')->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }
}
