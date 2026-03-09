@extends('layout.admin.index')
@if($member->exists!=false)
    @section('page_title', 'Edit MemberShip Benefit')
@else
    @section('page_title', 'Create MemberShip Benefit')
@endif
@section('admin-main-content')

@php
    $isEdit = isset($member);
    // echo $blog_category;
    // exit;
@endphp

<div class="row mb-6 gy-6">
    <div class="col-xxl">
        <div class="card">

            <div class="card-body">
                @if($member->exists!=false)
                    <form action="{{ route('membership.benefit.update', $member->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                @else
                    <form action="{{ route('membership.benefit.store') }}" method="POST" enctype="multipart/form-data">
                @endif
                    @csrf
                    @if($isEdit)
                        @method('POST')
                    @endif


                    <div class="mb-4">
                        <label class="form-label fw-bold">Benefits</label>
                        <input type="text" name="Benefits" class="form-control @error('Benefits') is-invalid @enderror"
                            value="{{ old('Benefits', $isEdit ? $member->Benefits : '') }}">
                        @error('Benefits') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Honorary</label>
                        <input type="text" name="Honorary" class="form-control @error('Honorary') is-invalid @enderror"
                            value="{{ old('Honorary', $isEdit ? $member->Honorary : '') }}">
                        @error('Honorary') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                   
                    <div class="mb-4">
                        <label class="form-label fw-bold">Literacy</label>
                        <input type="text" name="Literacy" class="form-control @error('Literacy') is-invalid @enderror"
                            value="{{ old('Literacy', $isEdit ? $member->Literacy : '') }}">
                        @error('Literacy') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Student</label>
                        <input type="text" name="Student" class="form-control @error('Student') is-invalid @enderror"
                            value="{{ old('Student', $isEdit ? $member->Student : '') }}">
                        @error('Student') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Professional</label>
                        <input type="text" name="Professional" class="form-control @error('Professional') is-invalid @enderror"
                            value="{{ old('Professional', $isEdit ? $member->Professional : '') }}">
                        @error('Professional') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Institutional</label>
                        <input type="text" name="Institutional" class="form-control @error('Institutional') is-invalid @enderror"
                            value="{{ old('Institutional', $isEdit ? $member->Institutional : '') }}">
                        @error('Institutional') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Disclaimer</label>
                        <input type="text" name="disclaaimer" class="form-control @error('disclaaimer') is-invalid @enderror"
                            value="{{ old('disclaaimer', $isEdit ? $member->disclaaimer : '') }}">
                        @error('disclaaimer') 
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