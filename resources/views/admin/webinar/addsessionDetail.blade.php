@extends('layout.admin.index')
@if($session->exists!=false)
    @section('page_title', 'Edit Upcoming Session Detail')
@else
    @section('page_title', 'Create Upcoming Session Detail')
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
                        <label class="form-label fw-bold">Session Name</label>

                        <select name="session_id" class="form-control @error('session_id') is-invalid @enderror">
                            <option value="">Select</option>

                            @foreach($category as $categoryName)
                                <option value="{{ $categoryName->id }}"
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
                        <label class="form-label fw-bold">Topic Name</label>
                        <input type="text" name="topic_name" class="form-control @error('topic_name') is-invalid @enderror"
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
                            value="{{ old('date', $isEdit ? $session->date : '') }}">
                        @error('date') 
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Time</label>
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

<script>
document.addEventListener("DOMContentLoaded", function () {

    document.addEventListener("click", function (e) {

        if (e.target.classList.contains("add-point")) {

            let section = e.target.closest(".dynamic-points");
            let wrapper = section.querySelector(".points-wrapper");

            let inputName = wrapper.querySelector("input").getAttribute("name");

            wrapper.insertAdjacentHTML("beforeend", `
                <div class="input-group mb-2 point-item">
                    <input type="text"
                           name="${inputName}"
                           class="form-control"
                           placeholder="Enter point">
                    <button type="button" class="btn btn-danger remove-point">
                        Remove
                    </button>
                </div>
            `);
        }

        if (e.target.classList.contains("remove-point")) {
            e.target.closest(".point-item").remove();
        }

        if (e.target.classList.contains("add-faq")) {

            let section = e.target.closest(".dynamic-faqs");
            let wrapper = section.querySelector(".faq-wrapper");

            wrapper.insertAdjacentHTML("beforeend", `
                <div class="faq-item border p-3 mb-3 rounded">
                    <div class="mb-2">
                        <input type="text"
                               name="faqs_question[]"
                               class="form-control"
                               placeholder="Enter question">
                    </div>
                    <div class="mb-2">
                        <textarea name="faqs_answer[]"
                                  class="form-control"
                                  placeholder="Enter answer"></textarea>
                    </div>
                    <button type="button" class="btn btn-danger remove-faq">
                        Remove
                    </button>
                </div>
            `);
        }

        if (e.target.classList.contains("remove-faq")) {
            e.target.closest(".faq-item").remove();
        }

    });

});
</script>
@endsection