<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Appointment;
use App\Models\AppointmentItem;
use App\Models\Package;
use App\Models\LabTest;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use App\Exports\AppointmentsExport;
use App\Exports\AppointmentsPdfExport;
use App\Notifications\AppointmentCreatedNotification;
use App\Notifications\NewAppointmentNotification;
use App\Http\Controllers\WhatsappController;
use App\Notifications\AppointmentStatusNotification;
use App\Http\Controllers\EasebuzzController;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentBookedMail;
use App\Mail\NewAppointmentAdminMail;

class AppointmentController extends Controller
{
    /**
     * Validate list/export filters and enforce date_to >= date_from.
     * Returns the normalized [$dateFrom, $dateTo].
     */
    protected function validateFilterDates(Request $request): array
    {
        $validated = $request->validate([
            'q'         => ['nullable', 'string'],
            'sort'      => ['nullable', 'in:asc,desc'],
            'per_page'  => ['nullable', 'integer', 'min:1', 'max:200'],

            // both-or-none behavior + friendly message
            'date_from' => ['nullable', 'date', 'required_with:date_to', 'before_or_equal:today'],
            'date_to'   => ['nullable', 'date', 'required_with:date_from', 'after_or_equal:date_from', 'before_or_equal:today'],
        ], [
            'date_from.required_with'   => 'From and To date are required.',
            'date_to.required_with'     => 'From and To date are required.',
            'date_to.after_or_equal'    => 'To Date must be on or after From Date.',
            'date_from.before_or_equal' => 'From Date cannot be in the future.',
            'date_to.before_or_equal'   => 'To Date cannot be in the future.',
        ]);

        return [
            $validated['date_from'] ?? null,
            $validated['date_to']   ?? null,
        ];
    }

    /**
     * Show booking form.
     */
    public function create(Request $request): View|RedirectResponse
    {
         dd('sdf');
        $package = null;
        $displayPrice = null;
        $savePercent = 0;

        $packageId = $request->query('package_id');

        if ($packageId) {
            $package = Package::find($packageId);
            if (! $package) {
                return redirect()->route('packages.index')->with('error', 'Selected package not found.');
            }

            [$displayPrice, $savePercent] = $this->computeDisplayPriceFromPackage($package);
        }

        $packages = Package::orderBy('title')->get();
        $tests = LabTest::select('id', 'test_name', 'mrp')->orderBy('test_name')->get();

        return view('booking', compact('package', 'displayPrice', 'savePercent', 'packages', 'tests'));
    }

    public function adminCreate()
    {
        $hospitals = Hospital::orderBy('name')->get();

        // ✅ Lab tests use `test_name`
        $tests = LabTest::where('status', 'Published')
            ->orderBy('test_name')
            ->get();

        // ✅ Packages use `title`
        $packages = Package::where('status', 'Published')
            ->orderBy('title')
            ->get();

        return view('admin.appointments.create', compact(
            'hospitals',
            'tests',
            'packages'
        ));
    }

    public function edit(Appointment $appointment)
    {
        // 🚫 Block if completed
        if ($appointment->status === 'Completed') {
            return redirect()
                ->route('admin.appointments.index')
                ->with('error', 'Completed appointments cannot be edited.');
        }

        $items = AppointmentItem::where('appointment_id', $appointment->id)->get();

        return view('admin.appointments.edit', [
            'appointment' => $appointment,
            'items'       => $items,
        ]);
    }


    public function update(Request $request, Appointment $appointment)
    {
        // 🚫 Block if completed
        if ($appointment->status === 'completed') {
            return redirect()
                ->route('admin.appointments.index')
                ->with('error', 'Completed appointments cannot be updated.');
        }

        $data = $request->validate([
            'name'        => 'required|string',
            'gender'      => 'nullable|string',
            'dob'         => 'nullable|date',
            'age'         => 'nullable|integer',
            'email'       => 'nullable|email',
            'phone'       => 'required|string',
            'address'     => 'required|string',
            'city'        => 'required|string',
            'pincode'     => 'required|string',
            'landmark'    => 'nullable|string',
            'message'     => 'nullable|string',
            'subtotal'    => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
        ]);

        $appointment->update($data);

        return redirect()
            ->back()
            ->with('success', 'Appointment updated successfully');
    }


