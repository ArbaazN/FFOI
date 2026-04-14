@extends('layout.admin.index')

@section('page_title', 'Webinar Registrations')

@section('admin-main-content')

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">{{ $webinar->title }}</h5>
                <small class="text-muted">Registration list for this webinar</small>
            </div>
            <form action="{{ route('webinar.registrations', $webinar->id) }}" method="GET" class="w-25">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Search registrations..." />
            </form>
        </div>

        <div class="card-datatable">
            @if ($registrations->count() > 0)
                <table class="datatables-users table">
                    <thead class="border-top">
                        <tr>
                            <th>Sr.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>State</th>
                            <th>City</th>
                            <th>I am a</th>
                            <th>Area of Interest</th>
                            <th>Message</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrations as $registration)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $registration->name }}</td>
                                <td>{{ $registration->email }}</td>
                                <td>{{ $registration->contact ?: '-' }}</td>
                                <td>{{ $registration->state ?: '-' }}</td>
                                <td>{{ $registration->city ?: '-' }}</td>
                                <td>{{ $registration->current_status ?: '-' }}</td>
                                <td>{{ $registration->topic_interested_in ?: '-' }}</td>
                                <td>{{ $registration->message ?: '-' }}</td>
                                <td>{{ $registration->created_at?->format('d M Y, h:i A') ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3 me-3">
                    {{ $registrations->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">No registrations found for this webinar.</h5>
                </div>
            @endif
        </div>
    </div>

@endsection
