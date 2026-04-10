<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\AdminContactNotificationMail;
use App\Mail\ContactConfirmationMail;
use App\Models\Contact;
use App\Models\PartnerEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
                'data' => $contacts,
            ], 200);
        } catch (\Exception $e) {
            Log::channel('api')->error('Contact Fetch Error: '.$e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->contactRules());

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 422);
            }

            $contact = $this->saveContactEnquiry($request);

            return response()->json([
                'status' => true,
                'message' => 'Contact form submitted successfully.',
                'data' => $contact,
            ], 200);
        } catch (\Exception $e) {
            Log::channel('api')->error('Contact API Error: '.$e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function partnerStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->partnerRules());

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 422);
            }

            $partnerEnquiry = $this->savePartnerEnquiry($request);

            return response()->json([
                'status' => true,
                'message' => 'Partner with us form submitted successfully.',
                'data' => $partnerEnquiry,
            ], 200);
        } catch (\Exception $e) {
            Log::channel('api')->error('Partner With Us API Error: '.$e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    protected function contactRules(): array
    {
        return [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact' => 'nullable|string|max:20',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'who_i_am' => 'nullable|string|max:255',
            'area_of_interest' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ];
    }

    protected function partnerRules(): array
    {
        return [
            'fullname' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'who_i_am' => 'required|string|max:255',
            'message' => 'required|string',
            'consent' => 'required|accepted',
        ];
    }

    protected function saveContactEnquiry(Request $request): Contact
    {
        $contact = Contact::create($request->except('consent'));

        $this->sendEnquiryEmails($contact, [
            'contact_id' => $contact->id,
            'contact_email' => $contact->email,
            'type' => 'contact_us',
        ]);

        return $contact;
    }

    protected function savePartnerEnquiry(Request $request): PartnerEnquiry
    {
        $partnerEnquiry = PartnerEnquiry::create([
            'fullname' => $request->fullname,
            'contact' => $request->contact,
            'email' => $request->email,
            'preferred_territory' => $request->state,
            'city' => $request->city,
            'current_occupation_business' => $request->who_i_am,
            'partner_reason' => $request->message,
            'consent' => $request->boolean('consent'),
        ]);

        $this->sendEnquiryEmails($partnerEnquiry, [
            'partner_enquiry_id' => $partnerEnquiry->id,
            'partner_email' => $partnerEnquiry->email,
            'type' => 'partner_with_us',
        ]);

        return $partnerEnquiry;
    }

    protected function sendEnquiryEmails(object $enquiry, array $warningContext): void
    {
        $adminEmail = config('mail.admin_email');

        Mail::to($enquiry->email)
            ->send(new ContactConfirmationMail($enquiry));

        if (! empty($adminEmail)) {
            Mail::to($adminEmail)
                ->send(new AdminContactNotificationMail($enquiry));
        } else {
            Log::channel('api')->warning('Enquiry admin email is not configured.', $warningContext);
        }
    }
}
