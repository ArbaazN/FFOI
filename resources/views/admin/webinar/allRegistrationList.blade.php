@extends('layout.admin.index')

@section('page_title', 'Webinar Registrations')

@section('admin-main-content')
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <form action="{{ route('webinar.registration.list') }}" method="GET" class="d-flex gap-2 align-items-center">
                <div class="position-relative search-wrapper">
                    <input type="text" name="search" class="form-control autoSearch" value="{{ request('search') }}"
                        data-delay="1000" data-min="3" placeholder="Search Registration..." />
                    <span class="clear-search d-none"
                        style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; color:#999;">x</span>
                </div>
                {{-- <input type="date" name="date" class="form-control" value="{{ request('date') }}" />
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->filled('search') || request()->filled('date'))
                    <a href="{{ route('webinar.registration.list') }}" class="btn btn-label-secondary">Reset</a>
                @endif --}}
            </form>
        </div>

        <div class="card-datatable">
            @if ($registrations->count() > 0)
                <div class="table-responsive text-nowrap">
                <table class="datatables-users table">
                    <thead class="border-top">
                        <tr>
                            <th>Sr.</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>State</th>
                            <th>City</th>
                            <th>I am a</th>
                            <th>Area of Interest</th>
                            <th>Message</th>
                            <th>Session / Webinar</th>
                            <th>Session Date</th>
                            <th>Registered At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrations as $registration)
                            @php
                                $sessionLabel = $registration->session?->topic_name
                                    ?? $registration->webinar?->title
                                    ?? '-';
                            @endphp
                            <tr>
                                <td>{{ $registrations->firstItem() + $loop->index }}</td>
                                <td>{{ $registration->name }}</td>
                                <td>{{ $registration->contact ?: '-' }}</td>
                                <td>{{ $registration->email }}</td>
                                <td>{{ $registration->state ?: '-' }}</td>
                                <td>{{ $registration->city ?: '-' }}</td>
                                <td>{{ $registration->current_status ?: '-' }}</td>
                                <td>{{ $registration->topic_interested_in ?: '-' }}</td>
                                <td>{{ $registration->message ?: '-' }}</td>
                                <td>{{ $sessionLabel }}</td>
                                <td>{{ $registration->session?->date?->format('d M Y') ?: '-' }}</td>
                                <td>{{ $registration->created_at?->format('d M Y, h:i A') ?: '-' }}</td>
                                <td>
                                    <button type="button"
                                        class="btn btn-text-danger rounded-pill btn-icon deleteBtn"
                                        data-id="{{ $registration->id }}" data-name="{{ $registration->name }}"
                                        data-action="/webinar/registrations"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Delete Registration">
                                        <i class="icon-base ti tabler-trash icon-22px text-danger"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <div class="d-flex justify-content-end mt-3 me-3">
                    {{ $registrations->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">No registrations found.</h5>
                </div>
            @endif
        </div>
    </div>

@endsection
