<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class BlogController extends Controller
{
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        // ✅ ADD THIS (missing part)
        $relatedBlogs = Blog::where('id', '!=', $blog->id)
            ->latest()
            ->take(6)
            ->get();

        return view('blogs.show', compact('blog', 'relatedBlogs'));
    }


    public function index($categorySlug = null)
    {
        // dd('sd');
        $categories = \App\Models\BlogCategory::withCount(['blogs' => function($q) {
            $q->where('status', 'Published');
        }])->orderBy('name')->get();

        $query = Blog::where('status', 'Published')->latest();

        $selectedCategory = null;
        $categoryParam = $categorySlug ?? request('category');
        if ($categoryParam && $categoryParam !== '') {
            $selectedCategory = \App\Models\BlogCategory::where('slug', $categoryParam)->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        $blogs = $query->paginate(9)->appends(request()->query());

        return view('blogs.index', compact('blogs', 'categories', 'selectedCategory'));
    }
}
