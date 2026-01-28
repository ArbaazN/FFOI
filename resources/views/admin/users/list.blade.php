@extends('layout.admin.index')
@section('page_title', 'Users')

@section('admin-main-content')
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <form action="{{ route('users.index') }}" method="GET" class="w-25 position-relative search-wrapper">
                <input type="text" name="search" class="form-control autoSearch" value="{{ request('search') }}"
                    data-delay="1000" data-min="3" placeholder="Search Users..." />
                <span class="clear-search d-none"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; color:#999;">✖</span>
            </form>
            {{-- @can('user.create')
                <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
            @endcan --}}
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="border-top">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td><span class="badge rounded-pill bg-label-info">{{ ucfirst($user->role) }}</span></td>
                            <td>{{ $user->email ?? '-' }}</td>
                            <td>{{ $user->mobile ?? '-' }}</td>
                            <td>
                                <div class="d-inline-block text-nowrap">

                                    @php
                                        $canEdit = auth()->user()->can('user.edit');
                                        $isAdmin = auth()->user()->hasRole('admin');
                                    @endphp

                                    @if ($canEdit || $isAdmin)
                                        @can('user.edit')
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-text-secondary rounded-pill btn-icon"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit User">
                                                <i class="icon-base ti tabler-edit icon-22px"></i>
                                            </a>
                                        @endcan

                                        @role('admin')
                                            @if($user->role !== 'admin')
                                                <a href="{{ route('users.permission', $user->id) }}"
                                                    class="btn btn-text-secondary rounded-pill btn-icon"
                                                     data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Permission Settings">
                                                    <i class="icon-base ti tabler-list-check icon-22px"></i>
                                                </a>
                                            @endif
                                        @endrole
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No program found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection