@extends('layout.admin.index')
@section('page_title', $program ? 'Edit Program' : 'Create Program')

@section('admin-main-content')
    @php
        $isEdit = isset($program) && $program;
    @endphp

    <div class="row mb-6 gy-6">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body">

                    <form method="POST"
                        action="{{ $isEdit ? route('programs.update', $program->id) : route('programs.store') }}"
                        enctype="multipart/form-data">

                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        {{-- PROGRAM TYPE --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Program Type</label>
                            <div class="col-sm-10">
                                <select class="form-control @error('type') is-invalid @enderror" name="type">
                                    <option value="">Select Type</option>
                                    <option value="mms"
                                        {{ old('type', $isEdit ? $program->type : '') == 'mms' ? 'selected' : '' }}>
                                        MMS
                                    </option>
                                    <option value="pgdm"
                                        {{ old('type', $isEdit ? $program->type : '') == 'pgdm' ? 'selected' : '' }}>
                                        PGDM
                                    </option>
                                </select>
                                @error('type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- PROGRAM NAME --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Program Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $isEdit ? $program->name : '') }}"
                                    placeholder="Program Name">
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="1"
                                        {{ old('status', $isEdit ? $program->status : 1) == 1 ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0"
                                        {{ old('status', $isEdit ? $program->status : 1) == 0 ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- SHOW IN MENU --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Show in Menu</label>
                            <div class="col-sm-10">
                                <select name="show_in_menu" class="form-control">
                                    <option value="1"
                                        {{ old('show_in_menu', $isEdit ? optional($program->pages->first())->show_in_menu : 1) == 1 ? 'selected' : '' }}>
                                        Yes
                                    </option>
                                    <option value="0"
                                        {{ old('show_in_menu', $isEdit ? optional($program->pages->first())->show_in_menu : 1) == 0 ? 'selected' : '' }}>
                                        No
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- MENU ORDER --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Menu Order</label>
                            <div class="col-sm-10">
                                <input type="number" name="menu_order" class="form-control"
                                    value="{{ old('menu_order', $isEdit ? optional($program->pages->first())->menu_order : '') }}"
                                    placeholder="Menu sequence (1,2,3...)">
                            </div>
                        </div>

                        {{-- SUBMIT --}}
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">
                                    {{ $isEdit ? 'Update Program' : 'Add Program' }}
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
