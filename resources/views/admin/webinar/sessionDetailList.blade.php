@extends('layout.admin.index')

@section('page_title', 'Upcoming Sessions Details')

@section('admin-main-content')

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <form action="{{ route('webinar.session.detail.list') }}" method="GET"
                class="w-50 d-flex gap-2 align-items-center">
                <div class="w-100 position-relative search-wrapper">
                    <input type="text" name="search" class="form-control autoSearch" value="{{ request('search') }}"
                        data-delay="1000" data-min="3" placeholder="Search Topic Name..." />
                    <span class="clear-search d-none"
                        style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; color:#999;">x</span>
                </div>
                <select name="webinar_type" class="w-25 form-select" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <option value="upcoming" {{ request('webinar_type') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="other" {{ request('webinar_type') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </form>
            <a href="{{ route('webinar.session.detail.create') }}" class="btn btn-primary">Add Session details</a>
        </div>

        <div class="card-datatable">
            @if ($sessions->count() > 0)
                <table class="datatables-users table">
                    <thead class="border-top">
                        <tr>
                            <th>Sr.</th>
                            <th>Session Name</th>
                            <th>Type</th>
                            <th>Slug</th>
                            <th>Topic Name</th>
                            <th>Title</th>
                            <th>Registrations</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($sessions as $row)
                            <tr>
                                <td class="py-1">{{ $loop->iteration }}</td>
                                <td class="py-1">{{ $row->category->session_name ?? 'N/A' }}</td>
                                <td class="py-1 text-capitalize">{{ $row->webinar_type ?? '-' }}</td>
                                <td class="py-1">{{ $row->slug ?? 'N/A' }}</td>
                                <td class="py-1">{{ $row->topic_name }}</td>
                                <td class="py-1">{{ $row->title }}</td>
                                <td class="py-1">{{ $row->registration_count ?? 0 }}</td>
                                <td>{{ $row->created_at->format('d M Y, h:i A') ?? '-' }}</td>
                                <td class="py-1">
                                    <div class="d-inline-block text-nowrap">
                                        <a href="{{ route('webinar.session.detail.edit', $row->id) }}"
                                            class="btn btn-text-secondary rounded-pill btn-icon"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-original-title="Edit Session">
                                            <i class="icon-base ti tabler-edit icon-22px"></i>
                                        </a>
                                        <a href="{{ route('webinar.session.detail.registrations', $row->id) }}"
                                            class="btn btn-text-secondary rounded-pill btn-icon"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-original-title="View Registrations">
                                            <i class="icon-base ti tabler-eye icon-22px"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-text-danger rounded-pill btn-icon deleteBtn"
                                            data-id="{{ $row->id }}" data-name="{{ $row->topic_name }}"
                                            data-action="/webinar/session-details"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-original-title="Delete Session Detail">
                                            <i class="icon-base ti tabler-trash icon-22px text-danger"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No Session found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3 me-3">
                    {{ $sessions->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">No Session found.</h5>
                </div>
            @endif
        </div>
    </div>

@endsection
