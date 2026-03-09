@extends('layout.admin.index')

@section('page_title', 'MemberShip Benefit list')

@section('admin-main-content')

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <a href="{{ route('membership.benefit.create') }}" class="btn btn-primary">Add Membership Benefit</a>
        </div>

        <div class="card-datatable">
            @if ($members->count() > 0)
                <table class="datatables-users table">
                    <thead class="border-top">
                        <tr>
                            <th>Sr.</th>
                            <th>Benefits</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($members as $row)
                            <tr>
                                <td class="py-1">{{ $loop->iteration }}</td>
                                <td class="py-1">{{ $row->Benefits ?? 'N/A' }}</td>
                                <td class="">{{ $row->created_at->format('d M Y, h:i A') ?? '-'}}</td>
                                <td class="py-1">
                                    <div class="d-inline-block text-nowrap">
                                        <a href="{{ route('membership.benefit.create', $row->id) }}"
                                            class="btn btn-text-secondary rounded-pill btn-icon"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-original-title="Edit Benefit">
                                            <i class="icon-base ti tabler-edit icon-22px"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No Benefit found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3 me-3">
                    {{ $members->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">No Benefit found.</h5>
                </div>
            @endif
        </div>
    </div>

@endsection