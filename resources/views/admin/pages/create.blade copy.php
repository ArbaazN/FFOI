@extends('layout.admin.index')
@section('page_title', 'Page: ' . $page->title)

@section('admin-main-content')
    @php
        use Illuminate\Support\Str;
        use App\Models\Admin\Media;
    @endphp

    <form method="POST" action="{{ route('pages.update', $page->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- BASIC INFO --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Title</label>
                    <input type="text" class="form-control" name="title" value="{{ old('title', $page->title) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Slug</label>
                    <input type="text" class="form-control" name="slug" value="{{ old('slug', $page->slug) }}"
                        readonly>
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="card">
            <div class="card-body">
                <h5>SEO</h5>
                <div class="mb-3">
                    <label class="form-label fw-bold">Meta Title</label>
                    <input type="text" class="form-control" maxlength="60" name="meta_title"
                        value="{{ old('meta_title', $page->meta_title) }}">
                    <small class="text-muted">Recommended max 60 characters.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Meta Description</label>
                    <textarea class="form-control" maxlength="160" rows= "2" name="meta_description">{{ old('meta_description', $page->meta_description) }}</textarea>
                    <small class="text-muted">Recommended max 160 characters.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Meta Keys</label>
                    <input type="text" class="form-control" id="TagifyBasic" name="meta_keys"
                        value="{{ old('meta_keys', $page->meta_keys) }}">
                    <small class="text-muted">Comma separated keywords for SEO.</small>
                </div>
            </div>
        </div>

        <hr>

        {{-- SECTION NAVIGATION --}}
        <div class="mb-4 p-3 border rounded bg-light">
            <h5 class="mb-2">Quick Navigation</h5>
            @foreach ($content['sections'] as $i => $sec)
                <a href="#section-{{ $i }}" class="btn btn-sm btn-outline-primary me-2 mb-2">
                    {{ ucfirst($sec['type']) }}
                </a>
            @endforeach
        </div>

        <hr>

        @php
            if (!isset($content['sections'])) {
                $content['sections'] = [];
            }
        @endphp

        {{-- LOOP SECTIONS --}}
        @foreach ($content['sections'] as $index => $section)
            @php
                $sectionType = $section['type'];
                $sectionData = $section['data'] ?? [];
            @endphp

            <div class="card mb-4" id="section-{{ $index }}">
                <div class="card-header bg-dark text-white">
                    Section: {{ ucfirst($sectionType) }}
                </div>

                <div class="card-body">
                    <input type="hidden" name="sections[{{ $index }}][type]" value="{{ $sectionType }}">

                    {{-- LOOP FIELDS INSIDE SECTION --}}
                    @foreach ($sectionData as $field => $value)
                        @php
                            $label = ucfirst(str_replace('_', ' ', $field));

                            $isArray = is_array($value);
                            $isAssocObject = $isArray && array_keys($value) !== range(0, count($value) - 1);
                            $isRepeater = $isArray && !$isAssocObject && isset($value[0]) && is_array($value[0]);

                            // Gallery / images array (e.g. "images", "gallery", "photos")
                            $isImageArray =
                                $isArray &&
                                !$isAssocObject &&
                                !$isRepeater &&
                                Str::contains(strtolower($field), ['images', 'gallery', 'photos']);

                            $isIcon = Str::contains(strtolower($field), 'icon');
                            $isImage = Str::contains(strtolower($field), ['img', 'image', 'src', 'logo']);
                            $isVideo = Str::contains(strtolower($field), ['video', 'mp4', 'webm', 'ogg']);

                            $isHtml = is_string($value) && Str::contains($value, ['<strong', '<p', '<br', '</']);
                            $isStringList = is_string($value) && str_contains($value, '<li>');

                            $isLongText = is_string($value) && strlen($value) > 200 && !$isHtml;
                            $isPlainString = is_string($value) && !$isHtml && !$isStringList && !$isImage && !$isVideo;
                        @endphp

                        {{-- 1) ASSOC OBJECT: e.g. {"line1": "...", "line2": "..."} --}}
                        @if ($isAssocObject)
                            <div class="mb-3 pt-1 border p-3 rounded">
                                <label class="form-label fw-bold">{{ $label }}</label>

                                @foreach ($value as $childKey => $childVal)
                                    <div class="mb-3 pt-1">
                                        <label class="form-label fw-bold">{{ ucfirst($childKey) }}</label>
                                        <input type="text"
                                            name="sections[{{ $index }}][data][{{ $field }}][{{ $childKey }}]"
                                            class="form-control" value="{{ $childVal }}">
                                    </div>
                                @endforeach
                            </div>

                            {{-- 2) REPEATER: array of objects --}}
                        @elseif ($isRepeater)
                            <div class="mb-3 pt-1">
                                <label class="form-label fw-bold">{{ $label }} (Repeater)</label>

                                <div id="repeater-{{ $index }}-{{ $field }}">
                                    @foreach ($value as $itemIndex => $item)
                                        <div class="border p-3 mb-3 repeater-item rounded">
                                            <button type="button"
                                                class="btn btn-danger btn-sm float-end remove-repeater-item">
                                                Remove
                                            </button>

                                            <h6>Item {{ $itemIndex + 1 }}</h6>

                                            @foreach ($item as $itemField => $itemValue)
                                                @php
                                                    $itemLabel = ucfirst(str_replace('_', ' ', $itemField));
                                                    $itemPath = "sections[$index][data][$field][$itemIndex][$itemField]";
                                                    $itemIsArray = is_array($itemValue);
                                                    $itemIsImageArray =
                                                        $itemIsArray &&
                                                        !empty($itemValue) &&
                                                        Str::contains(strtolower($itemField), [
                                                            'images',
                                                            'gallery',
                                                            'photos',
                                                        ]);
                                                    $itemIsListHtml =
                                                        is_string($itemValue) && str_contains($itemValue, '<li>');
                                                    $itemIsIcon = Str::contains(strtolower($itemField), 'icon');
                                                    $itemIsImage = Str::contains(strtolower($itemField), [
                                                        'img',
                                                        'image',
                                                        'src',
                                                        'logo',
                                                    ]);
                                                    $itemIsVideo = Str::contains(strtolower($itemField), [
                                                        'video',
                                                        'mp4',
                                                        'webm',
                                                        'ogg',
                                                    ]);
                                                    $itemIsHtml =
                                                        is_string($itemValue) &&
                                                        Str::contains($itemValue, ['<strong', '<p', '<br', '</']);
                                                    $itemIsPlainText =
                                                        is_string($itemValue) &&
                                                        !$itemIsHtml &&
                                                        !$itemIsListHtml &&
                                                        !$itemIsImage &&
                                                        !$itemIsVideo;
                                                @endphp

                                                {{-- LIST inside object --}}
                                                @if ($itemIsListHtml) <div class="mb-3 pt-1">
                                                        <label class="form-label fw-bold">{{ $itemLabel }} (List)</label>
                                                        <textarea class="form-control editor"
                                                                  name="{{ $itemPath }}"
                                                                  rows="5">{!! $itemValue !!}</textarea>
                                                    </div>

                                                {{-- NESTED ARRAY / JSON --}}
                                                @elseif ($itemIsArray && !$itemIsImageArray)
                                                    <div class="mb-3 pt-1">
                                                        <label class="form-label fw-bold">{{ $itemLabel }} (JSON)</label>
                                                        <textarea class="form-control"
                                                                  name="{{ $itemPath }}"
                                                                  rows="4">{{ json_encode($itemValue, JSON_PRETTY_PRINT) }}</textarea>
                                                    </div>

                                                {{-- IMAGE (single) --}}
                                                @elseif ($itemIsImage)
                                                    @php
                                                        $itemMedia = is_numeric($itemValue) ? Media::find($itemValue) : null;
                                                    @endphp
                                                    <div class="mb-3 pt-1">
                                                        <label class="form-label fw-bold">{{ $itemLabel }} (Image)</label>

                                                        @if ($itemMedia)
                                                            <img src="{{ $itemMedia->file_url }}" alt=""
                                                                 class="img-fluid mb-2"
                                                                 style="max-height: 150px; object-fit: contain;">
                                                                 
                                                            <a href="{{ $itemMedia->file_url }}" target="_blank"
                                                               class="d-block mb-2 text-primary">
                                                                View Image
                                                            </a>
                                                        {{-- @elseif (is_string($itemValue) && $itemValue)
                                                            <a href="{{ $itemValue }}" target="_blank"
                                                               class="d-block mb-2 text-primary">
                                                                Existing Image
                                                            </a> --}} @endif

                                                <input type="file" class="form-control" name="{{ $itemPath }}"
                                                    accept="image/*">
                                                <input type="hidden" name="{{ $itemPath }}_old"
                                                    value="{{ $itemValue }}">
                                        </div>

                                        {{-- VIDEO --}}
                                    @elseif ($itemIsVideo)
                                        <div class="mb-3 pt-1">
                                            <label class="form-label fw-bold">{{ $itemLabel }} (Video)</label>

                                            @if (is_string($itemValue) && $itemValue)
                                                <video controls width="250" class="d-block mb-2">
                                                    <source src="{{ $itemValue }}" type="video/mp4">
                                                </video>
                                                <a href="{{ $itemValue }}" target="_blank"
                                                    class="d-block mb-2 text-primary">
                                                    Download / View Video
                                                </a>
                                            @endif

                                            <input type="file" class="form-control" name="{{ $itemPath }}"
                                                accept="video/mp4,video/webm,video/ogg">
                                            <input type="hidden" name="{{ $itemPath }}_old"
                                                value="{{ $itemValue }}">
                                        </div>

                                        {{-- HTML --}}
                                    @elseif ($itemIsHtml)
                                        <div class="mb-3 pt-1">
                                            <label class="form-label fw-bold">{{ $itemLabel }}</label>
                                            <textarea class="form-control editor" name="{{ $itemPath }}" rows="3">{{ $itemValue }}</textarea>
                                        </div>

                                        {{-- PLAIN TEXT --}}
                                    @elseif ($itemIsPlainText)
                                        <div class="mb-3 pt-1">
                                            <label class="form-label fw-bold">{{ $itemLabel }}</label>
                                            <textarea class="form-control editor" name="{{ $itemPath }}" rows="3">{{ $itemValue }}</textarea>
                                        </div>

                                        {{-- ICON --}}
                                    @elseif ($itemIsIcon)
                                        <div class="mb-3 pt-1">
                                            <label class="form-label fw-bold">{{ $itemLabel }} (Icon)</label>
                                            <input type="text" class="form-control" name="{{ $itemPath }}"
                                                value="{{ $itemValue }}">
                                        </div>

                                        {{-- FALLBACK --}}
                                    @else
                                        <div class="mb-3 pt-1">
                                            <label class="form-label fw-bold">{{ $itemLabel }}</label>
                                            <textarea class="form-control editor" name="{{ $itemPath }}" rows="3">{{ is_string($itemValue) ? $itemValue : json_encode($itemValue) }}</textarea>
                                        </div>
                                    @endif
                        @endforeach
                </div>
        @endforeach
        </div>

        <button type="button" class="btn btn-primary add-repeater-item mt-2" data-section="{{ $index }}"
            data-field="{{ $field }}">
            Add Item
        </button>
        </div>

        {{-- 3) ARRAY OF IMAGES (GALLERY) --}}
    @elseif ($isImageArray)
        <div class="mb-3 pt-1">
            <label class="form-label fw-bold">{{ $label }} (Gallery Images)</label>

            <div class="border p-3 rounded">
                @foreach ($value as $imgIndex => $imgVal)
                    @php
                        $galleryMedia = is_numeric($imgVal) ? Media::find($imgVal) : null;
                    @endphp

                    <div class="mb-3 pt-1">
                        <label>Image {{ $imgIndex + 1 }}</label>
                        <img src="{{ $galleryMedia ? $galleryMedia->file_url : $imgVal }}" alt=""
                            class="img-fluid d-block mb-2" style="max-height: 150px; object-fit: contain;">
                        @if ($galleryMedia)
                            <a href="{{ $galleryMedia->file_url }}" target="_blank" class="d-block text-primary mb-2">
                                View Image
                            </a>
                            {{-- @elseif (is_string($imgVal) && $imgVal)
                                                <a href="{{ $imgVal }}"
                                                   target="_blank"
                                                   class="d-block text-primary mb-2">
                                                    Existing File
                                                </a> --}}
                        @endif

                        <input type="file" class="form-control"
                            name="sections[{{ $index }}][data][{{ $field }}][{{ $imgIndex }}][file]"
                            accept="image/*">

                        <input type="hidden"
                            name="sections[{{ $index }}][data][{{ $field }}][{{ $imgIndex }}][old]"
                            value="{{ $imgVal }}">
                    </div>
                @endforeach

                <button type="button" class="btn btn-sm btn-primary add-gallery-image"
                    data-section="{{ $index }}" data-field="{{ $field }}">
                    Add Image
                </button>
            </div>
        </div>

        {{-- 4) STRING LIST (<li>...</li>) --}}
    @elseif ($isStringList)
        <div class="mb-3 pt-1">
            <label class="form-label fw-bold">{{ $label }} (List)</label>
            <textarea class="form-control editor" name="sections[{{ $index }}][data][{{ $field }}]"
                rows="6">{!! $value !!}</textarea>
        </div>

        {{-- 5) SINGLE IMAGE FIELD --}}
    @elseif ($isImage)
        @php
            $fieldMedia = is_numeric($value) ? Media::find($value) : null;
        @endphp
        <div class="mb-3 pt-1">
            <label class="form-label">{{ $label }} (Image)</label>

            @if ($fieldMedia)
                <div>
                    <a href="{{ $fieldMedia->file_url }}" target="_blank" class="d-block mb-2 text-primary">
                        <img src="{{ $fieldMedia->file_url }}" alt="" class="img-fluid mb-2"
                            style="max-height: 60px; object-fit: contain;">
                    </a>
                    {{-- @elseif (is_string($value) && $value)
                                        <a href="{{ $value }}" target="_blank"
                                        class="d-block mb-2 text-primary">
                                        Existing Image
                                    </a> --}}
                </div>
            @endif

            <input type="file" class="form-control"
                name="sections[{{ $index }}][data][{{ $field }}][file]" accept="image/*">
            <input type="hidden" name="sections[{{ $index }}][data][{{ $field }}][old]"
                value="{{ $value }}">
        </div>

        {{-- 6) VIDEO FIELD --}}
    @elseif ($isVideo)
        @php
            $fieldMedia = is_numeric($value) ? Media::find($value) : null;
            $videoUrl = $fieldMedia ? $fieldMedia->file_url : (is_string($value) ? $value : null);
        @endphp
        <div class="mb-3 pt-1">
            <label class="form-label">{{ $label }} (Video)</label>

            @if ($videoUrl)
                <video controls width="250" class="d-block mb-2">
                    <source src="{{ $videoUrl }}" type="video/mp4">
                </video>
                <a href="{{ $videoUrl }}" target="_blank" class="d-block text-primary mb-2">
                    Download / View Video
                </a>
            @endif

            <input type="file" class="form-control"
                name="sections[{{ $index }}][data][{{ $field }}][file]"
                accept="video/mp4,video/webm,video/ogg">
            <input type="hidden" name="sections[{{ $index }}][data][{{ $field }}][old]"
                value="{{ $value }}">
        </div>

        {{-- 7) HTML FIELD --}}
        @elseif ($isHtml)
            <div class="mb-3 pt-1">
                <label class="form-label fw-bold">{{ $label }}</label>
                <textarea class="form-control editor" name="sections[{{ $index }}][data][{{ $field }}]"
                    rows="4">{{ $value }}</textarea>
            </div>

        {{-- 8) LONG TEXT --}}
    @elseif ($isLongText)
        <div class="mb-3 pt-1">
            <label class="form-label fw-bold">{{ $label }}</label>
            <textarea class="form-control editor" name="sections[{{ $index }}][data][{{ $field }}]"
                rows="3">{{ $value }}</textarea>
        </div>

        {{-- 9) ICON FIELD --}}
    @elseif ($isIcon)
        <div class="mb-3 pt-1">
            <label class="form-label fw-bold">{{ $label }} (Icon)</label>
            <input type="text" class="form-control" name="sections[{{ $index }}][data][{{ $field }}]"
                value="{{ $value }}">
        </div>

        {{-- 10) ARRAY FALLBACK → JSON --}}
    @elseif ($isArray)
        <div class="mb-3 pt-1">
            <label class="form-label fw-bold">{{ $label }} (JSON)</label>
            <textarea class="form-control" name="sections[{{ $index }}][data][{{ $field }}]" rows="5">{{ json_encode($value, JSON_PRETTY_PRINT) }}</textarea>
        </div>

        {{-- 11) SIMPLE STRING --}}
    @elseif ($isPlainString)
        <div class="mb-3 pt-1">
            <label class="form-label">{{ $label }}</label>
            <textarea class="form-control editor" name="sections[{{ $index }}][data][{{ $field }}]"
                rows="3">{{ $value }}</textarea>
        </div>

        {{-- 12) CATCH-ALL --}}
    @else
        <div class="mb-3 pt-1">
            <label class="form-label">{{ $label }}</label>
            <textarea class="form-control editor" name="sections[{{ $index }}][data][{{ $field }}]"
                rows="3">{{ is_string($value) ? $value : json_encode($value) }}</textarea>
        </div>
        @endif
        @endforeach
        </div>
        </div>
        @endforeach

        <button class="btn btn-success">Save Changes</button>
    </form>
@endsection
