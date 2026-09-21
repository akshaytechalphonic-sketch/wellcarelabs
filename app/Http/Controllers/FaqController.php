<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('is_active', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(5);

        $faqPage = \App\Models\Page::whereIn('slug', ['faq', 'faqs'])->first();

        return view('faq', compact('faqs', 'faqPage'));
    }
}
