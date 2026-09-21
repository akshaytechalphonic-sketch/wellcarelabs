@extends('maindesign')
@section('title', 'Appointment Confirmed')
@section('content')

{{-- 🚫 Disable browser caching --}}
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

@php
  if (!isset($appointment) && isset($payment) && $payment && $payment->appointment_id) {
      $appointment = \App\Models\Appointment::find($payment->appointment_id);
  }
@endphp


<style>
/* ⬇️ KEEP YOUR EXISTING CSS AS-IS ⬇️ */
/* (NO CHANGE REQUIRED — reused exactly) */

 .wc-success-page {
            max-width: 1040px;
            margin: 32px auto 40px;
            padding: 0 16px;
        }

        /* Top Ribbon */
        .wc-success-ribbon {
            background: radial-gradient(circle at top left, #d1fae5 0, #ecfdf5 40%, #ffffff 100%);
            border-radius: 18px;
            padding: 22px 22px 18px;
            border: 1px solid #bbf7d0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .wc-success-ribbon-main {
            display: flex;
            align-items: center;
            gap: 14px;
        }

 
        .wc-success-chip {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background: #d1fae5;
            color: #065f46;
            font-weight: 700;
        }

        .wc-success-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0f172a;
        }

        .wc-success-caption {
            font-size: 0.92rem;
            color: #475569;
        }

        .wc-success-badge {
            width: 64px;
            height: 64px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
        }

        .wc-success-badge svg {
            width: 34px;
            height: 34px;
        }

        .wc-success-side {
            text-align: right;
            font-size: 0.8rem;
            color: #475569;
        }

        .wc-success-side strong {
            display: block;
            font-size: 0.86rem;
        }

        /* Main card */
        .wc-success-card {
            margin-top: 16px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
            border: 1px solid #f1f5f9;
            padding: 22px 22px 20px;
        }

        /* amount + status row */
        .wc-success-summary {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 16px;
            border-bottom: 1px dashed #e5e7eb;
            padding-bottom: 14px;
        }

        .wc-success-amount-label {
            font-size: 0.85rem;
            color: #111827;
            /* fixed typo & darker label */
        }

        .wc-success-amount-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: #059669;
        }

        .wc-success-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 11px;
            border-radius: 999px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            font-size: 0.82rem;
            font-weight: 700;
            color: #065f46;
        }

        /* Details grid */
        .wc-success-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px 18px;
            margin-top: 6px;
        }

        .wc-success-item {
            padding: 9px 11px;
            border-radius: 10px;
            background: #f9fafb;
            border: 1px solid #e2e8f0;
        }

        .wc-success-label {
            font-size: 0.8rem;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 4px;
        }

        .wc-success-value {
            font-size: 0.96rem;
            font-weight: 600;
            color: #111827;
            word-break: break-all;
        }

        /* footer */
        .wc-success-footer {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 14px;
            align-items: center;
        }

        .wc-success-help {
            font-size: 0.86rem;
            color: #64748b;
        }

        .wc-success-help strong {
            color: #0f172a;
        }

        /* ghost button (if you add later) */
        .btn-ghost-success {
            background: transparent;
            border: 1px solid #d1d9e6;
            padding: 8px 14px;
            border-radius: 10px;
            font-weight: 700;
            color: #475569;
        }

        /* Tablet / desktop: 2 columns for details */
        @media (min-width:720px) {
            .wc-success-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* Mobile & small tablets */
        @media (max-width:720px) {
            .wc-success-ribbon {
                flex-direction: column;
                align-items: flex-start;
            }

            .wc-success-side {
                text-align: left;
            }

            .wc-success-summary {
                flex-direction: column;
                align-items: flex-start;
            }

            .wc-success-card {
                padding: 18px 16px;
            }

            .wc-success-title {
                font-size: 1.15rem;
            }

            .wc-success-amount-value {
                font-size: 1.4rem;
            }
        }

        /* Very small phones */
        @media (max-width:480px) {
            .wc-success-page {
                margin: 24px auto 32px;
            }

            .wc-success-badge {
                width: 56px;
                height: 56px;
            }
        }
</style>

<div class="wc-success-page">

  {{-- Top Ribbon --}}
  <div class="wc-success-ribbon">
    <div class="wc-success-ribbon-main">
      <div class="wc-success-badge">
        <svg viewBox="0 0 24 24" fill="none">
          <circle cx="12" cy="12" r="11" fill="#10B981" fill-opacity="0.16" />
          <path d="M7 12.5l3 3L17 9" stroke="#059669" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>

      <div>
        <div class="wc-success-chip">Appointment Confirmed</div>
        <div class="wc-success-title">Thank you! Your appointment is booked.</div>
        <div class="wc-success-caption">
         Our team will contact you shortly to confirm the appointment. <br>
          Cash will be collected during sample collection.
        </div>
      </div>
    </div>

    <div class="wc-success-side">
      <strong>Need help?</strong>
      support@wellcarelabs.in<br>
      +91-9158980898
    </div>
  </div>

  {{-- Main card --}}
  <div class="wc-success-card">

    {{-- Amount + status --}}
<div class="wc-success-summary">
  <div>
    <div class="wc-success-amount-label">Total Amount Payable</div>
    <div class="wc-success-amount-value">
      ₹{{ number_format($appointment->total_price, 2) }}
    </div>
  </div>

  <span class="wc-success-status-pill">
    ● Pay at the Time of Sample Collection
  </span>
</div>


    {{-- Details --}}
    <div class="wc-success-grid">

      <div class="wc-success-item">
        <div class="wc-success-label">Booked At</div>
        <div class="wc-success-value">
{{ optional($appointment->created_at)->format('d M Y, h:i A') ?? '-' }}
        </div>
      </div>

      <div class="wc-success-item">
        <div class="wc-success-label">Customer</div>
        <div class="wc-success-value">
          {{ $appointment->name }}
        </div>
      </div>

      <div class="wc-success-item">
        <div class="wc-success-label">Status</div>
        <div class="wc-success-value" style="color:#059669;">
         @if(optional($appointment)->payment_method === 'cash')
  Pending (Cash)
@elseif(optional($appointment)->status === 'Completed')
  Payment Successful
@else
  Payment Processing
@endif

        </div>
      </div>

    </div>

    {{-- Footer --}}
    <div class="wc-success-footer">
      <div class="wc-success-help">
        Please keep the cash ready during sample collection.<br>
        Redirecting to home in <strong id="counter">30</strong> seconds…
      </div>
    </div>

  </div>

</div>

<script>
let seconds = 30;
const counter = document.getElementById('counter');

const timer = setInterval(() => {
  seconds--;
  counter.textContent = seconds;

  if (seconds <= 0) {
    clearInterval(timer);
    window.location.href = "{{ url('/') }}";
  }
}, 1000);



(function () {
    // 🚫 Prevent going back to checkout / payment
    window.history.pushState(null, "", window.location.href);

    window.onpopstate = function () {
        window.location.replace("{{ url('/') }}");
    };

    // 🔁 If page restored from browser cache (mobile back swipe)
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.location.replace("{{ url('/') }}");
        }
    });
})();
</script>

@endsection
