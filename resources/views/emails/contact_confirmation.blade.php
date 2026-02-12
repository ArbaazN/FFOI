<!DOCTYPE html>
<html>
<head>
    <title>Contact Confirmation</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border:1px solid grey;">
          <!-- Logo Header -->
        <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #7bba27;">
            <h1 style="color: #7bba27; margin: 0;">FFOI</h1>
        </div>
        
        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <h2 style="color: #7bba27; margin-top: 30px;">Hello {{ $contact->fullname }},</h2>
            <p>Thank you for contacting us. We have received your message.</p>
            @if(!empty($contact->message))
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
                <h3 style="color: #0b1d2e; margin-top: 0;">Message</h3>
                <p style="white-space: pre-wrap;">{{ $contact->message }}</p>
            </div>
            @endif
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
            <p style="color: #64748B; font-size: 12px;">
                We will get back to you soon.<br>
                Thanks & Regards,<br>FFOI Team
            </p>
            </div>
        </div>
        
    </div>
</body>
</html>
