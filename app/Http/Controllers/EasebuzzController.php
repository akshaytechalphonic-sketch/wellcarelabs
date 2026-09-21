<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Easebuzz;
use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\AppointmentItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewAppointmentNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentBookedMail;
use App\Mail\NewAppointmentAdminMail;

class EasebuzzController extends Controller
{
   public function createAppointmentFromRequest(Request $request, string $forcedPaymentType = null)
    {
        if ($forcedPaymentType) {
            $request->merge([
                'payment_type' => $forcedPaymentType,
            ]);
        }

        // ✅ ADD THIS
        $request->merge([
            'is_admin' => true,
        ]);

        return $this->submitForm($request);
    }


    public function submitForm(Request $request)
    {
        $isAdmin = (bool) $request->input('is_admin', false);

        // Accept items either as JSON payload or fallback to session('cart_items')
        // Validate incoming request (accept time_slot and optional date)
        $data = $request->validate([
            'name'        => 'nullable|string|max:255',
            'phone'       => 'required|digits:10',
            'email'       => 'nullable|email|max:255',
            'amount'      => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
            'service'     => 'nullable|string|max:255',
            'time_slot'   => 'nullable|string|max:20',
            'date'        => 'nullable|date',
            'items'       => 'nullable', // JSON string or array

            // 🧍 patient / address fields (same as checkout form)
            'gender'   => 'required|in:male,female,other',
            'dob'      => 'required|date',
            'age'      => 'nullable|integer|min:0|max:120',
            'address'  => 'required|string|max:1000',
            'city'     => 'required|string|max:100',
            'pincode'  => 'required|digits:6',
            'message'  => 'nullable|string|max:1000',
            'landmark' => 'nullable|string|max:255',
            'payment_type' => 'nullable|in:cash,online',
        ]);

        // Prepare basic values
        $paymentType = $data['payment_type'] ?? 'online';
        $name  = trim($data['name'] ?? 'Guest');
        $email = trim($data['email'] ?? 'guest@wellcare.com');
        $phone = preg_replace('/\D/', '', $data['phone']);

        if ($phone === '' || strlen($phone) !== 10) {
            return response()->json(['status' => 'error', 'message' => 'Invalid phone number.'], 422);
        }

        // 🔹 Compute age from dob on server (trust backend more than client)
        $computedAge = null;
        if (!empty($data['dob'])) {
            try {
                $computedAge = Carbon::parse($data['dob'])->age;
            } catch (\Throwable $e) {
                $computedAge = $data['age'] ?? null;
            }
        } else {
            $computedAge = $data['age'] ?? null;
        }

        // ------------------- HOSPITAL ATTRIBUTION (copied from AppointmentController) -------------------
        $incomingRef = $request->input('hospital_unique_id')
            ?? $request->input('hospital_ref')
            ?? $request->query('ref')
            ?? (is_array(session('hospital_ref')) ? (session('hospital_ref')['unique_id'] ?? null) : session('hospital_ref'));

        $hospitalId = null;
        $hospitalUnique = $incomingRef ?? null;

        if ($hospitalUnique && class_exists(Hospital::class)) {
            try {
                $hospital = Hospital::where('unique_id', $hospitalUnique)->first();
                if ($hospital) {
                    $hospitalId = $hospital->id;
                }
            } catch (\Throwable $e) {
                // silently ignore
            }
        }
        // ----------------------------------------------------------------------------------------------

        // Normalize items payload
        $items = $request->input('items', null);
        if (is_string($items) && $items !== '') {
            $decoded = json_decode($items, true);
            $items = is_array($decoded) ? $decoded : null;
        }
        // fallback to session cart
        if (!is_array($items) || count($items) === 0) {
            $items = session()->get('cart_items', []);
        }

        $normalized = [];
        foreach ($items as $raw) {
            if (is_array($raw)) {
                $normalized[] = [
                    'item_type'  => $raw['item_type'] ?? ($raw['type'] ?? 'item'),
                    'item_id'    => isset($raw['item_id']) ? (int)$raw['item_id'] : (isset($raw['id']) ? (int)$raw['id'] : null),
                    'item_name'  => $raw['item_name'] ?? $raw['name'] ?? ($raw['title'] ?? 'Item'),
                    'item_price' => isset($raw['item_price']) ? (float)$raw['item_price'] : (float)($raw['price'] ?? 0),
                    'quantity'   => max(1, (int)($raw['quantity'] ?? 1)),
                ];
            }
        }

        // fallback service: prefer provided, else first item name, else default
        $service = trim($data['service'] ?? ($normalized[0]['item_name'] ?? 'Online Appointment'));

        // Determine amount: prefer explicit total_price or amount param, but compute authoritative subtotal from items
        $clientAmount = $data['total_price'] ?? $data['amount'] ?? 0;
        $clientAmount = (float) $clientAmount;

        // Normalize time slot to H:i:s if provided (basic handling: accept "HH:MM" or "HH:MM:SS")
        $timeSlotDb = null;
        if (!empty($data['time_slot'])) {
            try {
                // If already HH:MM, append seconds
                if (preg_match('/^\d{1,2}:\d{2}$/', $data['time_slot'])) {
                    $timeSlotDb = Carbon::createFromFormat('H:i', $data['time_slot'])->format('H:i:s');
                } else {
                    // try parse generic
                    $timeSlotDb = Carbon::parse($data['time_slot'])->format('H:i:s');
                }
            } catch (\Throwable $e) {
                $timeSlotDb = null;
            }
        }

        // Determine date for appointment (use provided date or today)
        $appointmentDate = !empty($data['date']) ? Carbon::parse($data['date'])->toDateString() : Carbon::now()->toDateString();

        // Prevent duplicate appointment within 2 minutes for same phone + date + time
        $existing = Appointment::where('phone', $phone)
            ->where('date', $appointmentDate)
            ->where('time_slot', $timeSlotDb)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->first();

        if ($existing) {

            // AJAX request → return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'duplicate',
                    'message' => 'Appointment already created.',
                    'appointment_id' => $existing->id
                ], 409);
            }

            // NORMAL request → redirect with flash message
            return redirect()
                ->route('checkout.index') // or home / cart page
                ->with('duplicate_error', 'Appointment already created.');
        }


        // Create appointment & items atomically
        DB::beginTransaction();
        try {
            // Create appointment (including hospital attribution + patient fields)
            $appointment = Appointment::create([
                'name'               => $name,
                'email'              => $email,
                'phone'              => $phone,
                'date'               => $appointmentDate,
                'time_slot'          => $timeSlotDb,
                'service'            => $service,
                'status'             => 'Pending',
                'payment_method'     => 'pending',
                'hospital_id'        => $hospitalId,
                'hospital_unique_id' => $hospitalUnique,

                // 🧍 patient / address fields
                'gender'   => $data['gender'],
                'dob'      => $data['dob'],
                'age'      => $computedAge,
                'address'  => $data['address'],
                'city'     => $data['city'],
                'pincode'  => $data['pincode'],
                'message'  => $data['message'] ?? null,
                'landmark' => $data['landmark'] ?? null,
            ]);

            // Insert items and compute subtotal
            $subtotal = 0.0;
            foreach ($normalized as $it) {
                $price = (float)($it['item_price'] ?? 0);
                $qty = max(1, (int)($it['quantity'] ?? 1));

                if (method_exists($appointment, 'items')) {
                    $appointment->items()->create([
                        'item_type'  => $it['item_type'],
                        'item_id'    => $it['item_id'],
                        'item_name'  => $it['item_name'],
                        'item_price' => $price,
                        'quantity'   => $qty,
                    ]);
                } else {
                    DB::table('appointment_items')->insert([
                        'appointment_id' => $appointment->id,
                        'item_type'      => $it['item_type'],
                        'item_id'        => $it['item_id'],
                        'item_name'      => $it['item_name'],
                        'item_price'     => $price,
                        'quantity'       => $qty,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }

                $subtotal += ($price * $qty);
            }

            // If no items were present, use client-provided amount as subtotal (still save)
            if ($subtotal <= 0) {
                $subtotal = max(0, round($clientAmount, 2));
            } else {
                $subtotal = round($subtotal, 2);
            }

            // ---- Apply coupon snapshot from session if present (safe server-side snapshot) ----
            $applied = session()->get('applied_coupon', []);
            $discount = 0.0;
            if (!empty($applied)) {
                if (isset($applied['discount_applied'])) {
                    $discount = (float)$applied['discount_applied'];
                } elseif (isset($applied['discount_value'])) {
                    $discount = (float)$applied['discount_value'];
                } elseif (isset($applied['value'])) {
                    // fallback if session stores 'value'
                    $discount = (float)$applied['value'];
                }
            }
            $discount = max(0, min($discount, $subtotal));
            $grandTotal = max(0, round($subtotal - $discount, 2));

            // ---- Only persist coupon snapshot when a coupon code exists AND discount > 0 ----
            if (!empty($applied) && !empty($applied['coupon_code']) && $discount > 0) {
                $appointment->discount_amount = $discount;
                $appointment->coupon_code     = $applied['coupon_code'];
                $appointment->discount_type   = $applied['discount_type'] ?? null;
                $appointment->discount_value  = $applied['discount_value'] ?? ($applied['value'] ?? null);
                $appointment->coupon_id       = $applied['coupon_id'] ?? null;
            } else {
                // ensure fields are cleared so stale session data doesn't persist
                $appointment->discount_amount = 0.00;
                $appointment->coupon_code     = null;
                $appointment->discount_type   = null;
                $appointment->discount_value  = null;
                $appointment->coupon_id       = null;
            }

            $appointment->subtotal    = $subtotal;
            $appointment->total_price = $grandTotal;
            $appointment->save();

            // ---------------------------
            // Coupon usage handling (UPDATED FOR YOUR SCHEMA)
            // ---------------------------
            if (!empty($applied) && !empty($applied['coupon_id']) && $discount > 0) {
                $couponId = (int) ($applied['coupon_id']);
                $now = now();

                if (Schema::hasTable('coupon_usages')) {
                    $cuCols = Schema::getColumnListing('coupon_usages');

                    $usageRow = [];
                    if (in_array('coupon_id', $cuCols)) {
                        $usageRow['coupon_id'] = $couponId;
                    }
                    if (in_array('appointment_id', $cuCols)) {
                        $usageRow['appointment_id'] = $appointment->id;
                    }
                    if (in_array('quantity', $cuCols)) {
                        $usageRow['quantity'] = 1;
                    }
                    if (in_array('used_at', $cuCols)) {
                        $usageRow['used_at'] = $now;
                    }
                    if (in_array('created_at', $cuCols)) {
                        $usageRow['created_at'] = $now;
                    }
                    if (in_array('updated_at', $cuCols)) {
                        $usageRow['updated_at'] = $now;
                    }

                    $uniqueKey = [];
                    if (in_array('appointment_id', $cuCols) && in_array('coupon_id', $cuCols)) {
                        $uniqueKey = [
                            'appointment_id' => $appointment->id,
                            'coupon_id'      => $couponId
                        ];
                    } elseif (in_array('appointment_id', $cuCols)) {
                        $uniqueKey = ['appointment_id' => $appointment->id];
                    } elseif (in_array('coupon_id', $cuCols)) {
                        $uniqueKey = ['coupon_id' => $couponId];
                    } else {
                        DB::table('coupon_usages')->insert($usageRow);
                        $uniqueKey = null;
                    }

                    if (!empty($uniqueKey)) {
                        DB::table('coupon_usages')->updateOrInsert($uniqueKey, $usageRow);
                    }

                    if (Schema::hasTable('coupons')) {
                        $coupon = DB::table('coupons')->where('id', $couponId)->first();
                        if ($coupon && isset($coupon->usage_limit) && $coupon->usage_limit !== null) {
                            $usedCount = DB::table('coupon_usages')->where('coupon_id', $couponId)->count();
                            if ($usedCount >= (int)$coupon->usage_limit) {
                                DB::table('coupons')->where('id', $couponId)->update(['is_active' => 0, 'updated_at' => $now]);
                            }
                        }
                    }
                } else {
                    throw new \Exception('coupon_usages table not found');
                }
            }

            // Clear session coupon/cart to avoid leaking stale data into future bookings
            session()->forget('applied_coupon');
            session()->forget('cart_items');

            DB::commit();
            // ✅ WhatsApp Booking Confirmation (Dovesoft Template) - after DB commit
            // ✅ Send Admin Email Notification
            try {
                $adminEmail = config('mail.admin_email');
                if (!empty($adminEmail) && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                    Mail::to($adminEmail)->send(new NewAppointmentAdminMail($appointment));
                }
            } catch (\Throwable $e) {
                \Log::error('Failed to send admin appointment email notification (EasebuzzController)', [
                    'appointment_id' => $appointment->id ?? null,
                    'error'          => $e->getMessage()
                ]);
            }

            // ✅ Send Patient Email Notification
            try {
                if (!empty($appointment->email) && filter_var($appointment->email, FILTER_VALIDATE_EMAIL) && !str_contains($appointment->email, 'guest@wellcare')) {
                    Mail::to($appointment->email)->send(new AppointmentBookedMail($appointment));
                }
            } catch (\Throwable $e) {
                \Log::error('Failed to send patient appointment email notification (EasebuzzController)', [
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
                \Log::error('Failed to send appointment WhatsApp notification to admin (EasebuzzController)', [
                    'appointment_id' => $appointment->id ?? null,
                    'error'          => $e->getMessage()
                ]);
            }
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
                        'bookingupdatednew',
                        'en',
                        $bodyParams
                    );
                }
            } catch (\Throwable $e) {
                // do not break payment flow
            }

            // ================= CASH PAYMENT =================
            if ($paymentType === 'cash') {

                $appointment->payment_method = 'cash';
                $appointment->status = 'Pending';
                $appointment->save();

                // ✅ AJAX response for cash
                return response()->json([
                    'status' => 'cash_success',
                    'redirect_url' => route('cash.success', $appointment->id)
                ]);
            }
            // ================= ONLINE PAYMENT =================
            $appointment->payment_method = 'online';
            $appointment->save();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to save appointment: ' . $e->getMessage()], 500);
        }

        // ---------------- Prepare Easebuzz payment (UPDATED) ----------------
        $merchantKey = env('EASEBUZZ_MERCHANT_KEY', 'EBY158C6C9');
        $salt        = env('EASEBUZZ_SALT', 'Q8F0K3E91R');
        $ENV         = env('EASEBUZZ_ENV', 'prod');

        $easebuzzObj = new Easebuzz($merchantKey, $salt, $ENV);
        $txnid = 'TX' . date('YmdHis') . rand(1000, 9999);

        // Build safe productinfo (fixes "Invalid value for productinfo.")
        $rawProduct  = $appointment->service ?? $service ?? 'Appointment';
        $sanitized   = preg_replace('/[^A-Za-z0-9 _-]/', '', $rawProduct); // only safe chars
        if ($sanitized === '' || $sanitized === null) {
            $sanitized = 'Appointment-' . $appointment->id;
        }
        $productInfo = substr($sanitized, 0, 50); // max 50 chars

        $postData = [
            'txnid'       => $txnid,
            'amount'      => number_format((float)$appointment->total_price, 2, '.', ''),
            'firstname'   => $name,
            'email'       => $email,
            'phone'       => $phone,
            'productinfo' => $productInfo,
            'surl'        => 'https://wellcarelabs.in/ci/paymentSuccess',
            'furl'        => 'https://wellcarelabs.in/ci/paymentSuccess',
            'udf1'        => (string)$appointment->id,           // appointment id so callback knows
            'udf2'        => (string)($hospitalUnique ?? ''),    // hospital unique id carried through gateway
            'udf3'        => '',
            'udf4'        => '',
            'udf5'        => '',
            'udf6'        => '',
            'udf7'        => '',
            'udf8'        => '',
            'udf9'        => '',
            'udf10'       => '',
        ];

        // Build hash (udf2..udf10 placeholders included)
        $parts = [
            $merchantKey,
            $postData['txnid'],
            $postData['amount'],
            $postData['productinfo'],
            $postData['firstname'],
            $postData['email'],
            $postData['udf1'],
            $postData['udf2'] ?? '',
            $postData['udf3'] ?? '',
            $postData['udf4'] ?? '',
            $postData['udf5'] ?? '',
            $postData['udf6'] ?? '',
            $postData['udf7'] ?? '',
            $postData['udf8'] ?? '',
            $postData['udf9'] ?? '',
            $postData['udf10'] ?? '',
            $salt
        ];
        $stringToHash = implode('|', $parts);
        $postData['hash'] = hash('sha512', $stringToHash);

        try {
            $result = $easebuzzObj->initiatePaymentAPI($postData);

            // Case 1: gateway returned redirect URL (success init)
            if (is_string($result) && filter_var($result, FILTER_VALIDATE_URL)) {
                return redirect()->away($result);
            }

            // Case 2: gateway returned JSON/array (including validation errors)
            $gateway = $result;
            if (is_string($gateway)) {
                $decoded = json_decode($gateway, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $gateway = $decoded;
                }
            }

            $gwStatus = null;
            if (is_array($gateway) && isset($gateway['status'])) {
                $gwStatus = (int) $gateway['status'];
            }

            // If Easebuzz rejected the request (e.g. "Invalid value for productinfo.")
            if ($gwStatus === 0) {
                $errorMsg = $gateway['error_desc'] ?? ($gateway['data'] ?? 'Payment gateway rejected the request.');

                $appointment->status = 'payment_failed';
                $appointment->payment_method = 'failed';
                $appointment->save();

                return response()->json([
                    'status'         => 'error',
                    'message'        => $errorMsg,
                    'appointment_id' => $appointment->id,
                    'txnid'          => $txnid,
                    'gateway'        => $gateway,
                ], 422);
            }

            // Default: init considered OK (no URL but also no explicit status=0)
            return response()->json([
                'status'         => 'ok',
                'message'        => 'Appointment saved and payment initiated',
                'appointment_id' => $appointment->id,
                'txnid'          => $txnid,
                'gateway'        => $gateway,
            ], 200);
        } catch (\Throwable $e) {
            try {
                if (isset($appointment) && $appointment->id) {
                    $appointment->status = 'payment_failed';
                    $appointment->payment_method = 'failed';
                    $appointment->save();
                }
            } catch (\Throwable $inner) {
                // ignore silently
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Payment initialization failed.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Payment callback handler (POST from gateway)
     */
    public function successpayment(Request $request)
    {
        $payload = $request->all();

        // Only handle POST callbacks here
        if (! $request->isMethod('post')) {
            return response()->view('payments.debug', ['payload' => $payload]);
        }

        // Safe getter (tries exact key then lowercase key)
        $get = function ($k, $default = null) use ($payload) {
            return $payload[$k] ?? $payload[strtolower($k)] ?? $default;
        };

        // Map gateway fields (based on sample you pasted)
        $txnidRaw = (string)($get('txnid') ?? ('unknown_' . time()));
        $txnid = trim($txnidRaw);
        $udf1 = $get('udf1') ?? null;
        $appointmentId = is_numeric($udf1) ? (int)$udf1 : null;

        // read hospital unique id from udf2 if present
        $udf2 = $get('udf2') ?? null;
        $hospitalUniqueFromCallback = $udf2 ?: null;

        $amount = (float)($get('amount') ?? $get('net_amount_debit') ?? 0);
        $status = (string)($get('status') ?? $get('error') ?? 'unknown');
        $easepayid = (string)($get('easepayid') ?? null);
        $bankRef = (string)($get('bank_ref_num') ?? $get('bank_ref') ?? null);
        $mode = (string)($get('mode') ?? $get('payment_source') ?? null);
        $cardType = (string)($get('card_type') ?? null);
        $rawCard = (string)($get('cardnum') ?? '');
        $phone = (string)($get('phone') ?? null);
        $firstname = (string)($get('firstname') ?? $get('name') ?? null);
        $email = (string)($get('email') ?? null);
        $hash = (string)($get('hash') ?? null);
        $product = (string)($get('productinfo') ?? null);

        // Mask card number (store masked only)
        $maskedCard = null;
        if ($rawCard !== '') {
            $digits = preg_replace('/\D/', '', $rawCard);
            if (strlen($digits) >= 4) {
                $maskedCard = 'XXXXXXXXXXXX' . substr($digits, -4);
            } else {
                $maskedCard = $rawCard;
            }
        }

        // Parse addedon into datetime if present
        $addedOnRaw = $get('addedon') ?? $get('added_on') ?? null;
        $addedOn = null;
        if (!empty($addedOnRaw)) {
            try {
                $addedOn = Carbon::parse($addedOnRaw)->toDateTimeString();
            } catch (\Throwable $e) {
                $addedOn = null;
            }
        }

        // Verify payments table exists and get its columns
        if (! Schema::hasTable('payments')) {
            return response()->json(['status' => 'error', 'message' => 'payments table not found. Please create it first.'], 500);
        }
        $paymentsCols = Schema::getColumnListing('payments');

        // Build a DB payload and keep only columns that exist in your payments table
        $dbPayload = [
            'txnid'          => $txnid,
            'appointment_id' => $appointmentId,
            'amount'         => $amount,
            'status'         => $status,
            'payment_source' => $get('payment_source') ?? 'Easebuzz',
            'easepayid'      => $easepayid,
            'bank_ref_num'   => $bankRef,
            'mode'           => $mode,
            'card_type'      => $cardType,
            'cardnum'        => $maskedCard,
            'firstname'      => $firstname,
            'email'          => $email,
            'phone'          => $phone,
            'addedon'        => $addedOn,
            'hash'           => $hash,
            'payload'        => json_encode($payload),
            'updated_at'     => now(),
            'created_at'     => now(),
        ];

        // include hospital info on payments row if columns exist
        if (!empty($hospitalUniqueFromCallback) && in_array('hospital_unique_id', $paymentsCols)) {
            $dbPayload['hospital_unique_id'] = $hospitalUniqueFromCallback;
        }
        if (in_array('hospital_id', $paymentsCols) && !empty($appointmentId) && Schema::hasTable('appointments')) {
            $apptHospitalId = DB::table('appointments')->where('id', $appointmentId)->value('hospital_id');
            if ($apptHospitalId) {
                $dbPayload['hospital_id'] = $apptHospitalId;
            }
        }

        $insertable = array_intersect_key($dbPayload, array_flip($paymentsCols));

        // truncate lengths defensively
        if (isset($insertable['cardnum']) && is_string($insertable['cardnum'])) {
            $insertable['cardnum'] = mb_substr($insertable['cardnum'], 0, 255);
        }
        if (isset($insertable['payload']) && is_string($insertable['payload'])) {
            $insertable['payload'] = mb_substr($insertable['payload'], 0, 65535);
        }

        // Persist idempotently and update appointment if present
        try {
            DB::beginTransaction();

            DB::table('payments')->updateOrInsert(
                ['txnid' => $txnid],
                $insertable
            );

            // Update appointment row only if it exists and column names match
            if (!empty($appointmentId) && Schema::hasTable('appointments')) {
                $appt = DB::table('appointments')->where('id', $appointmentId)->first();
                if ($appt) {
                    $lower = strtolower($status);
                    if (in_array($lower, ['success', 'completed', 'ok'])) {
                        $apptStatus = 'Completed';

                        // ✅ Clear cart only when payment is successful
                        session()->forget('cart_items');

                        if (auth()->check() && Schema::hasTable('carts')) {
                            DB::table('carts')
                                ->where('user_id', auth()->id())
                                ->delete();
                        }
                    } elseif (in_array($lower, ['failure', 'failed'])) {
                        $apptStatus = 'payment_failed';
                    } else {
                        $apptStatus = 'payment_' . $lower;
                    }

                    $apptUpdate = [
                        'status'         => $apptStatus,
                        'payment_method' => $status,
                    ];

                    // set paid_at only if status is success
                    if (in_array($lower, ['success', 'completed', 'ok'])) {
                        $apptUpdate['paid_at'] = now();
                    }

                    // attempt to patch hospital attribution (only if missing)
                    $apptUpdates = [];
                    if (empty($appt->hospital_id) && !empty($hospitalUniqueFromCallback) && Schema::hasTable('hospitals')) {
                        $h = DB::table('hospitals')->where('unique_id', $hospitalUniqueFromCallback)->first();
                        if ($h) {
                            if (Schema::hasColumn('appointments', 'hospital_id')) {
                                $apptUpdates['hospital_id'] = $h->id;
                            }
                            if (Schema::hasColumn('appointments', 'hospital_unique_id')) {
                                $apptUpdates['hospital_unique_id'] = $hospitalUniqueFromCallback;
                            }
                        }
                    }

                    // only keep appointment columns that exist
                    $apptCols   = Schema::getColumnListing('appointments');
                    $apptUpdate = array_intersect_key($apptUpdate, array_flip($apptCols));
                    $apptUpdate = array_merge($apptUpdate, array_intersect_key($apptUpdates, array_flip($apptCols)));

                    if (!empty($apptUpdate)) {
                        DB::table('appointments')->where('id', $appointmentId)->update($apptUpdate);
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'DB error: ' . $e->getMessage(), 'txnid' => $txnid], 500);
        }

        // Send notifications if online payment succeeded (after DB commit)
        if (in_array(strtolower($status), ['success', 'completed', 'ok']) && !empty($appointmentId)) {
            try {
                $appointmentObj = Appointment::find($appointmentId);
                if ($appointmentObj) {
                    // Admin DB notification
                    try {
                        $admins = User::where('role', 'admin')->get();
                        if ($admins->isNotEmpty()) {
                            Notification::send($admins, new NewAppointmentNotification($appointmentObj));
                        }
                    } catch (\Throwable $e) {
                        \Log::error('Failed to send admin DB notification on Easebuzz successpayment', [
                            'appointment_id' => $appointmentId,
                            'error' => $e->getMessage()
                        ]);
                    }

                    // Admin WhatsApp notification
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
                        \Log::error('Failed to send admin WhatsApp on Easebuzz successpayment', [
                            'appointment_id' => $appointmentId,
                            'error' => $e->getMessage()
                        ]);
                    }

                    // Customer WhatsApp notification
                    try {
                        if (!empty($appointmentObj->phone)) {
                            $mobile = preg_replace('/\D+/', '', $appointmentObj->phone);
                            if (strlen($mobile) === 10) {
                                $mobile = '91' . $mobile;
                            }
                            try {
                                $formattedDate = !empty($appointmentObj->date)
                                    ? \Carbon\Carbon::parse($appointmentObj->date)->format('d/m/Y')
                                    : '';
                            } catch (\Throwable $e) {
                                $formattedDate = (string) ($appointmentObj->date ?? '');
                            }
                            try {
                                $formattedSlot = !empty($appointmentObj->time_slot)
                                    ? \Carbon\Carbon::parse($appointmentObj->time_slot)->format('h:i A')
                                    : '';
                            } catch (\Throwable $e) {
                                $formattedSlot = (string) ($appointmentObj->time_slot ?? '');
                            }
                            $bodyParams = [
                                $appointmentObj->name ?? '',
                                $formattedDate,
                                $formattedSlot,
                                (string) ($appointmentObj->service ?? 'Lab Test'),
                                $appointmentObj->name ?? '',
                                (string) ($appointmentObj->age ?? ''),
                                (string) ($appointmentObj->gender ?? ''),
                            ];

                            app(\App\Services\DovesoftService::class)->sendTemplate(
                                $mobile,
                                'bookingupdatednew',
                                'en',
                                $bodyParams
                            );
                        }
                    } catch (\Throwable $e) {
                        \Log::error('Failed to send customer WhatsApp on Easebuzz successpayment', [
                            'appointment_id' => $appointmentId,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                \Log::error('Notification dispatch error on Easebuzz successpayment', [
                    'appointment_id' => $appointmentId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Redirect to result page (PRG) — keep UX same as earlier
        return redirect()->route('payments.result', ['txnid' => $txnid]);
    }

    public function showPaymentResult($txnid)
    {
        $payment = DB::table('payments')->where('txnid', $txnid)->first();
        if (!$payment) {
            return response()->view('payments.result', ['payment' => null], 200);
        }
        $payment->payload_decoded = json_decode($payment->payload, true);
        return view('payments.result', ['payment' => $payment]);
    }
}
