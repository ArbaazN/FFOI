<!DOCTYPE html>
<html>
<head>
    <title>{{ $type === 'partner_with_us' ? 'New Partner With Us Submission' : 'New Contact Submission' }}</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border:1px solid grey;">
          <!-- Logo Header -->
        <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #7bba27;">
        <h1 style="color: #7bba27; margin: 0;">FFOI</h1>
        </div>
        
        <h2 style="color: #7bba27; margin-top: 30px;">
            {{ $type === 'partner_with_us' ? 'New Partner With Us Submission' : 'New Contact Form Submission' }}
        </h2>
        
        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
        <h3 style="color: #0b1d2e; margin-top: 0;">Contact Information</h3>
            @if(!empty($contact->fullname))<p><strong>Full Name:</strong> {{ $contact->fullname }}</p>@endif
            @if(!empty($contact->contact))<p><strong>Mobile Number:</strong> {{ $contact->contact }}</p>@endif
            @if(!empty($contact->email))<p><strong>Email Address:</strong> {{ $contact->email }}</p>@endif
            @if($type === 'partner_with_us')
                @if(!empty($contact->preferred_territory))<p><strong>State (Preferred Territory):</strong> {{ $contact->preferred_territory }}</p>@endif
                @if(!empty($contact->city))<p><strong>City:</strong> {{ $contact->city }}</p>@endif
                <p><strong>Consent:</strong> {{ !empty($contact->consent) ? 'Yes' : 'No' }}</p>
            @elseif(!empty($contact->city))
                <p><strong>Location:</strong> {{ $contact->city }}, {{ $contact->state }}</p>
            @endif
        </div>

        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
        <h3 style="color: #0b1d2e; margin-top: 0;">Enquiry Details</h3>
            @if($type === 'partner_with_us')
                @if(!empty($contact->current_occupation_business))<p><strong>Current Occupation / Business:</strong> {{ $contact->current_occupation_business }}</p>@endif
            @else
                @if(!empty($contact->who_i_am))<p><strong>User Type:</strong> {{ $contact->who_i_am }}</p>@endif
                @if(!empty($contact->area_of_interest))<p><strong>Area of Interest:</strong> {{ $contact->area_of_interest }}</p>@endif
            @endif
        </div>

        @if($type === 'partner_with_us' && !empty($contact->partner_reason))
        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <h3 style="color: #0b1d2e; margin-top: 0;">Why do you want to become an FFOI Partner?</h3>
            <p style="white-space: pre-wrap;">{{ $contact->partner_reason }}</p>
        </div>
        @endif

        @if($type !== 'partner_with_us' && !empty($contact->message))
        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <h3 style="color: #0b1d2e; margin-top: 0;">Message</h3>
            <p style="white-space: pre-wrap;">{{ $contact->message }}</p>
        </div>
        @endif
       
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
        <p style="color: #64748B; font-size: 12px;">
            This email was sent from the FFOI {{ $type === 'partner_with_us' ? 'Partner With Us' : 'Contact' }} form.
        </p>
        </div>
    </div>
</body>
</html>
