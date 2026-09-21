<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\Page;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LabTestController extends Controller
{
    protected int $perPage = 20;

    public function index(Request $request)
    {
        
        $perPage = 10;
        $q = trim((string) $request->get('q', ''));
        $status = trim((string) $request->get('status', ''));

        $query = LabTest::query();

        // Search by test_name or description if `q` provided
        if ($q !== '') {
            $query->where(function ($b) use ($q) {
                $b->where('test_name', 'like', "%{$q}%")
                    ->orWhere('mrp', 'like', "%{$q}%")
                    ->orWhere('discounted_price', 'like', "%{$q}%");
            });
        }

        // Filter by status if provided (normalize input)
        if ($status !== '') {
            $normalized = ucfirst(strtolower($status)); // Published, Draft, Archived
            if (in_array($normalized, ['Published', 'Draft', 'Archived'], true)) {
                $query->where('status', $normalized);
            }
        }

        $tests = $query->orderBy('id', 'desc')
            ->paginate($perPage)
            ->appends(['q' => $q, 'status' => $status]);

        return view('admin.labtests.index', compact('tests', 'q', 'status'));
    }


    public function create(Request $request)
    {
        $labtest = new LabTest();
        $page = Page::all();

        $viewHtml = view()->exists('admin.labtests.partials.form')
            ? view('admin.labtests.partials.form', compact('labtest', 'page'))->render()
            : view('admin.labtests.create', compact('labtest', 'page'))->render();

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'html' => $viewHtml,
            ], 200);
        }

        return view('admin.labtests.create', compact('labtest', 'page'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_name'        => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('lab_tests', 'test_name'),
            ],
            'mrp'              => ['required', 'numeric', 'min:1', 'max:1000000'],
            'b2b'              => ['nullable', 'numeric', 'min:0'],
            'discounted_price' => ['nullable', 'numeric', 'lt:mrp', 'min:0'],
            'description'      => ['required', 'string', 'max:5000'],
            'status'           => ['required', Rule::in(['Draft', 'Published'])],
            'test_code'        => ['nullable', 'string', 'max:50'],
            'sample_type'      => ['nullable', 'string', 'max:100'],
            'fasting'          => ['nullable', 'string', 'max:50'],
            'parameters_count' => ['nullable', 'integer', 'min:0'],
            'why_done'         => ['nullable', 'string', 'max:5000'],
            'who_should_test'  => ['nullable', 'string', 'max:5000'],
            'how_to_read'      => ['nullable', 'string', 'max:5000'],
            'what_to_ask'      => ['nullable', 'string', 'max:5000'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_tags'        => ['nullable', 'string'],
            'page_id'          => ['nullable', 'exists:dynamic_pages,id'],
        ], [
            'test_name.required'       => 'Please enter a name for the test.',
            'test_name.min'            => 'Test name must be at least :min characters.',
            'test_name.max'            => 'Test name cannot exceed :max characters.',
            'test_name.unique'         => 'A lab test with this name already exists. Please enter a different name.',
            'mrp.required'             => 'Please provide the MRP.',
            'mrp.numeric'              => 'MRP must be a valid number.',
            'mrp.min'                  => 'MRP must be at least :min.',
            'mrp.max'                  => 'MRP cannot exceed :max.',
            'b2b.numeric'              => 'B2B price must be a valid number.',
            'discounted_price.numeric' => 'Discounted price must be a valid number.',
            'discounted_price.lt'      => 'The discounted price must be less than the MRP.',
            'description.required'     => 'Please provide a description for the test.',
            'description.max'          => 'Description cannot exceed :max characters.',
            'status.required'          => 'Please select a status.',
            'status.in'                => 'Invalid status selected.',
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

        $labtest = LabTest::create($data);

        session()->flash('success', 'Test created successfully.');

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            // Rebuild list using same filters if provided
            $q = trim((string) $request->get('q', ''));
            $status = trim((string) $request->get('status', ''));

            $listQuery = LabTest::query();
            if ($q !== '') {
                $listQuery->where(function ($b) use ($q) {
                    $b->where('test_name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            }
            if ($status !== '') {
                $normalized = ucfirst(strtolower($status));
                if (in_array($normalized, ['Published', 'Draft', 'Archived'], true)) {
                    $listQuery->where('status', $normalized);
                }
            }

            $tests = $listQuery->orderBy('id', 'desc')->paginate($this->perPage)->appends(['q' => $q, 'status' => $status]);
            $html = view('admin.labtests.partials.list', compact('tests'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Test created successfully.',
                'html'    => $html,
                'labtest' => $labtest,
            ], 201);
        }

        return redirect()->route('admin.labtests.index')->with('success', 'Test created successfully.');
    }

    public function edit(Request $request, LabTest $labtest)
    {
        $page = Page::all();

        $viewHtml = view()->exists('admin.labtests.partials.form')
            ? view('admin.labtests.partials.form', compact('labtest', 'page'))->render()
            : view('admin.labtests.edit', compact('labtest', 'page'))->render();

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'html' => $viewHtml,
            ], 200);
        }

        return view('admin.labtests.edit', compact('labtest', 'page'));
    }

    public function update(Request $request, LabTest $labtest)
    {
        $validator = Validator::make($request->all(), [
                'test_name'        => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('lab_tests', 'test_name')->ignore($labtest->id),
            ],
            'mrp'              => ['required', 'numeric', 'min:1', 'max:1000000'],
            'b2b'              => ['nullable', 'numeric', 'min:0'],
            'discounted_price' => ['nullable', 'numeric', 'lt:mrp'],
            'description'      => ['required', 'string', 'max:5000'],
            'status'           => ['required', Rule::in(['Draft', 'Published'])],
            'test_code'        => ['nullable', 'string', 'max:50'],
            'sample_type'      => ['nullable', 'string', 'max:100'],
            'fasting'          => ['nullable', 'string', 'max:50'],
            'parameters_count' => ['nullable', 'integer', 'min:0'],
            'why_done'         => ['nullable', 'string', 'max:5000'],
            'who_should_test'  => ['nullable', 'string', 'max:5000'],
            'how_to_read'      => ['nullable', 'string', 'max:5000'],
            'what_to_ask'      => ['nullable', 'string', 'max:5000'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_tags'        => ['nullable', 'string'],
            'page_id'          => ['nullable', 'exists:dynamic_pages,id'],
        ], [
            'test_name.required'       => 'Please enter a name for the test.',
            'test_name.min'            => 'Test name must be at least :min characters.',
            'test_name.max'            => 'Test name cannot exceed :max characters.',
            'test_name.unique'         => 'A lab test with this name already exists. Please enter a different name.',
            'mrp.required'             => 'Please provide the MRP.',
            'mrp.numeric'              => 'MRP must be a valid number.',
            'mrp.min'                  => 'MRP must be at least :min.',
            'mrp.max'                  => 'MRP cannot exceed :max.',
            'b2b.numeric'              => 'B2B price must be a valid number.',
            'discounted_price.numeric' => 'Discounted price must be a valid number.',
            'discounted_price.lt'      => 'The discounted price must be less than the MRP.',
            'description.required'     => 'Please provide a description for the test.',
            'description.max'          => 'Description cannot exceed :max characters.',
            'status.required'          => 'Please select a status.',
            'status.in'                => 'Invalid status selected.',
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

        $labtest->update($data);

        session()->flash('success', 'Test updated successfully.');

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            // Rebuild list using same filters if provided
            $q = trim((string) $request->get('q', ''));
            $status = trim((string) $request->get('status', ''));

            $listQuery = LabTest::query();
            if ($q !== '') {
                $listQuery->where(function ($b) use ($q) {
                    $b->where('test_name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            }
            if ($status !== '') {
                $normalized = ucfirst(strtolower($status));
                if (in_array($normalized, ['Published', 'Draft', 'Archived'], true)) {
                    $listQuery->where('status', $normalized);
                }
            }

            $tests = $listQuery->orderBy('id', 'desc')->paginate($this->perPage)->appends(['q' => $q, 'status' => $status]);
            $html = view('admin.labtests.partials.list', compact('tests'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Test updated successfully.',
                'html'    => $html,
                'labtest' => $labtest,
            ], 200);
        }

        return redirect()->route('admin.labtests.index')->with('success', 'Test updated successfully.');
    }

    public function destroy(Request $request, LabTest $labtest)
    {
        $labtest->delete();

        session()->flash('danger', 'Test deleted.');

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            // Rebuild list using same filters if provided
            $q = trim((string) $request->get('q', ''));
            $status = trim((string) $request->get('status', ''));

            $listQuery = LabTest::query();
            if ($q !== '') {
                $listQuery->where(function ($b) use ($q) {
                    $b->where('test_name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            }
            if ($status !== '') {
                $normalized = ucfirst(strtolower($status));
                if (in_array($normalized, ['Published', 'Draft', 'Archived'], true)) {
                    $listQuery->where('status', $normalized);
                }
            }

            $tests = $listQuery->orderBy('id', 'desc')->paginate($this->perPage)->appends(['q' => $q, 'status' => $status]);
            $html = view('admin.labtests.partials.list', compact('tests'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Test deleted.',
                'html'    => $html,
            ], 200);
        }

        return redirect()->route('admin.labtests.index')->with('danger', 'Test deleted.');
    }

    public function show(Request $request, LabTest $labtest)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($labtest);
        }
        return view('admin.labtests.show', compact('labtest'));
    }
}
