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
                    <form action="{{ route('membership.update', $member->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                @else
                    <form action="{{ route('membership.store') }}" method="POST" enctype="multipart/form-data">
                @endif
                    @csrf
                    @if($isEdit)
                        @method('POST')
                    @endif

                    <h5>SEO</h5>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Title</label>
                        <input type="text" class="form-control" maxlength="60" name="meta_title"
                            value="{{ old('meta_title', $isEdit ? $member->meta_title : '') }}">
                        <small class="text-muted">Recommended max 60 characters.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Description</label>
                        <textarea class="form-control" maxlength="160" rows="2" name="meta_description">{{ old('meta_description', $isEdit ? $member->meta_description : '') }}</textarea>
                        <small class="text-muted">Recommended max 160 characters.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Keys</label>
                        <input type="text" class="form-control" id="TagifyBasic" name="meta_key"
                            value="{{ old('meta_key', $isEdit ? $member->meta_key : '') }}">
                        <small class="text-muted">Comma separated keywords for SEO.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $isEdit ? $member->title : '') }}">
                        @error('title') 
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

                    <hr>
                    <h5>Hero Section (Above the Fold)</h5>
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
                        <label class="form-label fw-bold">Primary CTA</label>
                        <input type="text" name="primary_cta" class="form-control @error('primary_cta') is-invalid @enderror"
                            value="{{ old('primary_cta', $isEdit ? $member->primary_cta : '') }}">
                        @error('primary_cta') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Secondory CTA</label>
                        <input type="text" name="secondory_cta" class="form-control @error('secondory_cta') is-invalid @enderror"
                            value="{{ old('secondory_cta', $isEdit ? $member->secondory_cta : '') }}">
                        @error('secondory_cta') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                   <hr>
                   <h5>What FFOI Membership Is (And Is Not)</h5>
                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">What FFOI Membership IS</label>

                        <div class="points-wrapper">
                            @php
                                $points = old('what_ffoi_membership', $isEdit && $member->what_ffoi_membership 
                                    ? json_decode($member->what_ffoi_membership, true) 
                                    : ['']);
                            @endphp

                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="what_ffoi_membership[]" 
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
                        <label class="form-label fw-bold">What FFOI Membership Is NOT</label>

                        <div class="points-wrapper">
                            @php
                                $points = old('what_ffoi_membership_not', $isEdit && $member->what_ffoi_membership_not 
                                    ? json_decode($member->what_ffoi_membership_not, true) 
                                    : ['']);
                            @endphp

                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="what_ffoi_membership_not[]" 
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
                        <label class="form-label fw-bold">Disclaimer</label>
                        <input type="text" name="what_ffoi_membership_desclaimer" class="form-control @error('what_ffoi_membership_desclaimer') is-invalid @enderror"
                            value="{{ old('what_ffoi_membership_desclaimer', $isEdit ? $member->what_ffoi_membership_desclaimer : '') }}">
                        @error('what_ffoi_membership_desclaimer') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>
                   <h5>Why FFOI Created a Membership Framework</h5>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>

                        <div class="points-wrapper">
                            @php
                                $points = old('why_ffoi_created', $isEdit && $member->why_ffoi_created 
                                    ? json_decode($member->why_ffoi_created, true) 
                                    : ['']);
                            @endphp

                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="why_ffoi_created[]" 
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
                        <label class="form-label fw-bold">Disclaimer</label>
                        <input type="text" name="why_ffoi_created_desclaimer" class="form-control @error('why_ffoi_created_desclaimer') is-invalid @enderror"
                            value="{{ old('why_ffoi_created_desclaimer', $isEdit ? $member->why_ffoi_created_desclaimer : '') }}">
                        @error('why_ffoi_created_desclaimer') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Progress</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('why_ffoi_created_progress', $isEdit && $member->why_ffoi_created_progress 
                                    ? json_decode($member->why_ffoi_created_progress, true) 
                                    : ['']);
                            @endphp
                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="why_ffoi_created_progress[]" 
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

                    <hr>
                    <h5>Membership Categories Overview</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Category desc</label>
                        <input type="text" name="category_status_desc" class="form-control @error('category_status_desc') is-invalid @enderror"
                            value="{{ old('category_status_desc', $isEdit ? $member->category_status_desc : '') }}">
                        @error('category_status_desc') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Membership status</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('membership_status', $isEdit && $member->membership_status 
                                    ? json_decode($member->membership_status, true) 
                                    : ['']);
                            @endphp
                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="membership_status[]" 
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
                        <label class="form-label fw-bold">Anual Membership</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('anual_membership', $isEdit && $member->anual_membership 
                                    ? json_decode($member->anual_membership, true) 
                                    : ['']);
                            @endphp
                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="anual_membership[]" 
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
                        <label class="form-label fw-bold">Life Membership</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('life_membership', $isEdit && $member->life_membership 
                                    ? json_decode($member->life_membership, true) 
                                    : ['']);
                            @endphp
                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="life_membership[]" 
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
                        <label class="form-label fw-bold">Life Membership disclaimer</label>
                        <input type="text" name="life_membership_disclaimer" class="form-control @error('life_membership_disclaimer') is-invalid @enderror"
                            value="{{ old('life_membership_disclaimer', $isEdit ? $member->life_membership_disclaimer : '') }}">
                        @error('life_membership_disclaimer') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>
                    <h5>Primary Call to Action</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Heading</label>
                        <input type="text" name="primary_call_heading" class="form-control @error('primary_call_heading') is-invalid @enderror"
                            value="{{ old('primary_call_heading', $isEdit ? $member->primary_call_heading : '') }}">
                        @error('primary_call_heading') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Description</label>
                        <input type="text" name="primary_call_desc" class="form-control @error('primary_call_desc') is-invalid @enderror"
                            value="{{ old('primary_call_desc', $isEdit ? $member->primary_call_desc : '') }}">
                        @error('primary_call_desc') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Primary CTA</label>
                        <input type="text" name="primary_call_primary_CTA" class="form-control @error('primary_call_primary_CTA') is-invalid @enderror"
                            value="{{ old('primary_call_primary_CTA', $isEdit ? $member->primary_call_primary_CTA : '') }}">
                        @error('primary_call_primary_CTA') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Secondary CTA</label>
                        <input type="text" name="primary_call_secondary_CTA" class="form-control @error('primary_call_secondary_CTA') is-invalid @enderror"
                            value="{{ old('primary_call_secondary_CTA', $isEdit ? $member->primary_call_secondary_CTA : '') }}">
                        @error('primary_call_secondary_CTA') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>
                    <h5>Footer Trust Block</h5>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('footer_text', $isEdit && $member->footer_text 
                                    ? json_decode($member->footer_text, true) 
                                    : ['']);
                            @endphp
                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="footer_text[]" 
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

                    <hr>
                    <h5>NEXT (LOGICAL) BUILDS</h5>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('next_build', $isEdit && $member->next_build 
                                    ? json_decode($member->next_build, true) 
                                    : ['']);
                            @endphp
                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="next_build[]" 
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
                        <label class="form-label fw-bold">Disclaimer</label>
                        <input type="text" name="next_build_disclaimer" class="form-control @error('next_build_disclaimer') is-invalid @enderror"
                            value="{{ old('next_build_disclaimer', $isEdit ? $member->next_build_disclaimer : '') }}">
                        @error('next_build_disclaimer') 
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