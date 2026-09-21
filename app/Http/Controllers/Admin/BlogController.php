<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    protected int $perPage = 10;

    /* ======================
       INDEX
    =======================*/
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $status = trim((string) $request->get('status', ''));

        $query = Blog::query();

        if ($q !== '') {
            $query->where('title', 'like', "%{$q}%");
        }

        if ($status !== '') {
            $normalized = ucfirst(strtolower($status)); // Draft / Published
            if (in_array($normalized, ['Draft', 'Published'], true)) {
                $query->where('status', $normalized);
            }
        }

        $blogs = $query
            ->orderBy('id', 'desc')
            ->paginate($this->perPage)
            ->appends(['q' => $q, 'status' => $status]);

        return view('admin.blogs.index', compact('blogs', 'q', 'status'));
    }

    /* ======================
       CREATE
    =======================*/
    public function create()
    {
        $blog = new Blog();
        $categories = BlogCategory::orderBy('name')->get();
        return view('admin.blogs.create', compact('blog', 'categories'));
    }

    /* ======================
       STORE
    =======================*/
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'             => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string','min:10'],
            'content'           => ['required', 'string','min:10'],
            'featured_image'    => ['required', 'image', 'max:10240'],
            'status'            => ['required', Rule::in(['Draft', 'Published'])],
            'category_id'       => ['nullable', 'exists:blog_categories,id'],
        ], [
            'title.required'   => 'Please enter blog title.',
            'content.required' => 'Please enter blog content.',
            'featured_image.required' => 'Please upload a featured image.',
            'status.required'  => 'Please select status.',
            'status.in'        => 'Invalid blog status selected.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request
                ->file('featured_image')
                ->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog created successfully.');
    }

    /* ======================
       EDIT
    =======================*/
    public function edit(Blog $blog)
    {
        $categories = BlogCategory::orderBy('name')->get();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    /* ======================
       UPDATE
    =======================*/
    public function update(Request $request, Blog $blog)
    {
        $validator = Validator::make($request->all(), [
            'title'             => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'content'           => ['required', 'string'],
            'featured_image'    => ['nullable', 'image', 'max:10240'],
            'status'            => ['required', Rule::in(['Draft', 'Published'])],
            'category_id'       => ['nullable', 'exists:blog_categories,id'],
        ], [
            'title.required'   => 'Please enter blog title.',
            'content.required' => 'Please enter blog content.',
            'status.required'  => 'Please select status.',
            'status.in'        => 'Invalid blog status selected.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }

            $data['featured_image'] = $request
                ->file('featured_image')
                ->store('blogs', 'public');
        }

        // 🔑 SAME DIRTY CHECK AS LAB TEST
        $blog->fill($data);

        if (! $blog->isDirty()) {
            return redirect()
                ->route('admin.blogs.index')
                ->with('info', 'No changes were made.');
        }

        $blog->save();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog updated successfully.');
    }

    /* ======================
       DESTROY
    =======================*/
    public function destroy(Blog $blog)
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('danger', 'Blog deleted.');
    }

    public function show(Blog $blog)
    {
        if (request()->ajax()) {
            return response()->json([
                'data' => [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'content' => $blog->content,
                    'short_description' => $blog->short_description,
                    'status' => $blog->status,
                    'featured_image' => $blog->featured_image
                        ? asset('storage/' . $blog->featured_image)
                        : null,
                    'created_at' => $blog->created_at,
                ]
            ]);
        }

        return view('admin.blogs.show', compact('blog'));
    }
}
