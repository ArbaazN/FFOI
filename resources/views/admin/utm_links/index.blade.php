@extends('layout.admin.index')

@section('page_title', 'UTM Links')

@section('admin-main-content')

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <form action="{{ route('utm-links.index') }}" method="GET" class="w-25 position-relative search-wrapper">
                <input type="text" name="search" class="form-control autoSearch" value="{{ request('search') }}"
                    data-delay="1000" data-min="3" placeholder="Search..." />
                <span class="clear-search d-none"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; color:#999;">✖</span>
            </form>
            @can('utm.create')
            <a href="{{ route('utm-links.create') }}" class="btn btn-primary">
                New UTM Link
            </a>
            @endcan
        </div>

        <div class="card-datatable">
            @if ($links->count() === 0)
                <div class="text-center py-5">
                    <h5 class="text-muted">No UTM links created yet.</h5>
                </div>
            @else
                
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th>SR.NO</th>
                        <th>Name</th>
                        <th>Base URL</th>
                        <th>Source</th>
                        <th>Medium</th>
                        <th>Campaign</th>
                        <th>Active</th>
                        <th>Created</th>
                        <th width="130">Actions</th>
                    </tr>
                </thead>
                    
                <tbody>
                    @foreach ($links as $index => $link)
                    <tr>
                        <td class="py-1">{{ $links->firstItem() + $index }}</td>
                        <td class="py-1">{{ $link->name }}</td>
                        <td class="py-1" style="max-width: 220px; word-break: break-all;">
                            <small>{{ $link->original_url }}</small>
                        </td>
                        <td class="py-1">{{ $link->utm_source }}</td>
                        <td class="py-1">{{ $link->utm_medium }}</td>
                        <td class="py-1">{{ $link->utm_campaign }}</td>
                        <td class="py-1">
                            @if ($link->is_active)
                                <span class="badge bg-label-success rounded-pill">Yes</span>
                            @else
                                <span class="badge bg-label-danger rounded-pill">No</span>
                            @endif
                        </td>
                        <td class="py-1">{{ $link->created_at?->format('d-m-Y') }}</td>
                            <td class="py-1">
                                <div class="d-inline-block text-nowrap">
                                    @php
                                        $canEdit = auth()->user()->can('utm.edit');
                                        $canDelete = auth()->user()->can('utm.delete');
                                    @endphp
                                    
                                    @if ($canEdit || $canDelete)
                                        @can('utm.edit')
                                        <a href="{{ route('utm-links.edit', $link) }}"
                                                class="btn btn-text-secondary rounded-pill btn-icon">
                                            <i class="icon-base ti tabler-edit icon-22px"></i>
                                        </a>
                                        @endcan
                                        @can('utm.delete')
                                
                                        <form action="{{ route('utm-links.destroy', $link) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this UTM link?');">
                                        @csrf
                                        @method('DELETE')
                                            <button class="btn btn-sm">
                                                <i class="icon-base ti tabler-trash icon-22px"></i>
                                            </button>
                                        </form>
                                        @endcan
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="8">
                                <strong>Full URL:</strong>
                                <a href="{{ $link->full_url }}" target="_blank">
                                    <small>{{ $link->full_url }}</small>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-3 me-3">
                {{ $links->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
    </div>
@endsection