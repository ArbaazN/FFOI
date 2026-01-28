@extends('layout.admin.index')

@section('page_title', 'Blog Categories')

@section('admin-main-content')

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <form action="{{ route('blog.categories.index') }}" method="GET"
                class="w-25 position-relative search-wrapper">
                <input type="text" name="search" class="form-control autoSearch" value="{{ request('search') }}"
                    data-delay="1000" data-min="3" placeholder="Search Category..." />
                <span class="clear-search d-none"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; color:#999;">✖</span>
            </form>
            @can('blog.create')
                <a href="{{ route('blog.categories.create') }}" class="btn btn-primary">Add Category</a>
            @endcan
        </div>

        <div class="card-datatable">
            @if ($categories->count() > 0)
                <table class="datatables-users table">
                    <thead class="border-top">
                        <tr>
                            <th>Sr.</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($categories as $row)
                            <tr>
                                <td class="py-1">{{ $loop->iteration }}</td>
                                <td class="py-1">{{ $row->name }}</td>
                                <td class="py-1">
                                    {!! $row->status == 1
                                        ? '<span class="badge bg-label-success rounded-pill">Active</span>'
                                        : '<span class="badge bg-label-danger rounded-pill">Inactive</span>' !!}
                                </td>
                                <td class="">{{ $row->created_at->format('d M Y, h:i A') ?? '-'}}</td>
                                <td class="py-1">
                                    <div class="d-inline-block text-nowrap">
                                        @php
                                            $canEdit = auth()->user()->can('blog.edit');
                                            $canShow = auth()->user()->can('blog.view');
                                        @endphp

                                        @if ($canEdit || $canShow)
                                            @can('blog.edit')
                                                <a href="{{ route('blog.categories.edit', $row->id) }}"
                                                    class="btn btn-text-secondary rounded-pill btn-icon"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-original-title="Edit Blog">
                                                    <i class="icon-base ti tabler-edit icon-22px"></i>
                                                </a>
                                            @endcan
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No Category found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3 me-3">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">No Category found.</h5>
                </div>
            @endif
        </div>
    </div>

@endsection