@extends('layout.admin.index')
@section('page_title', isset($campus) ? 'Edit Campuse' : 'Create Campuse')

@section('admin-main-content')
    @php
        $isEdit = isset($campus) && $campus;
    @endphp
    <div class="row mb-6 gy-6">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body">
                    <form method="POST"
                        action="{{ $isEdit ? route('campuses.update', $campus->id) : route('campuses.store') }}"
                        enctype="multipart/form-data">

                        @csrf

                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="campus-name">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="campus-name" name="name" value="{{ old('name', $isEdit ? $campus->name : '') }}"
                                    placeholder="Campus Name" />
                                @error('name')
                                    {{-- <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span> --}}
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="campus-status">Status</label>
                            <div class="col-sm-10">
                                <select class="form-control @error('status') is-invalid @enderror" id="campus-status"
                                    name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="1" @if (old('status', $isEdit ? $campus->status : '1') == '1') selected @endif>
                                        Active
                                    </option>
                                    <option value="0" @if (old('status', $isEdit ? $campus->status : '1') == '0') selected @endif>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                    {{-- <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span> --}}
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Show in Menu</label>
                            <div class="col-sm-10">
                                <select name="show_in_menu" class="form-control">
                                    <option value="1"
                                        {{ old('show_in_menu', $isEdit ? optional($campus->pages->first())->show_in_menu : 1) == 1 ? 'selected' : '' }}>
                                        Yes
                                    </option>
                                    <option value="0"
                                        {{ old('show_in_menu', $isEdit ? optional($campus->pages->first())->show_in_menu : 1) == 0 ? 'selected' : '' }}>
                                        No
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Menu Order</label>
                            <div class="col-sm-10">
                                <input type="number" name="menu_order" class="form-control"
                                    value="{{ old('menu_order', $isEdit ? optional($campus->pages->first())->menu_order : '') }}"
                                    placeholder="Menu sequence (e.g. 1,2,3)">
                            </div>
                        </div>

                        <div class="row text-end">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary w-25">
                                    {{ $isEdit ? 'Update Campus' : 'Add Campus' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
