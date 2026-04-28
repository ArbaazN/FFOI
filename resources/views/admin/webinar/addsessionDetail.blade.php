@extends('layout.admin.index')
@if($session->exists!=false)
    @section('page_title', 'Edit Upcoming Session Detail')
@else
    @section('page_title', 'Create Upcoming Session Detail')
@endif
@section('admin-main-content')

@php
    $isEdit = $session->exists;
    // echo $blog_category;
    // exit;
@endphp

<div class="row mb-6 gy-6">
    <div class="col-xxl">
        <div class="card">

            <div class="card-body">
                @if($session->exists!=false)
                    <form action="{{ route('webinar.detail.session.update', $session->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                @else
                    <form action="{{ route('webinar.session.detail.store') }}" method="POST" enctype="multipart/form-data">
                @endif
                    @csrf
                    @if($isEdit)
                        @method('POST')
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-bold">Banner Image</label>

                        @if ($isEdit && $session->banner_image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $session->banner_image) }}" alt="Image"
                                    class="img-thumbnail" style="max-width: 50px;" accept="image/*">
                            </div>
                        @endif

                        <input type="file" name="banner_image" class="form-control @error('banner_image') is-invalid @enderror"
                            accept="image/*">
                        @error('banner_image')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Session Name</label>

                        <select name="session_id" id="session_id" class="form-control @error('session_id') is-invalid @enderror">
                            <option value="">Select</option>

                            @foreach($category as $categoryName)
                                <option value="{{ $categoryName->id }}"
                                    data-session-name="{{ $categoryName->session_name }}"
                                    {{ old('session_id', $session->session_id ?? '') == $categoryName->id ? 'selected' : '' }}>
                                    {{ $categoryName->session_name }}
                                </option>
                            @endforeach

                        </select>

                        @error('session_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Session Type</label>
                        <select name="webinar_type" class="form-control @error('webinar_type') is-invalid @enderror">
                            <option value="">Select</option>
                            <option value="upcoming" {{ old('webinar_type', $isEdit ? $session->webinar_type : 'upcoming') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="other" {{ old('webinar_type', $isEdit ? $session->webinar_type : '') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('webinar_type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Meeting Link</label>
                        <input type="url" name="meeting_link" class="form-control @error('meeting_link') is-invalid @enderror"
                            value="{{ old('meeting_link', $isEdit ? $session->meeting_link : '') }}"
                            placeholder="https://example.com/meeting-link">
                        @error('meeting_link')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Topic Name</label>
                        <input type="text" id="topic_name" name="topic_name"
                            class="form-control @error('topic_name') is-invalid @enderror" readonly
                            value="{{ old('topic_name', $isEdit ? $session->topic_name : '') }}">
                        @error('topic_name') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $isEdit ? $session->title : '') }}">
                        @error('title') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Sub Title</label>
                        <input type="text" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror"
                            value="{{ old('subtitle', $isEdit ? $session->subtitle : '') }}">
                        @error('subtitle') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Date</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                             value="{{ old('date', $session->date ? \Carbon\Carbon::parse($session->date)->format('Y-m-d') : '') }}">
                        @error('date') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">From</label>
                        <input type="time" name="time_from" class="form-control @error('time_from') is-invalid @enderror"
                            value="{{ old('time_from', $isEdit ? $session->time_from : '') }}">
                        @error('time_from') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">To</label>
                        <input type="time" name="time" class="form-control @error('time') is-invalid @enderror"
                            value="{{ old('time', $isEdit ? $session->time : '') }}">
                        @error('time') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Mode</label>
                        <input type="text" name="mode" class="form-control @error('mode') is-invalid @enderror"
                            value="{{ old('mode', $isEdit ? $session->mode : '') }}">
                        @error('mode') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">By</label>
                        <input type="text" name="by" class="form-control @error('by') is-invalid @enderror"
                            value="{{ old('by', $isEdit ? $session->by : '') }}">
                        @error('by') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>
                    <h5>Why Attend This Session?</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Heading</label>
                        <input type="text" name="why_attend_section_heading" class="form-control @error('why_attend_section_heading') is-invalid @enderror"
                            value="{{ old('why_attend_section_heading', $isEdit ? $session->why_attend_section_heading : '') }}">
                        @error('why_attend_section_heading') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>

                        <div class="points-wrapper">
                            @php
                                $points = old('why_attend_section_points', $isEdit && $session->why_attend_section_points 
                                    ? json_decode($session->why_attend_section_points, true) 
                                    : ['']);
                            @endphp

                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="why_attend_section_points[]" 
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
                    <h5>What You’ll Learn (Session Agenda)</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Heading</label>
                        <input type="text" name="why_learn_heading" class="form-control @error('why_learn_heading') is-invalid @enderror"
                            value="{{ old('why_learn_heading', $isEdit ? $session->why_learn_heading : '') }}">
                        @error('why_learn_heading') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>

                        <div class="points-wrapper">
                            @php
                                $points = old('why_learn_points', $isEdit && $session->why_learn_points 
                                    ? json_decode($session->why_learn_points, true) 
                                    : ['']);
                            @endphp

                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="why_learn_points[]" 
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

                        @if ($isEdit && $session->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $session->image) }}" alt="Image"
                                    class="img-thumbnail" style="max-width: 50px;" accept="image/*">
                            </div>
                        @endif

                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                            accept="image/*">
                        @error('image')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Heading</label>
                        <input type="text" name="who_attend_heading" class="form-control @error('who_attend_heading') is-invalid @enderror"
                            value="{{ old('who_attend_heading', $isEdit ? $session->who_attend_heading : '') }}">
                        @error('who_attend_heading') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('who_attend_points', $isEdit && $session->who_attend_points 
                                    ? json_decode($session->who_attend_points, true) 
                                    : ['']);
                            @endphp
                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="who_attend_points[]" 
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
                        <input type="text" name="who_attend_disclaimer" class="form-control @error('who_attend_disclaimer') is-invalid @enderror"
                            value="{{ old('who_attend_disclaimer', $isEdit ? $session->who_attend_disclaimer : '') }}">
                        @error('who_attend_disclaimer') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>
                    <h5>Career Roles Linked</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Heading</label>
                        <input type="text" name="career_role_heading" class="form-control @error('career_role_heading') is-invalid @enderror"
                            value="{{ old('career_role_heading', $isEdit ? $session->career_role_heading : '') }}">
                        @error('career_role_heading') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('career_role_points', $isEdit && $session->career_role_points 
                                    ? json_decode($session->career_role_points, true) 
                                    : ['']);
                            @endphp
                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="career_role_points[]" 
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
                        <input type="text" name="career_role_disclaimer" class="form-control @error('career_role_disclaimer') is-invalid @enderror"
                            value="{{ old('career_role_disclaimer', $isEdit ? $session->career_role_disclaimer : '') }}">
                        @error('career_role_disclaimer') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>
                    <h5>How This Session Helps Your Career</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Image</label>

                        @if ($isEdit && $session->image_attend)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $session->image_attend) }}" alt="Image"
                                    class="img-thumbnail" style="max-width: 50px;" accept="image/*">
                            </div>
                        @endif

                        <input type="file" name="image_attend" class="form-control @error('image_attend') is-invalid @enderror"
                            accept="image/*">
                        @error('image_attend')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Heading</label>
                        <input type="text" name="how_session_help_heading" class="form-control @error('how_session_help_heading') is-invalid @enderror"
                            value="{{ old('how_session_help_heading', $isEdit ? $session->how_session_help_heading : '') }}">
                        @error('how_session_help_heading') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('how_session_help_points', $isEdit && $session->how_session_help_points 
                                    ? json_decode($session->how_session_help_points, true) 
                                    : ['']);
                            @endphp
                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="how_session_help_points[]" 
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
                        <input type="text" name="how_session_help_disclaimer" class="form-control @error('how_session_help_disclaimer') is-invalid @enderror"
                            value="{{ old('how_session_help_disclaimer', $isEdit ? $session->how_session_help_disclaimer : '') }}">
                        @error('how_session_help_disclaimer') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    
                    <hr>
                    <h5>Why Learn with FFOI?</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Heading</label>
                        <input type="text" name="learn_with_ffoi_heading" class="form-control @error('learn_with_ffoi_heading') is-invalid @enderror"
                            value="{{ old('learn_with_ffoi_heading', $isEdit ? $session->learn_with_ffoi_heading : '') }}">
                        @error('learn_with_ffoi_heading') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 dynamic-points">
                        <label class="form-label fw-bold">Points</label>
                        <div class="points-wrapper">
                            @php
                                $points = old('learn_with_ffoi_points', $isEdit && $session->learn_with_ffoi_points 
                                    ? json_decode($session->learn_with_ffoi_points, true) 
                                    : ['']);
                            @endphp
                            @foreach($points as $point)
                                <div class="input-group mb-2 point-item">
                                    <input type="text" name="learn_with_ffoi_points[]" 
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
                    <h5>Instructor</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold"> Image</label>

                        @if ($isEdit && $session->instructor_image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $session->instructor_image) }}" alt="Image"
                                    class="img-thumbnail" style="max-width: 50px;" accept="image/*">
                            </div>
                        @endif

                        <input type="file" name="instructor_image" class="form-control @error('instructor_image') is-invalid @enderror"
                            accept="image/*">
                        @error('instructor_image')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold"> Name</label>
                        <input type="text" name="instructor_name" class="form-control @error('instructor_name') is-invalid @enderror"
                            value="{{ old('instructor_name', $isEdit ? $session->instructor_name : '') }}">
                        @error('instructor_name') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold"> Designation</label>
                        <input type="text" name="instructor_designation" class="form-control @error('instructor_designation') is-invalid @enderror"
                            value="{{ old('instructor_designation', $isEdit ? $session->instructor_designation : '') }}">
                        @error('instructor_designation') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold"> Experience</label>
                        <input type="text" name="instructor_experience" class="form-control @error('instructor_experience') is-invalid @enderror"
                            value="{{ old('instructor_experience', $isEdit ? $session->instructor_experience : '') }}">
                        @error('instructor_experience') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold"> Desc</label>
                        <input type="text" name="instructor_desc" class="form-control @error('instructor_desc') is-invalid @enderror"
                            value="{{ old('instructor_desc', $isEdit ? $session->instructor_desc : '') }}">
                        @error('instructor_desc') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold"> Logo Image1</label>
                        @if ($isEdit && $session->instructor_logo_image1)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $session->instructor_logo_image1) }}" alt="Image"
                                    class="img-thumbnail" style="max-width: 50px;" accept="image/*">
                            </div>
                        @endif

                        <input type="file" name="instructor_logo_image1" class="form-control @error('instructor_logo_image1') is-invalid @enderror"
                            accept="image/*">
                        @error('instructor_logo_image1')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold"> Logo Image2</label>
                        @if ($isEdit && $session->instructor_logo_image2)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $session->instructor_logo_image2) }}" alt="Image"
                                    class="img-thumbnail" style="max-width: 50px;" accept="image/*">
                            </div>
                        @endif

                        <input type="file" name="instructor_logo_image2" class="form-control @error('instructor_logo_image2') is-invalid @enderror"
                            accept="image/*">
                        @error('instructor_logo_image2')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>


                    <hr>
                    <h5>FAQs</h5>

                    <div class="mb-4 dynamic-faqs">
                        <label class="form-label fw-bold">FAQs</label>

                        <div class="faq-wrapper">
                            @php
                                $questions = old('faqs_question',
                                    $isEdit && $session->faqs_question
                                    ? json_decode($session->faqs_question, true)
                                    : ['']);

                                $answers = old('faqs_answer',
                                    $isEdit && $session->faqs_answer
                                    ? json_decode($session->faqs_answer, true)
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
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="best_of_industries_heading" class="form-control @error('best_of_industries_heading') is-invalid @enderror"
                            value="{{ old('best_of_industries_heading', $isEdit ? $session->best_of_industries_heading : '') }}">
                        @error('best_of_industries_heading') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 dynamic-boi">
                        <label class="form-label fw-bold">Items</label>

                        <div class="boi-wrapper">
                            @php
                                $icons = old('image_new',
                                    $isEdit && $session->image_new
                                    ? json_decode($session->image_new, true)
                                    : ['']);

                                $names = old('name_new',
                                    $isEdit && $session->name_new
                                    ? json_decode($session->name_new, true)
                                    : ['']);

                                $designations = old('Designation_new',
                                    $isEdit && $session->Designation_new
                                    ? json_decode($session->Designation_new, true)
                                    : ['']);

                                $descs = old('Description_new',
                                    $isEdit && $session->Description_new
                                    ? json_decode($session->Description_new, true)
                                    : ['']);

                                $areas = old('Areaofexperties_new',
                                    $isEdit && $session->Areaofexperties_new
                                    ? json_decode($session->Areaofexperties_new, true)
                                    : ['']);

                                $logo1s = old('logo_image1_new',
                                    $isEdit && $session->logo_image1_new
                                    ? json_decode($session->logo_image1_new, true)
                                    : ['']);

                                $logo2s = old('logo_image2_new',
                                    $isEdit && $session->logo_image2_new
                                    ? json_decode($session->logo_image2_new, true)
                                    : ['']);

                                $linkedins = old('linkedIn_new',
                                    $isEdit && $session->linkedIn_new
                                    ? json_decode($session->linkedIn_new, true)
                                    : ['']);
                            @endphp
                            @foreach($names as $index => $name)
                                <div class="boi-item border p-3 mb-3 rounded">
                                    <div class="mb-2">
                                        <label for="">Profile Image</label>
                                        <input type="file"
                                            name="image_new[]"
                                            class="form-control">
                                        @if(!empty($icons[$index]))
                                            <img src="{{ asset('storage/' . $icons[$index]) }}" width="50">
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <label for="">Name</label>
                                        <input type="text"
                                            name="name_new[]"
                                            class="form-control"
                                            value="{{ $name }}">
                                    </div>
                                    <div class="mb-2">
                                        <label for="">Designation</label>
                                        <input type="text"
                                            name="Designation_new[]"
                                            class="form-control"
                                            value="{{ $designations[$index] ?? '' }}">
                                    </div>
                                    <div class="mb-2">
                                        <label for="">Description</label>
                                        <input type="text"
                                            name="Description_new[]"
                                            class="form-control"
                                            value="{{ $descs[$index] ?? '' }}">
                                    </div>
                                    <div class="mb-2">
                                        <label for="">Area of experties</label>
                                        <input type="text"
                                            name="Areaofexperties_new[]"
                                            class="form-control"
                                            value="{{ $areas[$index] ?? '' }}">
                                    </div>
                                    <div class="mb-2">
                                        <label for="">Logo Image1 (Image)</label>
                                        <input type="file"
                                            name="logo_image1_new[]"
                                            class="form-control">
                                        @if(!empty($logo1s[$index]))
                                            <img src="{{ asset('storage/' . $logo1s[$index]) }}" width="50">
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <label for="">Logo Image2 (Image)</label>
                                        <input type="file"
                                            name="logo_image2_new[]"
                                            class="form-control">
                                        @if(!empty($logo2s[$index]))
                                            <img src="{{ asset('storage/' . $logo2s[$index]) }}" width="50">
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <label for="">Linkedin url</label>
                                        <input type="text"
                                            name="linkedIn_new[]"
                                            class="form-control"
                                            value="{{ $linkedins[$index] ?? '' }}">
                                    </div>


                                    <button type="button" class="btn btn-danger remove-boi">
                                        Remove
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-primary mt-2 add-boi">
                            + Add Item
                        </button>
                    </div>


                    <hr>
                    <h5>Final CTA</h5>
                    <div class="mb-4">
                        <label class="form-label fw-bold">CTA Description</label>
                        <input type="text" name="final_CTA_desc" class="form-control @error('final_CTA_desc') is-invalid @enderror"
                            value="{{ old('final_CTA_desc', $isEdit ? $session->final_CTA_desc : '') }}">
                        @error('final_CTA_desc') 
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sessionSelect = document.getElementById('session_id');
        const topicInput = document.getElementById('topic_name');

        if (!sessionSelect || !topicInput) {
            return;
        }

        const syncTopicName = () => {
            const selectedOption = sessionSelect.options[sessionSelect.selectedIndex];
            topicInput.value = selectedOption ? (selectedOption.dataset.sessionName || '') : '';
        };

        sessionSelect.addEventListener('change', syncTopicName);
        syncTopicName();
    });
</script>
@endpush
