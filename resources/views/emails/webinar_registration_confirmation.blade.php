<!DOCTYPE html>
<html>
<head>
    <title>Webinar Registration Confirmation</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border:1px solid grey;">
        <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #7bba27;">
            <h1 style="color: #7bba27; margin: 0;">FFOI</h1>
        </div>

        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <h2 style="color: #7bba27; margin-top: 30px;">Hello {{ $contact->fullname ?? $contact->name ?? 'Guest' }},</h2>
            <p>Thank you for registering for our webinar{{ !empty($contact->webinar_title) ? ': ' . $contact->webinar_title : '' }}.</p>

            @if(!empty($contact->meeting_link))
            <div style="background-color: #ffffff; padding: 20px; border-radius: 10px; margin: 20px 0; border: 1px solid #e0e0e0;">
                <h3 style="color: #0b1d2e; margin-top: 0;">Meeting Link</h3>
                <p style="margin-bottom: 8px;">You can join the webinar using the link below:</p>
                <p style="word-break: break-all; margin: 0;">
                    <a href="{{ $contact->meeting_link }}" target="_blank" style="color: #7bba27;">{{ $contact->meeting_link }}</a>
                </p>
            </div>
            @endif

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                <p style="color: #64748B; font-size: 12px;">
                    We look forward to seeing you in the webinar.<br>
                    Thanks & Regards,<br>FFOI Team
                </p>
            </div>
        </div>
    </div>
</body>
</html>
