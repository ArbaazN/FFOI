@extends('layout.admin.index')

@section('page_title', 'Partner With Us Enquiries')

@section('admin-main-content')
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <form action="{{ route('enquiries.partner-with-us') }}" method="GET" class="w-25 position-relative search-wrapper">
                <input type="text" name="search" class="form-control autoSearch" value="{{ request('search') }}"
                    data-delay="1000" data-min="3" placeholder="Search Partner Enquiries..." />
                <span class="clear-search d-none"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; color:#999;">x</span>
            </form>
        </div>

        <div class="card-datatable">
            @if ($enquiries->count() > 0)
                <div class="table-responsive text-nowrap">
                    <table class="datatables-users table">
                        <thead class="border-top">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>City / State</th>
                                <th>Organisation Type</th>
                                <th>Area of Interest</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enquiries as $enquiry)
                                <tr>
                                    <td>{{ ($enquiries->firstItem() ?? 0) + $loop->index }}</td>
                                    <td>{{ $enquiry->fullname }}</td>
                                    <td>{{ $enquiry->email ?? '-' }}</td>
                                    <td>{{ $enquiry->contact ?? '-' }}</td>
                                    <td>{{ trim(($enquiry->city ?? '').(!empty($enquiry->city) && !empty($enquiry->preferred_territory) ? ', ' : '').($enquiry->preferred_territory ?? '')) ?: '-' }}</td>
                                    <td>{{ $enquiry->current_occupation_business ?? '-' }}</td>
                                    <td>-</td>
                                    <td>{{ $enquiry->partner_reason ? \Illuminate\Support\Str::words($enquiry->partner_reason, 3, '...') : '-' }}</td>
                                    <td>{{ optional($enquiry->created_at)->format('d M Y, h:i A') ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('enquiries.partner-with-us.show', $enquiry->id) }}"
                                            class="btn btn-text-secondary rounded-pill btn-icon"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Enquiry">
                                            <i class="icon-base ti tabler-eye icon-22px"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No partner enquiries found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3 me-3">
                        {{ $enquiries->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="p-4 text-center">
                    <h5>No Partner With Us enquiries found.</h5>
                </div>
            @endif
        </div>
    </div>
@endsection
