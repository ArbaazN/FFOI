<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner With Us Confirmation</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f6ea; font-family: Arial, sans-serif; color: #183153;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background: linear-gradient(180deg, #f3f6ea 0%, #fbfcf7 100%);">
        <tr>
            <td align="center" style="padding: 32px 16px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width: 680px; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 18px 45px rgba(15, 42, 29, 0.12);">
                    <tr>
                        <td style="background: linear-gradient(135deg, #183153 0%, #7bba27 100%); padding: 28px 32px 36px;">
                            <div style="display: inline-block; padding: 8px 14px; border: 1px solid rgba(255,255,255,0.28); border-radius: 999px; font-size: 12px; letter-spacing: 1.2px; text-transform: uppercase; color: #ffffff;">
                                Partnership Enquiry Received
                            </div>
                            <h1 style="margin: 18px 0 10px; font-size: 30px; line-height: 1.2; font-weight: 700; color: #ffffff;">
                                Thank you for your interest in partnering with FFOI
                            </h1>
                            <p style="margin: 0; font-size: 15px; line-height: 1.7; color: rgba(255,255,255,0.9);">
                                We have received your partnership request and our team will connect with you soon.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 32px;">
                            <p style="margin: 0 0 16px; font-size: 16px; line-height: 1.7; color: #183153;">
                                Hello <strong>{{ $contact->fullname ?? 'Partner' }}</strong>,
                            </p>

                            <div style="margin-bottom: 24px; padding: 22px 24px; background-color: #f4f9ef; border: 1px solid #d8e8c1; border-radius: 18px;">
                                <p style="margin: 0; font-size: 15px; line-height: 1.8; color: #27412a;">
                                    Your “Partner With Us” enquiry has been submitted successfully. We appreciate your interest in growing with FFOI.
                                </p>
                            </div>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 24px; background-color: #ffffff; border: 1px solid #e6ece2; border-radius: 18px;">
                                <tr>
                                    <td style="padding: 22px 24px;">
                                        <h2 style="margin: 0 0 16px; font-size: 18px; color: #0f2a1d;">Submitted Details</h2>

                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding: 0 0 10px; font-size: 13px; color: #6b7b6a; width: 38%;">Full Name</td>
                                                <td style="padding: 0 0 10px; font-size: 14px; color: #183153; font-weight: 600;">{{ $contact->fullname ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 0 0 10px; font-size: 13px; color: #6b7b6a;">Email</td>
                                                <td style="padding: 0 0 10px; font-size: 14px; color: #183153; font-weight: 600;">{{ $contact->email ?? '-' }}</td>
                                            </tr>
                                            @if(!empty($contact->contact))
                                                <tr>
                                                    <td style="padding: 0 0 10px; font-size: 13px; color: #6b7b6a;">Contact</td>
                                                    <td style="padding: 0 0 10px; font-size: 14px; color: #183153; font-weight: 600;">{{ $contact->contact }}</td>
                                                </tr>
                                            @endif
                                            @if(!empty($contact->preferred_territory))
                                                <tr>
                                                    <td style="padding: 0 0 10px; font-size: 13px; color: #6b7b6a;">Preferred Territory</td>
                                                    <td style="padding: 0 0 10px; font-size: 14px; color: #183153; font-weight: 600;">{{ $contact->preferred_territory }}</td>
                                                </tr>
                                            @endif
                                            @if(!empty($contact->city))
                                                <tr>
                                                    <td style="padding: 0 0 10px; font-size: 13px; color: #6b7b6a;">City</td>
                                                    <td style="padding: 0 0 10px; font-size: 14px; color: #183153; font-weight: 600;">{{ $contact->city }}</td>
                                                </tr>
                                            @endif
                                            @if(!empty($contact->current_occupation_business))
                                                <tr>
                                                    <td style="padding: 0; font-size: 13px; color: #6b7b6a;">Current Occupation / Business</td>
                                                    <td style="padding: 0; font-size: 14px; color: #183153; font-weight: 600;">{{ $contact->current_occupation_business }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            @if(!empty($contact->partner_reason))
                                <div style="margin-bottom: 24px; background-color: #fffdf7; border: 1px solid #efe4b5; border-radius: 18px;">
                                    <div style="padding: 18px 24px; border-bottom: 1px solid #f2e9c6;">
                                        <h2 style="margin: 0; font-size: 18px; color: #0f2a1d;">Your Partnership Message</h2>
                                    </div>
                                    <div style="padding: 22px 24px;">
                                        <p style="margin: 0; font-size: 15px; line-height: 1.8; color: #334155; white-space: pre-wrap;">{{ $contact->partner_reason }}</p>
                                    </div>
                                </div>
                            @endif

                            <div style="padding: 20px 24px; background-color: #183153; border-radius: 18px;">
                                <p style="margin: 0 0 8px; font-size: 15px; line-height: 1.7; color: #ffffff; font-weight: 700;">
                                    What happens next?
                                </p>
                                <p style="margin: 0; font-size: 14px; line-height: 1.8; color: rgba(255,255,255,0.86);">
                                    Our team will review your partnership enquiry and get in touch to discuss the next steps and collaboration opportunities.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 32px 30px;">
                            <div style="border-top: 1px solid #e7eee2; padding-top: 22px; text-align: center;">
                                <p style="margin: 0 0 6px; font-size: 14px; color: #183153; font-weight: 700;">
                                    Thanks & Regards,
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
