<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Mail\HospitalDeleteOtpMail;
use Illuminate\Support\Facades\Mail;
use App\Notifications\HospitalPasswordCreateNotification;

class HospitalController extends Controller
{
    protected int $perPage = 20;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /** List hospitals with optional search + pagination */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', $this->perPage);
        $perPage = max(1, min(200, $perPage));

        $q = trim((string) $request->get('q', ''));
        $query = Hospital::query();

        if ($q !== '') {
            $query->where(function ($b) use ($q) {
                $b->where('name', 'like', "%{$q}%")
                    ->orWhere('unique_id', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $hospitals = $query->orderBy('id', 'desc')
            ->paginate($perPage)
            ->appends(['q' => $q, 'per_page' => $perPage]);

        return view('admin.hospitals.index', compact('hospitals', 'q'));
    }

    /** Show create form (supports AJAX) */
    public function create(Request $request)
    {
        $hospital = new Hospital();
        $viewHtml = view()->exists('admin.hospitals.partials.form')
            ? view('admin.hospitals.partials.form', compact('hospital'))->render()
            : view('admin.hospitals.create', compact('hospital'))->render();

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json(['success' => true, 'html' => $viewHtml], 200);
        }

        return view('admin.hospitals.create', compact('hospital'));
    }

    /** Store new hospital + create/link owner user (hospital_manager) */
    public function store(Request $request)
    {
        // sanitize + auto-generate unique_id
        $incoming = (string) $request->input('unique_id', '');
        $incoming = strtoupper(preg_replace('/[^A-Z0-9\-_]/', '', $incoming));
        if (empty($incoming)) {
            $incoming = $this->generateUniqueId(8);
        }
        $request->merge(['unique_id' => $incoming]);

        $validator = Validator::make($request->all(), [
            'name'          => ['required', 'string', 'min:3', 'max:255', Rule::unique('hospitals', 'name')],
            'email'         => ['required', 'email', 'max:255'],
            'phone'         => ['required', 'string', 'regex:/^[0-9]{10}$/', 'max:10'],
            'address'       => ['required', 'string', 'max:1000'],
            'unique_id'     => ['required', 'string', 'min:3', 'max:64', Rule::unique('hospitals', 'unique_id')],
            'owner_name'    => ['required', 'string', 'max:255'],
            'owner_mobile'  => ['required', 'string', 'regex:/^[0-9]{10}$/', 'max:10'],
            'doctor_name'   => ['required', 'string', 'max:255'],
            'doctor_mobile' => ['required', 'string', 'regex:/^[0-9]{10}$/', 'max:10'],
        ], [
            'name.required'          => 'Please enter hospital name.',
            'name.unique'            => 'A hospital with this name already exists. Please choose a different name.',
            'email.required'         => 'Please enter hospital email address.',
            'email.email'            => 'Please enter a valid email address.',
            'phone.required'         => 'Please enter hospital phone number.',
            'phone.regex'            => 'Phone number must be exactly 10 digits.',
            'address.required'       => 'Please enter hospital address.',
            'unique_id.required'     => 'Unique ID is required (or generate one).',
            'unique_id.unique'       => 'That Unique ID is already taken. Please choose another.',
            'owner_name.required'    => 'Please enter the owner / manager name.',
            'owner_mobile.required'  => 'Please enter the owner / manager mobile number.',
            'owner_mobile.regex'     => 'Owner / manager mobile must be exactly 10 digits.',
            'doctor_name.required'   => 'Please enter the doctor name.',
            'doctor_mobile.required' => 'Please enter the doctor mobile number.',
            'doctor_mobile.regex'    => 'Doctor mobile must be exactly 10 digits.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        $hospital         = null;
        $ownerUser        = null;
        $ownerJustCreated = false;

        DB::transaction(function () use (&$hospital, &$ownerUser, &$ownerJustCreated, $data) {
            // 1) Find or create owner user (login account)
            // Use hospital email as login email, owner_name as display name.
            $ownerUser = User::where('email', $data['email'])->first();

            if (! $ownerUser) {
                $ownerUser = User::create([
                    'name'     => $data['owner_name'],
                    'email'    => $data['email'],
                    'password' => Hash::make(Str::random(10)), // temporary; real one via reset link
                    'role'     => 'hospital_manager',
                ]);

                $ownerJustCreated = true;
            } else {
                // If an existing user is not admin, ensure they are at least hospital_manager
                if ($ownerUser->role !== 'admin') {
                    $ownerUser->role = 'hospital_manager';
                    $ownerUser->save();
                }
            }

            // 2) Create the hospital and link to owner user
            $hospital = new Hospital($data);
            $hospital->owners_user_id = $ownerUser->id;
            $hospital->save();
        });

        // After transaction commit, if we created a new owner user, send them CREATE-PASSWORD email
        if ($ownerJustCreated && $ownerUser) {
            // Generate a password reset token for first-time password creation
            $token = Password::broker()->createToken($ownerUser);

            // Send “Welcome, create your password” email (different from reset)
            $ownerUser->notify(new HospitalPasswordCreateNotification($token));
        }

        session()->flash(
            'success',
            'Hospital created successfully. A login link has been emailed to the owner at ' . $data['email'] . '.'
        );

        // AJAX / JSON response branch
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            $q = trim((string) $request->get('q', ''));
            $listQuery = Hospital::query();
            if ($q !== '') {
                $listQuery->where(function ($b) use ($q) {
                    $b->where('name', 'like', "%{$q}%")
                        ->orWhere('unique_id', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            }
            $hospitals = $listQuery->orderBy('id', 'desc')
                ->paginate($this->perPage)
                ->appends(['q' => $q]);

            $html = view()->exists('admin.hospitals.partials.list')
                ? view('admin.hospitals.partials.list', compact('hospitals'))->render()
                : '';

            return response()->json([
                'success'  => true,
                'message'  => 'Hospital created successfully. Login link emailed to owner.',
                'hospital' => $hospital,
                'html'     => $html,
            ], 201);
        }

        return redirect()
            ->route('admin.hospitals.index', $hospital->id)
            ->with('success', 'Hospital created. Owner will receive a login link by email.');
    }

    /** Show edit form */
    public function edit(Request $request, Hospital $hospital)
    {
        $viewHtml = view()->exists('admin.hospitals.partials.form')
            ? view('admin.hospitals.partials.form', compact('hospital'))->render()
            : view('admin.hospitals.edit', compact('hospital'))->render();

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json(['success' => true, 'html' => $viewHtml], 200);
        }

        return view('admin.hospitals.edit', compact('hospital'));
    }

    /** Update hospital */
    public function update(Request $request, Hospital $hospital)
    {
        // sanitize incoming unique_id: uppercase + allow A-Z0-9-_ only
        $incoming = (string) $request->input('unique_id', $hospital->unique_id ?? '');
        $incoming = strtoupper(preg_replace('/[^A-Z0-9\-_]/', '', $incoming));
        $request->merge(['unique_id' => $incoming]);

        $validator = Validator::make($request->all(), [
            'name'          => ['required', 'string', 'min:3', 'max:255', Rule::unique('hospitals', 'name')->ignore($hospital->id)],
            'email'         => ['required', 'email', 'max:255'],
            'phone'         => ['required', 'string', 'regex:/^[0-9]{10}$/', 'max:10'],
            'address'       => ['required', 'string', 'max:1000'],
            'unique_id'     => ['required', 'string', 'min:3', 'max:64', Rule::unique('hospitals', 'unique_id')->ignore($hospital->id)],
            'owner_name'    => ['required', 'string', 'max:255'],
            'owner_mobile'  => ['required', 'string', 'regex:/^[0-9]{10}$/', 'max:10'],
            'doctor_name'   => ['required', 'string', 'max:255'],
            'doctor_mobile' => ['required', 'string', 'regex:/^[0-9]{10}$/', 'max:10'],
        ], [
            'name.required'          => 'Please enter hospital name.',
            'name.unique'            => 'A hospital with this name already exists. Please choose a different name.',
            'email.required'         => 'Please enter hospital email address.',
            'email.email'            => 'Please enter a valid email address.',
            'phone.required'         => 'Please enter hospital phone number.',
            'phone.regex'            => 'Phone number must be exactly 10 digits.',
            'address.required'       => 'Please enter hospital address.',
            'unique_id.required'     => 'Unique ID is required.',
            'unique_id.unique'       => 'That Unique ID is already taken. Please choose another.',
            'owner_name.required'    => 'Please enter the owner / manager name.',
            'owner_mobile.required'  => 'Please enter the owner / manager mobile number.',
            'owner_mobile.regex'     => 'Owner / manager mobile must be exactly 10 digits.',
            'doctor_name.required'   => 'Please enter the doctor name.',
            'doctor_mobile.required' => 'Please enter the doctor mobile number.',
            'doctor_mobile.regex'    => 'Doctor mobile must be exactly 10 digits.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        $hospital->fill($data);
        $hospital->save();

        session()->flash('success', 'Hospital updated.');

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            $q = trim((string) $request->get('q', ''));
            $listQuery = Hospital::query();
            if ($q !== '') {
                $listQuery->where(function ($b) use ($q) {
                    $b->where('name', 'like', "%{$q}%")
                        ->orWhere('unique_id', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            }
            $hospitals = $listQuery->orderBy('id', 'desc')
                ->paginate($this->perPage)
                ->appends(['q' => $q]);
            $html = view()->exists('admin.hospitals.partials.list')
                ? view('admin.hospitals.partials.list', compact('hospitals'))->render()
                : '';

            return response()->json([
                'success'  => true,
                'message'  => 'Hospital updated successfully.',
                'hospital' => $hospital,
                'html'     => $html,
            ], 200);
        }

        return redirect()
            ->route('admin.hospitals.index', $hospital->id)
            ->with('success', 'Hospital updated.');
    }

    /** Delete hospital */
    public function destroy(Request $request, Hospital $hospital)
    {
        try {
            // 1) Capture the owner user (if any) before deleting hospital
            $ownerUser = null;
            if (! empty($hospital->owners_user_id)) {
                $ownerUser = User::find($hospital->owners_user_id);
            }

            // 2) Delete the hospital itself
            $hospital->delete();

            // 3) Optionally delete the owner user if:
            //    - user exists
            //    - user is JUST a hospital_manager (not admin)
            //    - user is not linked as owner to any other hospital
            if ($ownerUser && $ownerUser->role === 'hospital_manager') {
                $otherHospitalsCount = Hospital::where('owners_user_id', $ownerUser->id)->count();

                if ($otherHospitalsCount === 0) {
                    $ownerUser->delete();
                }
            }

            // 4) Existing response logic
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                $q = trim((string) $request->get('q', ''));
                $listQuery = Hospital::query();
                if ($q !== '') {
                    $listQuery->where(function ($b) use ($q) {
                        $b->where('name', 'like', "%{$q}%")
                            ->orWhere('unique_id', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
                }
                $hospitals = $listQuery->orderBy('id', 'desc')
                    ->paginate($this->perPage)
                    ->appends(['q' => $q]);
                $html = view()->exists('admin.hospitals.partials.list')
                    ? view('admin.hospitals.partials.list', compact('hospitals'))->render()
                    : '';

                return response()->json([
                    'success' => true,
                    'message' => 'Hospital deleted.',
                    'html'    => $html,
                ], 200);
            }

            return redirect()->route('admin.hospitals.index')->with('danger', 'Hospital deleted.');
        } catch (\Throwable $e) {
            Log::error('Failed to delete hospital', [
                'id'    => $hospital->id ?? null,
                'error' => $e->getMessage(),
            ]);
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete hospital.'], 500);
            }
            return back()->with('error', 'Failed to delete hospital.');
        }
    }

    /** 🔐 Send password reset link to hospital login user (admin action) */
    public function sendResetLink(Request $request, Hospital $hospital)
    {
        try {
            // 1) Find the linked login user
            $user = null;

            // Prefer owners_user_id (set in store())
            if (! empty($hospital->owners_user_id)) {
                $user = User::find($hospital->owners_user_id);
            }

            // Fallback: try by hospital email
            if (! $user && $hospital->email) {
                $user = User::where('email', $hospital->email)->first();
            }

            if (! $user) {
                if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No login user is linked with this hospital.',
                    ], 404);
                }

                return back()->with('error', 'No login user is linked with this hospital.');
            }

            // 2) Send standard password reset link
            $status = Password::broker()->sendResetLink([
                'email' => $user->email,
            ]);

            if ($status === Password::RESET_LINK_SENT) {
                $msg = 'Password reset link sent to ' . $user->email . '.';

                if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => $msg,
                    ]);
                }

                return back()->with('success', $msg);
            }

            // 3) Failed case
            $msg = 'Could not send password reset link. Please try again.';

            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], 500);
            }

            return back()->with('error', $msg);
        } catch (\Throwable $e) {
            Log::error('Failed to send hospital reset link', [
                'hospital_id' => $hospital->id ?? null,
                'error'       => $e->getMessage(),
            ]);

            $msg = 'Something went wrong while sending reset link.';

            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], 500);
            }

