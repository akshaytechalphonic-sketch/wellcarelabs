<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking Confirmation</title>
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
            background-color: #0d6efd;
            color: #ffffff;
            padding: 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 25px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 15px;
        }
        .info-card {
            background-color: #f8fafc;
            border-left: 4px solid #0d6efd;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 6px 6px 0;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #edf2f7;
            font-size: 14px;
        }
        .details-table td.label {
            font-weight: 600;
            color: #4a5568;
            width: 40%;
        }
        .details-table td.value {
            color: #2d3748;
        }
        .total-row td {
            font-weight: bold;
            font-size: 15px;
            color: #0d6efd;
            border-bottom: none;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #718096;
            border-top: 1px solid #edf2f7;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 4px;
            background-color: #e2e8f0;
            color: #2d3748;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Wellcare Labs</h1>
            <p>Appointment Booking Confirmation</p>
        </div>

        <div class="content">
            <p class="greeting">Dear <strong>{{ $appointment->name ?? 'Valued Customer' }}</strong>,</p>
            <p>Thank you for choosing <strong>Wellcare Labs</strong>! Your appointment has been successfully booked. Below are your booking details:</p>

            <div class="info-card">
                <strong>Booking Reference:</strong> #{{ $appointment->id }}<br>
                <strong>Status:</strong> <span class="badge">{{ ucfirst($appointment->status ?? 'Pending') }}</span>
            </div>

            <table class="details-table">
                <tr>
                    <td class="label">Customer Name:</td>
                    <td class="value">{{ $appointment->name }}</td>
                </tr>
                <tr>
                    <td class="label">Email Address:</td>
                    <td class="value">{{ $appointment->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Phone Number:</td>
                    <td class="value">{{ $appointment->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Appointment Date:</td>
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
                    <td class="value">{{ $appointment->service ?? $appointment->package_name ?? 'Diagnostic Test' }}</td>
                </tr>
                @if(!empty($appointment->address))
                <tr>
                    <td class="label">Collection Address:</td>
                    <td class="value">
                        {{ $appointment->address }}, {{ $appointment->city ?? '' }} {{ $appointment->pincode ? '('.$appointment->pincode.')' : '' }}
                        @if(!empty($appointment->landmark))
                            <br><small>Landmark: {{ $appointment->landmark }}</small>
                        @endif
                    </td>
                </tr>
                @endif
                <tr>
                    <td class="label">Payment Method:</td>
                    <td class="value">{{ ucfirst($appointment->payment_method ?? 'Pending') }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label">Total Price:</td>
                    <td class="value">₹{{ number_format((float)($appointment->total_price ?? 0), 2) }}</td>
                </tr>
            </table>

            <p style="font-size: 13px; color: #4a5568;">Our representative will contact you shortly regarding your appointment and sample collection.</p>
        </div>

        <div class="footer">
            <p>If you have any questions or need to reschedule, please contact us at <strong>support@wellcarelabs.in</strong></p>
            <p>&copy; {{ date('Y') }} Wellcare Labs. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
