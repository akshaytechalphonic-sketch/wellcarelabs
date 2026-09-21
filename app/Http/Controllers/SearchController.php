<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabTest;
use App\Models\Package;

class SearchController extends Controller
{
    /**
     * Show combined search results (published tests + packages + special packages)
     */
    public function index(Request $request)
    {
        $q = trim($request->query('q', ''));

        // If no search query, redirect
        if ($q === '') {
            return redirect()->route('services');
        }

        // ===== PUBLISHED LAB TESTS =====
        $tests = LabTest::where('status', 'published')
            ->where('test_name', 'like', "%{$q}%")
            ->orderBy('test_name')
            ->paginate(12, ['*'], 'tests_page');

        // ===== PUBLISHED NORMAL PACKAGES =====
        $packages = Package::where('status', 'published')
            ->where('is_special', 0)
            ->where('title', 'like', "%{$q}%")
            ->orderBy('title')
            ->paginate(12, ['*'], 'packages_page');

        // ===== PUBLISHED SPECIAL PACKAGES =====
        $specialPackages = Package::where('status', 'published')
            ->where('is_special', 1)
            ->where('title', 'like', "%{$q}%")
            ->orderBy('title')
            ->paginate(12, ['*'], 'special_page');

        return view('search.results', compact(
            'tests',
            'packages',
            'specialPackages',
            'q'
        ));
    }

    /**
     * AJAX live search (published only)
     */
    public function ajax(Request $request)
    {
        $q = trim($request->query('q', ''));

        if ($q === '') {
            return response()->json([
                'tests' => [],
                'packages' => [],
                'special_packages' => [],
            ]);
        }

        // ===== PUBLISHED LAB TESTS =====
        $tests = LabTest::select('id', 'test_name')
            ->where('status', 'published')
            ->where('test_name', 'like', "%{$q}%")
            ->orderBy('test_name')
            ->limit(7)
            ->get();

        // ===== PUBLISHED NORMAL PACKAGES =====
        $packages = Package::select('id', 'title')
            ->where('status', 'published')
            ->where('is_special', 0)
            ->where('title', 'like', "%{$q}%")
            ->orderBy('title')
            ->limit(7)
            ->get();

        // ===== PUBLISHED SPECIAL PACKAGES =====
        $specialPackages = Package::select('id', 'title')
            ->where('status', 'published')
            ->where('is_special', 1)
            ->where('title', 'like', "%{$q}%")
            ->orderBy('title')
            ->limit(7)
            ->get();

        return response()->json([
            'tests' => $tests,
            'packages' => $packages,
            'special_packages' => $specialPackages,
        ]);
    }
}
