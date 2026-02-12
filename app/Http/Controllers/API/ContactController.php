<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactConfirmationMail;
use App\Mail\AdminContactNotificationMail;
use Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'fullname' => 'required|string|max:255',
                'email' => 'required|email',
                'contact' => 'nullable|string|max:20',
                'state' => 'nullable|string|max:100',
                'city' => 'nullable|string|max:100',
                'who_i_am' => 'nullable|string|max:255',
                'area_of_interest' => 'nullable|string|max:255',
                'message' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }

            // Save to DB
            $contact = Contact::create($request->all());

            // 1️⃣ Send confirmation to User
            Mail::to($contact->email)
                ->send(new ContactConfirmationMail($contact));

            // 2️⃣ Send notification to Admin
            Mail::to(config('mail.admin_email'))
                ->send(new AdminContactNotificationMail($contact));

            return response()->json([
                'status' => true,
                'message' => 'Contact form submitted successfully.',
                'data' => $contact
            ], 200);
    }
}
