{{-- resources/views/hospital/appointments/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Appointment')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
$genderIcon = match(strtolower($appointment->gender ?? '')) {
'male' => 'fa-mars',
'female' => 'fa-venus',
'other' => 'fa-genderless',
default => 'fa-user'
};

// Items calculation
$items = [];
if (!empty($appointment->items) && count($appointment->items)) {
foreach ($appointment->items as $it) {
$name = $it->item_name ?? ($it->name ?? ($it->test_name ?? ($it->package_name ?? 'Item')));
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
$fallbackName = $appointment->package_name ??
($appointment->service ?? ($appointment->test->test_name ?? null));
if ($fallbackName) {
$price = (float) ($appointment->unit_price ??
($appointment->final_price ??
($appointment->discounted_price ?? ($appointment->total_price_display ?? 0))));
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
$couponAmount = (float) ($appointment->discount_amount ??
($appointment->coupon_amount ?? ($appointment->coupon_discount ?? 0)));
}
$couponAmount = max(0, min($couponAmount, $itemsFinalTotal));
$couponCode = $appointment->coupon_code ?? ($appointment->applied_coupon_code ?? null);

// Tax & fee
$taxAmount = (float) ($appointment->tax_amount ?? 0);
$collectionFee = (float) ($appointment->collection_charges ?? ($appointment->home_collection_fee ?? 0));
$discountedSubtotal = max(0, $itemsFinalTotal - $couponAmount);
$grandTotal = max(0, $discountedSubtotal + $taxAmount + $collectionFee);
@endphp

<style>
    :root {
        --wc-bg: #ffffff;
        --wc-panel: rgba(255, 255, 255, 0.98);
        --wc-border: #e7eef2;
        --wc-shadow: 0 10px 30px rgba(15, 38, 34, 0.04);
        --wc-text: #0f1724;
        --wc-muted: #5f6b6b;
        --wc-accent: #0f9d80;
        --wc-accent-2: #1fb28a;
        --wc-pill-pending: #f7e6c9;
        --wc-pill-reschedule: #ede7fb;
        --wc-pill-approved: #e6f5ff;
        --wc-pill-completed: #e9f8f2;
        --wc-pill-cancelled: #ffe7e8;
        --rounded: 12px;

        /* New variables for consistency */
        --primary: #0f9d80;
        --primary-light: #e9f8f2;
        --primary-dark: #0c7c65;
        --secondary: #1fb28a;
        --success: #0b7b4f;
        --warning: #f5b800;
        --danger: #b71c1c;
        --info: #0b6b99;
        --purple: #6f4bb7;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
        --border-radius: 12px;
        --border-radius-sm: 8px;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 10px 20px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 15px 30px rgba(0, 0, 0, 0.08);
    }

    body {
        background: linear-gradient(135deg, #f7fbfd 0%, #f0f7ff 100%);
        min-height: 100vh;
    }

    .hospital-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Appointment Header */
    .appointment-header {
        background: white;
        border-radius: var(--border-radius);
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-md);
        border-left: 4px solid var(--primary);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .header-left {
        flex: 1;
        min-width: 300px;
    }

    .header-left h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--wc-text);
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .appointment-id {
        font-size: 0.9rem;
        background: var(--primary-light);
        color: var(--primary-dark);
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
    }

    .header-left .subtitle {
        color: var(--wc-muted);
        font-size: 0.95rem;
        margin: 0;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Status Badge - Chip Style */
    .status-badge {
        border-radius: 999px;
        border: 1px solid;
        background: #fff;
        padding: 8px 16px;
        font-size: 0.86rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: default;
        font-weight: 600;
        white-space: nowrap;
        color: var(--gray-700);
        transition: background .15s ease, border-color .15s ease, color .15s ease, box-shadow .15s ease, transform .1s ease;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-badge:hover {
        transform: translateY(-1px);
    }

    /* CHIP BUTTONS */
    .chip-button {
        border-radius: 999px;
        border: 1px solid var(--wc-border);
        background: #fff;
        padding: 8px 16px;
        font-size: 0.86rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-weight: 500;
        white-space: nowrap;
        color: var(--gray-700);
        transition: background .15s ease, border-color .15s ease, color .15s ease, box-shadow .15s ease, transform .1s ease;
        text-decoration: none;
    }

    .chip-button:hover {
        background: var(--gray-50);
        border-color: var(--gray-400);
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .chip-button:active {
        transform: scale(0.98);
    }

    .chip-button.primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border-color: var(--primary-dark);
    }

    .chip-button.primary:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        box-shadow: 0 4px 8px rgba(15, 157, 128, 0.2);
    }

    .chip-button.success {
        background: linear-gradient(135deg, var(--success), #0a6c47);
        color: white;
        border-color: var(--success);
    }

    .chip-button.success:hover {
        background: linear-gradient(135deg, #0a6c47, var(--success));
        box-shadow: 0 4px 8px rgba(11, 123, 79, 0.2);
    }

    .chip-button.purple {
        background: linear-gradient(135deg, var(--purple), #5d3ea8);
        color: white;
        border-color: var(--purple);
    }

    .chip-button.purple:hover {
        background: linear-gradient(135deg, #5d3ea8, var(--purple));
        box-shadow: 0 4px 8px rgba(111, 75, 183, 0.2);
    }

    .chip-button.outline {
        background: white;
        color: var(--gray-700);
        border: 1px solid var(--wc-border);
    }

    .chip-button.outline:hover {
        background: var(--gray-50);
        border-color: var(--gray-400);
    }

    /* Main Layout */
    .main-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px;
    }

    @media (max-width: 1024px) {
        .main-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Cards */
    .card {
        background: white;
        border-radius: var(--border-radius);
        border: 1px solid var(--wc-border);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card:last-child {
        margin-bottom: 0;
    }

    .card-header {
        padding: 20px;
        border-bottom: 1px solid var(--wc-border);
        background: linear-gradient(90deg, var(--gray-50), white);
    }

    .card-header h3 {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--wc-text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header h3 i {
        color: var(--primary);
    }

    .card-body {
        padding: 20px;
    }

    /* Patient Information Section */
    .patient-info-compact {
        display: grid;
        grid-template-columns: 80px 1fr;
        gap: 20px;
        align-items: start;
    }

    @media (max-width: 640px) {
        .patient-info-compact {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 16px;
        }
    }

    .patient-avatar-compact {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        color: white;
        border: 3px solid white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .patient-details-compact {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    @media (max-width: 768px) {
        .patient-details-compact {
            grid-template-columns: 1fr;
        }
    }

    .patient-detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .patient-detail-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-500);
        font-weight: 600;
    }

    .patient-detail-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .patient-detail-value.highlight {
        color: var(--primary);
    }

    /* Appointment Details Section */
    .appointment-details-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    @media (max-width: 1024px) {
        .appointment-details-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .appointment-details-grid {
            grid-template-columns: 1fr;
        }
    }

    .appointment-detail-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 20px;
        background: var(--gray-50);
        border-radius: var(--border-radius-sm);
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
    }

    .appointment-detail-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        background: white;
    }

    .appointment-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-light), white);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        font-size: 1.2rem;
        color: var(--primary);
        border: 2px solid var(--primary-light);
    }

    .appointment-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-500);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .appointment-value {
        font-size: 1rem;
        font-weight: 700;
        color: var(--gray-800);
    }

    .appointment-value.highlight {
        color: var(--primary);
    }

    /* Contact Info Section */
    .contact-info-section {
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid var(--wc-border);
    }

    .contact-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    @media (max-width: 640px) {
        .contact-info-grid {
            grid-template-columns: 1fr;
        }
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: var(--gray-50);
        border-radius: var(--border-radius-sm);
        border-left: 3px solid var(--primary);
    }

    .contact-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1rem;
        flex-shrink: 0;
    }

    .contact-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .contact-label {
        font-size: 0.75rem;
        color: var(--gray-500);
    }

    .contact-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--gray-800);
    }

    /* Info Grid for other sections */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    @media (max-width: 640px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-500);
        font-weight: 600;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--gray-800);
    }

    .info-value.highlight {
        color: var(--primary);
        font-weight: 700;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    /* Pricing Summary */
    .pricing-summary {
        position: sticky;
        top: 20px;
    }

    .price-card {
        background: white;
        border-radius: var(--border-radius);
        border: 1px solid var(--wc-border);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }

    .price-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 20px;
        text-align: center;
    }

    .price-header h3 {
        margin: 0 0 8px 0;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .price-body {
        padding: 0;
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 20px;
        border-bottom: 1px solid var(--wc-border);
    }

    .price-item:last-child {
        border-bottom: none;
    }

    .price-label {
        font-size: 0.875rem;
        color: var(--gray-600);
    }

    .price-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-800);
    }

    .price-total {
        background: var(--gray-50);
        font-size: 1rem;
    }

    .price-total .price-label {
        font-weight: 700;
        color: var(--gray-900);
    }

    .price-total .price-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--primary);
    }

    /* Chip Styles */
    .chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
        background: white;
        border: 1px solid var(--wc-border);
    }

    .chip.hospital {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary-dark);
    }

    .chip.coupon {
        background: rgba(124, 58, 237, 0.1);
        border-color: rgba(124, 58, 237, 0.3);
        color: #7c3aed;
    }

    /* Payment Badge */
    .payment-badge {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 24px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary), var(--secondary));
    }

    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -28px;
        top: 4px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--primary);
        border: 3px solid white;
        box-shadow: 0 0 0 2px var(--primary);
    }

    .timeline-date {
        font-size: 0.75rem;
        color: var(--gray-500);
        margin-bottom: 4px;
        font-weight: 600;
    }

    .timeline-content {
        background: var(--gray-50);
        padding: 12px;
        border-radius: 8px;
        border-left: 3px solid var(--primary);
    }

    /* Notes Section */
    .notes-section {
        background: linear-gradient(135deg, rgba(245, 184, 0, 0.1), rgba(245, 184, 0, 0.05));
        border-radius: var(--border-radius-sm);
        padding: 16px;
        border-left: 4px solid var(--warning);
    }

    /* Hospital Badges */
    .hospital-badges {
        display: flex;
        gap: 8px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    /* Header Actions */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .appointment-header {
            flex-direction: column;
            align-items: stretch;
            gap: 16px;
        }

        .header-right {
            flex-direction: column;
            align-items: stretch;
        }

        .header-actions {
            flex-direction: column;
            gap: 12px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .chip-button {
            justify-content: center;
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .header-left h1 {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .appointment-id {
            align-self: flex-start;
        }

        .header-actions {
            width: 100%;
        }
    }

    /* Service Items Table */
    .service-items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }

    .service-items-table th {
        background: var(--gray-50);
        padding: 12px 16px;
        text-align: left;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-600);
        font-weight: 600;
        border-bottom: 2px solid var(--wc-border);
    }

    .service-items-table td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--wc-border);
        color: var(--gray-700);
    }

    .service-items-table tr:last-child td {
        border-bottom: none;
    }

    .service-items-table .text-right {
        text-align: right;
    }

    .service-items-table .text-center {
        text-align: center;
    }

    .service-items-table .font-semibold {
        font-weight: 600;
    }
