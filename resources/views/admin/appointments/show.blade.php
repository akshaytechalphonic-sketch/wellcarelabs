{{-- resources/views/admin/appointments/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- External CSS --}}
    <link href="{{ asset('Back_end/css/admin/appointment-show.css') }}" rel="stylesheet">

    @php
        $status = strtolower($appointment->status ?? 'pending');
        $statusColor = match ($status) {
            'approved' => '#0b6b99',
            'completed' => '#0b7b4f',
            'cancelled' => '#b71c1c',
            'reschedule' => '#6f4bb7',
            default => '#f5b800',
        };

        $statusBgColor = match ($status) {
            'approved' => 'rgba(11, 107, 153, 0.1)',
            'completed' => 'rgba(11, 123, 79, 0.1)',
            'cancelled' => 'rgba(183, 28, 28, 0.1)',
            'reschedule' => 'rgba(111, 75, 183, 0.1)',
            default => 'rgba(245, 184, 0, 0.1)',
        };

        $hospital = $appointment->hospital ?? null;
        $report = $appointment->report ?? null;
        $reportViewUrl = $report ? route('admin.reports.view', $report->id) : null;
        $reportDownloadUrl = $report ? route('admin.reports.download', $report->id) : null;
        $latestPayment = $appointment->payments()->latest('id')->first();

        // Calculate age
        $age = $appointment->age;
        if (is_null($age) && !empty($appointment->dob)) {
            try {
                $age = \Carbon\Carbon::parse($appointment->dob)->age;
            } catch (\Throwable $e) {
                $age = null;
            }
        }

        // Gender icon
        $genderIcon = match (strtolower($appointment->gender ?? '')) {
            'male' => 'fa-mars',
            'female' => 'fa-venus',
            'other' => 'fa-genderless',
            default => 'fa-user',
        };

        // Service details
        $serviceType =
            $appointment->package_name ?? ($appointment->service ?? ($appointment->test->test_name ?? 'General'));

        // Payment details
        $paymentMethod = strtolower($appointment->payment_method ?? '');
    @endphp

    <div class="admin-container">
        <!-- IMPROVED: Appointment Header with Chip Buttons -->
        <div class="appointment-header">
            <div class="header-left">
                <h1>
                    Appointment Information
                </h1>
                @if ($hospital)
                    <div class="hospital-badges">
                        <div class="chip hospital">
                            <i class="fa-solid fa-hospital"></i>
                            {{ $hospital->name ?? 'Hospital' }}
                        </div>
                        @if (!empty($hospital->branch))
                            <div class="chip"
                                style="background: rgba(29, 216, 169, 0.1); border-color: rgba(29, 216, 169, 0.3); color: var(--success);">
                                <i class="fa-solid fa-location-dot"></i>
                                {{ $hospital->branch }}
                            </div>
                        @endif
                    </div>
                @elseif(!empty($appointment->hospital_id))
                    <div class="hospital-badges">
                        <div class="chip hospital">
                            <i class="fa-solid fa-hospital"></i>
                            Hospital #{{ $appointment->hospital_id }}
                        </div>
                    </div>
                @endif
            </div>

            <div class="header-actions" style="display:flex; gap:10px; align-items:center;">
                <!-- Status Badge -->
                <div class="status-badge"
                    style="border-color: {{ $statusColor }}; color: {{ $statusColor }}; background: {{ $statusBgColor }};">
                    <span class="dot"
                        style="background: {{ $statusColor }}; width: 8px; height: 8px; border-radius: 50%;"></span>
                    {{ ucfirst($status) }}
                </div>

                {{-- Edit Button --}}
                @if ($status !== 'completed')
                    <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="chip-button warning"
                        title="Edit Appointment">
                        <i class="fa-solid fa-pen"></i>
                        Edit
                    </a>
                @else
                    <span class="chip-button disabled" title="Completed appointments cannot be edited">
                        <i class="fa-solid fa-lock"></i>
                        Locked
                    </span>
                @endif

                <!-- Back Button -->
                <a href="{{ route('admin.appointments.index') }}" class="chip-button outline">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
            </div>

        </div>

        <!-- Main Layout -->
        <div class="main-layout">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Patient Information - COMPACT VERSION -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fa-solid fa-user-circle"></i> Patient Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="patient-info-compact">
                            <div class="patient-avatar-compact">
                                {{ substr($appointment->name ?? 'P', 0, 1) }}
                            </div>
                            <div class="patient-details-compact">
                                <div class="patient-detail-item">
                                    <span class="patient-detail-label">Full Name</span>
                                    <span class="patient-detail-value highlight">{{ $appointment->name ?? '-' }}</span>
                                </div>
                                <div class="patient-detail-item">
                                    <span class="patient-detail-label">Gender</span>
                                    <span class="patient-detail-value">
                                        <i class="fa-solid {{ $genderIcon }}"></i>
                                        {{ ucfirst($appointment->gender ?? 'Not specified') }}
                                    </span>
                                </div>
                                <div class="patient-detail-item">
                                    <span class="patient-detail-label">Date of Birth</span>
                                    <span class="patient-detail-value">
                                        @if (!empty($appointment->dob))
                                            {{ \Carbon\Carbon::parse($appointment->dob)->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="patient-detail-item">
                                    <span class="patient-detail-label">Age</span>
                                    <span class="patient-detail-value">{{ $age !== null ? $age . ' years' : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="contact-info-section">
                            <div class="contact-info-grid">
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fa-solid fa-phone"></i>
                                    </div>
                                    <div class="contact-text">
                                        <span class="contact-label">Phone Number</span>
                                        <span class="contact-value">{{ $appointment->phone ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div class="contact-text">
                                        <span class="contact-label">Email Address</span>
                                        <span class="contact-value">{{ $appointment->email ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointment Details -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fa-solid fa-calendar-check"></i> Appointment Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="appointment-details-grid">
                            <div class="appointment-detail-card">
                                <div class="appointment-icon">
                                    <i class="fa-solid fa-calendar-day"></i>
                                </div>
                                <span class="appointment-label">Date</span>
                                <span class="appointment-value highlight">
                                    {{ $appointment->date ? \Carbon\Carbon::parse($appointment->date)->format('d M Y') : '-' }}
                                </span>
                            </div>

                            <div class="appointment-detail-card">
                                <div class="appointment-icon">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <span class="appointment-label">Time Slot</span>
                                <span class="appointment-value highlight">
                                    {{ $appointment->time_slot ? \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A') : '-' }}
                                </span>
                            </div>

                            <div class="appointment-detail-card">
                                <div class="appointment-icon">
                                    <i class="fa-solid fa-flask"></i>
                                </div>
                                <span class="appointment-label">Service Type</span>
                                <span class="appointment-value">
                                    {{ $appointment->package_name ?? ($appointment->service ?? ($appointment->test->test_name ?? 'General')) }}
                                </span>
                            </div>

                            <div class="appointment-detail-card">
                                <div class="appointment-icon">
                                    <i class="fa-solid fa-hospital"></i>
                                </div>
                                <span class="appointment-label">Hospital</span>
                                <span class="appointment-value">
                                    {{ $hospital ? $hospital->name : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fa-solid fa-location-dot"></i> Address Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Address</span>
                                <span class="info-value">{{ $appointment->address ?? '-' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">City</span>
                                <span class="info-value">{{ $appointment->city ?? '-' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Pincode</span>
                                <span class="info-value">{{ $appointment->pincode ?? '-' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Landmark</span>
                                <span class="info-value">{{ $appointment->landmark ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fa-solid fa-credit-card"></i> Payment Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Payment Type</span>
                                <span class="info-value">
                                    @php
                                        $ptype = strtolower($appointment->payment_method ?? '');
                                    @endphp
                                    @if ($ptype === 'cash')
                                        <span class="payment-badge"
                                            style="background:#ecfdf5;color:#166534;border:1px solid #86efac;">
                                            💵 Cash on Collection
                                        </span>
                                    @elseif($ptype === 'online')
                                        <span class="payment-badge"
                                            style="background:#eff6ff;color:#1d4ed8;border:1px solid #93c5fd;">
                                            💳 Online Payment
                                        </span>
                                    @else
                                        <span class="payment-badge"
                                            style="background:#fff7ed;color:#9a3412;border:1px solid #fed7aa;">
                                            {{ ucfirst($appointment->payment_method ?? 'Pending') }}
                                        </span>
                                    @endif
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Payment Status</span>
                                <span class="info-value">
                                    {{ $latestPayment?->status ?? ($latestPayment?->payment_method ?? '-') }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Transaction ID</span>
                                <span class="info-value">
                                    {{ $latestPayment?->transaction_id ?? ($latestPayment?->txnid ?? '-') }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Total Amount</span>
                                <span class="info-value highlight">
                                    @if (!is_null($appointment->total_price_display))
                                        ₹{{ number_format($appointment->total_price_display, 2) }}
                                    @else
                                        —
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Actions -->
                @if ($report)
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fa-solid fa-file-medical"></i> Report Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="action-buttons">
                                <a href="{{ $reportViewUrl }}" target="_blank" class="chip-button primary"
                                    title="View report">
                                    <i class="fa-regular fa-eye"></i>
                                    View Report
                                </a>
                                <a href="{{ $reportDownloadUrl }}" class="chip-button success" title="Download report">
                                    <i class="fa-solid fa-download"></i>
                                    Download Report
                                </a>
                                <button type="button" class="chip-button purple share-report-btn" title="Share report"
                                    data-share-url="{{ $reportDownloadUrl }}"
                                    data-share-action="{{ route('appointments.share-whatsapp', $appointment->id) }}"
                                    data-share-message="Wellcare Labs - Report for {{ $appointment->name }} ({{ $appointment->id }})"
                                    data-share-email="{{ $appointment->email ?? '' }}"
                                    data-share-phone="{{ $appointment->phone ?? '' }}"
                                    data-report-id="{{ $report->id }}">
                                    <i class="fa-solid fa-share-from-square"></i>
                                    Share Report
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Additional Notes -->
                @if ($appointment->message)
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fa-solid fa-note-sticky"></i> Additional Notes</h3>
                        </div>
                        <div class="card-body">
                            <div class="notes-section">
                                <p style="margin: 0; color: var(--gray-800); line-height: 1.6;">
                                    {{ $appointment->message }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Clean Layout -->
            <div class="right-column">
                <div class="pricing-summary">
                    <!-- Payment Summary -->
                    <div class="price-card">
                        <div class="price-header">
                            <h3>Payment Summary</h3>
                            <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Complete breakdown</p>
                        </div>
                        <div class="price-body">
                            <div class="price-item" style="border-bottom: 2px solid var(--wc-border);">
                                <div class="price-label"><strong>Services</strong></div>
                                <div class="price-value"><strong>Amount</strong></div>
                            </div>
                            @php
                                // Items calculation
                                $items = [];
                                if (!empty($appointment->items) && count($appointment->items)) {
                                    foreach ($appointment->items as $it) {
                                        $name =
                                            $it->item_name ??
                                            ($it->name ?? ($it->test_name ?? ($it->package_name ?? 'Item')));
                                        $price = (float) ($it->item_price ?? 0);
                                        $qty = max(1, (int) ($it->quantity ?? 1));
                                        $items[] = [
                                            'name' => $name,
                                            'unit_price' => $price,
                                            'qty' => $qty,
                                            'line_total' => $price * $qty,
                                        ];
                                    }
                                } else {
                                    $fallbackName =
                                        $appointment->package_name ??
                                        ($appointment->service ?? ($appointment->test->test_name ?? null));
                                    if ($fallbackName) {
                                        $price =
                                            (float) ($appointment->unit_price ??
                                                ($appointment->final_price ??
                                                    ($appointment->discounted_price ??
                                                        ($appointment->total_price_display ?? 0))));
                                        $items[] = [
                                            'name' => $fallbackName,
                                            'unit_price' => $price,
                                            'qty' => 1,
                                            'line_total' => $price,
                                        ];
                                    }
                                }

                                $itemsFinalTotal = 0.0;
                                foreach ($items as $row) {
                                    $itemsFinalTotal += (float) $row['line_total'];
                                }

                                // Coupon logic
                                $discountType = strtolower((string) ($appointment->discount_type ?? ''));
                                $discountValue = (float) ($appointment->discount_value ?? 0);
                                if ($discountType === 'percent' && $discountValue > 0) {
                                    $couponAmount = round(($itemsFinalTotal * $discountValue) / 100, 2);
                                } elseif ($discountType === 'fixed' && $discountValue > 0) {
                                    $couponAmount = round($discountValue, 2);
                                } else {
                                    $couponAmount =
                                        (float) ($appointment->discount_amount ??
                                            ($appointment->coupon_amount ?? ($appointment->coupon_discount ?? 0)));
                                }
                                $couponAmount = max(0, min($couponAmount, $itemsFinalTotal));
                                $couponCode = $appointment->coupon_code ?? ($appointment->applied_coupon_code ?? null);

                                // Tax & fee
                                $taxAmount = (float) ($appointment->tax_amount ?? 0);
                                $collectionFee =
                                    (float) ($appointment->collection_charges ??
                                        ($appointment->home_collection_fee ?? 0));
                                $discountedSubtotal = max(0, $itemsFinalTotal - $couponAmount);
                                $grandTotal = max(0, $discountedSubtotal + $taxAmount + $collectionFee);
                            @endphp

                            @foreach ($items as $item)
                                <div class="price-item">
                                    <div class="price-label">{{ $item['name'] }}</div>
                                    <div class="price-value">₹{{ number_format($item['line_total'], 2) }}</div>
                                </div>
                            @endforeach

                            <div class="price-item">
                                <div class="price-label">Subtotal</div>
                                <div class="price-value">₹{{ number_format($itemsFinalTotal, 2) }}</div>
                            </div>

                            @if ($couponAmount > 0)
                                <div class="price-item" style="background: rgba(124, 58, 237, 0.05);">
                                    <div class="price-label">
                                        <i class="fa-solid fa-ticket-alt" style="color: #7c3aed;"></i>
                                        Coupon Discount
                                        @if ($couponCode)
                                            <br><small>{{ $couponCode }}</small>
                                        @endif
                                    </div>
                                    <div class="price-value" style="color: #7c3aed; font-weight: 700;">
                                        -₹{{ number_format($couponAmount, 2) }}
                                    </div>
                                </div>

                                <div class="price-item" style="background: rgba(124, 58, 237, 0.02);">
                                    <div class="price-label">Discounted Subtotal</div>
                                    <div class="price-value">₹{{ number_format($discountedSubtotal, 2) }}</div>
                                </div>
                            @endif

                            <div class="price-item price-total">
                                <div class="price-label">Grand Total</div>
                                <div class="price-value">
                                    @if (!is_null($appointment->total_price_display))
                                        ₹{{ number_format($appointment->total_price_display, 2) }}
                                    @else
                                        ₹{{ number_format($grandTotal, 2) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($latestPayment)
                            <div class="price-item"
                                style="background: var(--gray-50); border-top: 1px solid var(--wc-border);">
                                <div class="price-label">Payment Status</div>
                                <div class="price-value">
                                    @php
                                        $paymentStatus = $latestPayment->status ?? 'pending';
                                        $paymentColor = match (strtolower($paymentStatus)) {
                                            'paid', 'completed' => 'green',
                                            'failed', 'cancelled' => 'red',
                                            'pending' => 'orange',
                                            default => 'gray',
                                        };
                                    @endphp
                                    <span style="color: {{ $paymentColor }}; font-weight: 600;">
                                        {{ ucfirst($paymentStatus) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Timeline -->
                    <div class="card" style="margin-top: 24px;">
                        <div class="card-header">
                            <h3><i class="fa-solid fa-history"></i> Timeline</h3>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-date">Appointment Booked</div>
                                    <div class="timeline-content">
                                        @if ($appointment->created_at)
                                            {{ $appointment->created_at->format('M d, Y h:i A') }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>

                                @if ($appointment->date)
                                    <div class="timeline-item">
                                        <div class="timeline-date">Scheduled Date</div>
                                        <div class="timeline-content">
                                            @php
                                                try {
                                                    $date = \Carbon\Carbon::parse($appointment->date);
                                                    $timeSlot = $appointment->time_slot
                                                        ? \Carbon\Carbon::parse($appointment->time_slot)
                                                        : null;
                                                } catch (\Exception $e) {
                                                    $date = null;
                                                    $timeSlot = null;
                                                }
                                            @endphp

                                            @if ($date)
                                                {{ $date->format('M d, Y') }}
                                                @if ($timeSlot)
                                                    at {{ $timeSlot->format('h:i A') }}
                                                @endif
                                            @else
                                                Date not available
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if ($latestPayment && $latestPayment->created_at)
                                    <div class="timeline-item">
                                        <div class="timeline-date">Payment Processed</div>
                                        <div class="timeline-content">
                                            {{ $latestPayment->created_at->format('M d, Y h:i A') }}
                                            <strong>{{ $latestPayment?->transaction_id ?? ($latestPayment?->txnid ?? '-') }}</strong>
                                        </div>
                                    </div>
                                @endif

                                @if ($report && $report->created_at)
                                    <div class="timeline-item">
                                        <div class="timeline-date">Report Generated</div>
                                        <div class="timeline-content">
                                            {{ $report->created_at->format('M d, Y h:i A') }}
                                        </div>
                                    </div>
                                @endif

                                <!-- Add this section to show payment status when no payment record exists -->
                                @if (!$latestPayment && $appointment->payment_method)
                                    <div class="timeline-item">
                                        <div class="timeline-date">Payment Status</div>
                                        <div class="timeline-content">
                                            Payment marked as:
                                            <strong>{{ ucfirst($appointment->payment_method) }}</strong>
                                            @if ($appointment->payment_method === 'online' && !$latestPayment)
                                                <br><small class="text-warning">No payment record found in
                                                    database</small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hospital Information - FIXED ALIGNMENT -->
                @if ($hospital)
                    <div class="card" style="margin-top: 24px;">
                        <div class="card-header">
                            <h3><i class="fa-solid fa-hospital"></i> Hospital Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="hospital-info-card">
                                <div class="hospital-header">
                                    <div class="hospital-icon">
                                        <i class="fa-solid fa-hospital"></i>
                                    </div>
                                    <div class="hospital-title-wrapper">
                                        <h3 class="hospital-title">{{ $hospital->name ?? 'Hospital' }}</h3>
                                        @if (!empty($hospital->branch))
                                            <p class="hospital-subtitle">{{ $hospital->branch }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="hospital-details-grid">
                                    @if (!empty($hospital->address))
                                        <div class="hospital-detail-item">
                                            <span class="hospital-detail-label">
                                                <i class="fa-solid fa-location-dot"></i>
                                                Address
                                            </span>
                                            <span class="hospital-detail-value">{{ $hospital->address }}</span>
                                        </div>
                                    @endif

                                    @if (!empty($hospital->city))
                                        <div class="hospital-detail-item">
                                            <span class="hospital-detail-label">
                                                <i class="fa-solid fa-city"></i>
                                                City
                                            </span>
                                            <span class="hospital-detail-value">{{ $hospital->city }}</span>
                                        </div>
                                    @endif

                                    @if (!empty($hospital->pincode))
                                        <div class="hospital-detail-item">
                                            <span class="hospital-detail-label">
                                                <i class="fa-solid fa-map-pin"></i>
                                                Pincode
                                            </span>
                                            <span class="hospital-detail-value">{{ $hospital->pincode }}</span>
                                        </div>
                                    @endif

                                    @if (!empty($hospital->phone))
                                        <div class="hospital-detail-item">
                                            <span class="hospital-detail-label">
                                                <i class="fa-solid fa-phone"></i>
                                                Phone
                                            </span>
                                            <span class="hospital-detail-value">
                                                <a href="tel:{{ $hospital->phone }}">{{ $hospital->phone }}</a>
                                            </span>
                                        </div>
                                    @endif

                                    @if (!empty($hospital->email))
                                        <div class="hospital-detail-item">
                                            <span class="hospital-detail-label">
                                                <i class="fa-solid fa-envelope"></i>
                                                Email
                                            </span>
                                            <span class="hospital-detail-value">
                                                <a href="mailto:{{ $hospital->email }}">{{ $hospital->email }}</a>
                                            </span>
                                        </div>
                                    @endif

                                    @if (!empty($hospital->contact_person))
                                        <div class="hospital-detail-item">
                                            <span class="hospital-detail-label">
                                                <i class="fa-solid fa-user-tie"></i>
                                                Contact Person
                                            </span>
                                            <span class="hospital-detail-value">{{ $hospital->contact_person }}</span>
                                        </div>
                                    @endif

                                    @if (!empty($hospital->contact_person_phone))
                                        <div class="hospital-detail-item">
                                            <span class="hospital-detail-label">
                                                <i class="fa-solid fa-mobile-alt"></i>
                                                Contact Phone
                                            </span>
                                            <span class="hospital-detail-value">
                                                <a
                                                    href="tel:{{ $hospital->contact_person_phone }}">{{ $hospital->contact_person_phone }}</a>
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                @if (!empty($hospital->specialization) || !empty($hospital->description))
                                    <div class="hospital-description">
                                        @if (!empty($hospital->specialization))
                                            <div class="hospital-description-label">
                                                <i class="fa-solid fa-stethoscope"></i>
                                                Specializations
                                            </div>
                                            <div class="specializations">
                                                @php
                                                    $specializations = is_array($hospital->specialization)
                                                        ? $hospital->specialization
                                                        : array_filter(
                                                            array_map('trim', explode(',', $hospital->specialization)),
                                                        );
                                                @endphp
                                                @foreach ($specializations as $spec)
                                                    @if (!empty($spec))
                                                        <span class="specialization-badge">{{ $spec }}</span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (!empty($hospital->description))
                                            <div style="margin-top: 16px;">
                                                <div class="hospital-description-label">
                                                    <i class="fa-solid fa-file-alt"></i>
                                                    Description
                                                </div>
                                                <p class="hospital-description-text">{{ $hospital->description }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ✅ Shared Share Modal Partial --}}
    @include('partials.share-modal')

    {{-- External JS --}}
    <script src="{{ asset('Back_end/js/admin/appointment-show.js') }}"></script>
@endsection
