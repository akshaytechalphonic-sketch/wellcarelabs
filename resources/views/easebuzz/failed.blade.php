@extends('maindesign')

@section('title', 'Payment Failed')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-danger">Payment Failed ✖</h2>
            <p class="lead">Unfortunately your payment did not complete.</p>

            @if(isset($payment) && $payment)
                <dl class="row">
                    <dt class="col-sm-4">Transaction ID</dt>
                    <dd class="col-sm-8">{{ $payment->txnid ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Amount</dt>
                    <dd class="col-sm-8">₹ {{ number_format((float)($payment->amount ?? 0), 2) }}</dd>

                    <dt class="col-sm-4">Payer</dt>
                    <dd class="col-sm-8">{{ $payment->payer_name ?? ($payment->payer_email ?? 'N/A') }}</dd>

                    <dt class="col-sm-4">Payment Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-danger text-white">{{ ucfirst($payment->status ?? 'failed') }}</span>
                    </dd>

                    <dt class="col-sm-4">Stored At</dt>
                    <dd class="col-sm-8">{{ optional($payment->created_at)->toDayDateTimeString() ?? '—' }}</dd>
                </dl>

                <div class="mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-primary">Try Again</a>
                    <a href="{{ route('payments.history') }}" class="btn btn-outline-secondary">Payment History</a>
                </div>

                <hr>

                <h5>Gateway response (for debugging)</h5>
                <pre style="max-height:300px; overflow:auto; background:#f8f9fa; padding:12px; border-radius:6px;">
{{ json_encode(is_array($payment->response) ? $payment->response : json_decode($payment->response ?? '{}', true), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) }}
                </pre>
            @else
                <div class="alert alert-warning">
                    Payment record not found. If you were charged, please contact support with your transaction id.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
