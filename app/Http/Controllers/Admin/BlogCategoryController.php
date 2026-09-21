<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BlogCategoryController extends Controller
{
    protected int $perPage = 10;

    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $query = BlogCategory::query();

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
        }

        $categories = $query
            ->orderBy('id', 'desc')
            ->paginate($this->perPage)
            ->appends(['q' => $q]);

        return view('admin.blog_categories.index', compact('categories', 'q'));
    }

    public function create()
    {
        $category = new BlogCategory();
        return view('admin.blog_categories.create', compact('category'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('blog_categories', 'name')],
        ], [
            'name.required' => 'Please enter the category name.',
            'name.unique' => 'A category with this name already exists.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        BlogCategory::create($data);

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Blog category created successfully.');
    }

    public function edit(BlogCategory $blogCategory)
    {
        return view('admin.blog_categories.edit', compact('blogCategory'));
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('blog_categories', 'name')->ignore($blogCategory->id)],
        ], [
            'name.required' => 'Please enter the category name.',
            'name.unique' => 'A category with this name already exists.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $blogCategory->update($data);

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Blog category updated successfully.');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('danger', 'Blog category deleted successfully.');
    }
}
