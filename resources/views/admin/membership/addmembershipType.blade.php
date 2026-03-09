@extends('layout.admin.index')
@if($member->exists!=false)
    @section('page_title', 'Edit MemberShip Type')
@else
    @section('page_title', 'Create MemberShip Type')
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
                    <form action="{{ route('membership.type.update', $member->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                @else
                    <form action="{{ route('membership.type.store') }}" method="POST" enctype="multipart/form-data">
                @endif
                    @csrf
                    @if($isEdit)
                        @method('POST')
                    @endif


                    <div class="mb-4">
                        <label class="form-label fw-bold">Headline</label>
                        <input type="text" name="headline" class="form-control @error('headline') is-invalid @enderror"
                            value="{{ old('headline', $isEdit ? $member->headline : '') }}">
                        @error('headline') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Sub Headline</label>
                        <input type="text" name="sub_headline" class="form-control @error('sub_headline') is-invalid @enderror"
                            value="{{ old('sub_headline', $isEdit ? $member->sub_headline : '') }}">
                        @error('sub_headline') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                   
                    <div class="mb-4">
                        <label class="form-label fw-bold">Short Desc</label>
                        <input type="text" name="short_desc" class="form-control @error('short_desc') is-invalid @enderror"
                            value="{{ old('short_desc', $isEdit ? $member->short_desc : '') }}">
                        @error('short_desc') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                   
                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Who It Is For?</label>

                        <div class="points-wrapper">
                            @php
                                $points = old('it_is_for', $isEdit && $member->it_is_for 
                                    ? json_decode($member->it_is_for, true) 
                                    : ['']);
                            @endphp

                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="it_is_for[]" 
                                        class="form-control"
                                        value="{{ $point }}"
                                        placeholder="Enter point">
                                    <button type="button" class="btn btn-danger remove-point">Remove</button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-primary mt-2 add-point">
                            + Add Point
                        </button>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Purpose</label>
                        <input type="text" name="purpose" class="form-control @error('purpose') is-invalid @enderror"
                            value="{{ old('purpose', $isEdit ? $member->purpose : '') }}">
                        @error('purpose') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Honorary Members Contribute Through</label>

                        <div class="points-wrapper">
                            @php
                                $points = old('contribute_through', $isEdit && $member->contribute_through 
                                    ? json_decode($member->contribute_through, true) 
                                    : ['']);
                            @endphp

                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="contribute_through[]" 
                                        class="form-control"
                                        value="{{ $point }}"
                                        placeholder="Enter point">
                                    <button type="button" class="btn btn-danger remove-point">Remove</button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-primary mt-2 add-point">
                            + Add Point
                        </button>
                    </div>


                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Planned Access</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('planned_access', $isEdit && $member->planned_access 
                                    ? json_decode($member->planned_access, true) 
                                    : ['']);
                            @endphp
                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="planned_access[]" 
                                        class="form-control"
                                        value="{{ $point }}"
                                        placeholder="Enter point">
                                    <button type="button" class="btn btn-danger remove-point">Remove</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-primary mt-2 add-point">
                            + Add Point
                        </button>
                    </div>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Honorary Member Privileges</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('priviledges', $isEdit && $member->priviledges 
                                    ? json_decode($member->priviledges, true) 
                                    : ['']);
                            @endphp
                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="priviledges[]" 
                                        class="form-control"
                                        value="{{ $point }}"
                                        placeholder="Enter point">
                                    <button type="button" class="btn btn-danger remove-point">Remove</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-primary mt-2 add-point">
                            + Add Point
                        </button>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Privileges Key</label>
                        <input type="text" name="priviledges_key" class="form-control @error('priviledges_key') is-invalid @enderror"
                            value="{{ old('priviledges_key', $isEdit ? $member->priviledges_key : '') }}">
                        @error('priviledges_key') 
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