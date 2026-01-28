@extends('layout.admin.index')
@section('page_title', 'Campuses')

@section('admin-main-content')
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <form action="{{ route('campuses.index') }}" method="GET" class="w-25 position-relative search-wrapper">
                <input type="text" name="search" class="form-control autoSearch" value="{{ request('search') }}"
                    data-delay="1000" data-min="3" placeholder="Search Campues..." />
                <span class="clear-search d-none"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; color:#999;">✖</span>
            </form>
            @can('campus.create')
                <a href="{{ route('campuses.create') }}" class="btn btn-primary">Add Campus</a>
            @endcan
        </div>

        <div class="card-datatable">

            @if ($campuses->count() > 0)
                <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="border-top">
                        <tr>
                            <th>Sr. No.</th>
                            <th>Campus Name</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($campuses as $campus)
                            @php $page = $campus->pages()->first(); @endphp

                            <tr>
                                <td class="py-1">{{ $loop->iteration }}</td>
                                <td class="py-1">{{ ucfirst($campus->name) }}</td>
                                <td class="py-1">
                                    @if ($campus->status == 1)
                                        <span class="badge bg-label-success rounded-pill">Active</span>
                                    @else
                                        <span class="badge bg-label-danger rounded-pill">Inactive</span>
                                    @endif
                                </td>
                                <td class="">{{ $campus->created_at->format('d M Y, h:i A') ?? '-'}}</td>
                                <td class="py-1">
                                    <div class="d-inline-block text-nowrap">
                                        @php
                                            $canDuplicate = auth()->user()->can('campus.create');
                                            $canEdit = auth()->user()->can('campus.edit');
                                            $canPageEdit = auth()->user()->can('page.edit');
                                        @endphp

                                        @if ($canEdit || $canDuplicate || $canPageEdit)
                                            @can('campus.edit')
                                                <a href="{{ route('campuses.edit', $campus->id) }}"
                                                    class="btn btn-text-secondary rounded-pill btn-icon"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-original-title="Edit Campus">
                                                    <i class="icon-base ti tabler-edit icon-22px"></i>
                                                </a>
                                            @endcan

                                            @can('page.edit')
                                                <a href="{{ $page ? route('pages.edit', $page->id) : 'javascript:void(0)' }}"
                                                    class="btn btn-text-secondary rounded-pill btn-icon"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-original-title="Edit Page">
                                                    <i class="icon-base ti tabler-file-pencil icon-22px"></i>
                                                </a>
                                            @endcan

                                            @can('campus.create')
                                                <button type="button"
                                                    class="btn btn-text-secondary rounded-pill btn-icon duplicateBtn"
                                                    data-id="{{ $campus->id }}" data-name="{{ $campus->name }}"
                                                    data-action="/campuses" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-original-title="Duplicate Campus">
                                                    <i class="icon-base ti tabler-copy icon-22px"></i>
                                                </button>
                                            @endcan
                                            @can('campus.edit')
                                                <button type="button"
                                                    class="btn btn-text-danger rounded-pill btn-icon deleteBtn"
                                                    data-id="{{ $campus->id }}" data-name="{{ $campus->name }}"
                                                    data-action="/campuses" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-original-title="Delete Campus">
                                                    <i class="icon-base ti tabler-trash icon-22px text-danger"></i>
                                                </button>
                                            @endcan
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                    
                </div>
                <div class="d-flex justify-content-end mt-3 me-3">
                    {{ $campuses->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">No campuses found.</h5>
                </div>
            @endif

        </div>
    </div>
@endsection
