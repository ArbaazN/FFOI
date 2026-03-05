@extends('layout.admin.index')

@section('page_title', 'Webinar List')

@section('admin-main-content')

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <form action="{{ route('webinar.list') }}" method="GET"
                class="w-25 position-relative search-wrapper">
                <input type="text" name="search" class="form-control autoSearch" value="{{ request('search') }}"
                    data-delay="1000" data-min="3" placeholder="Search Webinar Title..." />
                <span class="clear-search d-none"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; color:#999;">✖</span>
            </form>
            <a href="{{ route('webinar.create') }}" class="btn btn-primary">Add Webinar</a>
        </div>

        <div class="card-datatable">
            @if ($webinar->count() > 0)
                <table class="datatables-users table">
                    <thead class="border-top">
                        <tr>
                            <th>Sr.</th>
                            <th>Title Name</th>
                            <th>Slug</th>
                            <th>Subtitle</th>
                            <th>Short Desc</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($webinar as $row)
                            <tr>
                                <td class="py-1">{{ $loop->iteration }}</td>
                                <td class="py-1">{{ $row->title }}</td>
                                <td class="py-1">{{ $row->slug }}</td>
                                <td class="py-1">{{ $row->subtitle }}</td>
                                <td class="py-1">{{ $row->short_desc }}</td>
                                <td class="">{{ $row->created_at->format('d M Y, h:i A') ?? '-'}}</td>
                                <td class="py-1">
                                    <div class="d-inline-block text-nowrap">
                                        <a href="{{ route('webinar.create', $row->id) }}"
                                            class="btn btn-text-secondary rounded-pill btn-icon"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-original-title="Edit Session">
                                            <i class="icon-base ti tabler-edit icon-22px"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No webinar found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3 me-3">
                    {{ $webinar->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">No webinar found.</h5>
                </div>
            @endif
        </div>
    </div>

@endsection