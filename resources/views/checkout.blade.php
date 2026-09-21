{{-- resources/views/checkout.blade.php --}}
@extends('maindesign')

@section('title', 'Checkout - Wellcare Labs')

@section('content')

    {{-- 🚫 Disable browser caching for checkout page --}}
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <div class="wc-wrapper">
        <div style="text-align:center;margin-top:0px;margin-bottom:0px;">
            <h1 style="font-size:2.4rem;font-weight:700;color:#0a2540;position:relative;display:inline-block;">
                Wellcare Appointment Form
                <div
                    style="width:80px;height:4px;margin:10px auto 16px;border-radius:3px;background:linear-gradient(90deg,#0047ff,#00ccff);">
                </div>
            </h1>
        </div>

        {{-- Server-side validation errors (Laravel) --}}
        @if ($errors->any())
            <div class="wc-alert wc-alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- Flag to trigger success modal (message text handled in JS for safety) --}}
        @if (session('success'))
            <div style="display:none" id="serverSuccessData" data-enabled="1"></div>
        @endif


        {{-- LAYOUT: SUMMARY (LEFT) + FORM (RIGHT) --}}
        <div class="wc-layout">
            {{-- ITEMS SUMMARY (LEFT) --}}
            <aside class="wc-card wc-summary">
                <h3 class="wc-section-title">Selected Items</h3>

                @if (!empty($items))
                    <div class="wc-items">
                        @foreach ($items as $it)
                            @php
                                $qty = (int) ($it['quantity'] ?? 1);
                                $unit = (float) $it['price'];
                                $line = $unit * $qty;
                            @endphp
                            <div class="wc-item-card">
                                <div class="wc-item-row">
                                    <div class="wc-item-left">
                                        <div class="wc-badges">
                                            @php
                                                $getVal = function ($obj, $key, $default = null) {
                                                    if (is_array($obj)) {
                                                        return $obj[$key] ?? $default;
                                                    }
                                                    if (is_object($obj)) {
                                                        return $obj->{$key} ?? $default;
                                                    }
                                                    return $default;
                                                };
                                                $type = (string) ($getVal($it, 'item_type', '') ?: '');
                                                $label = $getVal($it, 'name')
                                                    ? $getVal($it, 'name')
                                                    : ucfirst($type ?: 'item');
                                            @endphp
                                        </div>
                                        <div class="wc-item-name">{{ $it['name'] }}</div>
                                    </div>
                                    <div class="wc-item-right">
                                        @if ($qty > 1)
                                            <div class="wc-item-qty">× {{ $qty }}</div>
                                        @endif
                                        <div class="wc-item-line">₹{{ number_format($line, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    @php
                        $subtotalAmount = (float) ($total ?? 0);
                        if ($subtotalAmount <= 0 && !empty($items)) {
                            $subtotalAmount = 0;
                            foreach ($items as $it) {
                                $qty = (int) ($it['quantity'] ?? 1);
                                $unit = (float) ($it['price'] ?? 0);
                                $subtotalAmount += $qty * $unit;
                            }
                        }

                        $tz = config('app.timezone', 'Asia/Kolkata');
                    @endphp

                    <div class="wc-total">
                        {{-- Coupon input --}}
                        <div class="wc-field" style="margin-bottom:10px;">
                            <label for="coupon_code" style="display:block;font-weight:700;margin-bottom:6px;">Coupon
                                Code</label>
                            <div style="display:flex;gap:8px;">
                                <input id="coupon_code" name="coupon_code" type="text"
                                    style="flex:1;padding:10px 12px;border:1px solid var(--wc-border);border-radius:10px;"
                                    placeholder="ENTERCODE" autocomplete="off">
                                <button id="applyCouponBtn" type="button" class="wc-btn wc-btn-primary"
                                    style="width:auto;padding:10px 14px;">Apply</button>
                                <button id="removeCouponBtn" type="button" class="wc-btn"
                                    style="width:auto;padding:10px 14px;background:#eef2ff;display:none;">Remove</button>
                            </div>
                            <p id="couponFeedback" class="wc-help" style="margin-top:6px;"></p>
                        </div>

                        {{-- Available Coupons list (frontend) --}}
                        @if (isset($availableCoupons) && $availableCoupons->count())
                            @php
                                $subtotalAmount = $subtotalAmount ?? 0;
                                $packageId = $packageId ?? null;
                                $userId = $userId ?? null;
                                $visibleCoupons = 0;
                            @endphp

                            <div class="wc-available-coupons">
                                <div class="wc-avc-header">
                                    <span class="wc-avc-title">Available Coupons</span>
                                    <span class="wc-avc-count" id="couponCount">loading...</span>
                                </div>

                                <div class="wc-avc-list" id="couponListContainer">
                                    @foreach ($availableCoupons as $coupon)
                                        @php
                                            // Simple check - show ALL coupons
                                            $isVisible = $coupon->show_on_frontend;

                                            if (!$isVisible) {
                                                continue;
                                            }

                                            $visibleCoupons++;

                                            // Check if coupon can be applied
                                            [$canApply, $reasonKey] = $coupon->canApply(
                                                $subtotalAmount,
                                                $packageId,
                                                $userId,
                                            );
                                            $isApplicable = $canApply;

                                            // Get amount needed if minimum order not met
                                            $amountNeeded = 0;
                                            $message = '';

                                            if (!$isApplicable && $reasonKey === 'min_order') {
                                                $amountNeeded = max(
                                                    0,
                                                    ($coupon->min_order_amount ?? 0) - $subtotalAmount,
                                                );
                                                $message =
                                                    'Add ₹' .
                                                    number_format($amountNeeded, 2) .
                                                    ' more to apply this coupon';
                                            } elseif (!$isApplicable) {
                                                // If coupon cannot be applied for other reasons (expired, inactive, etc.)
                                                // We still show it but with different styling
                                                $messages = $coupon->couponMessages();
                                                $message = $messages[$reasonKey] ?? $messages['invalid'];
                                            }

                                            $isPercent = $coupon->type === 'percent';
                                            $valueLabel = $isPercent
                                                ? number_format($coupon->value, 2) . '% OFF'
                                                : '₹' . number_format($coupon->value, 2) . ' OFF';

                                            $used = method_exists($coupon, 'usages')
                                                ? $coupon->usages()->sum('quantity')
                                                : 0;

                                            $limit = $coupon->usage_limit;
                                            $remaining = is_null($limit) ? null : max($limit - $used, 0);

                                            // Determine status
                                            $statusClass = '';
                                            $buttonClass = '';
                                            $buttonText = 'Apply';
                                            $isDisabled = false;
                                            $tooltipText = '';

                                            if (!$isApplicable) {
                                                if ($reasonKey === 'min_order') {
                                                    $statusClass = 'wc-avc-item-needs-more';
                                                    $buttonClass = 'wc-avc-btn-disabled';
                                                    $buttonText = 'Add More Items';
                                                    $tooltipText =
                                                        'Add ₹' . number_format($amountNeeded, 2) . ' more to apply';
                                                } else {
                                                    $statusClass = 'wc-avc-item-ineligible';
                                                    $buttonClass = 'wc-avc-btn-disabled';
                                                    $buttonText = 'Not Eligible';
                                                    $tooltipText = $message;
                                                    $isDisabled = true;
                                                }
                                            }
                                        @endphp

                                        <div class="blink-coupon {{ $isApplicable ? '' : 'disabled' }}"
                                            data-code="{{ $coupon->code }}"
                                            data-applicable="{{ $isApplicable ? '1' : '0' }}">

                                            <div class="blink-left">
                                                <div class="blink-discount">
                                                    {{ $valueLabel }}
                                                </div>
                                            </div>

                                            <div class="blink-middle">
                                                <div class="blink-code">{{ $coupon->code }}</div>

                                                <div class="blink-desc">
                                                    @if ($isApplicable)
                                                        Applicable on orders above
                                                        ₹{{ number_format($coupon->min_order_amount ?? 0) }}
                                                    @else
                                                        {{ $message }}
                                                    @endif
                                                </div>

                                                @if ($coupon->expires_at)
                                                    <div class="blink-expiry">
                                                        Expires {{ $coupon->expires_at->format('d M') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="blink-right">
                                                <button type="button" class="blink-apply wc-coupon-apply-btn"
                                                    data-code="{{ $coupon->code }}"
                                                    data-applicable="{{ $isApplicable ? '1' : '0' }}"
                                                    {{ $isApplicable ? '' : 'disabled' }}>
                                                    {{ $isApplicable ? 'APPLY' : 'NOT ELIGIBLE' }}
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if ($visibleCoupons === 0)
                                    <div class="wc-avc-empty">
                                        <i class="far fa-tag" style="margin-right:8px;"></i>
                                        No coupons available for your current selection
                                    </div>
                                @endif

                                <script>
                                    // Update coupon count
                                    document.querySelector('.wc-avc-count').textContent =
                                        '{{ $visibleCoupons }} coupon{{ $visibleCoupons == 1 ? '' : 's' }} available';
                                </script>
                            </div>
                        @endif
                        {{-- /Available Coupons list --}}

                        {{-- Rest of your totals section --}}
                        <div class="wc-total-row" style="margin-top:10px;">
                            <span>Subtotal</span>
                            <strong id="orderSubtotal"
                                data-value="{{ number_format($subtotalAmount, 2, '.', '') }}">₹{{ number_format($subtotalAmount, 2) }}</strong>
                        </div>

                        <div id="discountRow" class="wc-total-row" style="display:none;">
                            <span>Discount (<span id="discountLabel"></span>)</span>
                            <strong id="orderDiscount">- ₹0.00</strong>
                        </div>

                        <div class="wc-total-row"
                            style="border-top:1px dashed var(--wc-border);padding-top:10px;margin-top:10px%;">
                            <span><strong>Total Payable</strong></span>
                            <strong id="orderTotal">₹{{ number_format($subtotalAmount, 2) }}</strong>
                        </div>
                    </div>
                @else
                    <p class="wc-muted">No items found. Please add tests/packages from the cart.</p>
                @endif
            </aside>

            {{-- FORM (RIGHT) --}}
            <form id="bookingForm" action="{{ route('form.submit') }}" method="POST" class="wc-card wc-form">
                @csrf
                <input type="hidden" name="payment_type" id="payment_mode">


                {{-- Hidden inputs for cart items (server-side to avoid JS dependency) --}}
                @php $__idx = 0; @endphp
                @foreach ($items ?? [] as $it)
                    <input type="hidden" name="items[{{ $__idx }}][key]" value="{{ $it['key'] }}">
                    <input type="hidden" name="items[{{ $__idx }}][item_type]" value="{{ $it['item_type'] }}">
                    <input type="hidden" name="items[{{ $__idx }}][item_id]" value="{{ $it['item_id'] }}">
                    <input type="hidden" name="items[{{ $__idx }}][item_name]" value="{{ $it['name'] }}">
                    <input type="hidden" name="items[{{ $__idx }}][item_price]"
                        value="{{ (float) $it['price'] }}">
                    <input type="hidden" name="items[{{ $__idx }}][quantity]"
                        value="{{ (int) ($it['quantity'] ?? 1) }}">
                    @php $__idx++; @endphp
                @endforeach

                {{-- Hidden coupon fields --}}
                <input type="hidden" id="applied_coupon_code" name="applied_coupon_code" value="">
                <input type="hidden" id="applied_discount" name="applied_discount" value="">

                {{-- Patient Details --}}
                <div class="wc-section">
                    <h3 class="wc-section-title">Patient Details</h3>
                    <div class="wc-grid">
                        {{-- Full Name --}}
                        <div class="wc-field">
                            <label for="name">Full Name <span class="wc-req">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                placeholder="Enter full name">
                            @error('name')
                                <div class="wc-help" style="color:#dc2626;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Gender --}}
                        <div class="wc-field">
                            <label for="gender">Gender <span class="wc-req">*</span></label>
                            <select id="gender" name="gender" required>
                                <option value="">Select gender</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="wc-help" style="color:#dc2626;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Date of Birth --}}
                        <div class="wc-field">
                            <label for="dob">Date of Birth <span class="wc-req">*</span></label>
                            <input type="text" id="dob" name="dob" value="{{ old('dob') }}" required
                                placeholder="Select date of birth" autocomplete="off">
                            <div id="dobFeedback" class="wc-help" style="color:#dc2626;display:none;margin-top:6px;">
                            </div>
                            @error('dob')
                                <div class="wc-help" style="color:#dc2626;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Age --}}
                        <div class="wc-field">
                            <label for="age">Age <span class="wc-req">*</span></label>
                            <input type="number" id="age" name="age" value="{{ old('age') }}"
                                min="0" max="120" step="1" required placeholder="e.g. 32" readonly>
                            @error('age')
                                <div class="wc-help" style="color:#dc2626;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mobile --}}
                        <div class="wc-field">
                            <label for="phone">Mobile No <span class="wc-req">*</span></label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                inputmode="numeric" pattern="[0-9]{10}" placeholder="10-digit mobile number">
                            <small class="wc-help">Enter 10 digits, e.g. 9876543210</small>
                            @error('phone')
                                <div class="wc-help" style="color:#dc2626;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="wc-field">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="name@example.com (optional)">
                        </div>
                    </div>
                </div>

                {{-- Appointment Details --}}
                <div class="wc-section">
                    <h3 class="wc-section-title">Appointment Details</h3>
                    <div class="wc-grid">
                        <div class="wc-field">
                            <label for="date">Preferred Collection Date <span class="wc-req">*</span></label>
                            <input type="date" id="date" name="date" value="{{ old('date') }}" required>
                            @error('date')
                                <div class="wc-help" style="color:#dc2626;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="wc-field">
                            <label for="time_slot">Preferred Time Slot <span class="wc-req">*</span></label>
                            <select id="time_slot" name="time_slot" required>
                                <option value="">-- Select a date first --</option>
                            </select>
                            <small id="slotHint" class="wc-help">Slots are hourly: 05:30–06:30, 06:30–07:30, etc.</small>
                            <div id="slotNotice" class="wc-help" style="color:#dc2626;display:none;margin-top:6px;">
                            </div>
                            @error('time_slot')
                                <div class="wc-help" style="color:#dc2626;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Address Details --}}
                <div class="wc-section">
                    <h3 class="wc-section-title">Address Details</h3>
                    <div class="wc-grid wc-grid-1">
                        <div class="wc-field">
                            <label for="address">Full Address <span class="wc-req">*</span></label>
                            <textarea id="address" name="address" rows="3" required placeholder="House/Flat, Street, Locality">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="wc-help" style="color:#dc2626;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="wc-grid">
                        <div class="wc-field">
                            <label for="city">Area / City <span class="wc-req">*</span></label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" required
                                placeholder="e.g. Pune">
                            @error('city')
                                <div class="wc-help" style="color:#dc2626;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="wc-field">
                            <label for="pincode">PIN Code <span class="wc-req">*</span></label>
                            <input type="text" id="pincode" name="pincode" value="{{ old('pincode') }}" required
                                inputmode="numeric" pattern="[0-9]{6}" placeholder="6-digit PIN">
                            @error('pincode')
                                <div class="wc-help" style="color:#dc2626;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="wc-field">
                            <label for="message">Message (Optional)</label>
                            <textarea id="message" name="message" rows="3" placeholder="Any special instructions for sample collection">{{ old('message') }}</textarea>

                            @error('message')
                                <div class="wc-help" style="color:#dc2626;margin-top:6px;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <div class="wc-field">
                            <label for="landmark">Landmark (Optional)</label>
                            <input type="text" id="landmark" name="landmark" value="{{ old('landmark') }}"
                                placeholder="Nearby landmark">
                            @error('landmark')
                                <div class="wc-help" style="color:#dc2626;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>



                <button id="openPaymentModal" type="button" class="bk-btn-primary btn-more">
                    Book Appointment Now
                </button>

            </form>
        </div>
    </div>

    {{-- SUCCESS MODAL --}}
    <div id="successModalOverlay" aria-hidden="true" style="display:none;">
        <div id="successModal" role="dialog" aria-modal="true" aria-labelledby="successTitle">
            <div class="success-badge">✔</div>
            <h3 id="successTitle">Appointment booked successfully</h3>
            <p id="successMessage">Thank you — your appointment has been scheduled.</p>
            <div><a href="{{ url('/') }}" id="goHomeBtn" class="btn">Go to Home</a></div>
            <p class="redirect-note">Redirecting to home in <strong id="redirectCounter">5</strong> seconds...</p>
        </div>
    </div>



    <div id="paymentModalOverlay" style="display:none;">
        <div class="payment-modal">
            <h3>Select Payment Method</h3>

            <button type="button" id="payOnlineBtn" class="pay-btn online">
                💳 Pay Online
            </button>

            <button type="button" id="payCashBtn" class="pay-btn cash">
                💵 Cash on Collection
            </button>

            <button type="button" id="closePaymentModal" class="close-btn">
                Cancel
            </button>
        </div>
    </div>


    {{-- STYLES --}}
    <style>
        /* ===== BLINKIT / ZEPTO COUPON UI ===== */

        .blink-coupon {
            display: grid;
            grid-template-columns: 64px 1fr auto;
            gap: 14px;
            padding: 14px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            align-items: center;
            transition: all .2s ease;
        }

        .blink-coupon:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }

        .blink-coupon.disabled {
            opacity: 0.6;
            background: #f9fafb;
        }

        /* LEFT DISCOUNT PILL */
        .blink-discount {
            background: #16a34a;
            color: #fff;
            font-size: .8rem;
            font-weight: 700;
            padding: 6px 8px;
            border-radius: 8px;
            text-align: center;
            line-height: 1.1;
        }

        /* CENTER TEXT */
        .blink-middle {
            min-width: 0;
        }

        .blink-code {
            font-family: monospace;
            font-size: .95rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .blink-desc {
            font-size: .82rem;
            color: #6b7280;
            line-height: 1.3;
        }

        .blink-expiry {
            font-size: .75rem;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* APPLY BUTTON */
        .blink-apply {
            border: 1px solid #16a34a;
            background: #ecfdf5;
            color: #166534;
            font-size: .78rem;
            font-weight: 700;
            padding: 8px 14px;
            border-radius: 999px;
            cursor: pointer;
            transition: all .2s ease;
        }

        .blink-apply:hover:not(:disabled) {
            background: #16a34a;
            color: #fff;
        }

        .blink-apply:disabled {
            background: #e5e7eb;
            color: #9ca3af;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }

        /* MOBILE */
        @media (max-width: 640px) {
            .blink-coupon {
                grid-template-columns: 48px 1fr;
            }

            .blink-right {
                grid-column: 1 / -1;
                text-align: right;
            }
        }

        /* Coupon Styles */
        .wc-available-coupons {
            margin: 16px 0 20px;
            padding: 16px;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            border: 1px solid #dbeafe;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
        }

        .wc-avc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .wc-avc-title {
            font-weight: 700;
            font-size: 1rem;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .wc-avc-title:before {
            content: "🎁";
            font-size: 1.1rem;
        }

        .wc-avc-count {
            font-size: 0.85rem;
            color: #6b7280;
            background: #f3f4f6;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 500;
        }

        .wc-avc-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .wc-avc-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 16px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .wc-avc-item:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: #10b981;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .wc-avc-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            border-color: #c7d2fe;
        }

        .wc-avc-item:hover:before {
            opacity: 1;
        }

        .wc-avc-item-needs-more {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-color: #fbbf24;
        }

        .wc-avc-item-needs-more:before {
            background: #f59e0b;
        }

        .wc-avc-item-ineligible {
            background: #f9fafb;
            opacity: 0.8;
            border-color: #d1d5db;
        }

        .wc-avc-main {
            flex: 1;
            min-width: 0;
        }

        .wc-avc-line1 {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .wc-avc-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        }

        .wc-avc-code {
            font-weight: 700;
            font-size: 1rem;
            color: #111827;
            letter-spacing: 1px;
            font-family: 'Courier New', monospace;
        }

        .wc-avc-hint {
            font-size: 0.8rem;
            color: #f59e0b;
            font-weight: 600;
            margin-left: 8px;
            padding: 3px 8px;
            background: #fffbeb;
            border-radius: 4px;
            border: 1px solid #fde68a;
        }

        .wc-avc-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 0.85rem;
            color: #6b7280;
        }

        .wc-avc-min-amount,
        .wc-avc-validity {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            background: #f3f4f6;
            border-radius: 4px;
        }

        .wc-avc-progress-container {
            margin-top: 12px;
            padding: 12px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .wc-avc-progress-text {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .wc-avc-current {
            color: #059669;
            font-weight: 600;
        }

        .wc-avc-separator {
            color: #9ca3af;
        }

        .wc-avc-target {
            color: #111827;
            font-weight: 600;
        }

        .wc-avc-progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .wc-avc-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .wc-avc-progress-info {
            font-size: 0.8rem;
            color: #92400e;
            display: flex;
            align-items: center;
        }

        .wc-avc-remaining {
            margin-top: 8px;
            padding: 6px 10px;
            background: #fffbeb;
            border-radius: 6px;
            font-size: 0.85rem;
            color: #92400e;
            border: 1px solid #fde68a;
            display: inline-flex;
            align-items: center;
        }

        .wc-avc-actions {
            display: flex;
            align-items: center;
        }

        .wc-avc-apply {
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
        }

        .wc-avc-apply:hover:not(.wc-avc-btn-disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        }

        .wc-avc-btn-disabled {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
            cursor: not-allowed;
            opacity: 0.7;
            box-shadow: none;
        }

        .wc-avc-btn-disabled:hover {
            transform: none !important;
            box-shadow: none !important;
        }

        .wc-avc-empty {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Coupon Feedback Styles */
        .wc-coupon-feedback {
            padding: 10px 14px;
            border-radius: 8px;
            margin-top: 8px;
            font-size: 0.9rem;
            border: 1px solid transparent;
        }

        .wc-feedback-success {
            background-color: #d1fae5;
            color: #065f46;
            border-color: #a7f3d0;
        }

        .wc-feedback-error {
            background-color: #fee2e2;
            color: #991b1b;
            border-color: #fecaca;
        }

        .wc-feedback-warning {
            background-color: #fef3c7;
            color: #92400e;
            border-color: #fde68a;
        }

        .wc-feedback-info {
            background-color: #dbeafe;
            color: #1e40af;
            border-color: #bfdbfe;
        }

        .wc-coupon-feedback i {
            margin-right: 8px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .wc-avc-item {
                flex-direction: column;
                gap: 12px;
            }

            .wc-avc-actions {
                width: 100%;
            }

            .wc-avc-apply {
                width: 100%;
            }
        }

        #paymentModalOverlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .payment-modal {
            width: 360px;
            background: #fff;
            padding: 22px;
            border-radius: 14px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .25);
        }

        .payment-modal h3 {
            margin-bottom: 16px;
            font-size: 1.2rem;
            color: #0a2540;
        }

        .pay-btn {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .pay-btn.online {
            background: #0a66c2;
            color: #fff;
        }

        .pay-btn.cash {
            background: #16a34a;
            color: #fff;
        }

        .close-btn {
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
        }

        :root {
            --wc-primary: #0a66c2;
            --wc-primary-600: #095bb0;
            --wc-bg: #f6f8fb;
            --wc-text: #1f2937;
            --wc-muted: #6b7280;
            --wc-border: #e5e7eb;
            --wc-card: #ffffff;
        }

        body {
            background: var(--wc-bg);
        }

        .wc-wrapper {
            max-width: 1100px;
            margin: 32px auto 40px;
            padding: 12px;
            animation: fadeSlideUp .6s ease both;
        }

        .wc-layout {
            display: grid;
            grid-template-columns: minmax(280px, 1fr) minmax(0, 1.6fr);
            gap: 16px;
            align-items: start;
        }

        .wc-card {
            background: var(--wc-card);
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
            border: 1px solid var(--wc-border);
        }

        .wc-form {
            padding: 20px;
        }

        .wc-section {
            margin-bottom: 16px;
        }

        .wc-section-title {
            font-size: 1.05rem;
            color: var(--wc-text);
            font-weight: 700;
            margin: 4px 0 14px;
        }

        .wc-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .wc-grid-1 {
            grid-template-columns: 1fr;
        }

        .wc-field label {
            display: block;
            font-weight: 300;
            margin-bottom: 6px;
            color: var(--wc-text);
        }

        .wc-field input,
        .wc-field select,
        .wc-field textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--wc-border);
            border-radius: 10px;
            font-size: 0.98rem;
            outline: none;
            background: #fff;
        }

        .wc-field input:focus,
        .wc-field select:focus,
        .wc-field textarea:focus {
            border-color: var(--wc-primary);
            box-shadow: 0 0 0 3px rgba(10, 102, 194, .1);
        }

        .wc-help {
            display: block;
            color: var(--wc-muted);
            font-size: .84rem;
            margin-top: 6px;
        }

        .wc-req {
            color: #ef4444;
        }

        .wc-actions {
            margin-top: 10px;
        }

        .wc-btn {
            display: inline-block;
            width: 100%;
            padding: 14px 18px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            font-size: 1.05rem;
            cursor: pointer;
        }

        .wc-btn-primary {
            background: linear-gradient(135deg, var(--wc-primary), #007bff);
            color: #fff;
            box-shadow: 0 10px 22px rgba(10, 102, 194, .22);
        }

        .wc-summary {
            padding: 18px;
            position: sticky;
            top: 16px;
            height: fit-content;
        }

        .wc-items {
            display: grid;
            gap: 10px;
        }

        .wc-item-card {
            border: 1px solid var(--wc-border);
            border-radius: 12px;
            padding: 12px;
            background: #fff;
        }

        .wc-item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .wc-item-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .wc-badge {
            background: #e9eef8;
            color: #0a3b76;
            font-weight: 700;
            font-size: .75rem;
            padding: 6px 10px;
            border-radius: 999px;
        }

        .wc-item-name {
            font-weight: 600;
            color: #0b1a2b;
        }

        .wc-item-right {
            display: flex;
            align-items: baseline;
            gap: 12px;
        }

        .wc-item-qty {
            color: var(--wc-muted);
            font-size: .9rem;
        }

        .wc-item-line {
            font-weight: 600;
        }

        .wc-total {
            margin-top: 8px;
            border-top: 1px dashed var(--wc-border);
            padding-top: 12px;
        }

        .wc-total-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 1.05rem;
        }

        .wc-muted {
            color: var(--wc-muted);
        }

        .wc-alert {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 14px;
        }

        .wc-alert-danger {
            background: #fee2e2;
            color: #7f1d1d;
            border: 1px solid #fecaca;
        }

        #successModalOverlay {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.45);
            z-index: 9999;
        }

        #successModal {
            width: 420px;
            max-width: 92%;
            border-radius: 14px;
            background: #fff;
            padding: 22px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        #successModal h3 {
            margin: 8px 0 6px;
            font-size: 1.25rem;
            color: #0b5394;
        }

        #successModal p {
            margin: 8px 0 14px;
            color: #444;
            line-height: 1.3;
        }

        #successModal .success-badge {
            display: inline-block;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2ecc71, #16a085);
            color: white;
            font-weight: 700;
            font-size: 1.6rem;
            line-height: 70px;
            margin-bottom: 10px;
        }

        #successModal .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 10px;
            background: #0b5394;
            color: #fff;
            text-decoration: none;
            margin-top: 8px;
            font-weight: 600;
        }

        #successModal .redirect-note {
            color: #666;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 1024px) {
            .wc-layout {
                grid-template-columns: 1fr;
            }

            .wc-summary {
                position: static;
                top: auto;
            }
        }

        @media (max-width: 860px) {
            .wc-grid {
                grid-template-columns: 1fr;
            }
        }

        label.error {
            color: #dc2626;
            font-size: .85rem;
            margin-top: 6px;
            display: block;
        }
    </style>

    <script>
        (function() {
            // 🚫 Prevent going back to checkout after payment
            window.history.pushState(null, "", window.location.href);

            window.onpopstate = function() {
                window.location.replace("{{ url('/') }}");
            };

            // 🔁 If page restored from cache (mobile back gesture)
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    window.location.replace("{{ url('/') }}");
                }
            });
        })();
    </script>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 🔒 If checkout session already completed, redirect home
            const checkoutCompleted = {{ session()->has('checkout_completed') ? 'true' : 'false' }};
            if (checkoutCompleted) {
                window.location.replace("{{ url('/') }}");
                return;
            }

            const openBtn = document.getElementById('openPaymentModal');
            const overlay = document.getElementById('paymentModalOverlay');
            const closeBtn = document.getElementById('closePaymentModal');
            const payOnline = document.getElementById('payOnlineBtn');
            const payCash = document.getElementById('payCashBtn');
            const form = document.getElementById('bookingForm');
            const modeInput = document.getElementById('payment_mode');

            // Open modal only if form is valid
            openBtn.addEventListener('click', function() {
                if (!$('#bookingForm').valid()) return;
                overlay.style.display = 'flex';
            });

            closeBtn.onclick = () => overlay.style.display = 'none';

            payOnline.onclick = function() {
                modeInput.value = 'online';
                form.submit();
            };

            payCash.onclick = function() {
                modeInput.value = 'cash';
                overlay.style.display = 'none';
                submitBookingForm();
            };

            function submitBookingForm() {
                const form = document.getElementById('bookingForm');
                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        // ✅ CASH SUCCESS
                        if (data.status === 'cash_success' && data.redirect_url) {
                            window.location.href = data.redirect_url;
                            return;
                        }

                        // ONLINE PAYMENT
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        }

                        if (data.status === 'error') {
                            alert(data.message || 'Something went wrong');
                        }
                    });
            }

            /* === Time slot logic === */
            const dateInput = document.getElementById('date');
            const slotSelect = document.getElementById('time_slot');
            const slotNotice = document.getElementById('slotNotice');

            const SLOT_START_H = 5,
                SLOT_START_M = 30;
            const SLOT_NORMAL_LAST_START_H = 13,
                SLOT_NORMAL_LAST_START_M = 30;
            const SLOT_LENGTH_MIN = 60;
            const SLOT_STEP_MIN = 60;
            const SAME_DAY_BOOKING_CUTOFF_H = 12,
                SAME_DAY_BOOKING_CUTOFF_M = 0;
            const SAME_DAY_EXTENDED_LAST_START_H = 14,
                SAME_DAY_EXTENDED_LAST_START_M = 30;

            function toISODate(date) {
                const y = date.getFullYear();
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const d = String(date.getDate()).padStart(2, '0');
                return `${y}-${m}-${d}`;
            }

            (function setMinDate() {
                const now = new Date();
                if (dateInput) {
                    dateInput.min = toISODate(now);
                    if (!dateInput.value) dateInput.value = toISODate(now);
                }
            })();

            function parseLocalISODate(iso) {
                if (!iso || typeof iso !== 'string') return null;
                const parts = iso.split('-');
                if (parts.length !== 3) return null;
                const y = parseInt(parts[0], 10);
                const m = parseInt(parts[1], 10);
                const d = parseInt(parts[2], 10);
                if (isNaN(y) || isNaN(m) || isNaN(d)) return null;
                return new Date(y, m - 1, d);
            }

            function formatTime(totalMinutes) {
                const h24 = Math.floor(totalMinutes / 60);
                const m = totalMinutes % 60;
                const ampm = h24 >= 12 ? 'PM' : 'AM';
                let h = h24 % 12;
                if (h === 0) h = 12;
                return `${h}:${String(m).padStart(2,'0')} ${ampm}`;
            }

            function generateSlots(isoDate) {
                if (!slotSelect) return;
                slotSelect.innerHTML = '';
                if (slotNotice) {
                    slotNotice.style.display = 'none';
                    slotNotice.textContent = '';
                }

                const chosen = parseLocalISODate(isoDate);
                if (!chosen) {
                    const placeholder = document.createElement('option');
                    placeholder.text = '-- Select a valid date --';
                    placeholder.disabled = true;
                    placeholder.selected = true;
                    slotSelect.appendChild(placeholder);
                    return;
                }

                const firstStartMin = SLOT_START_H * 60 + SLOT_START_M;
                const normalLastStartMin = SLOT_NORMAL_LAST_START_H * 60 + SLOT_NORMAL_LAST_START_M;

                const today = new Date();
                const isToday = today.getFullYear() === chosen.getFullYear() &&
                    today.getMonth() === chosen.getMonth() &&
                    today.getDate() === chosen.getDate();

                const now = new Date();

                if (isToday) {
                    const cutoff = new Date(today.getFullYear(), today.getMonth(), today.getDate(),
                        SAME_DAY_BOOKING_CUTOFF_H, SAME_DAY_BOOKING_CUTOFF_M, 0, 0);
                    const extendedLastStartMin = SAME_DAY_EXTENDED_LAST_START_H * 60 +
                        SAME_DAY_EXTENDED_LAST_START_M;

                    if (now > cutoff) {
                        const no = document.createElement('option');
                        no.text = 'Same-day booking closed after 12:00 PM. Please choose a future date.';
                        no.disabled = true;
                        no.selected = true;
                        slotSelect.appendChild(no);
                        if (slotNotice) {
                            slotNotice.style.display = 'block';
                            slotNotice.textContent =
                                'Same-day booking closed after 12:00 PM. Please choose a future date.';
                        }
                        return;
                    }

                    let added = 0;
                    for (let startMin = firstStartMin; startMin <= extendedLastStartMin; startMin +=
                        SLOT_STEP_MIN) {
                        const start = new Date(chosen);
                        start.setHours(Math.floor(startMin / 60), startMin % 60, 0, 0);
                        if (start <= now) continue;
                        const opt = document.createElement('option');
                        const hh = String(Math.floor(startMin / 60)).padStart(2, '0');
                        const mm = String(startMin % 60).padStart(2, '0');
                        opt.value = `${hh}:${mm}`;
                        opt.textContent = `${formatTime(startMin)} - ${formatTime(startMin + SLOT_LENGTH_MIN)}`;
                        slotSelect.appendChild(opt);
                        added++;
                    }

                    if (added === 0) {
                        const none = document.createElement('option');
                        none.text = 'No available slots remain for today.';
                        none.disabled = true;
                        none.selected = true;
                        slotSelect.appendChild(none);
                    }
                    return;
                }

                let added = 0;
                for (let startMin = firstStartMin; startMin <= normalLastStartMin; startMin += SLOT_STEP_MIN) {
                    const hh = String(Math.floor(startMin / 60)).padStart(2, '0');
                    const mm = String(startMin % 60).padStart(2, '0');
                    const opt = document.createElement('option');
                    opt.value = `${hh}:${mm}`;
                    opt.textContent = `${formatTime(startMin)} - ${formatTime(startMin + SLOT_LENGTH_MIN)}`;
                    slotSelect.appendChild(opt);
                    added++;
                }

                if (added === 0) {
                    const none = document.createElement('option');
                    none.text = 'No slots available';
                    none.disabled = true;
                    none.selected = true;
                    slotSelect.appendChild(none);
                }
            }

            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    if (this.value) generateSlots(this.value);
                });
                generateSlots(dateInput.value);
            }

            /* DOB flatpickr */
            (function initDOBPicker() {
                const dobEl = document.getElementById('dob');
                if (!dobEl || typeof flatpickr === 'undefined') return;
                const today = new Date();
                const maxYear = today.getFullYear();
                const minYear = maxYear - 120;

                flatpickr(dobEl, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d M, Y",
                    maxDate: "today",
                    allowInput: true,
                    defaultDate: dobEl.value || null,
                    yearSelectorType: "static",
                    minDate: `${minYear}-01-01`
                });
            })();

            /* Clear cart localStorage on success */
            try {
                const serverSuccess = document.getElementById('serverSuccessData');
                if (serverSuccess && serverSuccess.dataset.enabled === '1') {
                    localStorage.removeItem('wellcare_custom_selected_tests_v1');
                    localStorage.removeItem('wellcare_package_added_flag_v1');
                    localStorage.setItem('wellcare_custom_sync', Date.now().toString());
                }
            } catch (e) {}

            /* Enhanced Coupon Module */
            (function couponModule() {
                const applyBtn = document.getElementById('applyCouponBtn');
                const removeBtn = document.getElementById('removeCouponBtn');
                const couponIn = document.getElementById('coupon_code');
                const feedback = document.getElementById('couponFeedback');

                const subtotalEl = document.getElementById('orderSubtotal');
                const discountRow = document.getElementById('discountRow');
                const discountLbl = document.getElementById('discountLabel');
                const discountEl = document.getElementById('orderDiscount');
                const totalEl = document.getElementById('orderTotal');

                const hCoupon = document.getElementById('applied_coupon_code');
                const hDiscount = document.getElementById('applied_discount');

                const routeApply = '{{ route('checkout.coupon.apply') }}';
                const routeRemove = '{{ route('checkout.coupon.remove') }}';

                function getCsrfToken() {
                    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                        document.querySelector('input[name="_token"]')?.value ||
                        '';
                }

                function headersJSON() {
                    return {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'X-Requested-With': 'XMLHttpRequest',
                    };
                }

                function numberFromSubtotal() {
                    const v = parseFloat(subtotalEl?.getAttribute('data-value') || '0');
                    return isNaN(v) ? 0 : v;
                }

                function formatINR(n) {
                    return '₹' + Number(n || 0).toFixed(2);
                }

                function showMsg(text, type = 'info') {
                    if (!feedback) return;

                    feedback.textContent = text || '';
                    feedback.className = 'wc-help wc-coupon-feedback';

                    // Remove all existing classes
                    feedback.classList.remove('wc-feedback-success', 'wc-feedback-error', 'wc-feedback-warning',
                        'wc-feedback-info');

                    // Add appropriate class
                    switch (type) {
                        case 'success':
                            feedback.classList.add('wc-feedback-success');
                            feedback.innerHTML = `<i class="fas fa-check-circle"></i> ${text}`;
                            break;
                        case 'error':
                            feedback.classList.add('wc-feedback-error');
                            feedback.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${text}`;
                            break;
                        case 'warning':
                            feedback.classList.add('wc-feedback-warning');
                            feedback.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${text}`;
                            break;
                        case 'info':
                            feedback.classList.add('wc-feedback-info');
                            feedback.innerHTML = `<i class="fas fa-info-circle"></i> ${text}`;
                            break;
                    }
                }

                function toggleLoading(b) {
                    if (applyBtn) {
                        applyBtn.disabled = b;
                        applyBtn.innerHTML = b ? '<i class="fas fa-spinner fa-spin"></i> Applying...' : 'Apply';
                    }
                    if (removeBtn) removeBtn.disabled = b;
                    if (couponIn) couponIn.disabled = b;
                }

                function clearCouponUI() {
                    if (discountRow) discountRow.style.display = 'none';
                    if (discountEl) discountEl.textContent = '- ₹0.00';
                    if (discountLbl) discountLbl.textContent = '';

                    if (hCoupon) hCoupon.value = '';
                    if (hDiscount) hDiscount.value = '';

                    // ✅ CLEAR INPUT BOX
                    if (couponIn) couponIn.value = '';

                    if (removeBtn) removeBtn.style.display = 'none';

                    const subtotal = numberFromSubtotal();
                    if (totalEl) totalEl.textContent = formatINR(subtotal);

                    // Reset all coupon buttons
                    document.querySelectorAll('.wc-coupon-apply-btn').forEach(btn => {
                        const isApplicable = btn.getAttribute('data-applicable') === '1';
                        if (isApplicable) {
                            btn.textContent = 'Apply';
                            btn.classList.remove('wc-avc-btn-disabled');
                            btn.disabled = false;
                        }
                    });

                    showMsg('Coupon removed. You can apply another coupon.', 'info');
                }


                async function applyCoupon() {
                    const code = (couponIn?.value || '').trim();
                    if (!code) {
                        showMsg('Please enter a coupon code.', 'error');
                        return;
                    }

                    const subtotal = numberFromSubtotal();
                    if (subtotal <= 0) {
                        showMsg('Your cart is empty. Please add items first.', 'error');
                        return;
                    }

                    toggleLoading(true);
                    showMsg('Checking coupon eligibility...', 'info');

                    try {
                        const res = await fetch(routeApply, {
                            method: 'POST',
                            headers: headersJSON(),
                            credentials: 'same-origin',
                            body: JSON.stringify({
                                code,
                                subtotal,
                                package_ids: []
                            }),
                        });

                        const json = await res.json();

                        if (!res.ok || !json.ok) {
                            // Check if it's a minimum amount issue
                            if (json.reason === 'min_order') {
                                const amountNeeded = json.amount_needed || 0;
                                const minAmount = json.coupon?.min_order_amount || 0;

                                let message =
                                    `Add <strong>₹${amountNeeded.toFixed(2)}</strong> more to your cart to apply this coupon. `;
                                message +=
                                    `Minimum order required: <strong>₹${minAmount.toFixed(2)}</strong>.`;

                                showMsg(message, 'warning');

                                // Update the specific coupon button to show "Add More Items"
                                document.querySelectorAll('.wc-coupon-apply-btn').forEach(btn => {
                                    if (btn.getAttribute('data-code') === code) {
                                        btn.textContent = 'Add More Items';
                                        btn.classList.add('wc-avc-btn-disabled');
                                        btn.disabled = true;
                                        btn.title = `Add ₹${amountNeeded.toFixed(2)} more to apply`;
                                    }
                                });
                            } else {
                                showMsg(json.message || 'Could not apply coupon.', 'error');
                            }
                            return;
                        }

                        const discount = json.calc.discount;
                        const total = json.calc.total;
                        const coupon = json.coupon;

                        const label = coupon.type === 'percent' ? `${coupon.value}%` : coupon.code;

                        if (discountLbl) discountLbl.textContent = label;
                        if (discountEl) discountEl.textContent = '- ' + formatINR(discount);
                        if (discountRow) discountRow.style.display = 'flex';
                        if (totalEl) totalEl.textContent = formatINR(total);

                        if (hCoupon) hCoupon.value = coupon.code;
                        if (hDiscount) hDiscount.value = discount;

                        if (removeBtn) removeBtn.style.display = 'inline-block';

                        showMsg(`🎉 Coupon <strong>${coupon.code}</strong> applied! You saved <strong>₹${discount.toFixed(2)}</strong>.`,
                            'success');

                        // Update all coupon buttons
                        document.querySelectorAll('.wc-coupon-apply-btn').forEach(btn => {
                            const btnCode = btn.getAttribute('data-code');
                            if (btnCode === code) {
                                btn.textContent = 'Applied ✓';
                                btn.classList.add('wc-avc-btn-disabled');
                                btn.disabled = true;
                                btn.title = 'Coupon already applied';
                            } else {
                                btn.textContent = 'Apply';
                                btn.classList.remove('wc-avc-btn-disabled');
                                btn.disabled = false;
                                btn.title = 'Apply this coupon';
                            }
                        });

                    } catch (error) {
                        console.error('Coupon error:', error);
                        showMsg('Network error. Please check your connection and try again.', 'error');
                    } finally {
                        toggleLoading(false);
                    }
                }

                async function removeCoupon() {
                    toggleLoading(true);
                    try {
                        await fetch(routeRemove, {
                            method: 'POST',
                            headers: headersJSON(),
                            credentials: 'same-origin',
                            body: JSON.stringify({}),
                        });
                        clearCouponUI();
                    } catch (error) {
                        console.error('Remove coupon error:', error);
                        showMsg('Error removing coupon. Please try again.', 'error');
                    } finally {
                        toggleLoading(false);
                    }
                }

                // Apply coupon from input
                applyBtn?.addEventListener('click', applyCoupon);
                removeBtn?.addEventListener('click', removeCoupon);

                couponIn?.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        applyCoupon();
                    }
                });

                // Apply coupon from available coupons list
                document.querySelectorAll('.wc-coupon-apply-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const code = this.getAttribute('data-code');
                        const isApplicable = this.getAttribute('data-applicable') === '1';
                        const tooltip = this.getAttribute('title');

                        if (!couponIn) return;

                        couponIn.value = code;

                        if (!isApplicable) {
                            if (tooltip.includes('Add ₹')) {
                                showMsg(tooltip, 'warning');
                            } else {
                                showMsg(tooltip || 'Coupon not applicable.', 'error');
                            }
                            return;
                        }

                        applyCoupon();
                    });
                });

                // Initialize
                window.WCLCoupon = {
                    applyCoupon,
                    removeCoupon,
                    clearCouponUI
                };
            })();

            /* DOB -> Age module */
            (function dobAgeModule() {
                const dobInput = document.getElementById('dob');
                const ageInput = document.getElementById('age');
                const dobFeedback = document.getElementById('dobFeedback');

                if (!dobInput || !ageInput) return;

                const MAX_AGE = 120;
                ageInput.readOnly = true;

                function parseLocalISO(iso) {
                    if (!iso || typeof iso !== 'string') return null;
                    const parts = iso.split('-');
                    if (parts.length !== 3) return null;
                    const y = parseInt(parts[0], 10),
                        m = parseInt(parts[1], 10),
                        d = parseInt(parts[2], 10);
                    if (isNaN(y) || isNaN(m) || isNaN(d)) return null;
                    return new Date(y, m - 1, d);
                }

                function computeYears(dobDate, fromDate) {
                    let years = fromDate.getFullYear() - dobDate.getFullYear();
                    const mDiff = fromDate.getMonth() - dobDate.getMonth();
                    const dDiff = fromDate.getDate() - dobDate.getDate();
                    if (mDiff < 0 || (mDiff === 0 && dDiff < 0)) years--;
                    if (years < 0) years = 0;
                    return years;
                }

                function updateAgeFromDOB(isoStr) {
                    if (!isoStr) {
                        ageInput.value = '';
                        if (dobFeedback) dobFeedback.style.display = 'none';
                        return;
                    }

                    const dob = parseLocalISO(isoStr);
                    if (!dob) {
                        if (dobFeedback) {
                            dobFeedback.textContent = 'Invalid date format.';
                            dobFeedback.style.display = 'block';
                        }
                        ageInput.value = '';
                        return;
                    }

                    const now = new Date();
                    if (dob > now) {
                        if (dobFeedback) {
                            dobFeedback.textContent = 'Date of birth cannot be in the future.';
                            dobFeedback.style.display = 'block';
                        }
                        ageInput.value = '';
                        return;
                    }

                    const years = computeYears(dob, now);
                    if (years > MAX_AGE) {
                        if (dobFeedback) {
                            dobFeedback.textContent =
                                `Age appears greater than ${MAX_AGE} years. Please verify DOB.`;
                            dobFeedback.style.display = 'block';
                        }
                    } else if (dobFeedback) {
                        dobFeedback.style.display = 'none';
                    }

                    ageInput.value = String(years);
                }

                if (dobInput.value) updateAgeFromDOB(dobInput.value);

                dobInput.addEventListener('change', function() {
                    updateAgeFromDOB(dobInput.value);
                });
                dobInput.addEventListener('blur', function() {
                    updateAgeFromDOB(dobInput.value);
                });
                dobInput.addEventListener('input', function() {
                    if (!dobInput.value) {
                        ageInput.value = '';
                        if (dobFeedback) dobFeedback.style.display = 'none';
                    }
                });

                ageInput.addEventListener('keydown', function(e) {
                    e.preventDefault();
                });

                const form = dobInput.closest('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        if (dobInput.value) {
                            const dob = parseLocalISO(dobInput.value);
                            if (!dob || dob > new Date()) {
                                e.preventDefault();
                                if (dobFeedback) {
                                    dobFeedback.textContent =
                                        'Please enter a valid date of birth (not in the future).';
                                    dobFeedback.style.display = 'block';
                                }
                                dobInput.focus();
                                return false;
                            }
                            if (ageInput.value === '') {
                                updateAgeFromDOB(dobInput.value);
                                if (ageInput.value === '') {
                                    e.preventDefault();
                                    if (dobFeedback) {
                                        dobFeedback.textContent =
                                            'Could not compute age from the given DOB. Please verify.';
                                        dobFeedback.style.display = 'block';
                                    }
                                    dobInput.focus();
                                    return false;
                                }
                            }
                        }
                    });
                }
            })();

            /* jQuery validation */
            (function clientValidation() {
                if (typeof $ === 'undefined' || typeof $.fn.validate === 'undefined') return;

                $.validator.addMethod("alphaSpaces", function(value, element) {
                    return this.optional(element) || /^[A-Za-z\s]+$/.test(value);
                }, "Only letters and spaces are allowed.");
                $.validator.addMethod("phone10", function(value, element) {
                    return this.optional(element) || /^[0-9]{10}$/.test(value);
                }, "Please enter a valid 10 digit mobile number.");
                $.validator.addMethod("notFutureDate", function(value, element) {
                    if (!value) return false;
                    const parts = value.split('-');
                    if (parts.length !== 3) return false;
                    const d = new Date(parts[0], parts[1] - 1, parts[2]);
                    const now = new Date();
                    return d <= new Date(now.getFullYear(), now.getMonth(), now.getDate());
                }, "Date cannot be in the future.");

                $('#bookingForm').validate({
                    rules: {
                        name: {
                            required: true,
                            alphaSpaces: true,
                            maxlength: 255
                        },
                        dob: {
                            required: true,
                            dateISO: true,
                            notFutureDate: true
                        },
                        age: {
                            required: true,
                            digits: true,
                            min: 0,
                            max: 120
                        },
                        phone: {
                            required: true,
                            phone10: true
                        },
                        date: {
                            required: true,
                            dateISO: true
                        },
                        time_slot: {
                            required: true
                        },
                        pincode: {
                            required: true,
                            digits: true,
                            minlength: 6,
                            maxlength: 6
                        },
                        city: {
                            required: true,
                            maxlength: 255
                        },
                        address: {
                            required: true,
                            maxlength: 3000
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter patient name.",
                            maxlength: "Name cannot exceed 255 characters."
                        },
                        dob: {
                            required: "Please provide date of birth.",
                            dateISO: "Please enter a valid date (YYYY-MM-DD)."
                        },
                        age: {
                            required: "Age is required (auto-calculated from DOB).",
                            digits: "Age must be a number."
                        },
                        phone: {
                            required: "Please provide your mobile number."
                        },
                        date: {
                            required: "Please select preferred collection date."
                        },
                        time_slot: {
                            required: "Please select preferred time slot."
                        },
                        pincode: {
                            required: "Please enter PIN code.",
                            minlength: "PIN should be 6 digits.",
                            maxlength: "PIN should be 6 digits."
                        },
                        city: {
                            required: "Please enter city/area."
                        },
                        address: {
                            required: "Please enter full address."
                        }
                    },
                    errorPlacement: function(error, element) {
                        if (element.attr("name") === "time_slot") {
                            error.insertAfter("#slotNotice");
                        } else if (element.attr("name") === "dob") {
                            error.insertAfter("#dobFeedback");
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    submitHandler: function(form) {
                        const dob = $('#dob').val();
                        const age = $('#age').val();
                        if (dob && (age === undefined || age === null || age === '')) {
                            $('#dob').trigger('change');
                            if (!$('#age').val()) {
                                alert('Could not compute age from DOB. Please verify DOB.');
                                return false;
                            }
                        }
                        form.submit();
                    }
                });

                $('#age').on('keydown paste', function(e) {
                    e.preventDefault();
                });
            })();

            /* Success modal flow */
            function runSuccessFlow() {
                try {
                    const serverNode = document.getElementById('serverSuccessData');
                    if (!serverNode) return;

                    const overlay = document.getElementById('successModalOverlay');
                    const counterEl = document.getElementById('redirectCounter');
                    const goHomeBtn = document.getElementById('goHomeBtn');
                    const form = document.getElementById('bookingForm');

                    if (!overlay) return;

                    try {
                        if (form) {
                            form.reset();
                            Array.from(form.querySelectorAll('input, textarea, select')).forEach(el => {
                                if (!el.name) return;
                                if (el.name === '_token') return;
                                try {
                                    if (el.tagName === 'SELECT') {
                                        el.selectedIndex = 0;
                                    } else {
                                        el.value = '';
                                    }
                                } catch (e) {}
                            });
                        }
                    } catch (err) {}

                    overlay.style.display = 'flex';
                    if (counterEl) counterEl.textContent = '5';

                    let countdown = 5;
                    const interval = setInterval(() => {
                        countdown--;
                        if (counterEl) counterEl.textContent = String(countdown);
                        if (countdown <= 0) {
                            clearInterval(interval);
                            window.location.replace("{{ url('/') }}");
                        }
                    }, 1000);

                    if (goHomeBtn) {
                        goHomeBtn.addEventListener('click', function() {
                            try {
                                clearInterval(interval);
                            } catch (e) {}
                        });
                    }
                } catch (ex) {}
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', runSuccessFlow);
            } else {
                setTimeout(runSuccessFlow, 0);
            }
        });

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                setTimeout(function() {
                    window.location.replace(window.location.href);
                }, 50);
            }
        });
    </script>
@endsection
