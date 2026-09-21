<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabTest;
use Illuminate\Support\Facades\Schema;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        
        // Allow ?per_page=6|12|24|48 (defaults to 12)
        $perPage = (int) $request->input('per_page', 12);
        $perPage = in_array($perPage, [6, 12, 24, 48], true) ? $perPage : 12;

        // Search query (trimmed)
        $q = trim((string) $request->input('q', ''));

        $query = LabTest::query()
            ->where('status', 'Published');

        if ($q !== '') {
            // determine which searchable columns actually exist in the table
            $availableColumns = Schema::getColumnListing((new LabTest)->getTable());
            $searchable = array_intersect($availableColumns, ['test_name', 'short_description', 'description']);

            // if none of the expected columns exist, fall back to test_name if present
            if (empty($searchable) && in_array('test_name', $availableColumns, true)) {
                $searchable = ['test_name'];
            }

            if (!empty($searchable)) {
                // escape %, _ and backslash to avoid wildcard injection
                $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q);
                $like = '%' . $escaped . '%';

                $query->where(function ($w) use ($searchable, $like) {
                    foreach ($searchable as $col) {
                        // Use where orWhere properly for first vs subsequent columns
                        if ($col === reset($searchable)) {
                            $w->where($col, 'LIKE', $like);
                        } else {
                            $w->orWhere($col, 'LIKE', $like);
                        }
                    }
                });
            }
            // else: no searchable columns exist — don't add search condition
        }

        $tests = $query
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString(); // keeps q and per_page in pagination links

        $page = \App\Models\Page::whereIn('slug', ['tests', 'services'])->first();

        return view('services', compact('tests', 'page'));
    }

    public function show(LabTest $labTest)
    {
        return view('services.show', compact('labTest'));
    }
}
