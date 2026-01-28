@extends('layout.admin.index')

@section('page_title', isset($blog) ? 'Edit Blog Category' : 'Create Blog Category')

@section('admin-main-content')

@php
    $isEdit = isset($category);
    // echo $blog_category;
    // exit;
@endphp

<div class="row mb-6 gy-6">
    <div class="col-xxl">
        <div class="card">

            <div class="card-body">
                <form method="POST"
                    action="{{ $isEdit ? route('blog.categories.update', $category->id) : route('blog.categories.store') }}"
                    enctype="multipart/form-data">

                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-bold">Category Name</label>
                        <input type="text" name="name" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('name', $isEdit ? $category->name : '') }}">
                        @error('title') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status"
                            class="form-control @error('status') is-invalid @enderror">
                            <option value="">Select Status</option>

                            <option value="1" {{ old('status', $isEdit ? $category->status : 1) == 1 ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="0" {{ old('status', $isEdit ? $category->status : 1) == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            {{ $isEdit ? 'Update' : 'Add' }}
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection