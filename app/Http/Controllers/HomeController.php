<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\LabTest;
use App\Models\Package;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Show the public welcome / home page.
     * Loads packages, lab tests and active banners.
     * Also accepts hospital attribution via ?ref= or ?hospital_unique_id=
     * and sets session/cookie ONLY when coming from a QR link.
     */
    public function index(Request $request)
    {
       
        $packages        = collect();
        $specialPackages = collect();
        $tests           = collect();
        $banners         = collect();
       
        $homeBlogs       = collect();
        $maxPackages     = "";
        $maxSpecials     = "";

        // Is this request coming from a QR link?
        $hasQrParam = $request->has('hospital_unique_id') || $request->has('ref');

        // Current session value (may be null)
        $hospitalRef = session('hospital_ref');

        // If user opens site normally (no QR param), clear any old QR attribution
        if (!$hasQrParam && !empty($hospitalRef)) {
            session()->forget('hospital_ref');
            cookie()->queue(cookie()->forget('hospital_ref'));
            $hospitalRef = null;
            Log::info('Home: cleared old hospital_ref because request has no QR params');
        }

        // Accept either ?hospital_unique_id=XXXX or ?ref=XXXX
        $uniqueFromQuery = $request->query('hospital_unique_id') ?? $request->query('ref') ?? null;

        // 1) If we received unique id in query (QR link), resolve hospital and persist session + cookie
        if ($hasQrParam && $uniqueFromQuery && class_exists(\App\Models\Hospital::class)) {
            try {
                $hospitalModel = new \App\Models\Hospital;
                if (Schema::hasTable($hospitalModel->getTable())) {
                    $h = \App\Models\Hospital::where('unique_id', $uniqueFromQuery)->first();
                    if ($h) {
                        $hospitalRef = [
                            'id'         => $h->id,
                            'unique_id'  => $h->unique_id,
                            'name'       => $h->name ?? null,
                            'scanned_at' => now()->toDateTimeString(),
                        ];
                        // persist to session + cookie (30 days)
                        session(['hospital_ref' => $hospitalRef]);
                        cookie()->queue(cookie('hospital_ref', json_encode($hospitalRef), 60 * 24 * 30));
                        Log::info('Home: hospital_ref set from query', [
                            'unique'      => $uniqueFromQuery,
                            'hospital_id' => $h->id
                        ]);
                    } else {
                        Log::debug('Home: provided unique id not found', ['unique' => $uniqueFromQuery]);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Home: failed resolving hospital from query: ' . $e->getMessage());
            }
        }

        // ⚠️ IMPORTANT:
        // Old logic that restored hospital_ref from cookie when session was empty
        // has been removed to avoid "sticky" QR attribution on normal visits.

        // Load special packages (published & marked special) — shown prominently on homepage
        if (class_exists(Package::class)) {
            try {
                if (Schema::hasTable((new Package)->getTable())) {
                    $specialPackages = Package::query()
                        ->whereRaw('LOWER(status) = ?', ['published'])
                        ->where('is_special', 1)
                        ->orderByDesc('updated_at')
                        ->limit($maxSpecials)
                        ->get();
                }
            } catch (\Throwable $e) {
                Log::warning('Home: failed to load special packages: ' . $e->getMessage());
            }
        }

        // Load Packages (only Published) — exclude special packages to avoid duplication
        if (class_exists(Package::class)) {
            try {
                if (Schema::hasTable((new Package)->getTable())) {
                    $q = Package::query()
                        ->whereRaw('LOWER(status) = ?', ['published']);

                    if ($specialPackages->isNotEmpty()) {
                        $ids = $specialPackages->pluck('id')->filter()->all();
                        if (!empty($ids)) {
                            $q->whereNotIn('id', $ids);
                        }
                    }

                    $packages = $q->orderByDesc('created_at')
                        ->limit($maxPackages)
                        ->get();
                }
            } catch (\Throwable $e) {
                Log::warning('Home: failed to load packages: ' . $e->getMessage());
            }
        }

        // Load Lab Tests (only published)
        if (class_exists(LabTest::class)) {
            try {
                if (Schema::hasTable((new LabTest)->getTable())) {
                    $tests = LabTest::query()
                        ->whereRaw('LOWER(status) = ?', ['published'])
                        ->orderByDesc('created_at')
                        ->get();
                }
            } catch (\Throwable $e) {
                Log::warning('Home: failed to load tests: ' . $e->getMessage());
            }
        }

        // Load active banners (Cached for maximum performance)
        if (class_exists(Banner::class)) {
            try {
                if (Schema::hasTable((new Banner)->getTable())) {
                    $banners = Cache::remember('homepage_active_banners', 86400, function () {
                        return Banner::query()
                            ->where('is_active', 1)
                            ->get()
                            ->transform(function ($b) {
                                if (isset($b->image)) {
                                    $b->image = $b->image ? ltrim($b->image, '/') : null;
                                }
                                return $b;
                            });
                    });
                }
            } catch (\Throwable $e) {
                Log::warning('Home: failed to load banners: ' . $e->getMessage());
            }
        }

        Log::info('HomeController index loaded', [
            'packages'        => $packages->count(),
            'specialPackages' => $specialPackages->count(),
            'tests'           => $tests->count(),
            'banners'         => $banners->count(),
            'hospitalRef'     => is_array($hospitalRef) ? ($hospitalRef['id'] ?? null) : null,
        ]);

        // Load homepage blogs (published & marked for home)
        if (class_exists(Blog::class)) {
            try {
                if (Schema::hasTable((new Blog)->getTable())) {
                    $homeBlogs = Blog::query()
                        ->where('status', 'Published')
                        ->limit(3)
                        ->get();
                }
            } catch (\Throwable $e) {
                Log::warning('Home: failed to load homepage blogs: ' . $e->getMessage());
            }
        }


        // Load testimonials (Published only)
        $testimonials = collect();
        if (class_exists(\App\Models\Testimonial::class)) {
            try {
                if (Schema::hasTable((new \App\Models\Testimonial)->getTable())) {
                    $testimonials = \App\Models\Testimonial::query()
                        ->where('status', 'Published')
                        ->latest()
                        ->take(6)
                        ->get();
                }
            } catch (\Throwable $e) {
                Log::warning('Home: failed to load testimonials: ' . $e->getMessage());
            }
        }

        $homepage=Page::where('slug','home')->first();
    
        return view('home', compact('homepage','packages', 'specialPackages', 'tests', 'banners', 'hospitalRef','homeBlogs', 'testimonials'));
    }

    /**
     * API: return active banners as JSON (used by frontend JS or SPA).
     */
    public function activeBanners()
    {
        $result = collect();

        if (class_exists(Banner::class)) {
            try {
                if (Schema::hasTable((new Banner)->getTable())) {
                    $banners = Banner::where('is_active', 1)
                        ->orderBy('position')
                        ->get(['id', 'title', 'subtitle', 'cta_text', 'cta_url', 'image', 'position']);

                    $result = $banners->map(function ($b) {
                        $img = $b->image ? ltrim($b->image, '/') : null;
                        return [
                            'id'        => $b->id,
                            'title'     => $b->title,
                            'subtitle'  => $b->subtitle,
                            'cta_text'  => $b->cta_text,
                            'cta_url'   => $b->cta_url,
                            'image'     => $img,
                            'image_url' => $b->image ? asset('storage/' . $img) : null,
                            'position'  => $b->position,
                        ];
                    });
                }
            } catch (\Throwable $e) {
                Log::warning('Home: failed to load banners for API: ' . $e->getMessage());
            }
        }
        return response()->json($result);
    }
}
