<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Wellcare Labs – Hospital Deletion OTP</title>
</head>

<body style="margin:0; padding:0; background-color:#e5eaef; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;">

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td align="center" style="padding:24px 12px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                   style="max-width:560px; background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 14px 36px rgba(15,23,42,0.18);">

                {{-- Top accent bar --}}
                <tr>
                    <td style="height:4px; background:linear-gradient(90deg,#0f766e,#0f9d80,#14b8a6); font-size:0;">&nbsp;</td>
                </tr>

                {{-- Header --}}
                <tr>
                    <td style="padding:22px 24px 18px 24px;">
                        <img
                            src="{{ $message->embed(public_path('assets/images/new_logo_banner.png')) }}"
                            alt="Wellcare Labs"
                            style="max-width:180px; height:auto; display:block;"
                        >

                        <div style="margin-top:18px;">
                            <h2 style="margin:0; font-size:20px; color:#0f172a;">
                                Hospital Deletion Verification
                            </h2>
                            <p style="margin:6px 0 0 0; font-size:13px; color:#6b7280;">
                                OTP confirmation required to proceed
                            </p>
                        </div>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:16px 24px 22px 24px; font-size:14px; line-height:1.65; color:#111827; background-color:#f9fafb;">
                        <p style="margin:0 0 12px 0;">
                            Dear Admin,
                        </p>

                        <p style="margin:0 0 12px 0;">
                            You have requested to <strong>permanently delete</strong> the following hospital:
                        </p>

                        <div style="margin:14px 0; padding:12px 14px; background-color:#ffffff; border:1px solid #e5e7eb; border-radius:10px;">
                            <strong style="font-size:15px; color:#0f172a;">
                                {{ $hospitalName }}
                            </strong>
                        </div>

                        <p style="margin:0 0 12px 0;">
                            Please use the OTP below to confirm this action:
                        </p>

                        {{-- OTP Box --}}
                        <div style="margin:18px 0; text-align:center;">
                            <div style="display:inline-block; padding:14px 22px; font-size:28px; font-weight:700;
                                        letter-spacing:6px; background-color:#ecfdf5;
                                        border:1px solid #bbf7d0; border-radius:12px; color:#047857;">
                                {{ $otp }}
                            </div>
                        </div>

                        <p style="margin:12px 0 0 0; font-size:13px;">
                            ⏳ This OTP is valid for <strong>10 minutes</strong>.
                        </p>

                        <p style="margin:10px 0 0 0; color:#b91c1c; font-size:13px;">
                            ⚠️ If you did not initiate this request, please ignore this email.
                        </p>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td align="center" style="padding:14px 24px 18px 24px; border-top:1px solid #e5e7eb; font-size:11px; color:#9ca3af;">
                        <div style="margin-bottom:4px;">
                            Regards,<br>
                            <span style="color:#111827; font-weight:600;">Wellcare Labs</span>
                        </div>

                        <div style="font-size:10px; margin-top:6px;">
                            This is an automated email. Please do not reply.
                        </div>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
