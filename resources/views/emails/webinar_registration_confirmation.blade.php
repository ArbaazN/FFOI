<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webinar Registration Confirmation</title>
</head>
<body style="margin: 0; padding: 0; background-color: #edf4f6; font-family: Arial, sans-serif; color: #183153;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background: linear-gradient(180deg, #edf4f6 0%, #f8fbfc 100%);">
        <tr>
            <td align="center" style="padding: 32px 16px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width: 680px; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 18px 45px rgba(15, 42, 29, 0.12);">
                    <tr>
                        <td style="background: linear-gradient(135deg, #10273a 0%, #7bba27 100%); padding: 28px 32px 36px;">
                            <div style="display: inline-block; padding: 8px 14px; border: 1px solid rgba(255,255,255,0.28); border-radius: 999px; font-size: 12px; letter-spacing: 1.2px; text-transform: uppercase; color: #ffffff;">
                                Webinar Registration Confirmed
                            </div>
                            <h1 style="margin: 18px 0 10px; font-size: 30px; line-height: 1.2; font-weight: 700; color: #ffffff;">
                                Your seat is reserved
                            </h1>
                            <p style="margin: 0; font-size: 15px; line-height: 1.7; color: rgba(255,255,255,0.92);">
                                Thank you for registering{{ !empty($contact->webinar_title) ? ' for ' . $contact->webinar_title : '' }}.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 32px;">
                            <p style="margin: 0 0 16px; font-size: 16px; line-height: 1.7; color: #183153;">
                                Hello <strong>{{ $contact->fullname ?? $contact->name ?? 'Guest' }}</strong>,
                            </p>

                            <div style="margin-bottom: 24px; padding: 22px 24px; background-color: #f4f9ef; border: 1px solid #d8e8c1; border-radius: 18px;">
                                <p style="margin: 0 0 8px; font-size: 15px; line-height: 1.8; color: #27412a; font-weight: 700;">
                                    Registration successful
                                </p>
                                <p style="margin: 0; font-size: 15px; line-height: 1.8; color: #35524a;">
                                    We’re excited to have you with us. Please keep this email handy for webinar access details.
                                </p>
                            </div>

                            @if(!empty($contact->webinar_title))
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 24px; background-color: #ffffff; border: 1px solid #e6ecef; border-radius: 18px;">
                                    <tr>
                                        <td style="padding: 22px 24px;">
                                            <h2 style="margin: 0 0 12px; font-size: 18px; color: #10273a;">Registered Webinar</h2>
                                            <p style="margin: 0; font-size: 16px; line-height: 1.7; color: #183153; font-weight: 700;">
                                                {{ $contact->webinar_title }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            @if(!empty($contact->session_date) || !empty($contact->session_time) || !empty($contact->mode))
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 24px; background-color: #ffffff; border: 1px solid #e6ecef; border-radius: 18px;">
                                    <tr>
                                        <td style="padding: 22px 24px;">
                                            <h2 style="margin: 0 0 16px; font-size: 18px; color: #10273a;">Session Details</h2>
                                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                                @if(!empty($contact->session_date))
                                                    <tr>
                                                        <td style="padding: 0 0 10px; font-size: 13px; color: #6b7b6a; width: 32%;">Date</td>
                                                        <td style="padding: 0 0 10px; font-size: 14px; color: #183153; font-weight: 600;">{{ $contact->session_date }}</td>
                                                    </tr>
                                                @endif
                                                @if(!empty($contact->session_time))
                                                    <tr>
                                                        <td style="padding: 0 0 10px; font-size: 13px; color: #6b7b6a;">Time</td>
                                                        <td style="padding: 0 0 10px; font-size: 14px; color: #183153; font-weight: 600;">{{ $contact->session_time }}</td>
                                                    </tr>
                                                @endif
                                                @if(!empty($contact->mode))
                                                    <tr>
                                                        <td style="padding: 0; font-size: 13px; color: #6b7b6a;">Mode</td>
                                                        <td style="padding: 0; font-size: 14px; color: #183153; font-weight: 600;">{{ $contact->mode }}</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            @if(!empty($contact->meeting_link))
                                <div style="margin-bottom: 24px; background-color: #10273a; border-radius: 20px; overflow: hidden;">
                                    <div style="padding: 18px 24px; border-bottom: 1px solid rgba(255,255,255,0.12);">
                                        <h2 style="margin: 0; font-size: 18px; color: #ffffff;">Join Link</h2>
                                    </div>
                                    <div style="padding: 24px;">
                                        <p style="margin: 0 0 16px; font-size: 14px; line-height: 1.8; color: rgba(255,255,255,0.82);">
                                            Use the button below to join the webinar directly.
                                        </p>
                                        <div style="margin-bottom: 16px;">
                                            <a href="{{ $contact->meeting_link }}" target="_blank" style="display: inline-block; background-color: #7bba27; color: #ffffff; text-decoration: none; padding: 14px 22px; border-radius: 12px; font-size: 14px; font-weight: 700;">
                                                Join Webinar
                                            </a>
                                        </div>
                                        <p style="margin: 0; font-size: 13px; line-height: 1.8; color: rgba(255,255,255,0.72); word-break: break-all;">
                                            {{ $contact->meeting_link }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <div style="padding: 20px 24px; background-color: #fffdf7; border: 1px solid #efe4b5; border-radius: 18px;">
                                <p style="margin: 0 0 8px; font-size: 15px; line-height: 1.7; color: #7a5d00; font-weight: 700;">
                                    Before the session starts
                                </p>
                                <p style="margin: 0; font-size: 14px; line-height: 1.8; color: #5b6470;">
                                    Please check your internet connection, keep this join link accessible, and join a few minutes early for the best experience.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 32px 30px;">
                            <div style="border-top: 1px solid #e7eef0; padding-top: 22px; text-align: center;">
                                <p style="margin: 0 0 6px; font-size: 14px; color: #183153; font-weight: 700;">
                                    We look forward to seeing you,
                                </p>
                                <p style="margin: 0; font-size: 14px; color: #6b7280;">
                                    FFOI Team
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
