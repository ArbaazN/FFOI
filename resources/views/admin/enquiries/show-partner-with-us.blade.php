@extends('layout.admin.index')

@section('page_title', 'View Partner With Us Enquiry')

@section('admin-main-content')
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Partner With Us Enquiry Details</h5>
            <a href="{{ route('enquiries.partner-with-us') }}" class="btn btn-primary">Back</a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Name</label>
                    <p class="mb-0">{{ $enquiry->fullname ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <p class="mb-0">{{ $enquiry->email ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mobile</label>
                    <p class="mb-0">{{ $enquiry->contact ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Date</label>
                    <p class="mb-0">{{ optional($enquiry->created_at)->format('d M Y, h:i A') ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">City</label>
                    <p class="mb-0">{{ $enquiry->city ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Preferred Territory</label>
                    <p class="mb-0">{{ $enquiry->preferred_territory ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Current Occupation / Business</label>
                    <p class="mb-0">{{ $enquiry->current_occupation_business ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Consent</label>
                    <p class="mb-0">{{ $enquiry->consent ? 'Yes' : 'No' }}</p>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Why do you want to become an FFOI Partner?</label>
                    <div class="border rounded p-3 bg-light" style="white-space: pre-wrap;">{{ $enquiry->partner_reason ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
