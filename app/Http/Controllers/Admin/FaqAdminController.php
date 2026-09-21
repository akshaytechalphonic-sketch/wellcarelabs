<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query();

        // Search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('question', 'like', "%{$q}%")
                    ->orWhere('answer', 'like', "%{$q}%");
            });
        }

        // Status filter
        if ($request->status === 'active') {
            $query->where('is_active', 1);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', 0);
        }

        $faqs = $query->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question'   => 'required|string|max:255',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'required|in:0,1',   // ✅ changed
        ]);

        $data['is_active']  = (int) $request->input('is_active'); // ✅ changed
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Faq::create($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ Added Successfully!');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question'   => 'required|string|max:255',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'required|in:0,1',   // ✅ changed
        ]);

        $data['is_active']  = (int) $request->input('is_active'); // ✅ changed
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $faq->update($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ Updated Successfully!');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ Deleted Successfully!');
    }

    public function toggle(Faq $faq)
    {
        $faq->is_active = !$faq->is_active;
        $faq->save();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ Status Updated!');
    }
}
