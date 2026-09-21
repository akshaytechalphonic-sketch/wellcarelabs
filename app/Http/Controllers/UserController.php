<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\LabTest;

class UserController extends Controller
{
    public function Index()
    {
        // Latest Published packages (show 6 on home — change take() as you need)
        $packages = Package::where('status', 'Published')
                           ->orderBy('created_at', 'desc')
                           ->take(6)
                           ->get();

        // Accept both 'published' (admin) and 'Published' (older logic)
        $tests = LabTest::where(function ($q) {
                        $q->whereRaw('LOWER(status) = ?', ['published'])
                          ->orWhereRaw('LOWER(status) = ?', ['Published'])
                          ->orWhereNull('status'); // optional: show tests without status
                    })
                    ->orderBy('created_at', 'desc')
                    ->take(12)
                    ->get();

        return view('home', compact('packages', 'tests'));
    }
}
