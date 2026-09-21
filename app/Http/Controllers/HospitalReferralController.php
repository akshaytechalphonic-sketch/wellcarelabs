<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Hospital;
use Carbon\Carbon;

class HospitalReferralController extends Controller
{
    /**
     * /h/{uniqueId}
     * - sets session and queues a cookie with hospital info (30 days)
     * - returns a tiny forwarding HTML that:
     *     • writes same cookie client-side (fallback)
     *     • performs window.location.replace('/') so the address bar shows '/'
     */
    public function redirect(Request $request, $uniqueId)
    {
        try {
            $uid = trim((string) $uniqueId);
            if ($uid === '') return redirect('/');

            // find hospital by unique id
            $hospital = null;
            if (class_exists(Hospital::class)) {
                $hospital = Hospital::where('unique_id', $uid)->first();
            }

            if (! $hospital) {
                Log::info("HospitalReferralController: hospital not found for uniqueId={$uid}");
                return redirect('/');
            }

            $payload = [
                'id' => $hospital->id,
                'unique_id' => $hospital->unique_id,
                'name' => $hospital->name,
                'scanned_at' => Carbon::now()->toDateTimeString(),
            ];

            // set server session (authoritative)
            session(['hospital_ref' => $payload]);

            // queue server cookie (30 days)
            $cookie = cookie('hospital_ref', json_encode($payload), 60 * 24 * 30);

            // render the tiny forwarding page (below) which sets cookie client-side and replaces location to '/'
            $html = view('admin.hospitals.hospital_qr_redirect', [
                'payload' => $payload,
                'redirectUrl' => url('/'),
            ])->render();

            return response($html, 200)
                ->withCookie($cookie)
                ->header('Content-Type', 'text/html; charset=UTF-8');
        } catch (\Throwable $e) {
            Log::error('HospitalReferralController redirect error: ' . $e->getMessage());
            return redirect('/');
        }
    }
}
