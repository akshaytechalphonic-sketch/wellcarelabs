<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Wellcare Labs – Lab Report</title>
</head>
<body style="margin:0; padding:0; background-color:#e5eaef; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;">

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td align="center" style="padding:24px 12px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                   style="max-width:640px; background-color:#ffffff; border-radius:18px; overflow:hidden; box-shadow:0 16px 40px rgba(15,23,42,0.16);">

                {{-- Top accent bar --}}
                <tr>
                    <td style="height:4px; background:linear-gradient(90deg,#0f766e,#0f9d80,#14b8a6); font-size:0; line-height:0;">&nbsp;</td>
                </tr>

                {{-- Header with logo + title --}}
                <tr>
                    <td align="center" style="padding:24px 24px 18px 24px; background-color:#ffffff;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td align="left" style="vertical-align:middle;">
                                    <img
                                        src="{{ $message->embed(public_path('assets/images/new_logo_banner.png')) }}"
                                        alt="Wellcare Labs"
                                        style="max-width:180px; height:auto; display:block;"
                                    >
                                </td>
                                <td align="right" style="vertical-align:middle;">
                                    <span style="display:inline-block; padding:6px 10px; border-radius:999px; background-color:#ecfdf5; border:1px solid #bbf7d0; color:#047857; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.07em;">
                                        Lab Report Ready
                                    </span>
                                </td>
                            </tr>
                        </table>

                        <div style="margin-top:18px; text-align:left;">
                            <div style="font-size:20px; font-weight:700; color:#0f172a; margin-bottom:4px;">
                                Your lab report is attached
                            </div>
                            <div style="font-size:13px; color:#6b7280;">
                                Please find the patient’s report summary below. The full detailed report is attached as a PDF document.
                            </div>
                        </div>
                    </td>
                </tr>

                {{-- Main content --}}
                <tr>
                    <td style="padding:8px 24px 20px 24px; font-size:14px; line-height:1.65; color:#111827; background-color:#f9fafb;">
                        {{-- Greeting --}}
                        <p style="margin:0 0 10px 0;">
                            @if(!empty($patient))
                                Dear {{ $patient }},
                            @else
                                Dear Sir/Madam,
                            @endif
                        </p>

                        {{-- Custom body from system or default text --}}
                        @if(!empty($body))
                            <p style="margin:0 0 12px 0;">
                                {!! nl2br(e($body)) !!}
                            </p>
                        @else
                            <p style="margin:0 0 12px 0;">
                                Please find attached your lab report from
                                <span style="font-weight:600; color:#0f766e;">Wellcare Labs</span>.
                                A brief summary of your report details is provided below for your reference.
                            </p>
                        @endif

                        {{-- CTA button (optional: works even if there is no online link) --}}
                        @php
                          /** Optionally pass $reportUrl from controller if you have a web link */
                          $reportUrl = $reportUrl ?? null;
                        @endphp

                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:12px 0 18px 0;">
                            <tr>
                                <td>
                                    @if(!empty($reportUrl))
                                        <a href="{{ $reportUrl }}"
                                           style="display:inline-block; padding:10px 20px; border-radius:999px; background:linear-gradient(90deg,#0f766e,#0f9d80,#14b8a6); color:#ffffff; text-decoration:none; font-size:13px; font-weight:600;">
                                            View Report Online
                                        </a>
                                    @else
                                        <span style="display:inline-block; padding:10px 18px; border-radius:999px; background-color:#e5f3ff; color:#1d4ed8; font-size:12px; font-weight:600;">
                                            The full report is attached as a PDF file.
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        {{-- Report summary card --}}
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:4px 0 10px 0;">
                            <tr>
                                <td style="background-color:#ffffff; border-radius:12px; border:1px solid #e5e7eb; padding:14px 16px;">
                                    <div style="font-size:11px; color:#6b7280; text-transform:uppercase; letter-spacing:0.12em; margin-bottom:8px;">
                                        Report Summary
                                    </div>

                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                            <td style="font-size:12px; color:#6b7280; padding:3px 0; width:30%;">Patient</td>
                                            <td style="font-size:13px; color:#111827; padding:3px 0;">
                                                <strong>{{ $patient ?? '-' }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12px; color:#6b7280; padding:3px 0;">Test / Package</td>
                                            <td style="font-size:13px; color:#111827; padding:3px 0;">
                                                <strong>{{ $test ?? '-' }}</strong>
                                            </td>
                                        </tr>

                                        @if(!empty($appointmentId ?? null))
                                          <tr>
                                              <td style="font-size:12px; color:#6b7280; padding:3px 0;">Appointment ID</td>
                                              <td style="font-size:13px; color:#111827; padding:3px 0;">
                                                  {{ $appointmentId }}
                                              </td>
                                          </tr>
                                        @endif

                                        @if(!empty($appointmentDate ?? null))
                                          <tr>
                                              <td style="font-size:12px; color:#6b7280; padding:3px 0;">Sample / Visit Date</td>
                                              <td style="font-size:13px; color:#111827; padding:3px 0;">
                                                  {{ $appointmentDate }}
                                              </td>
                                          </tr>
                                        @endif
                                      </table>
                                </td>
                            </tr>
                        </table>

                        {{-- Info / guidance block --}}
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:8px 0 6px 0;">
                            <tr>
                                <td style="background-color:#ecfdf5; border-radius:10px; padding:10px 12px; border:1px solid #bbf7d0;">
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                            <td valign="top" style="font-size:18px; padding-right:8px;">🩺</td>
                                            <td style="font-size:12px; color:#064e3b; line-height:1.55;">
                                                <strong style="display:block; font-size:12px; margin-bottom:2px;">Please note</strong>
                                                <span style="display:block; margin-bottom:2px;">
                                                    This report is for clinical use only. Kindly consult your doctor for interpretation and medical advice.
                                                </span>
                                                <span style="display:block;">
                                                    If there is any discrepancy in patient details or test information, please contact Wellcare Labs immediately.
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        {{-- Optional contact + help section --}}
                        <p style="margin:14px 0 0 0; font-size:12px; color:#6b7280;">
                            For any queries regarding this report, you may reach our support team:
                        </p>
                        <p style="margin:4px 0 0 0; font-size:12px; color:#0f172a;">
                            @if(!empty($labPhone ?? null))
                                📞 <strong>{{ $labPhone }}</strong><br>
                            @endif
                            @if(!empty($labEmail ?? null))
                                ✉️ <strong>{{ $labEmail }}</strong><br>
                            @endif
                            <span style="color:#6b7280;">
                                Thank you for choosing <span style="color:#0f766e; font-weight:600;">Wellcare Labs</span>.
                            </span>
                        </p>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td align="center" style="padding:14px 24px 18px 24px; border-top:1px solid #e5e7eb; font-size:11px; color:#9ca3af; background-color:#ffffff;">
                        <div style="margin-bottom:4px;">
                            Regards,<br>
                            <span style="color:#111827; font-weight:600;">Wellcare Labs</span>
                        </div>

                        @if(!empty($labAddress ?? null))
                          <div style="margin-top:4px; font-size:10px; color:#9ca3af;">
                              {{ $labAddress }}
                          </div>
                        @endif

                        <div style="font-size:10px; color:#9ca3af; margin-top:6px;">
                            This is an automated email. Please do not reply to this message.
                        </div>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
