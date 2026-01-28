@extends('layout.admin.index')
@section('page_title', isset($user) ? 'Edit User' : 'Create User')

@section('admin-main-content')
    <div class="card">
        <div class="card-body">
            <form 
                action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" 
                method="POST" 
                class="mt-3 row">

                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Mobile</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile ?? '') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">
                        {{ isset($user) ? 'New Password (optional)' : 'Password' }}
                    </label>
                    <input type="password" name="password" class="form-control"
                        {{ isset($user) ? '' : 'required' }}>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-control" required>
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="subadmin" {{ old('role', $user->role ?? '') == 'subadmin' ? 'selected' : '' }}>Sub Admin</option>
                    </select>
                </div>

                <div class="col-md-12 mb-1 text-end">
                    <button class="btn btn-primary w-25">
                        {{ isset($user) ? 'Update User' : 'Create User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection