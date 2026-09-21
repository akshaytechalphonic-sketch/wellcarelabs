<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Wellcare Labs – Password Reset</title>
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
                                Reset Your Password
                            </h2>
                            <p style="margin:6px 0 0 0; font-size:13px; color:#6b7280;">
                                Secure access to your WellCare account
                            </p>
                        </div>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:16px 24px 22px 24px; font-size:14px; line-height:1.65; color:#111827; background-color:#f9fafb;">
                        <p style="margin:0 0 12px 0;">
                            Hello {{ $name }},
                        </p>

                        <p style="margin:0 0 12px 0;">
                            Forgot your password? Don’t worry — it happens.
                        </p>

                        <p style="margin:0 0 16px 0;">
                            Click the button below to reset your password and regain access to your account.
                        </p>

                        {{-- Button --}}
                        <div style="text-align:center; margin:24px 0;">
                            <a href="{{ $resetUrl }}"
                               style="display:inline-block; padding:12px 22px; font-size:14px;
                                      background-color:#0f766e; color:#ffffff;
                                      text-decoration:none; border-radius:10px; font-weight:600;">
                                Reset Password
                            </a>
                        </div>

                        <p style="margin:0 0 10px 0; font-size:13px;">
                            ⏳ This link will expire after some time for security reasons.
                        </p>

                        <p style="margin:0; font-size:13px; color:#b91c1c;">
                            ⚠️ If you did not request a password reset, please ignore this email.
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