            return back()->with('error', $msg);
        }
    }

    /** Generate random uppercase unique id of provided length (alnum) */
    protected function generateUniqueId(int $len = 8): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $id = '';
            for ($i = 0; $i < $len; $i++) {
                $id .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (Hospital::where('unique_id', $id)->exists());

        return $id;
    }

    public function show(Request $request, Hospital $hospital)
    {
        if ($request->wantsJson()) {
            return response()->json($hospital);
        }

        // optional: fallback view if someone opens /admin/hospitals/{id} directly
        return view('admin.hospitals.show', compact('hospital'));
    }

    public function sendDeleteOtp(Request $request, Hospital $hospital)
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin' || empty($admin->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Admin email not found.'
            ], 422);
        }

        $otp = random_int(100000, 999999);

        DB::table('hospital_delete_otps')->updateOrInsert(
            ['hospital_id' => $hospital->id],
            [
                'otp_hash'   => Hash::make($otp),
                'expires_at' => now()->addMinutes(10),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        Mail::to($admin->email)
            ->send(new HospitalDeleteOtpMail($otp, $hospital->name));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to admin email.'
        ]);
    }

    public function verifyDeleteOtp(Request $request, Hospital $hospital)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $record = DB::table('hospital_delete_otps')
            ->where('hospital_id', $hospital->id)
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'OTP not found.'
            ], 422);
        }

        if (now()->greaterThan($record->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired.'
            ], 422);
        }

        if (!Hash::check($request->otp, $record->otp_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP.'
            ], 422);
        }

        DB::transaction(function () use ($hospital) {
            // reuse your EXISTING delete logic
            $request = request();
            $this->destroy($request, $hospital);

            DB::table('hospital_delete_otps')
                ->where('hospital_id', $hospital->id)
                ->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Hospital deleted successfully.'
        ]);
    }
}
