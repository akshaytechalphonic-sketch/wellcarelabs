@extends('layouts.app')
@section('title', $coupon->exists ? 'Dashboard - Edit Coupon' : 'Dashboard - Create Coupon')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- External CSS --}}
    <link href="{{ asset('Back_end/css/coupons/coupon-create.css') }}" rel="stylesheet">

    @php
        $tz = config('app.timezone', 'Asia/Kolkata');
        $status = $coupon->exists ? $coupon->computed_status ?? 'inactive' : null;
        $badgeClass = match ($status) {
            'live' => 'bg-success',
            'scheduled' => 'bg-warning text-dark',
            'expired' => 'bg-danger',
            'inactive' => 'bg-secondary',
            default => 'bg-secondary',
        };
    @endphp

    <div class="admin-page-wrapper">
        <div class="card shadow-sm admin-page-card-flush">
            {{-- Header --}}
            <div
                class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <h5 class="panel-title mb-0">{{ $coupon->exists ? 'Edit Coupon' : 'Create Coupon' }}</h5>
                        <small class="text-muted">Configure discount code rules</small>
                    </div>
                    @if ($coupon->exists)
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                    @endif
                </div>
                <a href="{{ route('admin.coupons.index') }}" class="btn-ghost-secondary">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back to List</span>
                </a>
            </div>

            {{-- Body --}}
            <div class="card-body card-body-soft">
                <form method="POST"
                    action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
                    id="couponForm" novalidate>
                    @csrf
                    @if ($coupon->exists)
                        @method('PUT')
                    @endif

                    {{-- Row 1: Code / Type / Value --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="wc-field-label">
                                Code <span class="text-danger">*</span>
                            </label>
                            <input name="code" type="text" required
                                class="form-control wc-control @error('code') is-invalid @enderror"
                                placeholder="e.g. WELCOME10" value="{{ old('code', $coupon->code) }}">
                            @error('code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="wc-field-label">
                                Type <span class="text-danger">*</span>
                            </label>

                            <select name="type" id="couponType"
                                class="form-select wc-control @error('type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                <option value="percent" @selected(old('type', $coupon->type) == 'percent')>Percent (%)</option>
                                <option value="fixed" @selected(old('type', $coupon->type) == 'fixed')>Fixed (₹)</option>
                            </select>

                            @error('type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="wc-field-label">
                                <span id="valueLabel">Value</span> <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" name="value" id="couponValue"
                                class="form-control wc-control @error('value') is-invalid @enderror"
                                placeholder="10 for 10% or ₹10" value="{{ old('value', $coupon->value) }}" required>
                            @error('value')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="valueHelp" class="wc-help-text form-text mt-1">
                                @if (old('type', $coupon->type) == 'percent')
                                    Enter percentage (0–100).
                                @else
                                    Enter flat amount in ₹.
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Row 2: Thresholds / Limits --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="wc-field-label">Min Order Amount</label>
                            <input type="number" step="0.01" name="min_order_amount"
                                class="form-control wc-control @error('min_order_amount') is-invalid @enderror"
                                placeholder="₹" value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                            @error('min_order_amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="wc-field-label">Global Usage Limit</label>
                            <input type="number" name="usage_limit"
                                class="form-control wc-control @error('usage_limit') is-invalid @enderror" placeholder="∞"
                                value="{{ old('usage_limit', $coupon->usage_limit) }}">
                            @error('usage_limit')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 3: Window (starts / expires) --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="wc-field-label">Starts At</label>
                            <input type="datetime-local" name="starts_at"
                                class="form-control wc-control @error('starts_at') is-invalid @enderror"
                                value="{{ old('starts_at', optional($coupon->starts_at?->timezone($tz))->format('Y-m-d\TH:i')) }}">
                            @error('starts_at')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                If set in the future, the coupon will be saved as <strong>Inactive</strong> until that time.
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="wc-field-label">Expires At</label>
                            <input type="datetime-local" name="expires_at"
                                class="form-control wc-control @error('expires_at') is-invalid @enderror"
                                value="{{ old('expires_at', optional($coupon->expires_at?->timezone($tz))->format('Y-m-d\TH:i')) }}">
                            @error('expires_at')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                If set in the past, the coupon will be saved as <strong>Inactive</strong>.
                            </div>
                        </div>
                    </div>

                    {{-- Row 4: Active big switch --}}
                    <div class="mb-3 d-flex align-items-center gap-3">
                        <label class="big-switch" id="activeSwitch" title="Toggle active">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                @checked(old('is_active', $coupon->is_active))>
                            <span class="knob"></span>
                        </label>

                        <div>
                            <div class="fw-semibold mb-0">
                                Status:
                                <span id="activeStatusText"
                                    style="color: {{ old('is_active', $coupon->is_active) ? '#0f9d80' : '#dc3545' }};">
                                    {{ old('is_active', $coupon->is_active) ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Row 5: Show on Frontend switch --}}
                    <div class="mb-3 d-flex align-items-center gap-3">
                        <label class="big-switch" id="frontendSwitch" title="Toggle show on frontend">
                            <input type="hidden" name="show_on_frontend" value="0">
                            <input type="checkbox" id="show_on_frontend" name="show_on_frontend" value="1"
                                @checked(old('show_on_frontend', $coupon->show_on_frontend ?? true))>
                            <span class="knob"></span>
                        </label>

                        <div>
                            <div class="fw-semibold mb-0">
                                Show on Frontend:
                                <span id="frontendStatusText"
                                    style="color: {{ old('show_on_frontend', $coupon->show_on_frontend ?? true) ? '#0f9d80' : '#dc3545' }};">
                                    {{ old('show_on_frontend', $coupon->show_on_frontend ?? true) ? 'Yes' : 'No' }}
                                </span>
                            </div>

                            <div class="text-muted small">
                                If OFF, coupon will be hidden from frontend coupons page but will still work if entered
                                manually.
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn-primary-pill">
                            <i class="fa-solid fa-check"></i>
                            <span>{{ $coupon->exists ? 'Update' : 'Create' }}</span>
                        </button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn-ghost-secondary">
                            <span>Cancel</span>
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- External JS --}}
    <script src="{{ asset('Back_end/js/coupons/coupon-create.js') }}"></script>
@endsection
