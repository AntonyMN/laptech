<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with('category')
            ->where('is_published', true)
            ->latest();

        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        return Inertia::render('Blog/Index', [
            'posts' => $query->paginate(9),
            'categories' => BlogCategory::withCount('posts')->get(),
            'currentCategory' => $request->category,
        ]);
    }

    public function show($slug)
    {
        $post = BlogPost::with('category')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $relatedPosts = BlogPost::where('blog_category_id', $post->blog_category_id)
            ->where('id', '!=', $post->id)
            ->where('is_published', true)
            ->limit(3)
            ->get();

        return Inertia::render('Blog/Show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts
        ]);
    }
}
