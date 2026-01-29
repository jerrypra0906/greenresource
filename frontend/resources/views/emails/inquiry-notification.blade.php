<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Inquiry – {{ config('app.name', 'Green Resources') }}</title>
    <!--[if mso]>
    <noscript>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    </noscript>
    <![endif]-->
    <style type="text/css">
        /* Reset and base for email clients */
        body, table, td, p, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; -webkit-font-smoothing: antialiased;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="padding: 24px 16px;">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td style="padding: 0;">
                            <!-- Greeting -->
                            <div style="padding: 0 24px 8px;">
                                <p style="margin: 0; font-size: 18px; font-weight: 600;">Dear Admin Team,</p>
                            </div>
                            <!-- Intro -->
                            <div style="padding: 16px 24px; font-size: 15px; line-height: 1.6;">
                                <p style="margin: 0 0 16px;">You have received a new inquiry from a website visitor. Please find the details below.</p>
                            </div>
                            <!-- Inquiry details -->
                            <div style="margin: 0 24px 24px; padding: 20px;">
                                <p style="margin: 0 0 16px; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;">Inquiry Details</p>
                                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; line-height: 1.7;">
                                    <tr><td style="padding: 4px 0;"><strong>Name:</strong></td><td style="padding: 4px 0;">{{ $inquiry->name }}</td></tr>
                                    <tr><td style="padding: 4px 0;"><strong>Email:</strong></td><td style="padding: 4px 0;">{{ $inquiry->email }}</td></tr>
                                    <tr><td style="padding: 4px 0;"><strong>Subject:</strong></td><td style="padding: 4px 0;">{{ $inquiry->subject }}</td></tr>
                                </table>
                                <p style="margin: 12px 0 4px; font-size: 14px; font-weight: 600;">Message:</p>
                                <p style="margin: 0; font-size: 14px; line-height: 1.6; white-space: pre-wrap;">{{ $inquiry->message }}</p>
                                <p style="margin: 16px 0 0; padding-top: 12px; font-size: 13px;">
                                    <strong>Submitted On (UTC+8):</strong> {{ $inquiry->submitted_at->timezone('Asia/Singapore')->format('F j, Y \a\t g:i A') }}
                                </p>
                                <p style="margin: 12px 0 0; font-size: 13px;">
                                    <a href="{{ url(route('admin.inquiries.show', $inquiry)) }}" style="color: #2563eb; text-decoration: underline;">Open in CMS</a>
                                </p>
                            </div>
                            <!-- Closing -->
                            <div style="padding: 0 24px 24px; font-size: 14px; line-height: 1.6;">
                                <p style="margin: 0 0 8px;">Please review and follow up as needed.</p>
                                <p style="margin: 0;">Thank you,<br><strong>System Notification</strong><br>{{ config('app.name', 'Green Resources') }}</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
