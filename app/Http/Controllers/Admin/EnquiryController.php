<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
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
        return $this->renderList($request, 'contact_us', 'admin.enquiries.contact-us', 'Contact Us');
    }

    public function partnerWithUs(Request $request)
    {
        return $this->renderList($request, 'partner_with_us', 'admin.enquiries.partner-with-us', 'Partner With Us');
    }

    public function showContactUs($id)
    {
        return $this->renderShow($id, 'contact_us', 'admin.enquiries.show-contact-us', 'Contact Us');
    }

    public function showPartnerWithUs($id)
    {
        return $this->renderShow($id, 'partner_with_us', 'admin.enquiries.show-partner-with-us', 'Partner With Us');
    }

    protected function renderList(Request $request, string $source, string $view, string $title)
    {
        try {
            $search = $request->input('search');

            $enquiries = Contact::query()
                ->where('source', $source)
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

            return view($view, compact('enquiries', 'search', 'title'));
        } catch (\Throwable $e) {
            Log::error("{$title} enquiry list error: ".$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', "Unable to load {$title} enquiries.");
        }
    }

    protected function renderShow(int $id, string $source, string $view, string $title)
    {
        try {
            $enquiry = Contact::where('source', $source)->findOrFail($id);

            return view($view, compact('enquiry', 'title'));
        } catch (\Throwable $e) {
            Log::error("{$title} enquiry detail error: ".$e->getMessage(), [
                'enquiry_id' => $id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', "Unable to load {$title} enquiry details.");
        }
    }
}
