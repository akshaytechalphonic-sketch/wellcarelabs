<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Appointment Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e1e8ed;
        }
        .header {
            background-color: #1e293b;
            color: #ffffff;
            padding: 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 13px;
            color: #94a3b8;
        }
        .content {
            padding: 30px 25px;
        }
        .alert-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
            color: #1e40af;
        }
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 5px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .details-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
        .details-table td.label {
            font-weight: 600;
            color: #64748b;
            width: 35%;
        }
        .details-table td.value {
            color: #0f172a;
        }
        .btn {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 20px;
            text-align: center;
        }
        .footer {
            background-color: #f8fafc;
            padding: 15px 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Wellcare Labs - Admin Notification</h1>
            <p>New Appointment Received</p>
        </div>

        <div class="content">
            <div class="alert-box">
                A new appointment <strong>#{{ $appointment->id }}</strong> has been booked on the portal.
            </div>

            <div class="section-title">Patient Information</div>
            <table class="details-table">
                <tr>
                    <td class="label">Patient Name:</td>
                    <td class="value"><strong>{{ $appointment->name }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Phone:</td>
                    <td class="value">{{ $appointment->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Email:</td>
                    <td class="value">{{ $appointment->email ?? 'N/A' }}</td>
                </tr>
                @if(!empty($appointment->gender) || !empty($appointment->age))
                <tr>
                    <td class="label">Gender / Age:</td>
                    <td class="value">
                        {{ ucfirst($appointment->gender ?? 'N/A') }} 
                        @if(!empty($appointment->age)) ({{ $appointment->age }} yrs) @endif
                    </td>
                </tr>
                @endif
            </table>

            <div class="section-title">Appointment Details</div>
            <table class="details-table">
                <tr>
                    <td class="label">Appointment ID:</td>
                    <td class="value">#{{ $appointment->id }}</td>
                </tr>
                <tr>
                    <td class="label">Requested Date:</td>
                    <td class="value">
                        @if(!empty($appointment->date))
                            {{ \Carbon\Carbon::parse($appointment->date)->format('d M, Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Time Slot:</td>
                    <td class="value">
                        @if(!empty($appointment->time_slot))
                            {{ \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A') }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Service / Package:</td>
                    <td class="value">{{ $appointment->service ?? $appointment->package_name ?? 'N/A' }}</td>
                </tr>
                @if(!empty($appointment->address))
                <tr>
                    <td class="label">Address:</td>
                    <td class="value">
                        {{ $appointment->address }}, {{ $appointment->city ?? '' }} {{ $appointment->pincode ? '('.$appointment->pincode.')' : '' }}
                        @if(!empty($appointment->landmark))
                            <br><small>Landmark: {{ $appointment->landmark }}</small>
                        @endif
                    </td>
                </tr>
                @endif
                @if(!empty($appointment->message))
                <tr>
                    <td class="label">Notes / Message:</td>
                    <td class="value">{{ $appointment->message }}</td>
                </tr>
                @endif
            </table>

            <div class="section-title">Payment & Pricing</div>
            <table class="details-table">
                <tr>
                    <td class="label">Payment Method:</td>
                    <td class="value">{{ ucfirst($appointment->payment_method ?? 'Pending') }}</td>
                </tr>
                @if(!empty($appointment->subtotal))
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="value">₹{{ number_format((float)$appointment->subtotal, 2) }}</td>
                </tr>
                @endif
                @if(!empty($appointment->discount_amount) && $appointment->discount_amount > 0)
                <tr>
                    <td class="label">Discount:</td>
                    <td class="value">-₹{{ number_format((float)$appointment->discount_amount, 2) }} {{ $appointment->coupon_code ? '('.$appointment->coupon_code.')' : '' }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Total Price:</td>
                    <td class="value"><strong>₹{{ number_format((float)($appointment->total_price ?? 0), 2) }}</strong></td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn">View Appointment in Admin Panel</a>
            </div>
        </div>

        <div class="footer">
            Wellcare Labs Administrative System Notification
        </div>
    </div>
</body>
</html>
