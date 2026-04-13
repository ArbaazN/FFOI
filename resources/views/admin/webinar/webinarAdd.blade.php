@extends('layout.admin.index')

@if($webinar->exists!=false)
    @section('page_title', 'Edit Webinar')
@else
    @section('page_title', 'Create Webinar')
@endif

@section('admin-main-content')

@php
    $isEdit = isset($webinar);
    // echo $blog_category;
    // exit;
@endphp

<div class="row mb-6 gy-6">
    <div class="col-xxl">
        <div class="card">

            <div class="card-body">
                @if($webinar->exists!=false)
                    <form action="{{ route('webinar.update', $webinar->id) }}" method="POST" enctype="multipart/form-data">
                @else
                    <form action="{{ route('webinar.store') }}" method="POST" enctype="multipart/form-data">
                @endif
                    @csrf
                    @if($isEdit)
                        @method('POST')
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-bold">Webinar Type</label>
                        <select name="webinar_type" class="form-select @error('webinar_type') is-invalid @enderror">
                            <option value="">Select webinar type</option>
                            <option value="upcoming" {{ old('webinar_type', $isEdit ? $webinar->webinar_type : 'upcoming') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="other" {{ old('webinar_type', $isEdit ? $webinar->webinar_type : '') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('webinar_type')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Banner Image</label>

                        @if ($isEdit && $webinar->banner_image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $webinar->banner_image) }}" alt="Image"
                                    class="img-thumbnail" style="max-width: 200px;" accept="image/*">
                            </div>
                        @endif

                        <input type="file" name="banner_image" class="form-control @error('banner_image') is-invalid @enderror"
                            accept="image/*">
                        @error('banner_image')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $isEdit ? $webinar->title : '') }}">
                        @error('title') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Sub Title</label>
                        <input type="text" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror"
                            value="{{ old('subtitle', $isEdit ? $webinar->subtitle : '') }}">
                        @error('subtitle') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Short Description</label>
                        <input type="text" name="short_desc" class="form-control @error('short_desc') is-invalid @enderror"
                            value="{{ old('short_desc', $isEdit ? $webinar->short_desc : '') }}">
                        @error('short_desc') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Meeting Link</label>
                        <input type="url" name="meeting_link" class="form-control @error('meeting_link') is-invalid @enderror"
                            value="{{ old('meeting_link', $isEdit ? $webinar->meeting_link : '') }}"
                            placeholder="https://example.com/meeting-link">
                        @error('meeting_link')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>
                    <h5>What You’ll Get (In 90 Minutes)</h5>
                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('desc', $isEdit && $webinar->desc 
                                    ? json_decode($webinar->desc, true) 
                                    : ['']);
                            @endphp

                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="desc[]" 
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
                    <h5>Who Should Attend?</h5>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Image</label>

                        @if ($isEdit && $webinar->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $webinar->image) }}" alt="Image"
                                    class="img-thumbnail" style="max-width: 200px;" accept="image/*">
                            </div>
                        @endif

                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                            accept="image/*">
                        @error('image')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('perfect_for_desc', $isEdit && $webinar->perfect_for_desc 
                                    ? json_decode($webinar->perfect_for_desc, true) 
                                    : ['']);
                            @endphp

                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="perfect_for_desc[]" 
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
                        <input type="text" name="perfect_for_desclaimer" class="form-control @error('perfect_for_desclaimer') is-invalid @enderror"
                            value="{{ old('perfect_for_desclaimer', $isEdit ? $webinar->perfect_for_desclaimer : '') }}">
                        @error('perfect_for_desclaimer') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>


                    <hr>
                    <h5>How It Works</h5>
                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('works_desc', $isEdit && $webinar->works_desc 
                                    ? json_decode($webinar->works_desc, true) 
                                    : ['']);
                            @endphp

                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="works_desc[]" 
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
                    <h5>Why FFOI?</h5>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Heading</label>
                        <input type="text" name="why_ffoi_heading" class="form-control @error('why_ffoi_heading') is-invalid @enderror"
                            value="{{ old('why_ffoi_heading', $isEdit ? $webinar->why_ffoi_heading : '') }}">
                        @error('why_ffoi_heading') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('why_ffoi_desc', $isEdit && $webinar->why_ffoi_desc 
                                    ? json_decode($webinar->why_ffoi_desc, true) 
                                    : ['']);
                            @endphp

                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="why_ffoi_desc[]" 
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
                    <h5>FAQs</h5>

                    <div class="mb-4 dynamic-faqs">
                        <label class="form-label fw-bold">FAQs</label>

                        <div class="faq-wrapper">
                            @php
                                $questions = old('faqs_question',
                                    $isEdit && $webinar->faqs_question
                                    ? json_decode($webinar->faqs_question, true)
                                    : ['']);

                                $answers = old('faqs_answer',
                                    $isEdit && $webinar->faqs_answer
                                    ? json_decode($webinar->faqs_answer, true)
                                    : ['']);
                            @endphp

                            @foreach($questions as $index => $question)
                                <div class="faq-item border p-3 mb-3 rounded">
                                    <div class="mb-2">
                                        <input type="text"
                                            name="faqs_question[]"
                                            class="form-control"
                                            value="{{ $question }}"
                                            placeholder="Enter question">
                                    </div>

                                    <div class="mb-2">
                                        <textarea name="faqs_answer[]"
                                                class="form-control"
                                                placeholder="Enter answer">{{ $answers[$index] ?? '' }}</textarea>
                                    </div>

                                    <button type="button" class="btn btn-danger remove-faq">
                                        Remove
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-primary mt-2 add-faq">
                            + Add FAQ
                        </button>
                    </div>

                    <hr>
                    <h5>Learn from the Best of Industry Industry</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Heading</label>
                        <input type="text" name="best_of_industries_heading" class="form-control @error('best_of_industries_heading') is-invalid @enderror"
                            value="{{ old('best_of_industries_heading', $isEdit ? $webinar->best_of_industries_heading : '') }}">
                        @error('best_of_industries_heading') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 dynamic-boi">
                        <div class="boi-wrapper">
                            @php
                            $name_news = old('name_new', json_decode($webinar->name_new ?? '[]', true));
                            $Designation_new = old('Designation_new', json_decode($webinar->Designation_new ?? '[]', true));
                            $Description_new = old('Description_new', json_decode($webinar->Description_new ?? '[]', true));
                            $Areaofexperties_new = old('Areaofexperties_new', json_decode($webinar->Areaofexperties_new ?? '[]', true));
                            $linkedIn_new = old('linkedIn_new', json_decode($webinar->linkedIn_new ?? '[]', true));

                            $image_new = old('image_new', json_decode($webinar->image_new ?? '[]', true));
                            $logo_image1_new = old('logo_image1_new', json_decode($webinar->logo_image1_new ?? '[]', true));
                            $logo_image2_new = old('logo_image2_new', json_decode($webinar->logo_image2_new ?? '[]', true));

                            @endphp

                            @foreach($name_news as $index => $name_new)
                                <div class="boi-item border p-3 mb-3 rounded">
                                    <div class="mb-2">
                                        <label for="">Profile image</label>
                                        @if(!empty($image_new[$index]))
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/'.$image_new[$index]) }}"
                                                    width="80"
                                                    height="80"
                                                    style="object-fit:cover;border-radius:5px;">
                                            </div>
                                        @endif
                                      
                                        <input type="file"
                                            name="image_new[]"
                                            class="form-control">
                                    </div>

                                    <div class="mb-2">
                                        <label for="">Name</label>
                                        <input type="text"
                                            name="name_new[]"
                                            class="form-control"
                                            value="{{ $name_new }}"
                                            placeholder="Enter Name">
                                    </div>

                                    <div class="mb-2">
                                        <label for="">Designation</label>
                                        <input type="text"
                                            name="Designation_new[]"
                                            class="form-control"
                                            value="{{ $Designation_new[$index] ?? '' }}"
                                            placeholder="Enter Designation">
                                    </div>

                                    <div class="mb-2">
                                        <label for="">Description</label>
                                        <input type="text"
                                            name="Description_new[]"
                                            class="form-control"
                                            value="{{ $Description_new[$index] ?? '' }}"
                                            placeholder="Enter Description">
                                    </div>

                                    <div class="mb-2">
                                        <label for="">Area of experties</label>
                                        <input type="text"
                                            name="Areaofexperties_new[]"
                                            class="form-control"
                                            value="{{ $Areaofexperties_new[$index] ?? '' }}"
                                            placeholder="Enter Area of experties">
                                    </div>

                                    <div class="mb-2">
                                        <label for="">Logo Image1</label>
                                        @if(!empty($logo_image1_new[$index]))
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/'.$logo_image1_new[$index]) }}"
                                                    width="80"
                                                    height="80"
                                                    style="object-fit:cover;border-radius:5px;">
                                            </div>
                                        @endif
                                        <input type="file"
                                            name="logo_image1_new[]"
                                            class="form-control"
                                            value="{{ $logo_image1_new[$index] ?? '' }}">
                                    </div>

                                    <div class="mb-2">
                                        <label for="">Logo Image2</label>
                                        @if(!empty($logo_image2_new[$index]))
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/'.$logo_image2_new[$index]) }}"
                                                    width="80"
                                                    height="80"
                                                    style="object-fit:cover;border-radius:5px;">
                                            </div>
                                        @endif
                                        <input type="file"
                                            name="logo_image2_new[]"
                                            class="form-control"
                                            value="{{ $logo_image2_new[$index] ?? '' }}">
                                    </div>

                                    <div class="mb-2">
                                        <label for="">Linkedin url</label>
                                        <input type="text"
                                            name="linkedIn_new[]"
                                            class="form-control"
                                            value="{{ $linkedIn_new[$index] ?? '' }}"
                                            placeholder="Enter Linkedinurl">
                                    </div>

                                    <button type="button" class="btn btn-danger remove-boi">
                                        Remove
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-primary mt-2 add-boi">
                            + Add Best of Industry
                        </button>
                    </div>


                    <hr>
                    <h5>Final CTA</h5>
                    <div class="mb-4">
                        <label class="form-label fw-bold">CTA Description</label>
                        <input type="text" name="final_CTA_desc" class="form-control @error('final_CTA_desc') is-invalid @enderror"
                            value="{{ old('final_CTA_desc', $isEdit ? $webinar->final_CTA_desc : '') }}">
                        @error('final_CTA_desc') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@endsection
