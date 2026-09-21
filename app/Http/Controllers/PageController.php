<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $pages = Page::query()
            ->where(function($query) {
                $query->where('status', 1);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('meta_title', 'like', "%{$q}%")
                        ->orWhere('meta_description', 'like', "%{$q}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('pages.index', compact('pages', 'q'));
    }
    public function show(Page $page)
    {
        abort_unless($page->status === 'Published', 404);

        $page->load(['sections' => function($q) {
                $q->where('status', 1)->orderBy('sort_order');
        }]);

        // Fetch published tests associated with this page
        $tests = \App\Models\LabTest::where('page_id', $page->id)
            ->where('status', 'Published')
            ->get();

        return view('pages.show', compact('page', 'tests'));
    }
}