</style>

<div class="hospital-container">
    <!-- Appointment Header -->
    <div class="appointment-header">
        <div class="header-left">
            <h1>
                Appointment Details
            </h1>
            <div class="hospital-badges">
                @if($hospital)
                <div class="chip hospital">
                    <i class="fa-solid fa-hospital"></i>
                    {{ $hospital->name ?? 'Hospital' }}
                </div>
                @endif
                @if(!empty($appointment->branch))
                <div class="chip" style="background: rgba(29, 216, 169, 0.1); border-color: rgba(29, 216, 169, 0.3); color: var(--success);">
                    <i class="fa-solid fa-location-dot"></i>
                    {{ $appointment->branch }}
                </div>
                @endif
            </div>
        </div>

        <div class="header-right">
            <div class="header-actions">
                <!-- Status Badge -->
                <div class="status-badge" style="border-color: {{ $statusColor }}; color: {{ $statusColor }}; background: {{ $statusBgColor }};">
                    <span class="dot" style="background: {{ $statusColor }}; width: 8px; height: 8px; border-radius: 50%;"></span>
                    {{ ucfirst($status) }}
                </div>

                <!-- Back Button -->
                <a href="{{ route('hospital.appointments.index') }}" class="chip-button outline">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="main-layout">
        <!-- Left Column -->
        <div class="left-column">
            <!-- Patient Information -->
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
                                {{ $appointment->time_slot ? \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A') : ($appointment->time_slot ?? '-') }}
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
                            <div class="info-item">
                                <span class="info-label">Payment Type</span>
                                <span class="info-value">
                                    @php
                                    $ptype = strtolower($appointment->payment_method ?? '');
                                    @endphp

                                    @if ($ptype === 'cash')
                                    <span class="payment-badge payment-badge--cash">
                                        💵 Cash on Collection
                                    </span>
                                    @elseif($ptype === 'online')
                                    <span class="payment-badge payment-badge--online">
                                        💳 Online Payment
                                    </span>
                                    @else
                                    <span class="payment-badge payment-badge--pending">
                                        {{ ucfirst($appointment->payment_method ?? 'Pending') }}
                                    </span>
                                    @endif
                                </span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Payment Status</span>
                                <span class="info-value">
                                    @if($latestPayment?->status)
                                    <span class="status-badge status-badge--{{ strtolower($latestPayment->status) }}">
                                        {{ $latestPayment->status }}
                                    </span>
                                    @else
                                    {{ $latestPayment?->payment_method ?? '-' }}
                                    @endif
                                </span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Transaction ID</span>
                                <span class="info-value transaction-id">
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
            </div>
        </div>

        <!-- Right Column - Pricing Summary -->
        <div class="right-column">
            <div class="pricing-summary">
                <div class="price-card">
                    <div class="price-header">
                        <h3>Payment Summary</h3>
                        <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Complete breakdown</p>
                    </div>
                    <div class="price-body">
                        <!-- Service Items -->
                        @if(count($items) > 0)
                        <div class="price-item" style="border-bottom: 2px solid var(--wc-border);">
                            <div class="price-label"><strong>Services</strong></div>
                            <div class="price-value"><strong>Amount</strong></div>
                        </div>

                        @foreach($items as $item)
                        <div class="price-item">
                            <div class="price-label">{{ $item['name'] }}</div>
                            <div class="price-value">₹{{ number_format($item['line_total'], 2) }}</div>
                        </div>
                        @endforeach
                        @endif

                        <div class="price-item">
                            <div class="price-label">Subtotal</div>
                            <div class="price-value">₹{{ number_format($itemsFinalTotal, 2) }}</div>
                        </div>

                        @if($couponAmount > 0)
                        <div class="price-item" style="background: rgba(124, 58, 237, 0.05);">
                            <div class="price-label">
                                <i class="fa-solid fa-ticket-alt" style="color: #7c3aed;"></i>
                                Coupon Discount
                                @if($couponCode)<br><small>{{ $couponCode }}</small>@endif
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

                        @if($taxAmount > 0)
                        <div class="price-item">
                            <div class="price-label">Tax</div>
                            <div class="price-value">₹{{ number_format($taxAmount, 2) }}</div>
                        </div>
                        @endif

                        @if($collectionFee > 0)
                        <div class="price-item">
                            <div class="price-label">Service Fee</div>
                            <div class="price-value">₹{{ number_format($collectionFee, 2) }}</div>
                        </div>
                        @endif

                        <div class="price-item price-total">
                            <div class="price-label">Grand Total</div>
                            <div class="price-value">₹{{ number_format($grandTotal, 2) }}</div>
                        </div>

                        <!-- Payment Status -->
                        @if($latestPayment)
                        <div class="price-item" style="background: var(--gray-50); border-top: 1px solid var(--wc-border);">
                            <div class="price-label">Payment Status</div>
                            <div class="price-value">
                                @php
                                $paymentStatus = $latestPayment->status ?? 'pending';
                                $paymentColor = match(strtolower($paymentStatus)) {
                                'paid', 'completed' => 'green',
                                'failed', 'cancelled' => 'red',
                                'pending' => 'orange',
                                default => 'gray'
                                };
                                @endphp
                                <span style="color: {{ $paymentColor }}; font-weight: 600;">
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
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
                                    {{ $appointment->created_at ? $appointment->created_at->format('M d, Y h:i A') : 'N/A' }}
                                </div>
                            </div>
                            @if($appointment->date)
                            <div class="timeline-item">
                                <div class="timeline-date">Scheduled Date</div>
                                <div class="timeline-content">
                                    {{ \Carbon\Carbon::parse($appointment->date)->format('M d, Y') }}
                                    @if($appointment->time_slot)
                                    at {{ \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A') }}
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if($latestPayment)
                            <div class="timeline-item">
                                <div class="timeline-date">Payment Processed</div>
                                <div class="timeline-content">
                                    {{ $latestPayment->created_at->format('M d, Y h:i A') }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>

@endsection