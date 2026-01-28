@extends('layout.admin.index')

@section('page_title', isset($blog) ? 'Edit Blog' : 'Create Blog')

@section('admin-main-content')

    @php
        $isEdit = isset($blog);
    @endphp

    <div class="row mb-6 gy-6">
        <div class="col-xxl">
            <div class="card">

                <div class="card-body">
                    <form method="POST" action="{{ $isEdit ? route('blog.update', $blog->id) : route('blog.store') }}"
                        enctype="multipart/form-data">

                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        {{-- SEO --}}
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                        <h5>SEO</h5>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Meta Title</label>
                            <input type="text" class="form-control" maxlength="60" name="meta_title"
                                value="{{ old('meta_title', $isEdit ? $blog->meta_title : '') }}">
                            <small class="text-muted">Recommended max 60 characters.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Meta Description</label>
                            <textarea class="form-control" maxlength="160" rows="2" name="meta_description">{{ old('meta_description', $isEdit ? $blog->meta_description : '') }}</textarea>
                            <small class="text-muted">Recommended max 160 characters.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Meta Keys</label>
                            <input type="text" class="form-control" id="TagifyBasic" name="meta_keywords"
                                value="{{ old('meta_keywords', $isEdit ? $blog->meta_keywords : '') }}">
                            <small class="text-muted">Comma separated keywords for SEO.</small>
                        </div>
                        {{-- </div>
                        </div> --}}

                        <hr>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Blog Image</label>

                            @if ($isEdit && $blog->images)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $blog->images) }}" alt="Blog Image"
                                        class="img-thumbnail" style="max-width: 200px;" accept="image/*">
                                </div>
                            @endif

                            <input type="file" name="images" class="form-control @error('images') is-invalid @enderror"
                                accept="image/*">
                            @error('images')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Mobile Blog Image</label>

                            @if ($isEdit && $blog->mobile_image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $blog->mobile_image) }}" alt="Mobile Blog Image"
                                        class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            @endif

                            <input type="file" name="mobile_image"
                                class="form-control @error('mobile_image') is-invalid @enderror" accept="image/*">

                            @error('mobile_image')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="mb-4">
                            <label class="form-label fw-bold">Title</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $isEdit ? $blog->title : '') }}">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Subtitle</label>
                            <input type="text" name="subtitle"
                                class="form-control @error('subtitle') is-invalid @enderror"
                                value="{{ old('subtitle', $isEdit ? $blog->subtitle : '') }}">
                            @error('subtitle')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Blog Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">Select Category</option>

                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $isEdit ? $blog->category_id : '') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Author</label>
                            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                                value="{{ old('author', $isEdit ? $blog->author : '') }}">
                            @error('author')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Publish Date</label>
                            <input type="date" name="publish_date"
                                class="form-control @error('publish_date') is-invalid @enderror"
                                value="{{ old('publish_date', $isEdit && $blog->publish_date ? $blog->publish_date->format('Y-m-d') : '') }}">
                            @error('publish_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Feature Blog</label>
                            <div class="form-check">
                                <input type="checkbox" name="feature_content" value="1" class="form-check-input"
                                    {{ old('feature_content', $isEdit ? $blog->feature_content : 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label">Mark as featured</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Content</label>
                            <textarea name="content" id="editor" class="form-control" rows="10">{!! old('content', $isEdit ? $blog->content : '') !!}</textarea>
                            @error('content')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="">Select Status</option>

                                <option value="1"
                                    {{ old('status', $isEdit ? $blog->status : 1) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0"
                                    {{ old('status', $isEdit ? $blog->status : 1) == 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                {{ $isEdit ? 'Update Blog' : 'Add Blog' }}
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', '|',
                        'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'blockQuote', '|',
                        'undo', 'redo'
                    ]
                },
            })
            .catch(error => console.error(error));
    </script>
@endpush