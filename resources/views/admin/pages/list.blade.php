@extends('layout.admin.index')

@section('page_title', 'Pages List')

@section('admin-main-content')
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <form action="{{ route('pages.index') }}" method="GET" class="w-25 position-relative search-wrapper">
                <input type="text" name="search" class="form-control autoSearch" value="{{ request('search') }}"
                    data-delay="1000" data-min="3" placeholder="Search Pages..." />
                <span class="clear-search d-none"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; color:#999;">✖</span>
            </form>
        </div>
        <div class="card-datatable">
            @if ($pages->count() > 0)
                <div class="table-responsive text-nowrap">
                    <table class="datatables-users table">
                        <thead class="border-top">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages as $page)
                                <tr>
                                    <td class="py-1">{{ $loop->iteration }}</td>
                                    <td class="py-1">{{ ucfirst($page->title) }}</td>
                                    <td class="py-1">/{{ $page->slug }}</td>
                                    <td class="py-1">{{ $page->updated_at->format('d M Y, h:i A') ?? '-'}}</td>
                                    <td class="py-1">
                                        <div class="d-inline-block text-nowrap">
                                            @if(auth()->user()->can('page.edit'))
                                            <a href="{{ route('pages.edit', $page->id) }}"
                                                class="btn btn-text-secondary rounded-pill btn-icon" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-original-title="Edit Page">
                                                <i class="icon-base ti tabler-file-pencil icon-22px"></i>
                                            </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                            {{-- <a href="{{ route('page.edit', $page->id) }}"
                                                class="btn btn-text-secondary rounded-pill btn-icon">
                                                <i class="icon-base ti tabler-list-check icon-22px"></i>
                                            </a> --}}
                                        </div>
                                        {{-- Add more actions if needed --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-1" colspan="5" class="text-center">No program found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3 me-3">
                        {{ $pages->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="p-4 text-center">
                    <h5>No Pages Found</h5>
                </div>
            @endif
        </div>
    </div>
@endsection