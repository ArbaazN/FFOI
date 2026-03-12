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
                            <label class="form-label fw-bold">Author Name</label>
                            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                                value="{{ old('author', $isEdit ? $blog->author : '') }}">
                            @error('author')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Author Image</label>

                            @if ($isEdit && $blog->author_image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $blog->author_image) }}" alt="Blog Image"
                                        class="img-thumbnail" style="max-width: 200px;" accept="image/*">
                                </div>
                            @endif

                            <input type="file" name="author_image" class="form-control @error('author_image') is-invalid @enderror"
                                accept="image/*">
                            @error('author_image')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Author Description</label>
                            <textarea name="author_desc" class="form-control editor" rows="10">{!! old('author_desc', $isEdit ? $blog->author_desc : '') !!}</textarea>
                            @error('content')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Updated On</label>
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

                        <hr>
                        <h5>Social Share Buttons</h5>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Facebook Url</label>
                            <input type="text" name="fb_url" class="form-control @error('fb_url') is-invalid @enderror"
                                value="{{ old('fb_url', $isEdit ? $blog->fb_url : '') }}">
                            @error('fb_url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Twitter Url</label>
                            <input type="text" name="twitter_url" class="form-control @error('twitter_url') is-invalid @enderror"
                                value="{{ old('twitter_url', $isEdit ? $blog->twitter_url : '') }}">
                            @error('twitter_url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Instagram Url</label>
                            <input type="text" name="insta_url" class="form-control @error('insta_url') is-invalid @enderror"
                                value="{{ old('insta_url', $isEdit ? $blog->insta_url : '') }}">
                            @error('insta_url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">LinkedIn Url</label>
                            <input type="text" name="linkedIn_url" class="form-control @error('linkedIn_url') is-invalid @enderror"
                                value="{{ old('linkedIn_url', $isEdit ? $blog->linkedIn_url : '') }}">
                            @error('linkedIn_url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Youtube Url</label>
                            <input type="text" name="yt_url" class="form-control @error('yt_url') is-invalid @enderror"
                                value="{{ old('yt_url', $isEdit ? $blog->yt_url : '') }}">
                            @error('yt_url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        
                        <hr>
                        <h5>FAQs</h5>

                        <div class="mb-4 dynamic-faqs">
                            <label class="form-label fw-bold">FAQs</label>

                            <div class="faq-wrapper">
                                @php
                                    $questions = old('faqs_question',
                                        $isEdit && $blog->faqs_question
                                        ? json_decode($blog->faqs_question, true)
                                        : ['']);

                                    $answers = old('faqs_answer',
                                        $isEdit && $blog->faqs_answer
                                        ? json_decode($blog->faqs_answer, true)
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
        const uploadedEditorImages = [];
        const formEl = document.querySelector('form');

        class BlogUploadAdapter {
            constructor(loader) {
                this.loader = loader;
                this.xhr = null;
            }

            upload() {
                return this.loader.file
                    .then(file => new Promise((resolve, reject) => {
                        this._initRequest();
                        this._initListeners(resolve, reject, file);
                        this._sendRequest(file);
                    }));
            }

            abort() {
                if (this.xhr) {
                    this.xhr.abort();
                }
            }

            _initRequest() {
                const xhr = this.xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('blog.uploadEditorImage') }}", true);
                xhr.responseType = 'json';
                xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            }

            _initListeners(resolve, reject, file) {
                const xhr = this.xhr;
                const genericError = `Couldn't upload file: ${file.name}.`;

                xhr.addEventListener('error', () => reject(genericError));
                xhr.addEventListener('abort', () => reject());
                xhr.addEventListener('load', () => {
                    const response = xhr.response;

                    if (!response || response.error) {
                        return reject(response?.error?.message || response?.message || genericError);
                    }

                    if (response.url) {
                        uploadedEditorImages.push(response.url);
                    }

                    resolve({
                        default: response.url
                    });
                });
            }

            _sendRequest(file) {
                const data = new FormData();
                data.append('upload', file);
                this.xhr.send(data);
            }
        }

        function BlogUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new BlogUploadAdapter(loader);
            };
        }

        let blogEditorInstance = null;

        function collectEditorImageSrcs(html) {
            const div = document.createElement('div');
            div.innerHTML = html || '';
            return Array.from(div.querySelectorAll('img'))
                .map(img => img.getAttribute('src'))
                .filter(Boolean);
        }

        ClassicEditor
            .create(document.querySelector('#editor'), {
                extraPlugins: [BlogUploadAdapterPlugin],
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', '|',
                        'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'blockQuote', '|',
                        'insertImage', 'imageUpload', 'mediaEmbed', '|',
                        'undo', 'redo'
                    ]
                },
                mediaEmbed: {
                    previewsInData: true
                }
            })
            .then(editor => {
                blogEditorInstance = editor;

                if (formEl) {
                    formEl.addEventListener('submit', () => {
                        const used = new Set(collectEditorImageSrcs(editor.getData()));
                        const removed = uploadedEditorImages.filter(url => !used.has(url));
                        const input = document.getElementById('removed_editor_images');
                        if (input) {
                            input.value = JSON.stringify(removed);
                        }
                    });
                }
            })
            .catch(error => console.error(error));

    </script>
@endpush