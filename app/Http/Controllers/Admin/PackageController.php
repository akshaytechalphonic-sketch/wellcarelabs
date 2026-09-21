<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\LabTest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    protected int $perPage = 10;

    /**
     * Display a listing of the packages with search + status filter support
     */
    public function index(Request $request)
    {
        
        $perPage = (int) $request->get('per_page', $this->perPage);
        $perPage = max(1, min(200, $perPage));

        $q = trim((string) $request->get('q', ''));
        $status = strtolower(trim((string) $request->get('status', '')));

        $query = Package::query();

        // 🔍 Search by title, MRP, and selling price (discounted_price)
        if ($q !== '') {
            $query->where(function ($b) use ($q) {
                $b->where('title', 'like', "%{$q}%")
                    ->orWhere('mrp', 'like', "%{$q}%")
                    ->orWhere('discounted_price', 'like', "%{$q}%");
            });
        }

        // 📦 Filter by status (published, draft, etc.)
        if (in_array($status, ['published', 'draft'])) {
            $query->whereRaw('LOWER(status) = ?', [$status]);
        }

        $packages = $query->orderBy('id', 'desc')
            ->paginate($perPage)
            ->appends([
                'q'      => $q,
                'status' => $status,
            ]);

        return view('admin.packages.index', compact('packages', 'q', 'status'));
    }


    public function create(Request $request)
    {
        $package = new Package();
        $tests = LabTest::orderBy('test_name')->get();
        $viewHtml = view('admin.packages.create', compact('package', 'tests'))->render();

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'html' => $viewHtml,
            ], 200);
        }

        return view('admin.packages.create', compact('package', 'tests'));
    }

    /**
     * Store a new package
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title'            => [
                    'required',
                    'string',
                    'min:3',
                    'max:255',
                    Rule::unique('packages', 'title'),
                ],
                'content'          => ['required', 'string', 'min:20'],
                'mrp'              => ['required', 'numeric', 'min:1', 'max:1000000'],
                'discounted_price' => ['nullable', 'numeric', 'min:0', 'lt:mrp'],
                'banner'           => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'is_special'       => 'nullable|boolean',
                'special_label'    => 'nullable|string|max:50',
                'meta_tags'        => 'nullable|string',
                'status'           => ['required', Rule::in(['Draft', 'Published'])],
                'test_ids'         => ['nullable', 'array'],
                'test_ids.*'       => ['exists:lab_tests,id'],
                'package_code'     => ['nullable', 'string', 'max:50'],
                'sample_type'      => ['nullable', 'string', 'max:100'],
                'fasting'          => ['nullable', 'string', 'max:50'],
                'parameters_count' => ['nullable', 'integer', 'min:0'],
                'why_done'         => ['nullable', 'string', 'max:5000'],
                'who_should_test'  => ['nullable', 'string', 'max:5000'],
                'how_to_read'      => ['nullable', 'string', 'max:5000'],
                'what_to_ask'      => ['nullable', 'string', 'max:5000'],
                'meta_title'       => ['nullable', 'string', 'max:255'],
                'meta_description' => ['nullable', 'string', 'max:1000'],
                'image_alt'        => ['nullable', 'string', 'max:255'],
                'faqs'             => ['nullable', 'array'],
                'faqs.*.question'  => ['nullable', 'string'],
                'faqs.*.answer'    => ['nullable', 'string'],
            ],
            [
                'title.required' => 'Please enter a package title.',
                'title.min' => 'The package title must be at least :min characters long.',
                'title.max' => 'The package title may not exceed :max characters.',
                'title.unique' => 'A package with this title already exists.',
                'content.required' => 'Please provide the package details.',
                'content.min' => 'The package description should be at least :min characters long.',
                'mrp.required' => 'Please enter the MRP amount.',
                'mrp.numeric' => 'MRP must be a valid number.',
                'mrp.min' => 'MRP must be at least :min.',
                'mrp.max' => 'MRP may not exceed ₹1,000,000.',
                'discounted_price.numeric' => 'Discounted price must be a valid number.',
                'discounted_price.lt' => 'Discounted price must be less than MRP.',
                'banner.required' => 'Please upload a banner image for the package.',
                'banner.image' => 'The banner must be a valid image file.',
                'banner.mimes' => 'Allowed banner formats: JPG, JPEG, PNG, WEBP.',
                'banner.max' => 'Banner size must not exceed 5 MB.',
                'status.required' => 'Please select a package status.',
                'status.in' => 'Status must be either Draft or Published.',
            ]
        );

        if ($validator->fails()) {
            return $request->ajax()
                ? response()->json(['success' => false, 'errors' => $validator->errors()], 422)
                : redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        // normalize status & slug
        $data['status'] = ucfirst(strtolower($data['status']));
        $data['slug'] = $this->generateUniqueSlug($data['title']);

        // normalize boolean from checkbox
        $data['is_special'] = $request->has('is_special') ? true : false;
        // ensure label only stored if special
        $data['special_label'] = $data['is_special'] ? ($data['special_label'] ?? null) : null;

        // filter and save faqs
        $faqs = $request->input('faqs', []);
        $cleanedFaqs = [];
        if (is_array($faqs)) {
            foreach ($faqs as $faq) {
                if (!empty($faq['question']) || !empty($faq['answer'])) {
                    $cleanedFaqs[] = [
                        'question' => $faq['question'] ?? '',
                        'answer' => $faq['answer'] ?? '',
                    ];
                }
            }
        }
        $data['faqs'] = $cleanedFaqs;

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('packages', 'public');
        }

        $package = Package::create($data);

        if ($request->has('test_ids')) {
            $package->tests()->sync($request->input('test_ids', []));
        }

        session()->flash('success', 'Package created successfully.');

        if ($request->ajax()) {
            $packages = Package::orderBy('id', 'desc')->paginate($this->perPage);
            $html = view('admin.packages.partials.list', compact('packages'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Package created successfully.',
                'html'    => $html,
                'package' => $package,
            ], 201);
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }

    /**
     * Edit existing package
     */
    public function edit(Request $request, Package $package)
    {
        $tests = LabTest::orderBy('test_name')->get();
        $selectedTestIds = $package->tests()->pluck('lab_tests.id')->toArray();
        $viewHtml = view('admin.packages.edit', compact('package', 'tests', 'selectedTestIds'))->render();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => $viewHtml,
            ], 200);
        }

        return view('admin.packages.edit', compact('package', 'tests', 'selectedTestIds'));
    }

    /**
     * Update an existing package
     */
    public function update(Request $request, Package $package)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title'            => [
                    'required',
                    'string',
                    'min:3',
                    'max:255',
                    Rule::unique('packages', 'title')->ignore($package->id),
                ],
                'content'          => ['required', 'string', 'min:20'],
                'mrp'              => ['required', 'numeric', 'min:1', 'max:1000000'],
                'discounted_price' => ['nullable', 'numeric', 'lt:mrp', 'min:0'],
                'banner'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'is_special'       => 'nullable|boolean',
                'special_label'    => 'nullable|string|max:50',
                'status'           => ['required', Rule::in(['Draft', 'Published'])],
                'test_ids'         => ['nullable', 'array'],
                'test_ids.*'       => ['exists:lab_tests,id'],
                'meta_tags'        => 'nullable|string',
                'package_code'     => ['nullable', 'string', 'max:50'],
                'sample_type'      => ['nullable', 'string', 'max:100'],
                'fasting'          => ['nullable', 'string', 'max:50'],
                'parameters_count' => ['nullable', 'integer', 'min:0'],
                'why_done'         => ['nullable', 'string', 'max:5000'],
                'who_should_test'  => ['nullable', 'string', 'max:5000'],
                'how_to_read'      => ['nullable', 'string', 'max:5000'],
                'what_to_ask'      => ['nullable', 'string', 'max:5000'],
                'meta_title'       => ['nullable', 'string', 'max:255'],
                'meta_description' => ['nullable', 'string', 'max:1000'],
                'image_alt'        => ['nullable', 'string', 'max:255'],
                'faqs'             => ['nullable', 'array'],
                'faqs.*.question'  => ['nullable', 'string'],
                'faqs.*.answer'    => ['nullable', 'string'],
            ],
            [
                'title.required' => 'Please enter a package title.',
                'title.unique' => 'Another package with this title already exists.',
                'title.min' => 'The package title must be at least :min characters long.',
                'content.required' => 'Please provide the package details.',
                'content.min' => 'The package description should be at least :min characters long.',
                'mrp.required' => 'Please enter the MRP amount.',
                'mrp.numeric' => 'MRP must be a valid number.',
                'mrp.min' => 'MRP must be at least :min.',
                'mrp.max' => 'MRP may not exceed ₹1,000,000.',
                'discounted_price.numeric' => 'Discounted price must be a valid number.',
                'discounted_price.lt' => 'Discounted price must be less than MRP.',
                'banner.image' => 'The banner must be a valid image file.',
                'banner.mimes' => 'Allowed banner formats: JPG, JPEG, PNG, WEBP.',
                'banner.max' => 'Banner size must not exceed 5 MB.',
                'status.required' => 'Please select a package status.',
                'status.in' => 'Status must be either Draft or Published.',
            ]
        );

        if ($validator->fails()) {
            return $request->ajax()
                ? response()->json(['success' => false, 'errors' => $validator->errors()], 422)
                : redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['status'] = ucfirst(strtolower($data['status']));

        // normalize special flag & label
        $data['is_special'] = $request->has('is_special') ? true : false;
        $data['special_label'] = $data['is_special'] ? ($data['special_label'] ?? null) : null;

        // filter and save faqs
        $faqs = $request->input('faqs', []);
        $cleanedFaqs = [];
        if (is_array($faqs)) {
            foreach ($faqs as $faq) {
                if (!empty($faq['question']) || !empty($faq['answer'])) {
                    $cleanedFaqs[] = [
                        'question' => $faq['question'] ?? '',
                        'answer' => $faq['answer'] ?? '',
                    ];
                }
            }
        }
        $data['faqs'] = $cleanedFaqs;

        // update slug if title changed
        if ($package->slug === null || $data['title'] !== $package->title) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $package->id);
        }

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('packages', 'public');
            if (!empty($package->banner) && Storage::disk('public')->exists($package->banner)) {
                Storage::disk('public')->delete($package->banner);
            }
            $data['banner'] = $path;
        }

        $package->update($data);

        $package->tests()->sync($request->input('test_ids', []));

        session()->flash('success', 'Package updated successfully.');

        if ($request->ajax()) {
            $packages = Package::orderBy('id', 'desc')->paginate($this->perPage);
            $html = view('admin.packages.partials.list', compact('packages'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Package updated successfully.',
                'html'    => $html,
                'package' => $package,
            ], 200);
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(Request $request, Package $package)
    {
        if (!empty($package->banner) && Storage::disk('public')->exists($package->banner)) {
            Storage::disk('public')->delete($package->banner);
        }

        $package->delete();

        session()->flash('danger', 'Package deleted successfully.');

        if ($request->ajax()) {
            $packages = Package::orderBy('id', 'desc')->paginate($this->perPage);
            $html = view('admin.packages.partials.list', compact('packages'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Package deleted successfully.',
                'html'    => $html,
            ], 200);
        }

        return redirect()->route('admin.packages.index')->with('danger', 'Package deleted successfully.');
    }

    /**
     * Generate unique slug for a given title
     */
    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $i = 1;

        $exists = Package::where('slug', $slug);
        if ($ignoreId) {
            $exists->where('id', '!=', $ignoreId);
        }

        while ($exists->exists()) {
            $slug = $baseSlug . '-' . $i++;
            $exists = Package::where('slug', $slug);
            if ($ignoreId) {
                $exists->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }

    public function show(Request $request, Package $package)
    {
        // ===== attempt to eager-load only if relation exists =====
        try {
            // relation methods are normal methods on the model; guard with method_exists
            if (method_exists($package, 'tests')) {
                $package->load('tests');
            }
        } catch (\Throwable $e) {
            // ignore relation load failures — we'll fall back below
            \Log::warning('Could not eager load tests relation for Package: ' . $package->id . ' — ' . $e->getMessage());
        }

        // prepare array payload
        $pkg = $package->toArray();

        // banner: convert storage path to full asset URL if necessary
        if (!empty($pkg['banner']) && !str_starts_with($pkg['banner'], 'http')) {
            $pkg['banner'] = asset('storage/' . ltrim($pkg['banner'], '/'));
        }

        // normalize is_special, status casing etc.
        $pkg['is_special'] = isset($pkg['is_special']) ? (int)$pkg['is_special'] : 0;
        $pkg['status'] = $pkg['status'] ?? null;

        // if there's no tests key or it's empty, try to parse tests from content (fallback)
        if (empty($pkg['tests']) || !is_array($pkg['tests'])) {
            $pkg['tests'] = [];

            $contentSource = $pkg['content'] ?? $pkg['short_description'] ?? '';
            if ($contentSource) {
                // Split by newline or comma, trim and filter empties
                $items = preg_split('/[\r\n]+|,/', $contentSource);
                $items = array_map('trim', $items);
                $items = array_filter($items, fn($v) => $v !== '');
                // map to simple objects with a name property (front-end expects name/title)
                foreach ($items as $it) {
                    $pkg['tests'][] = ['name' => $it];
                }
            }
        }

        // AJAX / JSON response for modal
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $pkg,
            ]);
        }
    }
}