    public function adminStore(Request $request, EasebuzzController $easebuzz)
    {
        $easebuzz->createAppointmentFromRequest($request, 'cash');

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Appointment created successfully.');
    }
    /**
     * Store a new appointment.
     */
    public function store(Request $request): RedirectResponse
    {
       
        // accept either 'slot' or 'time_slot'
        $normalizedSlot = $request->input('slot', $request->input('time_slot', null));

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:32',
            'date'         => 'required|date_format:Y-m-d',
            'slot'         => 'nullable|string',
            'time_slot'    => 'nullable|string',
            'package_id'   => 'nullable|integer|exists:packages,id',
            'package_name' => 'nullable|string|max:255',
            'test_id'      => 'nullable|integer|exists:lab_tests,id',
            'service'      => 'nullable|string|max:255',
            'message'      => 'nullable|string',
            'total_price'  => 'nullable|numeric|min:0',
            'items'                 => 'nullable|array',
            'items.*.item_type'     => 'required_with:items|string|max:50',
            'items.*.item_id'       => 'nullable|integer',
            'items.*.item_name'     => 'required_with:items|string|max:255',
            'items.*.item_price'    => 'required_with:items|numeric|min:0',
            'items.*.quantity'      => 'nullable|integer|min:1',
            // hospital attribution inputs
            'hospital_unique_id'    => 'nullable|string|max:100',
            'hospital_ref'          => 'nullable|string|max:100',
        ]);

        // ------------------- HOSPITAL ATTRIBUTION -------------------
        $incomingRef = $validated['hospital_unique_id']
            ?? $validated['hospital_ref']
            ?? $request->input('ref')
            ?? (is_array(session('hospital_ref')) ? session('hospital_ref')['unique_id'] ?? null : session('hospital_ref'));

        $hospitalId = null;
        $hospitalUnique = $incomingRef ?? null;

        if ($hospitalUnique && class_exists(Hospital::class)) {
            try {
                $hospital = Hospital::where('unique_id', $hospitalUnique)->first();
                if ($hospital) {
                    $hospitalId = $hospital->id;
                }
            } catch (\Throwable $e) {
                // swallow
            }
        }
        // -----------------------------------------------------------

        // Normalize items (accept JSON string or array)
        $items = $request->input('items', null);
        if (is_string($items) && $items !== '') {
            $decoded = json_decode($items, true);
            $items = is_array($decoded) ? $decoded : null;
        } elseif (!is_array($items)) {
            $items = null;
        }

        // Preload package & test
        $package = !empty($validated['package_id']) ? Package::find($validated['package_id']) : null;
        $test    = !empty($validated['test_id']) ? LabTest::find($validated['test_id']) : null;

        // Determine service fallback
        $serviceFallback = $validated['service'] ?? $validated['package_name'] ?? ($package->title ?? null);
        if (empty($serviceFallback) && $test) {
            $serviceFallback = $test->test_name ?? null;
        }
        if (empty($serviceFallback)) {
            $serviceFallback = 'Service';
        }

        // Normalize time slot to HH:MM:SS if possible
        $timeSlotDb = null;
        $slotRaw = $normalizedSlot;
        if (!empty($slotRaw)) {
            try {
                $format = strlen($slotRaw) === 5 ? 'H:i' : 'H:i:s';
                $timeSlotDb = Carbon::createFromFormat($format, $slotRaw)->format('H:i:s');
            } catch (\Throwable $e) {
                $timeSlotDb = null;
            }
        }

        $appointment = null;

        DB::transaction(function () use (
            $validated,
            $items,
            $timeSlotDb,
            $serviceFallback,
            $package,
            $test,
            &$appointment,
            $hospitalId,
            $hospitalUnique
        ) {
            // Create appointment core fields (including hospital attribution)
            $appointment = Appointment::create([
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'phone'        => $validated['phone'],
                'date'         => $validated['date'],
                'time_slot'    => $timeSlotDb,
                'package_id'   => $validated['package_id'] ?? null,
                'package_name' => $validated['package_name'] ?? ($package->title ?? null),
                'test_id'      => $validated['test_id'] ?? null,
                'service'      => $serviceFallback,
                'message'      => $validated['message'] ?? null,
                'status'       => 'Pending',
                'hospital_id'        => $hospitalId,
                'hospital_unique_id' => $hospitalUnique,
            ]);

            // ---- Compute authoritative subtotal from items/package/test ----
            $totalFromItems = 0.0;
            if (is_array($items) && count($items) > 0) {
                foreach ($items as $it) {
                    $qty   = isset($it['quantity']) ? (int)$it['quantity'] : 1;
                    $price = isset($it['item_price']) ? (float)$it['item_price'] : 0.0;
                    $name  = $it['item_name'] ?? ($it['name'] ?? 'Item');

                    $appointment->items()->create([
                        'item_type'  => $it['item_type'] ?? 'item',
                        'item_id'    => $it['item_id'] ?? null,
                        'item_name'  => $name,
                        'item_price' => $price,
                        'quantity'   => $qty,
                    ]);

                    $totalFromItems += ($price * $qty);
                }
            }

            // package/test fallback if no items
            $computedFromPkgOrTest = null;
            if ($package) {
                $computedFromPkgOrTest = $this->computeDisplayPriceFromPackage($package)[0];
            }
            if (is_null($computedFromPkgOrTest) && $test) {
                $computedFromPkgOrTest = isset($test->mrp) ? (float)$test->mrp : (isset($test->price) ? (float)$test->price : null);
            }

            // Subtotal priority: items -> pkg/test -> client provided (as last resort)
            $subtotal = $totalFromItems > 0
                ? $totalFromItems
                : (!is_null($computedFromPkgOrTest) ? $computedFromPkgOrTest : (float)($validated['total_price'] ?? 0));

            $subtotal = max(0, round((float)$subtotal, 2));

            // ---- Coupon snapshot from session (trusted) ----
            $applied = session('applied_coupon'); // set by /checkout/coupon/apply
            $discount = (float)($applied['discount_applied'] ?? 0.0);
            // never let discount exceed subtotal
            $discount = max(0, min($discount, $subtotal));
            $grandTotal = max(0, round($subtotal - $discount, 2));

            // ---- Persist pricing + coupon snapshot ----
            $appointment->subtotal        = $subtotal;
            $appointment->discount_amount = $discount;
            $appointment->coupon_code     = $applied['coupon_code']   ?? null;
            $appointment->discount_type   = $applied['discount_type'] ?? null;   // 'percent' | 'fixed'
            $appointment->discount_value  = $applied['discount_value'] ?? null;   // 10 or 200.00
            $appointment->coupon_id       = $applied['coupon_id']     ?? null;
            $appointment->total_price     = $grandTotal;
            $appointment->save();

            // ---- Log coupon usage + optional audit row ----
            if (!empty($applied)) {
                if (!empty($applied['coupon_id'])) {
                    Coupon::find($applied['coupon_id'])?->recordUsage($appointment->id, auth()->id(), 1);
                }
                if (class_exists('App\Models\AppointmentCoupon')) {
                    $appointment->appliedCoupons()->create([
                        'coupon_id'        => $applied['coupon_id'] ?? null,
                        'coupon_code'      => $applied['coupon_code'] ?? null,
                        'discount_type'    => $applied['discount_type'] ?? null,
                        'discount_value'   => $applied['discount_value'] ?? null,
                        'discount_applied' => $applied['discount_applied'] ?? 0,
                        'metadata'         => $applied['metadata'] ?? null,
                    ]);
                }
            }
        });

        // ✅ Send Admin Email Notification
        try {
            $adminEmail = config('mail.admin_email');
            if (!empty($adminEmail) && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                Mail::to($adminEmail)->send(new NewAppointmentAdminMail($appointment));
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send admin appointment email notification (AppointmentController)', [
                'appointment_id' => $appointment->id ?? null,
                'error'          => $e->getMessage()
            ]);
        }

        // ✅ Send Patient/Customer Email Notification
        try {
            if (!empty($appointment->email) && filter_var($appointment->email, FILTER_VALIDATE_EMAIL) && !str_contains($appointment->email, 'guest@wellcare')) {
                Mail::to($appointment->email)->send(new AppointmentBookedMail($appointment));
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send patient appointment email notification (AppointmentController)', [
                'appointment_id' => $appointment->id ?? null,
                'error'          => $e->getMessage()
            ]);
        }

        // Send WhatsApp notification to Admin for new booking
        try {
            $adminNumber = config('services.dovesoft.admin_number');
            if ($adminNumber) {
                $digits = preg_replace('/\D+/', '', $adminNumber);
                if (strlen($digits) === 10) {
                    $digits = '91' . $digits;
                }
                if ($digits) {
                    app(\App\Services\DovesoftService::class)->sendTemplate(
                        $digits,
                        'appointment',
                        'en',
                        []
                    );
                }
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send appointment WhatsApp notification to admin (AppointmentController)', [
                'appointment_id' => $appointment->id ?? null,
                'error'          => $e->getMessage()
            ]);
        }

        // Send WhatsApp notification to Customer for new booking
        try {
            if (!empty($appointment?->phone)) {
                $mobile = preg_replace('/\D+/', '', $appointment->phone);
                if (strlen($mobile) === 10) {
                    $mobile = '91' . $mobile;
                }
                try {
                    $formattedDate = !empty($appointment->date)
                        ? \Carbon\Carbon::parse($appointment->date)->format('d/m/Y')
                        : '';
                } catch (\Throwable $e) {
                    $formattedDate = (string) ($appointment->date ?? '');
                }
                try {
                    $formattedSlot = !empty($appointment->time_slot)
                        ? \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A')
                        : '';
                } catch (\Throwable $e) {
                    $formattedSlot = (string) ($appointment->time_slot ?? '');
                }
                $bodyParams = [
                    $appointment->name ?? '',                 // {{1}} Name
                    $formattedDate,                            // {{2}} Date
                    $formattedSlot,                            // {{3}} Slot
                    (string) ($appointment->service ?? 'Lab Test'), // {{4}} Test details
                    $appointment->name ?? '',                 // {{5}} Name
                    (string) ($appointment->age ?? ''),        // {{6}} Age
                    (string) ($appointment->gender ?? ''),     // {{7}} Gender
                ];

                app(\App\Services\DovesoftService::class)->sendTemplate(
                    $mobile,
                    'bookingupdatednew',
                    'en',
                    $bodyParams
                );
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send appointment WhatsApp notification to customer (AppointmentController)', [
                'appointment_id' => $appointment->id ?? null,
                'error'          => $e->getMessage()
            ]);
        }

        // Clear session state
        try {
            session()->forget('hospital_ref');
            session()->forget('applied_coupon');
            session()->forget('cart_items');
        } catch (\Throwable $e) {
            // swallow
        }

        return back()->with('success', 'Appointment booked successfully!');
    }

    /**
     * Apply payment filter to appointment queries
     */
    protected function applyPaymentFilter($query)
    {
        return $query->where(function ($q) {
            // Show appointments that either have successful/failed payments
            // OR have payment_method as 'cash'
            $q->whereHas('payments', function ($paymentQuery) {
                $paymentQuery->whereIn('status', ['success', 'failed']);
            })
                ->orWhere('payment_method', 'cash');
        });
    }

    /**
     * Paginated appointments for admin dashboard / API.
     */
    public function index(Request $request)
    {
        [$dateFrom, $dateTo] = $this->validateFilterDates($request);

        $perPage = min(max((int)$request->get('per_page', 20), 1), 200);
        $q       = trim((string)$request->get('q', ''));
        $sort    = strtolower($request->get('sort', ''));

        $query = Appointment::with(['items', 'package', 'test']);

        // Apply payment filter
        $query = $this->applyPaymentFilter($query);

        if ($q !== '') {
            $normalized = strtolower($q);

            // Status synonyms for exact keyword match like "completed", also covers variants
            $statusSynonyms = [
                'pending'    => ['pending', 'pending_payment', 'awaiting'],
                'approved'   => ['approved', 'approve'],
                'completed'  => ['completed', 'complete', 'done'],
                'cancelled'  => ['cancelled', 'canceled'],
                'reschedule' => ['reschedule', 'rescheduled'],
            ];

            $statusValues = collect($statusSynonyms)
                ->filter(fn($vals, $key) => $normalized === $key || in_array($normalized, array_map('strtolower', $vals), true))
                ->flatten()
                ->map(fn($v) => strtolower($v))
                ->values()
                ->all();

            $query->where(function ($builder) use ($q, $normalized, $statusValues) {
                if (ctype_digit($q)) {
                    $builder->orWhere('id', intval($q));
                }

                $builder->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('service', 'like', "%{$q}%")
                    ->orWhere('message', 'like', "%{$q}%");

                // also search the related hospitals table (if relation exists)
                $builder->orWhereHas('hospital', function ($h) use ($q) {
                    $h->where('name', 'like', "%{$q}%");
                });

                // search by date (YYYY-MM-DD)
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $q)) {
                    $builder->orWhereDate('date', $q);
                }

                // NEW: status-aware search (exact family or partial)
                if (!empty($statusValues)) {
                    $builder->orWhereIn(DB::raw('LOWER(status)'), $statusValues);
                } else {
                    $builder->orWhereRaw('LOWER(status) LIKE ?', ['%' . $normalized . '%']);
                }

                // relateds
                $builder->orWhereHas('package', function ($b) use ($q) {
                    $b->where('title', 'like', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%");
                });

                $builder->orWhereHas('test', function ($b) use ($q) {
                    $b->where('test_name', 'like', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%");
                });

                $builder->orWhereHas('items', function ($b) use ($q) {
                    $b->where('item_name', 'like', "%{$q}%")
                        ->orWhere('item_type', 'like', "%{$q}%");
                });
            });
        }

        // Date range
        if (!empty($dateFrom)) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $query->whereDate('date', '<=', $dateTo);
        }

        if (in_array($sort, ['asc', 'desc'])) {
            $query->orderByRaw("COALESCE(date, date) {$sort}");
        } else {
            $query->orderByDesc('id');
        }

        $appointments = $query->paginate($perPage)
            ->appends($request->only(['q', 'per_page', 'date_from', 'date_to', 'sort']));

        return view('admin.appointments.index', [
            'appointments' => $appointments,
            'q'            => $q,
            'dateFrom'     => $dateFrom,
            'dateTo'       => $dateTo,
            'sort'         => $sort,
            'perPage'      => $perPage,
        ]);
    }

    /**
     * Paginated appointments that originated from hospital QR codes.
     */
    public function indexHospitalQr(Request $request)
    {
        [$dateFrom, $dateTo] = $this->validateFilterDates($request);

        $perPage = min(max((int)$request->get('per_page', 20), 1), 200);
        $q       = trim((string)$request->get('q', ''));
        $sort    = strtolower($request->get('sort', ''));

        // Include relationships so we can search in related models
        $query = Appointment::with(['items', 'package', 'test', 'hospital']);

        // Apply payment filter
        $query = $this->applyPaymentFilter($query);

        if ($q !== '') {
            $normalized = strtolower($q);

            $statusSynonyms = [
                'pending'    => ['pending', 'pending_payment', 'awaiting'],
                'approved'   => ['approved', 'approve'],
                'completed'  => ['completed', 'complete', 'done'],
                'cancelled'  => ['cancelled', 'canceled'],
                'reschedule' => ['reschedule', 'rescheduled'],
            ];
            $statusValues = collect($statusSynonyms)
                ->filter(fn($vals, $key) => $normalized === $key || in_array($normalized, array_map('strtolower', $vals), true))
                ->flatten()
                ->map(fn($v) => strtolower($v))
                ->values()
                ->all();

            $query->where(function ($builder) use ($q, $normalized, $statusValues) {
                if (ctype_digit($q)) {
                    $builder->orWhere('id', intval($q));
                }

                $builder->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('service', 'like', "%{$q}%")
                    ->orWhere('message', 'like', "%{$q}%");

                if (Schema::hasColumn('appointments', 'hospital_name')) {
                    $builder->orWhere('hospital_name', 'like', "%{$q}%");
                }

                $builder->orWhereHas('hospital', function ($h) use ($q) {
                    $h->where('name', 'like', "%{$q}%");
                });

                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $q)) {
                    $builder->orWhereDate('date', $q);
                }

                // NEW: status-aware search
                if (!empty($statusValues)) {
                    $builder->orWhereIn(DB::raw('LOWER(status)'), $statusValues);
                } else {
                    $builder->orWhereRaw('LOWER(status) LIKE ?', ['%' . $normalized . '%']);
                }

                $builder->orWhereHas('package', function ($b) use ($q) {
                    $b->where('title', 'like', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%");
                });

                $builder->orWhereHas('test', function ($b) use ($q) {
                    $b->where('test_name', 'like', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%");
                });

                $builder->orWhereHas('items', function ($b) use ($q) {
                    $b->where('item_name', 'like', "%{$q}%")
                        ->orWhere('item_type', 'like', "%{$q}%");
                });
            });
        }

        // Date filters
        if (!empty($dateFrom)) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $query->whereDate('date', '<=', $dateTo);
        }

        // Sorting
        if (in_array($sort, ['asc', 'desc'])) {
            $query->orderByRaw("COALESCE(date, created_at) {$sort}");
        } else {
            $query->orderByDesc('id');
        }

        // Only hospital QR appointments
        $query->where(function ($qrb) {
            $qrb->whereNotNull('hospital_id')
                ->orWhere(function ($q2) {
                    $q2->whereNotNull('hospital_unique_id')
                        ->where('hospital_unique_id', '<>', '');
                });
        });

        $appointments = $query->paginate($perPage)
            ->appends($request->only(['q', 'per_page', 'date_from', 'date_to', 'sort']));

        return view('admin.appointments.hospital_qr_index', [
            'appointments' => $appointments,
            'q'            => $q,
            'dateFrom'     => $dateFrom,
            'dateTo'       => $dateTo,
            'sort'         => $sort,
            'perPage'      => $perPage,
        ]);
    }

    /**
     * Update appointment status (AJAX).
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        // 🔒 CHECK: If appointment is already completed, prevent any status change
        if ($appointment->isCompleted()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot change status of a completed appointment.',
                ], 422);
            }
            return back()->with('error', 'Cannot change status of a completed appointment.');
        }

        $status = $request->input('status', null);

        if ($status === null && $request->getContent()) {
            $content = $request->getContent();
            if ($content) {
                $decoded = json_decode($content, true);
                if (is_array($decoded) && array_key_exists('status', $decoded)) {
                    $status = $decoded['status'];
                }
            }
        }

        $validator = \Validator::make(['status' => $status], [
            'status' => 'required|in:Pending,Approved,Reschedule,Completed,Cancelled',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => implode('; ', $errors),
                    'errors'  => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator);
        }

        try {
            $appointment->status = $status;
            $appointment->save();

            // 🚫 Notification for status change REMOVED

            // ---------- send PDF via WhatsApp when status becomes Completed ----------
            try {
                if (strtolower($status) === 'completed') {
                    $publicUrl = null;
                    try {
                        $report = null;
                        if (method_exists($appointment, 'report')) {
                            $report = $appointment->report ?? ($appointment->report()->exists() ? $appointment->report : null);
                        } else {
                            $report = $appointment->report ?? null;
                        }

                        if (!empty($report)) {
                            $publicUrl = $report->public_url
                                ?? $report->report_url
                                ?? $report->file_url
                                ?? $report->url
                                ?? null;
                        }
                    } catch (\Throwable $e) {
                        $publicUrl = null;
                    }

                    if (empty($publicUrl)) {
                        $publicUrl = $appointment->report_url
                            ?? $appointment->report_link
                            ?? $appointment->report_path
                            ?? null;
                    }

                    $mobileTo = $appointment->phone ?? null;

                    if ($mobileTo && $publicUrl) {
                        try {
                            $wa = new WhatsappController();
                            $filename = basename(parse_url($publicUrl, PHP_URL_PATH) ?: 'report.pdf');
                            $caption  = "Wellcare Labs — Report for appointment #{$appointment->id}";
                            $wa->sendDocument($mobileTo, $publicUrl, $filename, $caption);
                        } catch (\Throwable $e) {
                            // swallow
                        }
                    }
                }
            } catch (\Throwable $inner) {
                // swallow
            }
            // -------------------------------------------------------------------------

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully!',
                    'status'  => $appointment->status,
                ], 200);
            }

            return back()->with('success', 'Status updated successfully!');
        } catch (\Throwable $e) {

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update status. Please try again.',
                ], 500);
            }

            return back()->with('error', 'Failed to update appointment status. Please try again.');
        }
    }



    /**
     * Show reschedule form for admin.
     */
    public function editReschedule(Request $request, Appointment $appointment): View
    {
        return view('admin.appointments.reschedule', compact('appointment'));
    }

    /**
     * Handle reschedule update.
     */
    public function updateReschedule(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'date'      => 'required|date_format:Y-m-d',
            'time_slot' => 'nullable|string|max:255',
        ]);

        try {
            // Update appointment
            $appointment->date      = $validated['date'];
            $appointment->time_slot = $validated['time_slot'] ?? null;
            $appointment->status    = 'Reschedule';
            $appointment->save();

            // ✅ WhatsApp Rescheduling Message (Dovesoft Template)
            try {
                if (!empty($appointment?->phone)) {

                    $mobile = preg_replace('/\D+/', '', $appointment->phone);
                    if (strlen($mobile) === 10) {
                        $mobile = '91' . $mobile;
                    }
                    try {
                        $formattedDate = !empty($appointment->date)
                            ? \Carbon\Carbon::parse($appointment->date)->format('d/m/Y')
                            : '';
                    } catch (\Throwable $e) {
                        $formattedDate = (string) ($appointment->date ?? '');
                    }

                    try {
                        $formattedSlot = !empty($appointment->time_slot)
                            ? \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A')
                            : '';
                    } catch (\Throwable $e) {
                        $formattedSlot = (string) ($appointment->time_slot ?? '');
                    }
                    $bodyParams = [
                        $appointment->name ?? '',                 // {{1}} Name
                        $formattedDate,                            // {{2}} Date
                        $formattedSlot,                            // {{3}} Slot
                        (string) ($appointment->service ?? ''),    // {{4}} Test details
                        $appointment->name ?? '',                 // {{5}} Name
                        (string) ($appointment->age ?? ''),        // {{6}} Age
                        (string) ($appointment->gender ?? ''),     // {{7}} Gender
                    ];
                    app(\App\Services\DovesoftService::class)->sendTemplate(
                        $mobile,
                        'reshedulingupdated1',
                        'en',
                        $bodyParams
                    );
                }
            } catch (\Throwable $e) {
                // silent fail
            }

            return redirect()
                ->route('admin.appointments.index')
                ->with('success', 'Appointment rescheduled successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to reschedule appointment. Please try again.');
        }
    }



    /**
     * Dashboard summary (cached).
     */
    public function summary(Request $request): JsonResponse
    {
        $summary = Cache::remember('appointments_summary', 300, function () {
            $now = Carbon::now();
            $startOfMonth = $now->copy()->startOfMonth()->toDateString();
            $endOfMonth = $now->copy()->endOfMonth()->toDateString();

            $query = Appointment::query();
            $query = $this->applyPaymentFilter($query);

            return [
                'total_this_month' => $query->whereBetween('date', [$startOfMonth, $endOfMonth])->count(),
                'pending' => $query->where('status', 'Pending')->count(),
                'completed' => $query->where('status', 'Completed')->count(),
                'rescheduled' => $query->where('status', 'Reschedule')->count(),
                'updated_at' => $now->toDateTimeString(),
            ];
        });

        return response()->json($summary);
    }

    /**
     * Export appointments to Excel.
     */
    public function exportExcel(Request $request)
    {
        [$dateFrom, $dateTo] = $this->validateFilterDates($request);

        $q          = trim((string) $request->get('q', ''));
        $sort       = strtolower((string) $request->get('sort', ''));
        $hospitalQr = $request->get('hospital_qr');

        $query = Appointment::with(['items', 'package', 'test', 'hospital']);

        // Apply payment filter
        $query = $this->applyPaymentFilter($query);

        if ($q !== '') {
            $normalized = strtolower($q);

            $statusSynonyms = [
                'pending'    => ['pending', 'pending_payment', 'awaiting'],
                'approved'   => ['approved', 'approve'],
                'completed'  => ['completed', 'complete', 'done'],
                'cancelled'  => ['cancelled', 'canceled'],
                'reschedule' => ['reschedule', 'rescheduled'],
            ];

            $statusValues = collect($statusSynonyms)
                ->filter(fn($vals, $key) => $normalized === $key || in_array($normalized, array_map('strtolower', $vals), true))
                ->flatten()
                ->map(fn($v) => strtolower($v))
                ->values()
                ->all();

            $query->where(function ($builder) use ($q, $normalized, $statusValues) {
                if (ctype_digit($q)) $builder->orWhere('id', intval($q));

                $builder->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('service', 'like', "%{$q}%")
                    ->orWhere('message', 'like', "%{$q}%")
                    ->orWhereHas('hospital', fn($h) => $h->where('name', 'like', "%{$q}%"));

                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $q))
                    $builder->orWhereDate('date', $q);

                if (!empty($statusValues))
                    $builder->orWhereIn(DB::raw('LOWER(status)'), $statusValues);
                else
                    $builder->orWhereRaw('LOWER(status) LIKE ?', ['%' . $normalized . '%']);

                $builder->orWhereHas('package', fn($b) => $b->where('title', 'like', "%{$q}%"));
                $builder->orWhereHas('test', fn($b) => $b->where('test_name', 'like', "%{$q}%"));
                $builder->orWhereHas('items', fn($b) => $b->where('item_name', 'like', "%{$q}%"));
            });
        }

        if (!empty($dateFrom)) $query->whereDate('date', '>=', $dateFrom);
        if (!empty($dateTo))   $query->whereDate('date', '<=', $dateTo);

        if (!empty($hospitalQr)) {
            $query->where(function ($qrb) {
                $qrb->whereNotNull('hospital_id')
                    ->orWhere(function ($q2) {
                        $q2->whereNotNull('hospital_unique_id')->where('hospital_unique_id', '<>', '');
                    });
            });
        }

        if (in_array($sort, ['asc', 'desc']))
            $query->orderByRaw("COALESCE(date, created_at) {$sort}");
        else
            $query->orderByDesc('id');

        $appointments = $query->get();
        if ($appointments->isEmpty())
            return back()->with('error', 'No appointments found for selected filters.');

        // Map rows (same as PDF) + Hospital
        $rows = $appointments->values()->map(function ($a, $index) {
            $itemLabel =
                $a->items_summary
                ?? optional($a->items->first())->item_name
                ?? $a->package_name
                ?? $a->service
                ?? optional($a->test)->test_name
                ?? '';

            $price = $a->total_price ?? $a->subtotal ?? 0;

            $dateStr = !empty($a->date)
                ? Carbon::parse($a->date)->format('d/m/Y')
                : '';

            $timeStr = '';
            $rawTime = $a->time_slot ?? $a->time ?? null;
            if ($rawTime) {
                try {
                    $timeStr = Carbon::parse($rawTime)->format('h:i A');
                } catch (\Throwable $e) {
                    $timeStr = $rawTime;
                }
            }

            $hospitalName = optional($a->hospital)->name ?? '';

            return (object) [
                'sr_no'    => $index + 1,
                'name'     => $a->name,
                'email'    => $a->email,
                'phone'    => $a->phone,
                'date'     => $dateStr,
                'time'     => $timeStr,
                'item'     => $itemLabel,
                'hospital' => $hospitalName,
                'price'    => $price,
                'status'   => $a->status,
            ];
        });

        // Anonymous Export Class with Hospital column
        $export = new class($rows) implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\WithMapping,
            \Maatwebsite\Excel\Concerns\ShouldAutoSize {

            protected $rows;
            public function __construct($rows)
            {
                $this->rows = $rows;
            }
            public function collection()
            {
                return $this->rows;
            }

            public function headings(): array
            {
                return [
                    'Sr.No',
                    'Name',
                    'Email',
                    'Phone',
                    'Date',
                    'Time',
                    'Item',
                    'Hospital Name',
                    'Price',
                    'Status',
                ];
            }

            public function map($row): array
            {
                return [
                    $row->sr_no,
                    $row->name,
                    $row->email,
                    $row->phone,
                    $row->date,
                    $row->time,
                    $row->item,
                    $row->hospital,
                    $row->price,
                    $row->status,
                ];
            }
        };

        $range = (!empty($dateFrom) || !empty($dateTo))
            ? '_' . ($dateFrom ?? 'start') . '_' . ($dateTo ?? 'end')
            : '';

        $suffix   = !empty($hospitalQr) ? '_hospital_qr' : '';
        $filename = 'appointments' . $suffix . $range . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download($export, $filename);
    }

    /**
     * Export appointments to PDF.
     */
    public function exportPdf(Request $request)
    {
        [$dateFrom, $dateTo] = $this->validateFilterDates($request);

        $q          = trim((string) $request->get('q', ''));
        $sort       = strtolower((string) $request->get('sort', ''));
        $hospitalQr = $request->get('hospital_qr');      // toggle
        $hospitalId = $request->get('hospital_id');      // dropdown (if any)

        $query = Appointment::with(['items', 'package', 'test', 'hospital']);

        // Apply payment filter
        $query = $this->applyPaymentFilter($query);

        // ---------- SEARCH / FILTER ----------
        if ($q !== '') {
            $normalized = strtolower($q);

            $statusSynonyms = [
                'pending'    => ['pending', 'pending_payment', 'awaiting'],
                'approved'   => ['approved', 'approve'],
                'completed'  => ['completed', 'complete', 'done'],
                'cancelled'  => ['cancelled', 'canceled'],
                'reschedule' => ['reschedule', 'rescheduled'],
            ];

            $statusValues = collect($statusSynonyms)
                ->filter(fn($vals, $key) => $normalized === $key || in_array($normalized, array_map('strtolower', $vals), true))
                ->flatten()
                ->map(fn($v) => strtolower($v))
                ->values()
                ->all();

            $query->where(function ($builder) use ($q, $normalized, $statusValues) {
                if (ctype_digit($q)) {
                    $builder->orWhere('id', intval($q));
                }

                $builder->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('service', 'like', "%{$q}%")
                    ->orWhere('message', 'like', "%{$q}%")
                    ->orWhereHas('hospital', fn($h) => $h->where('name', 'like', "%{$q}%"));

                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $q)) {
                    $builder->orWhereDate('date', $q);
                }

                if (!empty($statusValues)) {
                    $builder->orWhereIn(DB::raw('LOWER(status)'), $statusValues);
                } else {
                    $builder->orWhereRaw('LOWER(status) LIKE ?', ['%' . $normalized . '%']);
                }

                $builder->orWhereHas('package', fn($b) => $b->where('title', 'like', "%{$q}%"));
                $builder->orWhereHas('test', fn($b) => $b->where('test_name', 'like', "%{$q}%"));
                $builder->orWhereHas('items', fn($b) => $b->where('item_name', 'like', "%{$q}%"));
            });
        }

        // dates
        if (!empty($dateFrom)) $query->whereDate('date', '>=', $dateFrom);
        if (!empty($dateTo))   $query->whereDate('date', '<=', $dateTo);

        // hospital QR filter
        if (!empty($hospitalQr)) {
            $query->where(function ($qrb) {
                $qrb->whereNotNull('hospital_id')
                    ->orWhere(function ($q2) {
                        $q2->whereNotNull('hospital_unique_id')
                            ->where('hospital_unique_id', '<>', '');
                    });
            });
        }

        // explicit hospital filter
        if (!empty($hospitalId)) {
            $query->where('hospital_id', $hospitalId);
        }

        // sort
        if (in_array($sort, ['asc', 'desc'])) {
            $query->orderByRaw("COALESCE(date, created_at) {$sort}");
        } else {
            $query->orderByDesc('id');
        }

        $appointments = $query->get();

        if ($appointments->isEmpty()) {
            return back()->with('error', 'No appointments found for selected filters.');
        }

        // ---------- Hospital name for header ----------
        $hospitalName = null;
        if (!empty($hospitalId)) {
            $hospitalName = optional($appointments->first()->hospital)->name;
        } else {
            $hospitalNames = $appointments->pluck('hospital.name')->filter()->unique()->values();
            if ($hospitalNames->count() === 1) {
                $hospitalName = $hospitalNames->first();
            }
        }

        // ---------- Map rows for PDF (simple objects) ----------
        $rows = $appointments->values()->map(function ($a, $index) {
            // Item label same as list page
            $itemLabel =
                $a->items_summary
                ?? optional($a->items->first())->item_name
                ?? $a->package_name
                ?? $a->service
                ?? optional($a->test)->test_name
                ?? '';

            $price = $a->total_price ?? $a->subtotal ?? 0;

            // formatted date
            $dateStr = '';
            if (!empty($a->date)) {
                try {
                    $dateStr = Carbon::parse($a->date)->format('d/m/Y');
                } catch (\Throwable $e) {
                    $dateStr = (string)$a->date;
                }
            }

            // formatted time
            $timeStr = '';
            $rawTime = $a->time_slot ?? $a->time ?? null;
            if (!empty($rawTime)) {
                try {
                    $timeStr = Carbon::parse($rawTime)->format('h:i A');
                } catch (\Throwable $e) {
                    $timeStr = (string)$rawTime;
                }
            }

            return (object)[
                'sr_no'      => $index + 1,
                'name'       => $a->name,
                'email'      => $a->email,
                'phone'      => $a->phone,
                'date'       => $dateStr,
                'time'       => $timeStr,
                'item'       => $itemLabel,
                'price'      => $price,
                'status'     => $a->status,
                'raw_status' => strtolower($a->status ?? 'other'),
            ];
        });

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'defaultFont'          => 'DejaVu Sans',
        ])
            ->loadView('admin.appointments.pdf', [
                'appointments' => $rows,
                'hospitalName' => $hospitalName,
            ])
            ->setPaper('a4', 'portrait');

        $range = (!empty($dateFrom) || !empty($dateTo))
            ? '_' . ($dateFrom ?? 'start') . '_' . ($dateTo ?? 'end')
            : '';

        $suffix   = !empty($hospitalQr) ? '_hospital_qr' : '';
        $filename = 'appointments_pdf' . $suffix . $range . '_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Compute display price and save percent for a package.
     */
    protected function computeDisplayPriceFromPackage(Package $package): array
    {
        $mrp = (float) ($package->mrp ?? 0);
        $discounted = (float) ($package->discounted_price ?? 0);

        $priceCandidates = [
            $discounted,
            isset($package->total_price) ? (float)$package->total_price : null,
            isset($package->price) ? (float)$package->price : null,
            $mrp
        ];

        $displayPrice = null;
        foreach ($priceCandidates as $c) {
            if (!is_null($c) && $c > 0) {
                $displayPrice = (float)$c;
                break;
            }
        }

        $savePercent = ($mrp > 0 && $displayPrice) ? (int) round((($mrp - $displayPrice) / $mrp) * 100) : 0;
        return [$displayPrice, $savePercent];
    }

    /**
     * Dashboard view (admin).
     */
    public function dashboard(Request $request)
    {
        $now = Carbon::now();

        // Apply payment filter to recent appointments
        $recentAppointments = Appointment::with(['user'])
            ->where(function ($q) {
                $q->whereHas('payments', function ($paymentQuery) {
                    $paymentQuery->whereIn('status', ['success', 'failed']);
                })
                    ->orWhere('payment_method', 'cash');
            })
            ->orderByDesc('id')
            ->take(8)
            ->get();

        $startOfMonth = $now->copy()->startOfMonth()->toDateString();
        $endOfMonth   = $now->copy()->endOfMonth()->toDateString();

        // Apply payment filter to total counts
        $totalThisMonthAppointments = Appointment::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where(function ($q) {
                $q->whereHas('payments', function ($paymentQuery) {
                    $paymentQuery->whereIn('status', ['success', 'failed']);
                })
                    ->orWhere('payment_method', 'cash');
            })
            ->count();

        $last7From = $now->copy()->subDays(6)->toDateString();
        $last7To   = $now->toDateString();

        $totalApprovedLast7 = Appointment::where('status', 'Approved')
            ->whereBetween('date', [$last7From, $last7To])
            ->where(function ($q) {
                $q->whereHas('payments', function ($paymentQuery) {
                    $paymentQuery->whereIn('status', ['success', 'failed']);
                })
                    ->orWhere('payment_method', 'cash');
            })
            ->count();

        $totalPendingAppointments = Appointment::where('status', 'Pending')
            ->where(function ($q) {
                $q->whereHas('payments', function ($paymentQuery) {
                    $paymentQuery->whereIn('status', ['success', 'failed']);
                })
                    ->orWhere('payment_method', 'cash');
            })
            ->count();

        $totalCompletedLast7 = Appointment::where('status', 'Completed')
            ->whereBetween('date', [$last7From, $last7To])
            ->where(function ($q) {
                $q->whereHas('payments', function ($paymentQuery) {
                    $paymentQuery->whereIn('status', ['success', 'failed']);
                })
                    ->orWhere('payment_method', 'cash');
            })
            ->count();

        $totalPublishedPackages    = Package::whereBetween('created_at', [$now->copy()->subDays(29)->startOfDay(), $now->endOfDay()])->count();
        $totalPublishedThisMonth   = LabTest::whereBetween('created_at', [$now->copy()->subDays(29)->startOfDay(), $now->endOfDay()])->count();

        return view('dashboard', compact(
            'recentAppointments',
            'totalThisMonthAppointments',
            'totalApprovedLast7',
            'totalPendingAppointments',
            'totalCompletedLast7',
            'totalPublishedPackages',
            'totalPublishedThisMonth'
        ));
    }

    /**
     * Dashboard counts endpoint (JSON).
     */
    public function counts(Request $request): JsonResponse
    {
        $payload = Cache::remember('dashboard_counts_v1', 15, function () {
            $now = Carbon::now();

            $startOfMonth = $now->copy()->startOfMonth()->toDateString();
            $endOfMonth   = $now->copy()->endOfMonth()->toDateString();

            $last7From = $now->copy()->subDays(6)->toDateString();
            $last7To   = $now->copy()->toDateString();
            $prev7From = $now->copy()->subDays(13)->toDateString();
            $prev7To   = $now->copy()->subDays(7)->toDateString();

            // Apply payment filter to all appointment counts
            $paymentFilter = function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('payments', function ($paymentQuery) {
                        $paymentQuery->whereIn('status', ['success', 'failed']);
                    })
                        ->orWhere('payment_method', 'cash');
                });
            };

            $totalThisMonthAppointments = Appointment::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where($paymentFilter)
                ->count();

            $approved = Appointment::where('status', 'Approved')
                ->whereBetween('date', [$last7From, $last7To])
                ->where($paymentFilter)
                ->count();

            $pending = Appointment::where('status', 'Pending')
                ->where($paymentFilter)
                ->count();

            $completed = Appointment::where('status', 'Completed')
                ->whereBetween('date', [$last7From, $last7To])
                ->where($paymentFilter)
                ->count();

            $totalPublishedPackages    = Package::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $totalPublishedThisMonth   = LabTest::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

            $approvedPrev = Appointment::where('status', 'Approved')
                ->whereBetween('date', [$prev7From, $prev7To])
                ->where($paymentFilter)
                ->count();

            $appointmentsDelta = $approved - $approvedPrev;
            $appointmentsChangePercent = ($approvedPrev > 0) ? round((($approved - $approvedPrev) / $approvedPrev) * 100, 1) : ($approved > 0 ? 100 : 0);

            $prevMonthStart = $now->copy()->subMonthNoOverflow()->startOfMonth()->toDateString();
            $prevMonthEnd   = $now->copy()->subMonthNoOverflow()->endOfMonth()->toDateString();
            $packagesPrev = Package::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])->count();
            $packagesDelta = $totalPublishedPackages - $packagesPrev;
            $packagesChangePercent = ($packagesPrev > 0) ? round((($totalPublishedPackages - $packagesPrev) / $packagesPrev) * 100, 1) : ($totalPublishedPackages > 0 ? 100 : 0);

            $testsPrev = LabTest::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])->count();
            $testsDelta = $totalPublishedThisMonth - $testsPrev;
            $testsChangePercent = ($testsPrev > 0) ? round((($totalPublishedThisMonth - $testsPrev) / $testsPrev) * 100, 1) : ($totalPublishedThisMonth > 0 ? 100 : 0);

            return [
                'totalThisMonthAppointments' => $totalThisMonthAppointments,
                'approved' => $approved,
                'pending'  => $pending,
                'completed' => $completed,

                'totalPublishedPackages'    => $totalPublishedPackages,
                'totalPublishedThisMonth'   => $totalPublishedThisMonth,

                'appointmentsDelta'         => $appointmentsDelta,
                'appointmentsChangePercent' => $appointmentsChangePercent,

                'packagesDelta'             => $packagesDelta,
                'packagesChangePercent'     => $packagesChangePercent,

                'testsDelta'                => $testsDelta,
                'testsChangePercent'        => $testsChangePercent,

                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
        });

        return response()->json($payload);
    }

    public function show(Appointment $appointment)
    {
        // Eager-load what exists
        $with = [];
        foreach (['items', 'test', 'package', 'user', 'report', 'hospital'] as $rel) {
            if (method_exists($appointment, $rel)) $with[] = $rel;
        }
        if ($with) $appointment->load($with);

        // -------- Build items strictly from appointment_items --------
        $lineItems = [];
        $itemsRel  = $appointment->items ?? collect();

        if ($itemsRel->count()) {
            foreach ($itemsRel as $it) {
                $qty   = (int)($it->quantity ?? 1);
                $qty   = $qty > 0 ? $qty : 1;
                $name  = $it->item_name ?? $it->name ?? 'Item';
                $price = (float)($it->item_price ?? 0);          // authoritative
                $lineItems[] = [
                    'name'       => $name,
                    'qty'        => $qty,
                    'unit_final' => $price,
                    'line_sum'   => $price * $qty,
                ];
            }
        } else {
            // Fallback to single stored selection on appointment if no items relation
            $fallbackName = $appointment->package_name
                ?? $appointment->service
                ?? ($appointment->test->test_name ?? null);

            if ($fallbackName) {
                $price = (float)($appointment->unit_price
                    ?? $appointment->final_price
                    ?? $appointment->discounted_price
                    ?? $appointment->total_price_display
                    ?? 0);

                $lineItems[] = [
                    'name'       => $fallbackName,
                    'qty'        => 1,
                    'unit_final' => $price,
                    'line_sum'   => $price,
                ];
            }
        }

        // -------- Subtotal from items --------
        $itemsFinalTotal = array_sum(array_map(fn($r) => $r['line_sum'], $lineItems));

        // -------- Coupon from appointments table --------
        $discountType  = strtolower(trim((string)($appointment->discount_type ?? ''))); // 'percent' | 'fixed'
        $discountValue = (float)($appointment->discount_value ?? 0);

        $couponAmount = 0.0;
        if ($discountType === 'percent' && $discountValue > 0) {
            $couponAmount = round(($itemsFinalTotal * $discountValue) / 100, 2);
        } elseif ($discountType === 'fixed' && $discountValue > 0) {
            $couponAmount = round($discountValue, 2);
        } else {
            // fallback to any snapshot you saved earlier
            $couponAmount = (float)($appointment->discount_amount
                ?? $appointment->coupon_amount
                ?? $appointment->coupon_discount
                ?? 0);
        }
        // never more than items total
        $couponAmount = max(0, min($couponAmount, $itemsFinalTotal));

        // Optional extra fields if you use them
        $extraDiscount = (float)($appointment->extra_discount ?? 0);
        $taxAmount     = (float)($appointment->tax_amount ?? 0);
        $collectionFee = (float)($appointment->collection_charges ?? $appointment->home_collection_fee ?? 0);

        // Grand total (prefer stored if present)
        $grandTotal = isset($appointment->total_price) ? (float)$appointment->total_price
            : (isset($appointment->grand_total) ? (float)$appointment->grand_total
                : (isset($appointment->total_price_display) ? (float)$appointment->total_price_display
                    : max(0, $itemsFinalTotal - $couponAmount - $extraDiscount + $taxAmount + $collectionFee)));

        $pricing = [
            'items'            => $lineItems,
            'itemsFinalTotal'  => $itemsFinalTotal,
            'discountType'     => $discountType,
            'discountValue'    => $discountValue,
            'couponAmount'     => $couponAmount,
            'extraDiscount'    => $extraDiscount,
            'taxAmount'        => $taxAmount,
            'collectionFee'    => $collectionFee,
            'grandTotal'       => $grandTotal,
            'couponCode'       => $appointment->coupon_code ?? $appointment->applied_coupon_code ?? null,
        ];

        return view('admin.appointments.show', compact('appointment', 'pricing'));
    }
}
