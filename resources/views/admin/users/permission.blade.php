@extends('layout.admin.index')
@section('page_title', 'User Permissions')

@section('admin-main-content')
    <div class="card mb-6">

        <div class="card-header">
            <h5 class="mb-0">{{ $user->name }}</h5>
            <span class="card-subtitle">Assign module-wise permissions</span>
        </div>

        <form action="{{ route('users.permission.update', $user->id) }}" method="POST">
            @csrf

            <div class="table-responsive">
                <table class="table">
                    <thead class="border-top">
                        <tr>
                            <th class="text-nowrap">Module</th>
                            <th class="text-nowrap text-center">View</th>
                            <th class="text-nowrap text-center">Create</th>
                            <th class="text-nowrap text-center">Edit</th>
                            {{-- <th class="text-nowrap text-center">Delete</th> --}}
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($permissions as $module => $perms)
                            <tr>
                                <td class="text-nowrap text-heading">{{ ucfirst($module) }}</td>

                                @php
                                    $view = $perms->where('name', $module.'.view')->first();
                                    $create = $perms->where('name', $module.'.create')->first();
                                    $edit = $perms->where('name', $module.'.edit')->first();
                                    // $delete = $perms->where('name', $module.'.delete')->first();
                                @endphp

                                {{-- View --}}
                                <td class="text-center">
                                    @if($view)
                                        <input type="checkbox" name="permissions[]" value="{{ $view->name }}"
                                            class="form-check-input"
                                            {{ in_array($view->name, $userPermissions) ? 'checked' : '' }}>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Create --}}
                                <td class="text-center">
                                    @if($create)
                                        <input type="checkbox" name="permissions[]" value="{{ $create->name }}"
                                            class="form-check-input"
                                            {{ in_array($create->name, $userPermissions) ? 'checked' : '' }}>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Edit --}}
                                <td class="text-center">
                                    @if($edit)
                                        <input type="checkbox" name="permissions[]" value="{{ $edit->name }}"
                                            class="form-check-input"
                                            {{ in_array($edit->name, $userPermissions) ? 'checked' : '' }}>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Delete --}}
                                {{-- <td class="text-center">
                                    @if($delete)
                                        <input type="checkbox" name="permissions[]" value="{{ $delete->name }}"
                                            class="form-check-input"
                                            {{ in_array($delete->name, $userPermissions) ? 'checked' : '' }}>
                                    @else
                                        -
                                    @endif
                                </td> --}}

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-body text-end">
                <button type="submit" class="btn btn-primary me-3">Save changes</button>
            </div>
        </form>
    </div>
@endsection