@extends('layouts.app')

@section('title', 'Create Appointment')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous">

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-light: #dbeafe;
            --success-color: #10b981;
            --info-color: #0ea5e9;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --border-color: #e2e8f0;
            --text-muted: #64748b;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            --hover-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);

            /* Test specific colors */
            --test-primary: #0ea5e9;
            --test-light: #f0f9ff;
            --test-dark: #0369a1;
            --test-gradient: linear-gradient(135deg, #0ea5e9, #0284c7);

            /* Package specific colors */
            --package-primary: #10b981;
            --package-light: #f0fdf4;
            --package-dark: #059669;
            --package-gradient: linear-gradient(135deg, #10b981, #059669);
        }

        body {
            background: linear-gradient(135deg, #f0f9ff 0%, #fdf2f8 100%);
            min-height: 100vh;
        }

        /* Header Styles */
        .page-header {
            margin-top: 16px;
            /* 👈 ADD THIS */
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .header-text h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0 0 0.25rem 0;
            line-height: 1.3;
        }

        .header-text p {
            color: var(--text-muted);
            margin: 0;
            font-size: 0.95rem;
        }

        /* Main Container */
        .main-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Two Column Layout */
        .two-column-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 1200px) {
            .two-column-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Enhanced Card Styles */
        .enhanced-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .enhanced-card:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-2px);
        }

        .enhanced-card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(to right, #f8fafc, white);
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .section-number {
            width: 32px;
            height: 32px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .section-number.success {
            background: var(--success-color);
        }

        .section-number.warning {
            background: var(--warning-color);
        }

        .section-number.info {
            background: var(--info-color);
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .section-subtitle {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin: 0;
        }

        .enhanced-card-body {
            padding: 1.5rem;
        }

        /* Form Enhancements */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-label i {
            color: var(--primary-color);
            font-size: 0.9rem;
            width: 16px;
        }

        .required-star {
            color: var(--danger-color);
            margin-left: 2px;
        }

        .input-group-enhanced {
            position: relative;
        }

        .input-group-enhanced .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            z-index: 2;
        }

        .input-group-enhanced .form-control {
            padding-left: 3rem;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            height: 46px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .input-group-enhanced .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .input-group-enhanced textarea.form-control {
            min-height: 100px;
            padding-top: 0.75rem;
            resize: vertical;
        }

        /* Right Column - Summary Card */
        .summary-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            position: sticky;
            top: 6rem;
        }

        .summary-header {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            color: white;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .summary-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .items-count {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .summary-body {
            padding: 1.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-icon {
            font-size: 3.5rem;
            color: #e2e8f0;
            margin-bottom: 1rem;
        }

        .empty-text h5 {
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .empty-text p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1rem;
            border-radius: 10px;
            font-weight: 500;
            border: none;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-action.btn-test {
            background: var(--test-gradient);
            color: white;
        }

        .btn-action.btn-package {
            background: var(--package-gradient);
            color: white;
        }

        .btn-action.btn-clear {
            background: white;
            color: var(--danger-color);
            border: 1px solid var(--danger-color);
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-action.btn-test:hover {
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }

        .btn-action.btn-package:hover {
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Selected Items List */
        .items-list {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 1.5rem;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            transition: background 0.2s ease;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-row:hover {
            background: var(--light-color);
        }

        .item-info {
            flex: 1;
        }

        .item-type {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .item-type.test {
            background: rgba(14, 165, 233, 0.1);
            color: var(--test-primary);
            border: 1px solid rgba(14, 165, 233, 0.2);
        }

        .item-type.package {
            background: rgba(16, 185, 129, 0.1);
            color: var(--package-primary);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .item-name {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .item-category {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .item-price {
            text-align: right;
            margin-left: 1rem;
        }

        .price-amount {
            font-weight: 600;
            color: var(--dark-color);
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .remove-btn {
            background: none;
            border: none;
            color: var(--danger-color);
            cursor: pointer;
            padding: 0.25rem;
            font-size: 0.9rem;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        }

        .remove-btn:hover {
            opacity: 1;
        }

        /* Summary Footer */
        .summary-footer {
            border-top: 1px solid var(--border-color);
            padding-top: 1.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .summary-label {
            color: var(--text-muted);
        }

        .summary-value {
            font-weight: 500;
            color: var(--dark-color);
        }

        .summary-total {
            border-top: 2px solid var(--border-color);
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .total-label {
            font-weight: 600;
            font-size: 1rem;
        }

        .total-value {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        /* Modal Enhancements */
        .modal-glass {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-radius: 20px;
            overflow: hidden;
        }

        .modal-header-glass {
            color: white;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-title-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .modal-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* List View Styles */
        .list-view-container {
            height: 500px;
            display: flex;
            flex-direction: column;
        }

        .list-search-section {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: white;
        }

        .list-items-container {
            flex: 1;
            overflow-y: auto;
            padding: 0;
        }

        .list-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .list-item:hover {
            background: var(--light-color);
        }

        .list-item.selected {
            background: rgba(14, 165, 233, 0.05);
            border-left: 4px solid var(--test-primary);
        }

        .list-item.selected.package {
            background: rgba(16, 185, 129, 0.05);
            border-left: 4px solid var(--package-primary);
        }

        .list-item-checkbox {
            margin-right: 1rem;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .list-item-content {
            flex: 1;
            min-width: 0;
        }

        .list-item-title {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .list-item-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-weight: 500;
        }

        .badge-test {
            background: rgba(14, 165, 233, 0.1);
            color: var(--test-primary);
        }

        .badge-package {
            background: rgba(16, 185, 129, 0.1);
            color: var(--package-primary);
        }

        .list-item-description {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .list-item-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .list-item-code {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .list-item-category {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .list-item-price {
            text-align: right;
            margin-left: 1rem;
            min-width: 120px;
        }

        .list-item-price-amount {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .list-item-price-type {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .price-test {
            color: var(--test-primary);
        }

        .price-package {
            color: var(--package-primary);
        }

        .discounted-price {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .original-price {
            text-decoration: line-through;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .discount-badge {
            background: var(--package-primary);
            color: white;
            padding: 0.15rem 0.5rem;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 500;
        }

        .empty-list {
            padding: 3rem 1rem;
            text-align: center;
            color: var(--text-muted);
        }

        .empty-list-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
        }

        .toast-custom {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 768px) {

            .header-content,
            .main-container {
                padding: 0 1rem;
            }

            .header-row {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }

            .header-left {
                justify-content: center;
                flex-direction: column;
                text-align: center;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .two-column-layout {
                gap: 1rem;
            }

            .list-item {
                flex-direction: column;
                align-items: stretch;
            }

            .list-item-price {
                text-align: left;
                margin-left: 0;
                margin-top: 0.5rem;
            }
        }
    </style>

    {{-- Header Section --}}
    <header class="page-header">
        <div class="header-content">
            <div class="header-row">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="header-text">
                        <h1>Create New Appointment</h1>
                        <p>Schedule diagnostic sample collection</p>
                    </div>
                </div>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="main-container">
        <form method="POST" action="{{ route('admin.appointments.store') }}" id="appointmentForm" novalidate>
            @csrf
            <input type="hidden" name="payment_method" value="cash">

            <div class="two-column-layout">
                {{-- Left Column: Forms --}}
                <div>
                    {{-- Patient Information --}}
                    <div class="enhanced-card">
                        <div class="enhanced-card-header">
                            <div class="section-heading">
                                <div class="section-number">1</div>
                                <div>
                                    <h3 class="section-title">Patient Information</h3>
                                    <p class="section-subtitle">Enter patient's personal details</p>
                                </div>
                            </div>
                        </div>
                        <div class="enhanced-card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-user"></i>Full Name <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group-enhanced">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name') }}" placeholder="Enter full name">
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-phone"></i>Phone Number <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group-enhanced">
                                        <i class="fas fa-phone input-icon"></i>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ old('phone') }}" placeholder="9876543210" required>
                                    </div>
                                    @error('phone')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-envelope"></i>Email Address
                                    </label>
                                    <div class="input-group-enhanced">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email') }}" placeholder="patient@email.com">
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-birthday-cake"></i>Date of Birth
                                    </label>
                                    <div class="input-group-enhanced">
                                        <i class="fas fa-calendar input-icon"></i>
                                        <input type="date" name="dob" class="form-control"
                                            value="{{ old('dob') }}" max="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <input type="hidden" name="age" id="age">


                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-venus-mars"></i>Gender
                                    </label>
                                    <div class="input-group-enhanced">
                                        <i class="fas fa-venus-mars input-icon"></i>
                                        <select name="gender" class="form-control">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                            </option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Appointment Details --}}
                    <div class="enhanced-card">
                        <div class="enhanced-card-header">
                            <div class="section-heading">
                                <div class="section-number success">2</div>
                                <div>
                                    <h3 class="section-title">Appointment Details</h3>
                                    <p class="section-subtitle">Schedule collection date, time and location</p>
                                </div>
                            </div>
                        </div>
                        <div class="enhanced-card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-calendar-day"></i>Collection Date <span
                                            class="required-star">*</span>
                                    </label>
                                    <div class="input-group-enhanced">
                                        <i class="fas fa-calendar-alt input-icon"></i>
                                        <input type="date" name="date" id="date" class="form-control"
                                            value="{{ old('date') }}" min="{{ date('Y-m-d') }}" required>
                                    </div>
                                    @error('date')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-clock"></i>Time Slot <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group-enhanced">
                                        <i class="fas fa-clock input-icon"></i>
                                        <select name="time_slot" id="time_slot" class="form-control" required>
                                            <option value="">Select date first</option>
                                        </select>
                                    </div>
                                    @error('time_slot')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Address Details --}}
                    <div class="enhanced-card">
                        <div class="enhanced-card-header">
                            <div class="section-heading">
                                <div class="section-number warning">3</div>
                                <div>
                                    <h3 class="section-title">Address Details</h3>
                                    <p class="section-subtitle">Collection address information</p>
                                </div>
                            </div>
                        </div>
                        <div class="enhanced-card-body">
                            <div class="form-group mb-4">
                                <label class="form-label">
                                    <i class="fas fa-home"></i>Complete Address <span class="required-star">*</span>
                                </label>
                                <div class="input-group-enhanced">
                                    <i class="fas fa-home input-icon"></i>
                                    <textarea name="address" class="form-control" rows="3" placeholder="House no, Building, Street, Area"
                                        required>{{ old('address') }}</textarea>
                                </div>
                                @error('address')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-city"></i>City <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group-enhanced">
                                        <i class="fas fa-city input-icon"></i>
                                        <input type="text" name="city" class="form-control" placeholder="City"
                                            value="{{ old('city') }}" required>
                                    </div>
                                    @error('city')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-map-pin"></i>Pincode <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group-enhanced">
                                        <i class="fas fa-map-pin input-icon"></i>
                                        <input type="text" name="pincode" class="form-control" placeholder="Pincode"
                                            value="{{ old('pincode') }}" required>
                                    </div>
                                    @error('pincode')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" style="grid-column: span 2;">
                                    <label class="form-label">
                                        <i class="fas fa-location-dot"></i>Landmark (Optional)
                                    </label>
                                    <div class="input-group-enhanced">
                                        <i class="fas fa-location-dot input-icon"></i>
                                        <input type="text" name="landmark" class="form-control"
                                            placeholder="Nearby landmark" value="{{ old('landmark') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Summary --}}
                <div>
                    <div class="summary-card">
                        <div class="summary-header">
                            <h3 class="summary-title">Selected Items</h3>
                            <span class="items-count" id="itemCount">0 Items</span>
                        </div>

                        <div class="summary-body">
                            {{-- Empty State --}}
                            <div id="emptyState" class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-flask-vial"></i>
                                </div>
                                <div class="empty-text">
                                    <h5>No Items Selected</h5>
                                    <p>Add tests or packages to proceed</p>
                                </div>
                                <div class="action-buttons">
                                    <button type="button" class="btn-action btn-test" data-bs-toggle="modal"
                                        data-bs-target="#addTestModal">
                                        <i class="fas fa-search me-2"></i>Browse Tests
                                    </button>
                                    <button type="button" class="btn-action btn-package" data-bs-toggle="modal"
                                        data-bs-target="#addPackageModal">
                                        <i class="fas fa-box me-2"></i>Browse Packages
                                    </button>
                                </div>
                            </div>

                            {{-- Selected Items List --}}
                            <div id="selectedItemsList" style="display: none;">
                                <div class="items-list" id="selectedItemsContainer">
                                    <!-- Items will be inserted here -->
                                </div>

                                {{-- Summary --}}
                                <div class="summary-footer">
                                    <div class="summary-row">
                                        <span class="summary-label">Subtotal</span>
                                        <span class="summary-value">₹<span id="subtotal">0.00</span></span>
                                    </div>
                                    <div class="summary-row">
                                        <span class="summary-label">Discount</span>
                                        <span class="summary-value text-success">-₹<span id="discount">0.00</span></span>
                                    </div>
                                    <div class="summary-row summary-total">
                                        <span class="summary-label total-label">Total Amount</span>
                                        <span class="summary-value total-value">₹<span id="total">0.00</span></span>
                                    </div>

                                    <div class="action-buttons mt-4">
                                        <button type="button" class="btn-action btn-test" data-bs-toggle="modal"
                                            data-bs-target="#addTestModal">
                                            <i class="fas fa-plus me-2"></i>Add Tests
                                        </button>
                                        <button type="button" class="btn-action btn-package" data-bs-toggle="modal"
                                            data-bs-target="#addPackageModal">
                                            <i class="fas fa-plus me-2"></i>Add Packages
                                        </button>
                                        <button type="button" class="btn-action btn-clear" onclick="clearAllItems()"
                                            id="clearAllBtn">
                                            <i class="fas fa-trash-alt me-2"></i>Clear All
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="summary-body border-top" style="padding-top: 1.5rem;">
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-calendar-check me-2"></i>Schedule Appointment
                            </button>
                            <p class="text-muted small text-center mt-2 mb-0">
                                <i class="fas fa-info-circle me-1"></i>Cash payment on collection
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    {{-- Add Test Modal --}}
    <div class="modal fade" id="addTestModal" tabindex="-1" aria-labelledby="addTestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-glass">
                <div class="modal-header modal-header-glass" style="background: var(--test-gradient);">
                    <div class="modal-title-container">
                        <div class="modal-icon" style="background: rgba(255, 255, 255, 0.25);">
                            <i class="fas fa-flask-vial" style="color: white;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0" style="color: white;">Select Diagnostic Tests</h5>
                            <p class="mb-0 opacity-90" style="color: rgba(255, 255, 255, 0.9);">Choose from
                                {{ count($tests) }} available tests</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="list-view-container">
                    <div class="list-search-section">
                        <div class="input-group-enhanced">
                            <i class="fas fa-search input-icon" style="color: var(--test-primary);"></i>
                            <input type="text" id="testSearch" class="form-control"
                                placeholder="Search tests by name">
                        </div>
                    </div>

                    <div class="list-items-container" id="testList">
                        <!-- Empty state placeholder - hidden by default -->
                        <div class="empty-list" id="testEmptyState" style="display: none;">
                            <div class="empty-list-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <p id="testEmptyMessage">No tests found</p>
                            <p class="small text-muted">Try different keywords</p>
                        </div>

                        <!-- Tests will be rendered here -->
                        <div id="testItemsContainer">
                            @foreach ($tests as $test)
                                <div class="list-item test-item" data-id="{{ $test->id }}"
                                    data-name="{{ $test->test_name }}" data-price="{{ $test->mrp }}"
                                    data-category="{{ $test->category }}" data-description="{{ $test->description }}">
                                    <input type="checkbox" class="list-item-checkbox test-checkbox"
                                        id="test_{{ $test->id }}">
                                    <div class="list-item-content">
                                        <div class="list-item-title">
                                            <span>{{ $test->test_name }}</span>
                                            <span class="list-item-badge badge-test">TEST</span>
                                        </div>
                                    </div>
                                    <div class="list-item-price">
                                        <div class="list-item-price-amount price-test">₹{{ $test->mrp }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="badge me-3"
                            style="background: rgba(14, 165, 233, 0.1); color: var(--test-primary); border: 1px solid rgba(14, 165, 233, 0.2); padding: 0.5rem 1rem;"
                            id="selectedTestsCount">
                            0 tests selected
                        </span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearTestSelections()"
                            style="border-color: var(--test-primary); color: var(--test-primary);">
                            <i class="fas fa-undo me-1"></i> Clear
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn" onclick="addSelectedTests()"
                            style="background: var(--test-gradient); color: white; border: none;">
                            <i class="fas fa-check me-1"></i> Add Selected (0)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Package Modal --}}
    <div class="modal fade" id="addPackageModal" tabindex="-1" aria-labelledby="addPackageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-glass">
                <div class="modal-header modal-header-glass" style="background: var(--package-gradient);">
                    <div class="modal-title-container">
                        <div class="modal-icon" style="background: rgba(255, 255, 255, 0.25);">
                            <i class="fas fa-box" style="color: white;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0" style="color: white;">Select Health Packages</h5>
                            <p class="mb-0 opacity-90" style="color: rgba(255, 255, 255, 0.9);">Comprehensive health
                                checkup packages</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="list-view-container">
                    <div class="list-search-section">
                        <div class="input-group-enhanced">
                            <i class="fas fa-search input-icon" style="color: var(--package-primary);"></i>
                            <input type="text" id="packageSearch" class="form-control"
                                placeholder="Search packages by name">
                        </div>
                    </div>

                    <div class="list-items-container" id="packageList">
                        <!-- Empty state placeholder - hidden by default -->
                        <div class="empty-list" id="packageEmptyState" style="display: none;">
                            <div class="empty-list-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <p id="packageEmptyMessage">No packages found</p>
                            <p class="small text-muted">Try different keywords</p>
                        </div>

                        <!-- Packages will be rendered here -->
                        <div id="packageItemsContainer">
                            @foreach ($packages as $package)
                                <div class="list-item package-item" data-id="{{ $package->id }}"
                                    data-name="{{ $package->title }}"
                                    data-price="{{ $package->discounted_price ?? $package->mrp }}"
                                    data-mrp="{{ $package->mrp }}" data-description="{{ $package->description }}">
                                    <input type="checkbox" class="list-item-checkbox package-checkbox"
                                        id="package_{{ $package->id }}">
                                    <div class="list-item-content">
                                        <div class="list-item-title">
                                            <span>{{ $package->title }}</span>
                                            <span class="list-item-badge badge-package">PACKAGE</span>
                                        </div>
                                        @if ($package->description)
                                            <div class="list-item-description">
                                                {{ Str::limit($package->description, 100) }}</div>
                                        @endif
                                        <div class="list-item-meta">
                                            <span class="list-item-code">
                                            </span>
                                        </div>
                                    </div>
                                    <div class="list-item-price">
                                        @if ($package->discounted_price && $package->mrp > $package->discounted_price)
                                           
                                        @endif
                                        <div class="list-item-price-amount price-package">
                                            ₹{{ $package->discounted_price ?? $package->mrp }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="badge me-3"
                            style="background: rgba(16, 185, 129, 0.1); color: var(--package-primary); border: 1px solid rgba(16, 185, 129, 0.2); padding: 0.5rem 1rem;"
                            id="selectedPackagesCount">
                            0 packages selected
                        </span>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="clearPackageSelections()"
                            style="border-color: var(--package-primary); color: var(--package-primary);">
                            <i class="fas fa-undo me-1"></i> Clear
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn" onclick="addSelectedPackages()"
                            style="background: var(--package-gradient); color: white; border: none;">
                            <i class="fas fa-check me-1"></i> Add Selected (0)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        /* === TIME SLOT LOGIC === */

        const dateInput = document.getElementById('date');
        const slotSelect = document.getElementById('time_slot');

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

        function parseISODate(iso) {
            if (!iso) return null;
            const [y, m, d] = iso.split('-').map(Number);
            return new Date(y, m - 1, d);
        }

        function formatTime(totalMinutes) {
            const h24 = Math.floor(totalMinutes / 60);
            const m = totalMinutes % 60;
            const ampm = h24 >= 12 ? 'PM' : 'AM';
            let h = h24 % 12;
            if (h === 0) h = 12;
            return `${h}:${String(m).padStart(2, '0')} ${ampm}`;
        }

        function generateSlots(isoDate) {
            slotSelect.innerHTML = '';

            const chosen = parseISODate(isoDate);
            if (!chosen) {
                slotSelect.innerHTML = '<option value="">Select a valid date</option>';
                return;
            }

            const today = new Date();
            const now = new Date();

            const isToday =
                today.getFullYear() === chosen.getFullYear() &&
                today.getMonth() === chosen.getMonth() &&
                today.getDate() === chosen.getDate();

            const firstStartMin = SLOT_START_H * 60 + SLOT_START_M;
            const normalLastStartMin = SLOT_NORMAL_LAST_START_H * 60 + SLOT_NORMAL_LAST_START_M;
            const extendedLastStartMin = SAME_DAY_EXTENDED_LAST_START_H * 60 + SAME_DAY_EXTENDED_LAST_START_M;

            // Same-day cutoff
            if (isToday) {
                const cutoff = new Date(
                    today.getFullYear(),
                    today.getMonth(),
                    today.getDate(),
                    SAME_DAY_BOOKING_CUTOFF_H,
                    SAME_DAY_BOOKING_CUTOFF_M
                );

                if (now > cutoff) {
                    slotSelect.innerHTML =
                        '<option value="">Same-day booking closed after 12:00 PM</option>';
                    return;
                }
            }

            let added = 0;

            for (
                let startMin = firstStartMin; startMin <= (isToday ? extendedLastStartMin : normalLastStartMin); startMin +=
                SLOT_STEP_MIN
            ) {
                const start = new Date(chosen);
                start.setHours(
                    Math.floor(startMin / 60),
                    startMin % 60,
                    0,
                    0
                );

                if (isToday && start <= now) continue;

                const hh = String(Math.floor(startMin / 60)).padStart(2, '0');
                const mm = String(startMin % 60).padStart(2, '0');

                const opt = document.createElement('option');
                opt.value = `${hh}:${mm}`;
                opt.textContent =
                    `${formatTime(startMin)} - ${formatTime(startMin + SLOT_LENGTH_MIN)}`;

                slotSelect.appendChild(opt);
                added++;
            }

            if (added === 0) {
                slotSelect.innerHTML = '<option value="">No slots available</option>';
            }
        }

        if (dateInput) {
            dateInput.addEventListener('change', function() {
                generateSlots(this.value);
            });

            // Auto-load if date already selected
            if (dateInput.value) {
                generateSlots(dateInput.value);
            }
        }
    </script>

    <script>
        let selectedItems = [];

        document.addEventListener('DOMContentLoaded', function() {
            initializeEventListeners();
            updateSelectedItemsUI();
            initializeModalEvents();
        });

        function initializeEventListeners() {
            // Search functionality for tests
            const testSearch = document.getElementById('testSearch');
            if (testSearch) {
                testSearch.addEventListener('input', function(e) {
                    searchTests(e.target.value.toLowerCase());
                });
            }

            // Search functionality for packages
            const packageSearch = document.getElementById('packageSearch');
            if (packageSearch) {
                packageSearch.addEventListener('input', function(e) {
                    searchPackages(e.target.value.toLowerCase());
                });
            }

            // Checkbox click handlers
            document.querySelectorAll('.test-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    const item = this.closest('.list-item');
                    if (this.checked) {
                        item.classList.add('selected');
                    } else {
                        item.classList.remove('selected');
                    }
                    updateTestSelectionCount();
                });

                // Add click handler to entire list item
                const item = cb.closest('.list-item');
                item.addEventListener('click', function(e) {
                    if (e.target !== cb && !e.target.closest('.list-item-checkbox')) {
                        cb.checked = !cb.checked;
                        cb.dispatchEvent(new Event('change'));
                    }
                });
            });

            document.querySelectorAll('.package-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    const item = this.closest('.list-item');
                    if (this.checked) {
                        item.classList.add('selected');
                        item.classList.add('package');
                    } else {
                        item.classList.remove('selected');
                        item.classList.remove('package');
                    }
                    updatePackageSelectionCount();
                });

                // Add click handler to entire list item
                const item = cb.closest('.list-item');
                item.addEventListener('click', function(e) {
                    if (e.target !== cb && !e.target.closest('.list-item-checkbox')) {
                        cb.checked = !cb.checked;
                        cb.dispatchEvent(new Event('change'));
                    }
                });
            });

            // Form submission
            const form = document.getElementById('appointmentForm');
            if (form) {
                form.addEventListener('submit', handleFormSubmit);
            }
        }

        function initializeModalEvents() {
            // Initialize modals
            const testModal = document.getElementById('addTestModal');
            const packageModal = document.getElementById('addPackageModal');

            if (testModal) {
                testModal.addEventListener('show.bs.modal', function() {
                    // Clear any previous search
                    const testSearch = document.getElementById('testSearch');
                    if (testSearch) {
                        testSearch.value = '';
                        searchTests('');
                    }
                    clearTestSelections();
                });

                testModal.addEventListener('hidden.bs.modal', function() {
                    // Clear search when modal closes
                    const testSearch = document.getElementById('testSearch');
                    if (testSearch) {
                        testSearch.value = '';
                        searchTests('');
                    }
                });
            }

            if (packageModal) {
                packageModal.addEventListener('show.bs.modal', function() {
                    // Clear any previous search
                    const packageSearch = document.getElementById('packageSearch');
                    if (packageSearch) {
                        packageSearch.value = '';
                        searchPackages('');
                    }
                    clearPackageSelections();
                });

                packageModal.addEventListener('hidden.bs.modal', function() {
                    // Clear search when modal closes
                    const packageSearch = document.getElementById('packageSearch');
                    if (packageSearch) {
                        packageSearch.value = '';
                        searchPackages('');
                    }
                });
            }
        }

        function searchTests(searchTerm) {
            const testItems = document.querySelectorAll('#testItemsContainer .test-item');
            const emptyState = document.getElementById('testEmptyState');
            const emptyMessage = document.getElementById('testEmptyMessage');

            let visibleCount = 0;

            testItems.forEach(item => {
                const name = item.dataset.name ? item.dataset.name.toLowerCase() : '';
                const category = item.dataset.category ? item.dataset.category.toLowerCase() : '';
                const description = item.dataset.description ? item.dataset.description.toLowerCase() : '';
                const id = item.dataset.id ? item.dataset.id.toString().toLowerCase() : '';

                const isVisible = name.includes(searchTerm) ||
                    category.includes(searchTerm) ||
                    description.includes(searchTerm) ||
                    id.includes(searchTerm);

                item.style.display = isVisible ? 'flex' : 'none';
                if (isVisible) visibleCount++;
            });

            // Show/hide empty state
            if (searchTerm && visibleCount === 0) {
                if (emptyState && emptyMessage) {
                    emptyState.style.display = 'block';
                    emptyMessage.textContent = `No tests found matching "${searchTerm}"`;
                }
            } else {
                if (emptyState) {
                    emptyState.style.display = 'none';
                }
            }
        }

        function searchPackages(searchTerm) {
            const packageItems = document.querySelectorAll('#packageItemsContainer .package-item');
            const emptyState = document.getElementById('packageEmptyState');
            const emptyMessage = document.getElementById('packageEmptyMessage');

            let visibleCount = 0;

            packageItems.forEach(item => {
                const name = item.dataset.name ? item.dataset.name.toLowerCase() : '';
                const description = item.dataset.description ? item.dataset.description.toLowerCase() : '';
                const id = item.dataset.id ? item.dataset.id.toString().toLowerCase() : '';

                const isVisible = name.includes(searchTerm) ||
                    description.includes(searchTerm) ||
                    id.includes(searchTerm);

                item.style.display = isVisible ? 'flex' : 'none';
                if (isVisible) visibleCount++;
            });

            // Show/hide empty state
            if (searchTerm && visibleCount === 0) {
                if (emptyState && emptyMessage) {
                    emptyState.style.display = 'block';
                    emptyMessage.textContent = `No packages found matching "${searchTerm}"`;
                }
            } else {
                if (emptyState) {
                    emptyState.style.display = 'none';
                }
            }
        }

        function addSelectedTests() {
            const checkedBoxes = document.querySelectorAll('.test-checkbox:checked');
            let addedCount = 0;

            checkedBoxes.forEach(checkbox => {
                const item = checkbox.closest('.test-item');
                const id = item.dataset.id;
                const name = item.dataset.name;
                const price = parseFloat(item.dataset.price);
                const category = item.dataset.category || '';
                const description = item.dataset.description || '';

                if (!selectedItems.some(item => item.id == id && item.type === 'TEST')) {
                    selectedItems.push({
                        type: 'TEST',
                        id: id,
                        name: name,
                        price: price,
                        category: category,
                        description: description
                    });
                    addedCount++;
                }
                checkbox.checked = false;
                item.classList.remove('selected');
            });

            if (addedCount > 0) {
                updateSelectedItemsUI();
                updateTestSelectionCount();
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTestModal'));
                if (modal) modal.hide();
                showToast(`${addedCount} test(s) added`, 'success');
            } else {
                showToast('No new tests selected', 'info');
            }
        }

        function addSelectedPackages() {
            const checkedBoxes = document.querySelectorAll('.package-checkbox:checked');
            let addedCount = 0;

            checkedBoxes.forEach(checkbox => {
                const item = checkbox.closest('.package-item');
                const id = item.dataset.id;
                const name = item.dataset.name;
                const price = parseFloat(item.dataset.price);
                const description = item.dataset.description || '';

                if (!selectedItems.some(item => item.id == id && item.type === 'PACKAGE')) {
                    selectedItems.push({
                        type: 'PACKAGE',
                        id: id,
                        name: name,
                        price: price,
                        description: description
                    });
                    addedCount++;
                }
                checkbox.checked = false;
                item.classList.remove('selected');
                item.classList.remove('package');
            });

            if (addedCount > 0) {
                updateSelectedItemsUI();
                updatePackageSelectionCount();
                const modal = bootstrap.Modal.getInstance(document.getElementById('addPackageModal'));
                if (modal) modal.hide();
                showToast(`${addedCount} package(s) added`, 'success');
            } else {
                showToast('No new packages selected', 'info');
            }
        }

        function updateSelectedItemsUI() {
            const container = document.getElementById('selectedItemsContainer');
            const emptyState = document.getElementById('emptyState');
            const itemsList = document.getElementById('selectedItemsList');
            const itemCount = document.getElementById('itemCount');
            const clearAllBtn = document.getElementById('clearAllBtn');

            container.innerHTML = '';

            if (selectedItems.length === 0) {
                emptyState.style.display = 'block';
                itemsList.style.display = 'none';
                itemCount.textContent = '0 Items';
                clearAllBtn.disabled = true;

                document.getElementById('subtotal').textContent = '0.00';
                document.getElementById('discount').textContent = '0.00';
                document.getElementById('total').textContent = '0.00';
            } else {
                emptyState.style.display = 'none';
                itemsList.style.display = 'block';
                itemCount.textContent = `${selectedItems.length} Item${selectedItems.length !== 1 ? 's' : ''}`;
                clearAllBtn.disabled = false;

                let subtotal = 0;

                selectedItems.forEach((item, index) => {
                    subtotal += item.price;

                    const itemElement = document.createElement('div');
                    itemElement.className = 'item-row';
                    itemElement.innerHTML = `
            <div class="item-info">
                <span class="item-type ${item.type === 'PACKAGE' ? 'package' : 'test'}">
                    <i class="fas ${item.type === 'PACKAGE' ? 'fa-box' : 'fa-flask-vial'}"></i>
                    ${item.type === 'PACKAGE' ? 'Package' : 'Test'}
                </span>
                <div class="item-name">${escapeHtml(item.name)}</div>
                ${item.category ? `<div class="item-category">${escapeHtml(item.category)}</div>` : ''}
                
            </div>
            <div class="item-price">
                <div class="price-amount">₹${item.price.toFixed(2)}</div>
                <button type="button" class="remove-btn" onclick="removeItem(${index})" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
                    container.appendChild(itemElement);
                });

                document.getElementById('subtotal').textContent = subtotal.toFixed(2);
                document.getElementById('total').textContent = subtotal.toFixed(2);
            }

            updateHiddenInputs();
        }

        function removeItem(index) {
            const itemName = selectedItems[index].name;
            selectedItems.splice(index, 1);
            updateSelectedItemsUI();
            showToast(`"${itemName}" removed`, 'warning');
        }

        function clearAllItems() {
            if (selectedItems.length === 0) return;

            if (confirm('Remove all selected items from this appointment?')) {
                selectedItems = [];
                updateSelectedItemsUI();
                showToast('All items cleared', 'info');
            }
        }

        function updateHiddenInputs() {
            const existingContainer = document.getElementById('itemsContainer');
            if (existingContainer) existingContainer.remove();

            const container = document.createElement('div');
            container.id = 'itemsContainer';

            selectedItems.forEach((item, index) => {
                const map = {
                    item_type: item.type, // TEST / PACKAGE
                    item_id: item.id,
                    item_name: item.name,
                    item_price: item.price,
                    quantity: 1 // required by backend
                };

                Object.entries(map).forEach(([key, value]) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `items[${index}][${key}]`;
                    input.value = value;
                    container.appendChild(input);
                });
            });

            document.getElementById('appointmentForm').appendChild(container);
        }

        function handleFormSubmit(e) {
            if (selectedItems.length === 0) {
                e.preventDefault();
                showToast('Please add at least one test or package', 'danger');
                return false;
            }

            // Enable time slot before submission
            document.getElementById('time_slot').disabled = false;

            updateHiddenInputs();
            return true;
        }

        // Helper functions
        function clearTestSelections() {
            document.querySelectorAll('.test-checkbox').forEach(cb => {
                cb.checked = false;
                const item = cb.closest('.list-item');
                item.classList.remove('selected');
            });
            updateTestSelectionCount();
        }

        function clearPackageSelections() {
            document.querySelectorAll('.package-checkbox').forEach(cb => {
                cb.checked = false;
                const item = cb.closest('.list-item');
                item.classList.remove('selected');
                item.classList.remove('package');
            });
            updatePackageSelectionCount();
        }

        function updateTestSelectionCount() {
            const count = document.querySelectorAll('.test-checkbox:checked').length;
            document.getElementById('selectedTestsCount').textContent = `${count} test${count !== 1 ? 's' : ''} selected`;
            const addBtn = document.querySelector('#addTestModal .btn[onclick="addSelectedTests()"]');
            if (addBtn) {
                addBtn.innerHTML = `<i class="fas fa-check me-1"></i> Add Selected (${count})`;
            }
        }

        function updatePackageSelectionCount() {
            const count = document.querySelectorAll('.package-checkbox:checked').length;
            document.getElementById('selectedPackagesCount').textContent =
                `${count} package${count !== 1 ? 's' : ''} selected`;
            const addBtn = document.querySelector('#addPackageModal .btn[onclick="addSelectedPackages()"]');
            if (addBtn) {
                addBtn.innerHTML = `<i class="fas fa-check me-1"></i> Add Selected (${count})`;
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showToast(message, type = 'info') {
            const existing = document.querySelectorAll('.toast-container');
            existing.forEach(toast => toast.remove());

            const container = document.createElement('div');
            container.className = 'toast-container';

            const toast = document.createElement('div');
            toast.className = 'toast-custom';

            const icons = {
                'success': 'check-circle',
                'danger': 'exclamation-circle',
                'warning': 'exclamation-triangle',
                'info': 'info-circle'
            };

            const colors = {
                'success': '#10b981',
                'danger': '#ef4444',
                'warning': '#f59e0b',
                'info': '#0ea5e9'
            };

            toast.innerHTML = `
    <div style="color: ${colors[type] || colors.info};">
        <i class="fas fa-${icons[type] || 'info-circle'} fa-lg"></i>
    </div>
    <div style="flex: 1;">
        <strong style="color: #1e293b;">${escapeHtml(message)}</strong>
    </div>
    <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
`;

            container.appendChild(toast);
            document.body.appendChild(container);

            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
                if (container.parentElement) {
                    container.remove();
                }
            }, 3000);
        }
    </script>
    <script>
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {

            let isValid = true;

            // Clear old errors
            document.querySelectorAll('.js-error').forEach(el => el.remove());

            function showError(input, message) {
                const error = document.createElement('div');
                error.className = 'text-danger small mt-2 js-error';
                error.innerText = message;
                input.closest('.form-group').appendChild(error);
                isValid = false;
            }

            function checkRequired(name, message) {
                const input = document.querySelector(`[name="${name}"]`);
                if (!input || input.value.trim() === '') {
                    showError(input, message);
                }
            }

            function checkPhone() {
                const input = document.querySelector('[name="phone"]');
                const value = input.value.trim();
                if (!/^\d{10}$/.test(value)) {
                    showError(input, 'Phone number must be 10 digits.');
                }
            }

            function checkPincode() {
                const input = document.querySelector('[name="pincode"]');
                const value = input.value.trim();
                if (!/^\d{6}$/.test(value)) {
                    showError(input, 'Pincode must be 6 digits.');
                }
            }

            // Required Fields
            checkRequired('name', 'Full name is required.');
            checkRequired('phone', 'Phone number is required.');
            checkRequired('gender', 'Please select gender.');
            checkRequired('dob', 'Date of birth is required.');
            checkRequired('date', 'Collection date is required.');
            checkRequired('time_slot', 'Please select a time slot.');
            checkRequired('address', 'Address is required.');
            checkRequired('city', 'City is required.');
            checkRequired('pincode', 'Pincode is required.');

            // Format Checks
            if (document.querySelector('[name="phone"]').value.trim() !== '') {
                checkPhone();
            }

            if (document.querySelector('[name="pincode"]').value.trim() !== '') {
                checkPincode();
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>

@endsection
