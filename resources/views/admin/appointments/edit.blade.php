@extends('layouts.app')

@section('content')
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
        }

        .page-title {
            font-weight: 800;
            margin-bottom: 30px;
            color: #1e293b;
            position: relative;
            padding-left: 20px;
        }

        .page-title:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 80%;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border-radius: 4px;
        }

        .card {
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            background: white;
            margin-bottom: 24px;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            font-weight: 700;
            border-bottom: 2px solid #e2e8f0;
            padding: 20px 25px;
            font-size: 1.1rem;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header i {
            color: #3b82f6;
            font-size: 1.2rem;
        }

        .card-body {
            padding: 25px;
        }

        label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        label i {
            color: #64748b;
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            font-size: 0.95rem;
            padding: 12px 16px;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            background: white;
            transform: translateY(-1px);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        /* Custom pill badges ONLY for appointment items */
        .service-badge {
            font-size: 0.72rem;
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 600;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        /* Variants */
        .service-badge.package {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: #064e3b;
        }

        .service-badge.test {
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
            color: #0c4a6e;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            border: none;
            border-radius: 12px;
            padding: 14px 32px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        }

        .btn-light {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-light:hover {
            border-color: #3b82f6;
            background: #f8fafc;
            transform: translateY(-1px);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border-left: 5px solid #10b981;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 24px;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 2px solid #e2e8f0;
        }

        .info-card h6 {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-card p {
            color: #1e293b;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .info-card .info-value {
            color: #3b82f6;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .appointment-id-badge {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 700;
            color: #92400e;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
        }

        .table {
            margin: 0;
        }

        .table thead {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        }

        .table th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            font-weight: 700;
            padding: 16px 20px;
            border: none;
            border-bottom: 2px solid #e2e8f0;
        }

        .table td {
            font-size: 0.95rem;
            vertical-align: middle;
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .pricing-summary {
            background: linear-gradient(135deg, #f0f9ff 0%, #ecfeff 100%);
            border: 2px solid #7dd3fc;
            border-radius: 16px;
            padding: 24px;
            margin: 30px 0;
        }

        .pricing-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px dashed #cbd5e1;
        }

        .pricing-item:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.2rem;
            color: #0c4a6e;
        }

        .pricing-label {
            color: #475569;
            font-weight: 600;
        }

        .pricing-value {
            color: #1e293b;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .input-icon {
            position: relative;
        }

        .input-icon .form-control {
            padding-left: 45px;
        }

        .input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .empty-state {
            text-align: center;
            padding: 60px 30px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h4 {
            color: #64748b;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 16px;
            justify-content: flex-end;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid #e2e8f0;
        }
    </style>

    <div class="container py-4">

        <h3 class="page-title">
            <i class="fas fa-edit me-3"></i>
            Edit Appointment
        </h3>

        @php $items = $items ?? collect(); @endphp

        {{-- TEST / PACKAGE DETAILS --}}
        <div class="card">
            <div class="card-header">
                <i class="fas fa-flask"></i>
                Selected Tests & Packages
            </div>
            <div class="card-body p-0">
                @if ($items->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-vial"></i>
                        <h4>No Services Added</h4>
                        <p>Add tests or packages to this appointment</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag fa-xs"></i></th>
                                    <th><i class="fas fa-tag fa-xs"></i> Type</th>
                                    <th><i class="fas fa-list fa-xs"></i> Service</th>
                                    <th class="text-end"><i class="fas fa-money-bill fa-xs"></i> Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $i => $it)
                                    <tr>
                                        <td class="fw-semibold">{{ $i + 1 }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $it->item_type === 'PACKAGE' ? 'bg-success' : 'bg-info' }}">
                                                <i
                                                    class="fas fa-{{ $it->item_type === 'PACKAGE' ? 'box' : 'vial' }} me-1"></i>
                                                {{ $it->item_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <i
                                                class="fas fa-{{ $it->item_type === 'PACKAGE' ? 'box-open' : 'microscope' }} me-2 text-muted"></i>
                                            {{ $it->item_name }}
                                        </td>
                                        <td class="text-end fw-semibold">
                                            ₹{{ number_format($it->item_price, 2) }}
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('admin.appointments.update', $appointment->id) }}">
            @csrf
            @method('PATCH')

            {{-- PATIENT & APPOINTMENT INFO --}}
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-injured"></i>
                    Patient & Appointment Information
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Full Name</label>
                            <div class="input-icon">
                                <i class="fas fa-user-circle"></i>
                                <input type="text" name="name" class="form-control" value="{{ $appointment->name }}"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-venus-mars"></i> Gender</label>
                            <div class="input-icon">
                                <i class="fas fa-user-tag"></i>
                                <select name="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                                        <option value="{{ $value }}" @selected($appointment->gender == $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-birthday-cake"></i> Date of Birth</label>
                            <div class="input-icon">
                                <i class="fas fa-calendar-day"></i>
                                <input type="date" name="dob" class="form-control"
                                    value="{{ optional($appointment->dob)->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-phone"></i> Phone Number</label>
                            <div class="input-icon">
                                <i class="fas fa-mobile-alt"></i>
                                <input type="tel" name="phone" class="form-control" value="{{ $appointment->phone }}"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email Address</label>
                            <div class="input-icon">
                                <i class="fas fa-at"></i>
                                <input type="email" name="email" class="form-control"
                                    value="{{ $appointment->email }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ADDRESS SECTION --}}
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-map-marker-alt"></i>
                    Address Details
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label><i class="fas fa-home"></i> Complete Address</label>
                        <div class="input-icon">
                            <i class="fas fa-map-pin"></i>
                            <textarea name="address" class="form-control" rows="3" required>{{ $appointment->address }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-city"></i> City</label>
                            <div class="input-icon">
                                <i class="fas fa-building"></i>
                                <input type="text" name="city" class="form-control" value="{{ $appointment->city }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-map-pin"></i> Pincode</label>
                            <div class="input-icon">
                                <i class="fas fa-location-dot"></i>
                                <input type="text" name="pincode" class="form-control"
                                    value="{{ $appointment->pincode }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-landmark"></i> Landmark</label>
                            <div class="input-icon">
                                <i class="fas fa-flag"></i>
                                <input type="text" name="landmark" class="form-control"
                                    value="{{ $appointment->landmark }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PRICING SECTION --}}
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-receipt"></i>
                    Pricing Information
                </div>
                <div class="card-body">
                    <div class="pricing-summary">
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-file-invoice-dollar"></i> Subtotal</label>
                                <div class="input-icon">
                                    <i class="fas fa-indian-rupee-sign"></i>
                                    <input type="number" step="0.01" name="subtotal" class="form-control"
                                        value="{{ $appointment->subtotal }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-receipt"></i> Total Amount</label>
                                <div class="input-icon">
                                    <i class="fas fa-indian-rupee-sign"></i>
                                    <input type="number" step="0.01" name="total_price" class="form-control"
                                        value="{{ $appointment->total_price }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-buttons d-flex gap-2 mt-3">
                <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-light">
                    <i class="fas fa-eye me-2"></i>View Details
                </a>
                <a href="{{ url()->previous() }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Appointment
                </button>
            </div>
        </form>
    </div>
@endsection
