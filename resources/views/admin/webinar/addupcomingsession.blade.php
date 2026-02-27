@extends('layout.admin.index')

@if($session->exists!=false)
    @section('page_title', 'Edit Upcoming Session')
@else
    @section('page_title', 'Create Upcoming Session')
@endif
@section('admin-main-content')

@php
    $isEdit = isset($session);
    // echo $blog_category;
    // exit;
@endphp

<div class="row mb-6 gy-6">
    <div class="col-xxl">
        <div class="card">

            <div class="card-body">
                @if($session->exists!=false)
                <form method="POST"
                    action="{{ route('webinar.session.update', $session->id) }}"
                    enctype="multipart/form-data">
                @else
                <form method="POST"
                    action="{{ route('webinar.session.store') }}"
                    enctype="multipart/form-data">
                @endif
                    @csrf
                    @if($isEdit)
                        @method('POST')
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-bold">Session Name</label>
                        <input type="text" name="session_name" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('session_name', $isEdit ? $session->session_name : '') }}">
                        @error('title') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Heading</label>
                        <input type="text" name="heading" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('heading', $isEdit ? $session->heading : '') }}">
                        @error('title') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Short Description</label>
                        <input type="text" name="short_desc" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('short_desc', $isEdit ? $session->short_desc : '') }}">
                        @error('title') 
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