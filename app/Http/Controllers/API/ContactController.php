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

    public function index()
    {
        try {
            $contacts = Contact::latest()->get();
            return response()->json([
                'status' => true,
                'message' => 'Contacts fetched successfully.',
                'data' => $contacts
            ], 200);
        } catch (\Exception $e) {

           \Log::error('Contact Fetch Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try{
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

            $contact = Contact::create($request->all());

            Mail::to($contact->email)
                ->send(new ContactConfirmationMail($contact));

            Mail::to(config('mail.admin_email'))
                ->send(new AdminContactNotificationMail($contact));

            return response()->json([
                'status' => true,
                'message' => 'Contact form submitted successfully.',
                'data' => $contact
            ], 200);
        } catch (\Exception $e) {

            // Log error for debugging
            Log::error('Contact API Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
    
}
