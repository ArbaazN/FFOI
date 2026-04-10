<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\PartnerEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:enquiry.view')->only(['contactUs', 'partnerWithUs', 'showContactUs', 'showPartnerWithUs']);
    }

    public function contactUs(Request $request)
    {
        try {
            $search = $request->input('search');

            $enquiries = Contact::query()
                ->when($search, function ($query, $search) {
                    $query->where(function ($builder) use ($search) {
                        $builder->where('fullname', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('contact', 'LIKE', "%{$search}%")
                            ->orWhere('city', 'LIKE', "%{$search}%")
                            ->orWhere('state', 'LIKE', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(config('pagination.per_page'));

            $enquiries->appends(['search' => $search]);

            return view('admin.enquiries.contact-us', compact('enquiries', 'search'));
        } catch (\Throwable $e) {
            Log::error('Contact Us enquiry list error: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Unable to load Contact Us enquiries.');
        }
    }

    public function partnerWithUs(Request $request)
    {
        try {
            $search = $request->input('search');

            $enquiries = PartnerEnquiry::query()
                ->when($search, function ($query, $search) {
                    $query->where(function ($builder) use ($search) {
                        $builder->where('fullname', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('contact', 'LIKE', "%{$search}%")
                            ->orWhere('city', 'LIKE', "%{$search}%")
                            ->orWhere('preferred_territory', 'LIKE', "%{$search}%")
                            ->orWhere('current_occupation_business', 'LIKE', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(config('pagination.per_page'));

            $enquiries->appends(['search' => $search]);

            return view('admin.enquiries.partner-with-us', compact('enquiries', 'search'));
        } catch (\Throwable $e) {
            Log::error('Partner With Us enquiry list error: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Unable to load Partner With Us enquiries.');
        }
    }

    public function showContactUs($id)
    {
        try {
            $enquiry = Contact::findOrFail($id);

            return view('admin.enquiries.show-contact-us', compact('enquiry'));
        } catch (\Throwable $e) {
            Log::error('Contact Us enquiry detail error: '.$e->getMessage(), [
                'enquiry_id' => $id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Unable to load Contact Us enquiry details.');
        }
    }

    public function showPartnerWithUs($id)
    {
        try {
            $enquiry = PartnerEnquiry::findOrFail($id);

            return view('admin.enquiries.show-partner-with-us', compact('enquiry'));
        } catch (\Throwable $e) {
            Log::error('Partner With Us enquiry detail error: '.$e->getMessage(), [
                'enquiry_id' => $id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Unable to load Partner With Us enquiry details.');
        }
    }
}
