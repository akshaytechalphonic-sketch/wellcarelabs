<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PaymentController extends Controller
{
    /**
     * List all payments (History Page)
     */
    public function index(Request $request)
    {
        $payments = Payment::latest()->paginate(20);
        return view('payments.history', compact('payments'));
    }

    /**
     * Show payments for a specific appointment
     */
    public function showForAppointment(Appointment $appointment)
    {
        // load payments related to the appointment if relation exists
        if (method_exists($appointment, 'payments')) {
            $payments = $appointment->payments()->latest()->get();
        } else {
            // fallback: try to fetch payments by appointment_id column
            $payments = Payment::where('appointment_id', $appointment->id)->latest()->get();
        }

        return view('payments.for_appointment', [
            'appointment' => $appointment,
            'payments'    => $payments,
            'payment'     => $payments->first(),
        ]);
    }

    /**
     * PAYMENT SUCCESS PAGE
     * URL: /paymentSuccess?ID=base64(id)
     */
    public function paymentSuccess(Request $request)
    {
        $payment = null;

        if ($request->has('ID')) {
            $decodedId = base64_decode($request->query('ID'));
            $paymentId = (int) $decodedId;

            if ($paymentId > 0) {
                $payment = Payment::find($paymentId);
            }
        }

        // ✅ CLEAR CART ONLY IF PAYMENT IS SUCCESS
        if ($payment && strtolower($payment->status) === 'success') {

            // clear Laravel session cart
            session()->forget('cart_items');
            session()->forget('applied_coupon');

            // clear DB cart if user is logged in
            if (auth()->check() && \Schema::hasTable('carts')) {
                \DB::table('carts')
                    ->where('user_id', auth()->id())
                    ->delete();
            }
        }

        // fallback dummy (unchanged)
        if (!$payment) {
            $payment = (object) [
                'id'         => 1,
                'name'       => 'John Doe',
                'txnid'      => 'TXN123456789',
                'status'     => 'success',
                'amount'     => 499.00,
                'phone'      => '+911234567890',
                'addedon'    => now()->format('Y-m-d H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // paginator logic (unchanged)
        $items = collect([$payment]);
        $perPage      = 20;
        $currentPage  = LengthAwarePaginator::resolveCurrentPage() ?: 1;
        $currentItems = $items
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $paginator = new LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $currentPage,
            [
                'path'  => url('/paymentSuccess'),
                'query' => $request->query(),
            ]
        );

        return view('payments.for_appointment', [
            'appointment' => null,
            'payments'    => $paginator,
            'payment'     => $payment,
        ]);
    }


    /**
     * PAYMENT FAILED PAGE
     * URL: /paymentFailed?ID=base64(id)
     */
    public function paymentFailed(Request $request)
    {
        // try to load real payment from ID
        $payment = null;

        if ($request->has('ID')) {
            $decodedId = base64_decode($request->query('ID'));
            $paymentId = (int) $decodedId;
            if ($paymentId > 0) {
                $payment = Payment::find($paymentId);
            }
        }

        // fallback dummy FAILED payment if nothing found
        if (!$payment) {
            $payment = (object) [
                'id'         => 1,
                'name'       => 'John Doe',
                'txnid'      => 'TXNFAILED123456',
                'status'     => 'failed',
                'amount'     => 0.00,
                'phone'      => '+911234567890',
                'addedon'    => now()->format('Y-m-d H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        } else {
            // ensure status is treated as failed in the view
            $payment->status = 'failed';
        }

        // dedicated failed view (red cross UI)
        return view('payments.paymentFailed', [
            'payment' => $payment,
        ]);
    }
}
