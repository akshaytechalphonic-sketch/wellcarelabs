<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class PublicPackageController extends Controller
{



    
    /**
     * Display a paginated listing of public packages.
     */
 public function index(Request $request)
{
    $packages = Package::query()
        ->whereRaw('LOWER(status) = ?', ['published'])
        ->where('is_special', 0)          // ✅ BASIC ONLY
        ->orderByDesc('created_at')
        ->paginate(12);

    $page = \App\Models\Page::whereIn('slug', ['packages', 'all-packages'])->first();

    return view('packages', compact('packages', 'page'));
}


    /**
     * Show a single package (by slug if available).
     */
    public function show(Package $package)
    {
       
        // If you used route model binding with slug: routes/web.php should use {package:slug}
        return view('packages.show', compact('package'));

    }

    

    /**
     * API helper if needed — return Published packages as JSON.
     */
    public function Published()
    {
        $packages = Package::query()
            ->where('status', 'Published')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($packages);
    }

public function special()
{
  
    $packages = Package::query()
        ->whereRaw('LOWER(status) = ?', ['published'])
        ->where('is_special', 1)
        ->orderByDesc('updated_at')
        ->get(); // or ->get() if you don’t want pagination

    $page = \App\Models\Page::whereIn('slug', ['special-packages', 'packages-special', 'special'])->first();

    return view('special', compact('packages', 'page'));
}




}



