<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Package;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    protected int $perPage = 20;

    /**
     * Display listing of custom dynamic pages.
     */
    public function index(Request $request)
    {
       
        $q = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));

        $pages = Page::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%")
                        ->orWhere('meta_title', 'like', "%{$q}%");
                });
            })
            ->when($status !== '', function ($query) use ($status) {
                $mapped = ($status === 'Published') ? 1 : 0;
                $query->where('status', $mapped);
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.pages.partials.list', compact('pages'))->render(),
            ], 200);
        }

        return view('admin.pages.index', compact('pages', 'q', 'status'));
    }

    /**
     * Show the form for creating a new custom page.
     */
    public function create(Request $request)
    {
        $page = new Page();
        $packages = Package::whereRaw('LOWER(status) = ?', ['published'])->orderBy('title')->get();
        $faqs = Faq::orderBy('id', 'desc')->get();
        
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.pages.create', compact('page', 'packages', 'faqs'))->render(),
            ], 200);
        }

        return view('admin.pages.create', compact('page', 'packages', 'faqs'));
    }

    /**
     * Store a newly created custom page in database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', Rule::unique('dynamic_pages', 'slug')],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords'    => ['nullable', 'string', 'max:255'],
            'schema_markup'    => ['nullable', 'string'],
            'meta_tags'        => ['nullable', 'string'],
            'status'           => ['required', Rule::in(['Draft', 'Published'])],
            'banner_image'     => ['nullable', 'image', 'max:5120'],
            'image_alt'        => ['nullable', 'string', 'max:255'],
            'package_ids'      => ['nullable', 'array'],
            'package_ids.*'    => ['exists:packages,id'],
            'faq_ids'          => ['nullable', 'array'],
            'faq_ids.*'        => ['exists:faqs,id'],
        ], [
            'title.required' => 'Please enter the page title.',
            'slug.unique'    => 'A page with this URL slug already exists.',
            'status.required'=> 'Please select a status.',
            'status.in'      => 'Invalid status selected.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('pages', 'public');
        }

        $page = Page::create($data);
        $page->packages()->sync($request->input('package_ids', []));
        $page->faqs()->sync($request->input('faq_ids', []));

        // Save sections if any submitted
        if ($request->has('sections')) {
            $sectionsData = $request->input('sections', []);
            foreach ($sectionsData as $key => $sData) {
                $section = new \App\Models\Section();
                $section->sectionable_type = Page::class;
                $section->sectionable_id = $page->id;
                $section->type = $sData['type'] ?? 'text';
                $section->sort_order = intval($sData['sort_order'] ?? 0);

                $contentObj = [];
                if ($section->type === 'hero' || $section->type === 'banner') {
                    $contentObj = [
                        'title' => $sData['title'] ?? '',
                        'subtitle' => $sData['subtitle'] ?? '',
                        'image_url' => '',
                    ];
                } elseif ($section->type === 'biography' || $section->type === 'profile') {
                    $contentObj = [
                        'title' => $sData['title'] ?? '',
                        'subtitle' => $sData['subtitle'] ?? '',
                        'body' => $sData['body'] ?? '',
                        'image_url' => '',
                    ];
                } elseif ($section->type === 'quote') {
                    $contentObj = [
                        'author' => $sData['title'] ?? '',
                        'body' => $sData['body'] ?? '',
                    ];
                } elseif ($section->type === 'timeline' || $section->type === 'values_grid') {
                    $contentObj = [
                        'title' => $sData['title'] ?? '',
                        'subtitle' => $sData['subtitle'] ?? '',
                        'body' => $sData['body'] ?? '',
                    ];
                } else {
                    $contentObj = [
                        'title' => $sData['title'] ?? '',
                        'body' => $sData['body'] ?? '',
                    ];
                }

                if ($request->hasFile("sections.{$key}.image")) {
                    $file = $request->file("sections.{$key}.image");
                    $path = $file->store('sections', 'public');
                    $contentObj['image_url'] = '/storage/' . $path;
                }

                $section->content = $contentObj;
                $section->status = 1;
                $section->save();
            }
        }

        session()->flash('success', 'Custom page created successfully.');

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            $pages = Page::orderBy('id', 'desc')->paginate($this->perPage);
            $html = view('admin.pages.partials.list', compact('pages'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Custom page created successfully.',
                'html'    => $html,
                'page'    => $page,
            ], 201);
        }

        return redirect()->route('admin.pages.index')->with('success', 'Custom page created successfully.');
    }

    /**
     * Show the form for editing the specified custom page.
     */
    public function edit(Request $request, Page $page)
    {
        $page->load(['sections', 'packages', 'faqs']);
        $packages = Package::whereRaw('LOWER(status) = ?', ['published'])->orderBy('title')->get();
        $faqs = Faq::orderBy('id', 'desc')->get();

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.pages.edit', compact('page', 'packages', 'faqs'))->render(),
            ], 200);
        }

        return view('admin.pages.edit', compact('page', 'packages', 'faqs'));
    }

    /**
     * Update the specified custom page in database.
     */
    public function update(Request $request, Page $page)
    {
        $validator = Validator::make($request->all(), [
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', Rule::unique('dynamic_pages', 'slug')->ignore($page->id)],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords'    => ['nullable', 'string', 'max:255'],
            'schema_markup'    => ['nullable', 'string'],
            'meta_tags'        => ['nullable', 'string'],
            'status'           => ['required', Rule::in(['Draft', 'Published'])],
            'banner_image'     => ['nullable', 'image', 'max:5120'],
            'image_alt'        => ['nullable', 'string', 'max:255'],
            'package_ids'      => ['nullable', 'array'],
            'package_ids.*'    => ['exists:packages,id'],
            'faq_ids'          => ['nullable', 'array'],
            'faq_ids.*'        => ['exists:faqs,id'],
        ], [
            'title.required' => 'Please enter the page title.',
            'slug.unique'    => 'A page with this URL slug already exists.',
            'status.required'=> 'Please select a status.',
            'status.in'      => 'Invalid status selected.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('banner_image')) {
            if ($page->banner_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($page->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')->store('pages', 'public');
        }

        $page->fill($data);
        $page->save();

        $page->packages()->sync($request->input('package_ids', []));
        $page->faqs()->sync($request->input('faq_ids', []));

        // Save sections
        $keptSectionIds = [];
        if ($request->has('sections')) {
            $sectionsData = $request->input('sections', []);
            foreach ($sectionsData as $key => $sData) {
                if (strpos($key, 'new_') === 0) {
                    $section = new \App\Models\Section();
                    $section->sectionable_type = Page::class;
                    $section->sectionable_id = $page->id;
                } else {
                    $section = \App\Models\Section::find($key);
                    if (!$section) continue;
                }

                $section->type = $sData['type'] ?? 'text';
                $section->sort_order = intval($sData['sort_order'] ?? 0);

                // Reconstruct content array from inputs
                $contentObj = [];
                if ($section->type === 'hero' || $section->type === 'banner') {
                    $contentObj = [
                        'title' => $sData['title'] ?? '',
                        'subtitle' => $sData['subtitle'] ?? '',
                        'image_url' => $section->content['image_url'] ?? '',
                    ];
                } elseif ($section->type === 'biography' || $section->type === 'profile') {
                    $contentObj = [
                        'title' => $sData['title'] ?? '',
                        'subtitle' => $sData['subtitle'] ?? '',
                        'body' => $sData['body'] ?? '',
                        'image_url' => $section->content['image_url'] ?? '',
                    ];
                } elseif ($section->type === 'quote') {
                    $contentObj = [
                        'author' => $sData['title'] ?? '',
                        'body' => $sData['body'] ?? '',
                    ];
                } elseif ($section->type === 'timeline' || $section->type === 'values_grid') {
                    $contentObj = [
                        'title' => $sData['title'] ?? '',
                        'subtitle' => $sData['subtitle'] ?? '',
                        'body' => $sData['body'] ?? '',
                    ];
                } else {
                    $contentObj = [
                        'title' => $sData['title'] ?? '',
                        'body' => $sData['body'] ?? '',
                    ];
                }

                if ($request->hasFile("sections.{$key}.image")) {
                    if (!empty($section->content['image_url'])) {
                        $oldPath = str_replace('/storage/', '', $section->content['image_url']);
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                    }
                    $file = $request->file("sections.{$key}.image");
                    $path = $file->store('sections', 'public');
                    $contentObj['image_url'] = '/storage/' . $path;
                }

                $section->content = $contentObj;
                $section->status = 1;
                $section->save();

                $keptSectionIds[] = $section->id;
            }
        }

        // Delete any sections that were removed in the edit form
        $page->sections()->whereNotIn('id', $keptSectionIds)->delete();

        session()->flash('success', 'Custom page updated successfully.');

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            $pages = Page::orderBy('id', 'desc')->paginate($this->perPage);
            $html = view('admin.pages.partials.list', compact('pages'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Custom page updated successfully.',
                'html'    => $html,
                'page'    => $page,
            ], 200);
        }

        return redirect()->route('admin.pages.index')->with('success', 'Custom page updated successfully.');
    }

    /**
     * Remove the specified custom page from database.
     */
    public function destroy(Request $request, Page $page)
    {
        $page->delete();
        session()->flash('danger', 'Custom page deleted.');

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            $pages = Page::orderBy('id', 'desc')->paginate($this->perPage);
            $html = view('admin.pages.partials.list', compact('pages'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Custom page deleted.',
                'html'    => $html,
            ], 200);
        }

        return redirect()->route('admin.pages.index')->with('danger', 'Custom page deleted.');
    }
}
