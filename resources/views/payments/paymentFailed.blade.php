@extends('maindesign')
@section('title', 'Payment Failed')
@section('content')

    <?php
    
    use Illuminate\Support\Facades\DB;
    
    $paymentId = isset($_GET['ID']) ? base64_decode($_GET['ID']) : null;
    $pay = null;
    
    if ($paymentId) {
        $pay = DB::table('payments')->where('id', $paymentId)->first();
    }
    ?>

    <style>
        .wc-fail-page {
            max-width: 1040px;
            margin: 32px auto 48px;
            padding: 0 16px;
        }

        /* ------------------ RIBBON ------------------ */
        .wc-fail-ribbon {
            background: radial-gradient(circle at top left, #fee2e2 0, #fff5f5 40%, #ffffff 100%);
            border-radius: 18px;
            padding: 22px 22px 18px;
            border: 1px solid #fecaca;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .wc-fail-ribbon-main {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .wc-fail-badge {
            width: 64px;
            height: 64px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .wc-fail-badge svg {
            width: 34px;
            height: 34px;
        }

        .wc-fail-chip {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background: #fee2e2;
            color: #7f1d1d;
            font-weight: 700;
        }

        .wc-fail-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: #7f1d1d;
        }

        .wc-fail-caption {
            font-size: 0.92rem;
            color: #9f3a3a;
        }

        .wc-fail-side {
            text-align: right;
            font-size: 0.8rem;
            color: #9f3a3a;
        }

        .wc-fail-side strong {
            display: block;
            font-size: 0.86rem;
        }

        /* ------------------ MAIN CARD ------------------ */
        .wc-fail-card {
            margin-top: 16px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
            border: 1px solid #f1f5f9;
            padding: 22px 22px 20px;
        }

        /* amount + status row */
        .wc-fail-summary {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 16px;
            border-bottom: 1px dashed #e5e7eb;
            padding-bottom: 14px;
        }

        .wc-fail-amount-label {
            font-size: 0.85rem;
            color: #111827;
        }

        .wc-fail-amount-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #b91c1c;
        }

        .wc-fail-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 11px;
            border-radius: 999px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            font-size: 0.82rem;
            font-weight: 700;
            color: #991b1b;
        }

        /* ------------------ DETAILS GRID ------------------ */
        .wc-fail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 18px;
            margin-top: 6px;
        }

        .wc-fail-item {
            padding: 9px 11px;
            border-radius: 10px;
            background: #f9fafb;
            border: 1px solid #e2e8f0;
        }

        .wc-fail-label {
            font-size: 0.8rem;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 4px;
        }

        .wc-fail-value {
            font-size: 0.96rem;
            font-weight: 600;
            color: #111827;
            word-break: break-all;
        }

        /* reason box */
        .wc-fail-reason {
            margin-top: 18px;
            padding: 11px 12px;
            border-radius: 12px;
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border: 1px solid #fecaca;
            display: flex;
            gap: 10px;
        }

        .wc-fail-reason-icon {
            margin-top: 2px;
        }

        .wc-fail-reason-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #7f1d1d;
        }

        .wc-fail-reason-text {
            font-size: 0.9rem;
            color: #991b1b;
        }

        /* footer */
        .wc-fail-footer {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 14px;
            align-items: center;
        }

        {{ $pay && $pay->created_at ? \Carbon\Carbon::parse($pay->created_at)->format('d M Y, H:i') : '-' }} .wc-fail-help {
            font-size: 0.86rem;
            color: #64748b;
        }

        .wc-fail-help strong {
            color: #0f172a;
        }

        /* Responsive */
        @media (max-width:720px) {
            .wc-fail-ribbon {
                flex-direction: column;
                align-items: flex-start;
            }

            .wc-fail-side {
                text-align: left;
            }

            .wc-fail-summary {
                flex-direction: column;
            }

            .wc-fail-card {
                padding: 18px 16px;
            }

            .wc-fail-title {
                font-size: 1.15rem;
            }

            .wc-fail-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width:480px) {
            .wc-fail-page {
                margin: 24px auto 32px;
            }

            .wc-fail-badge {
                width: 56px;
                height: 56px;
            }
        }
    </style>

    <div class="wc-fail-page">

        {{-- Ribbon --}}
        <div class="wc-fail-ribbon">
            <div class="wc-fail-ribbon-main">
                <div class="wc-fail-badge">
                    <!-- red cross -->
                    <svg viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="11" fill="#dc2626" fill-opacity="0.16" />
                        <path d="M8 8l8 8M16 8l-8 8" stroke="#b91c1c" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>

                <div>
                    <div class="wc-fail-chip">Payment Failed</div>
                    <div class="wc-fail-title">We couldn’t process your payment</div>
                    <div class="wc-fail-caption">No amount has been deducted. You can try again safely.</div>
                </div>
            </div>

            <div class="wc-fail-side">
                <strong>Need help?</strong>
                support@wellcarelabs.in<br>
                +91-9158980898
            </div>
        </div>

        {{-- Main card --}}
        <div class="wc-fail-card">

            {{-- Summary --}}
            <div class="wc-fail-summary">
                <div>
                    <div class="wc-fail-amount-label">Attempted Amount</div>
                    <div class="wc-fail-amount-value">
                        ₹{{ $pay ? number_format($pay->amount ?? 0, 2) : '0.00' }}
                    </div>
                </div>

                <span class="wc-fail-status-pill">
                    ● Failed
                </span>
            </div>

            {{-- Details --}}
            <div class="wc-fail-grid">

                <div class="wc-fail-item">
                    <div class="wc-fail-label">Attempted At</div>
                    <div class="wc-fail-value">
                        {{ $pay && $pay->created_at ? \Carbon\Carbon::parse($pay->created_at)->format('d M Y, H:i') : '-' }}
                    </div>
                </div>

                <div class="wc-fail-item">
                    <div class="wc-fail-label">Transaction ID</div>
                    <div class="wc-fail-value">{{ $pay->txnid ?? '-' }}</div>
                </div>

                <div class="wc-fail-item">
                    <div class="wc-fail-label">Customer</div>
                    <div class="wc-fail-value">
                        {{ trim(($pay->name ?? '') . ' ' . ($pay->email ?? '')) ?: '-' }}
                    </div>
                </div>

                <div class="wc-fail-item">
                    <div class="wc-fail-label">Status</div>
                    <div class="wc-fail-value" style="color:#b91c1c;">
                        {{ $pay ? ucfirst($pay->status ?? 'failed') : 'Failed' }}
                    </div>
                </div>

            </div>

            {{-- Reason --}}
            <div class="wc-fail-reason">
                <div class="wc-fail-reason-icon">⚠️</div>
                <div>
                    <div class="wc-fail-reason-title">What happened?</div>
                    <div class="wc-fail-reason-text">
                        {{ $pay->error_message ?? 'The transaction was declined or timed out by your bank or payment provider.' }}
                    </div>
                </div>
            </div>

            {{-- Footer --}}
           <div class="wc-fail-footer">
    <div class="wc-fail-help">
        If the amount is debited but shows failed,
        contact <strong>Wellcare Labs support</strong> with your Transaction ID.<br>
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

    if (counter) {
        counter.textContent = seconds;
    }

    if (seconds <= 0) {
        clearInterval(timer);
        window.location.href = "{{ url('/') }}";
    }
}, 1000);
</script>


@endsection
