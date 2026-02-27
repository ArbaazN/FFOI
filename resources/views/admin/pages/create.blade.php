@extends('layout.admin.index')
@section('page_title', 'Page: ' . $page->title)

@section('admin-main-content')
    <style>
        .quick-nav-links {
            scrollbar-width: thin;
            scrollbar-color: #9aa0a6 transparent;
        }
        .quick-nav-links::-webkit-scrollbar {
            height: 6px;
        }
        .quick-nav-links::-webkit-scrollbar-track {
            background: transparent;
        }
        .quick-nav-links::-webkit-scrollbar-thumb {
            background-color: #9aa0a6;
            border-radius: 999px;
        }
    </style>
    @php
        use Illuminate\Support\Str;
        use App\Models\Admin\Media;

        function formatSectionName($name)
        {
            $removeWords = ['input', 'in','Textarea','textarea'];
            $name = str_replace('_', ' ', $name);

            // Convert camelCase to spaces
            $name = preg_replace('/(?<!^)([A-Z])/', ' $1', $name);

            // Split into words
            $parts = preg_split('/\s+/', trim($name));

            // Remove unwanted words (case-insensitive)
            $removeWords = array_map('strtolower', $removeWords);

            $parts = array_filter($parts, function ($part) use ($removeWords) {
                return !in_array(strtolower($part), $removeWords, true);
            });
            return ucwords(implode(' ', $parts));
        }
    @endphp

<div id="pageLoader" class="page-loader">
    <div class="loader-box">
        <div class="spinner"></div>
        <div class="loader-text">Loading page… please wait</div>
    </div>
</div>

<div id="pageContent" style="display:none;">

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
                    <textarea class="form-control" maxlength="160" rows="2" name="meta_description">{{ old('meta_description', $page->meta_description) }}</textarea>
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
        <div class="mb-4 p-3 border rounded bg-light sticky-top quick-nav">
            <div class="mt-2 justify-content-between d-flex align-items-center mb-3">
                <h5 class="mb-2">Quick Navigation</h5>
                <button class="btn btn-success">Save Changes</button>
            </div>
            <div class="quick-nav-links d-flex flex-nowrap gap-2 pb-1" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                @foreach ($content['sections'] as $i => $sec)
                    <a href="#section-{{ $i }}" class="btn btn-sm btn-outline-primary flex-shrink-0">
                        {{ formatSectionName(ucfirst($sec['type'])) }}
                    </a>
                @endforeach
            </div>
        </div>

        <hr>

        <div>
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
                    $sectionStatus = $section['status'] ?? 'enable'; // default
                @endphp

                <div class="card mb-4" id="section-{{ $index }}" style="scroll-margin-top: 210px;">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <div>
                            Section: {{ formatSectionName($sectionType) }}
                            <span id="section-status-badge-{{ $index }}"
                                class="badge {{ $sectionStatus === 'enable' ? 'bg-success' : 'bg-danger' }} ms-2">
                                {{ $sectionStatus === 'enable' ? 'Enabled' : 'Disabled' }}
                            </span>

                        </div>
                        <div>
                            <button type="button"
                                class="btn btn-sm {{ $sectionStatus === 'enable' ? 'btn-danger' : 'btn-success' }}"
                                onclick="toggleSectionStatus({{ $index }})"
                                id="section-toggle-btn-{{ $index }}">
                                {{ $sectionStatus === 'enable' ? 'Disable' : 'Enable' }}
                            </button>

                        </div>
                    </div>

                    <div class="card-body">
                        <input type="hidden" name="sections[{{ $index }}][type]" value="{{ $sectionType }}">
                        <input type="hidden" name="sections[{{ $index }}][status]"
                            id="section-status-{{ $index }}" value="{{ $sectionStatus }}">

                        {{-- LOOP FIELDS INSIDE SECTION --}}
                        @foreach ($sectionData as $field => $value)
                            @php
                                $label = ucfirst(str_replace('_', ' ', $field));

                                $isArray = is_array($value);
                                $isAssocObject = $isArray && array_keys($value) !== range(0, count($value) - 1);

                                // DO NOT treat multipleImg as repeater (we want gallery)
                                $isRepeater =
                                    $isArray &&
                                    !$isAssocObject &&
                                    isset($value[0]) &&
                                    is_array($value[0]) &&
                                    $field !== 'multipleImages';

                                $isInput = Str::contains(strtolower($field), 'input');
                                $isTextarea = Str::contains(strtolower($field), 'textarea');
                                $isEnabledKey = Str::contains(strtolower($field), 'enabled');
                                $isSelectKey = Str::contains(strtolower($field), 'select') || Str::contains(strtolower($field), 'styleinput');
                                $isButtonStyleSelect = strtolower($field) === 'buttonstyleselect';
                                $isGallery = Str::contains(strtolower($field), 'multipleimages');
                                $isImage = Str::contains(strtolower($field), ['img', 'image', 'src', 'logo']);
                                $isVideo = Str::contains(strtolower($field), ['video', 'mp4', 'webm', 'ogg']);

                                $isHtml = is_string($value) && Str::contains($value, ['<strong', '<p', '<br', '</']);
                                $isStringList = is_string($value) && str_contains($value, '<li>');

                                $isLongText = is_string($value) && strlen($value) > 200 && !$isHtml;
                                $isPlainString =
                                    is_string($value) && !$isHtml && !$isStringList && !$isImage && !$isVideo;

                                // --- unified gallery detection ---
                                $isSimpleImageList =
                                    $isArray &&
                                    !$isAssocObject &&
                                    !$isRepeater &&
                                    (is_string($value[0] ?? null) || is_numeric($value[0] ?? null));

                                $isObjectImageList =
                                    $isArray &&
                                    !$isAssocObject &&
                                    !$isRepeater &&
                                    isset($value[0]) &&
                                    is_array($value[0]) &&
                                    isset($value[0]['image']);

                                // $isGallery = $isSimpleImageList || $isObjectImageList;
                            @endphp


                            {{-- UNIFIED GALLERY --}}
                            @if ($isInput)
                                <div class="mb-3 pt-1">
                                    <label class="form-label fw-bold">{{ formatSectionName($label) }}</label>
                                    <input type="text" class="form-control"
                                        name="sections[{{ $index }}][data][{{ $field }}]"
                                        value="{{ $value }}">
                                </div>
                            @elseif ($isTextarea)
                                <div class="mb-3 pt-1">
                                    <label class="form-label fw-bold">{{ formatSectionName($label) }}</label>
                                    <textarea class="form-control" name="sections[{{ $index }}][data][{{ $field }}]"
                                        rows="4">{{ $value }}</textarea>
                                </div>
                            @elseif ($isEnabledKey)
                                <div class="mb-3 pt-1">
                                    <label class="form-label fw-bold">{{ formatSectionName($label) }}</label>
                                    <select class="form-select" name="sections[{{ $index }}][data][{{ $field }}]">
                                        <option value="true" {{ $value ? 'selected' : '' }}>true</option>
                                        <option value="false" {{ !$value ? 'selected' : '' }}>false</option>
                                    </select>
                                </div>
                            @elseif ($isSelectKey)
                                @php
                                    $buttonStyleOptions = ['primary', 'secondary', 'default'];
                                    if (is_string($value) && $value !== '' && !in_array($value, $buttonStyleOptions, true)) {
                                        $buttonStyleOptions[] = $value;
                                    }
                                @endphp
                                <div class="mb-3 pt-1">
                                    <label class="form-label fw-bold">{{ formatSectionName($label) }}</label>
                                    <select class="form-select" name="sections[{{ $index }}][data][{{ $field }}]">
                                        @foreach ($buttonStyleOptions as $opt)
                                            <option value="{{ $opt }}" {{ $value === $opt ? 'selected' : '' }}>
                                                {{ ucfirst($opt) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif ($isGallery)
                                <div class="mb-3 pt-1">
                                    <label class="form-label fw-bold">{{ formatSectionName($label) }} (Gallery)</label>

                                    <div class="border p-3 rounded">

                                        <div class="d-flex flex-wrap gap-2">

                                            @foreach ($value as $imgIndex => $imgItem)
                                                @php
                                                    if (is_array($imgItem) && isset($imgItem['image'])) {
                                                        $imgUrl = $imgItem['image'];
                                                        $imgId = $imgItem['id'] ?? null;
                                                    } else {
                                                        $media = is_numeric($imgItem) ? Media::find($imgItem) : null;
                                                        $imgUrl = $media ? $media->file_url : $imgItem;
                                                        $imgId = is_numeric($imgItem) ? $imgItem : null;
                                                    }
                                                @endphp

                                                @if ($imgUrl)
                                                    <div class="gallery-item position-relative border rounded p-1"
                                                        style="width:120px; height:120px; overflow:hidden;"
                                                        data-img-id="{{ $imgId }}">

                                                        <img src="{{ $imgUrl }}" class="w-100 h-100"
                                                            style="object-fit:cover;">

                                                        {{-- DELETE BUTTON --}}
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger delete-gallery-image position-absolute"
                                                            style="top:0px; right:0px; border-radius:50%; padding:1px 5px;">
                                                            X
                                                        </button>

                                                        {{-- hidden input to keep image id --}}
                                                        <input type="hidden"
                                                            name="sections[{{ $index }}][data][{{ $field }}][keep][]"
                                                            value="{{ $imgId }}">
                                                    </div>
                                                @endif
                                            @endforeach

                                        </div>

                                        <hr>

                                        {{-- Multi file input (new uploads) --}}
                                        <label class="fw-bold">Add More Images</label>
                                        <input type="file" class="form-control" accept="image/*"
                                            name="sections[{{ $index }}][data][{{ $field }}][files][]"
                                            multiple>

                                        {{-- old gallery (can be IDs, urls, or objects) --}}
                                        <input type="hidden"
                                            name="sections[{{ $index }}][data][{{ $field }}][old]"
                                            value="{{ json_encode($value) }}">

                                        {{-- marker so controller knows special gallery merge --}}
                                        <input type="hidden"
                                            name="sections[{{ $index }}][data][{{ $field }}][_is_gallery]"
                                            value="1">

                                    </div>
                                </div>
                                {{-- ASSOC OBJECT --}}
                            @elseif ($isAssocObject)
                                <div class="mb-3 pt-1 border p-3 rounded">
                                    <label class="form-label fw-bold">{{ formatSectionName($label) }}</label>

                                    @foreach ($value as $childKey => $childVal)
                                        @php
                                            $childKeyLower = strtolower($childKey);
                                            $childIsEnabled = $childKeyLower === 'enabled' || Str::contains($childKeyLower, 'enabled');
                                            $childIsButtonStyleSelect = $childKeyLower === 'buttonstyleselect';
                                            $childIsSelect = $childIsButtonStyleSelect || Str::contains($childKeyLower, 'select') || Str::contains($childKeyLower, 'styleinput');
                                            $childIsScalar = is_string($childVal) || is_numeric($childVal) || is_bool($childVal) || is_null($childVal);
                                            $childDisplay = $childIsScalar ? $childVal : json_encode($childVal);

                                            $buttonStyleOptions = ['primary', 'secondary', 'default'];
                                            if ($childIsSelect && is_string($childVal) && $childVal !== '' && !in_array($childVal, $buttonStyleOptions, true)) {
                                                $buttonStyleOptions[] = $childVal;
                                            }
                                        @endphp
                                        <div class="mb-3 pt-1">
                                            <label class="form-label fw-bold">{{ ucfirst($childKey) }}</label>
                                            @if ($childIsEnabled)
                                                <select class="form-select"
                                                    name="sections[{{ $index }}][data][{{ $field }}][{{ $childKey }}]">
                                                    <option value="true" {{ $childVal ? 'selected' : '' }}>true</option>
                                                    <option value="false" {{ !$childVal ? 'selected' : '' }}>false</option>
                                                </select>
                                            @elseif ($childIsSelect)
                                                <select class="form-select"
                                                    name="sections[{{ $index }}][data][{{ $field }}][{{ $childKey }}]">
                                                    @foreach ($buttonStyleOptions as $opt)
                                                        <option value="{{ $opt }}" {{ $childVal === $opt ? 'selected' : '' }}>
                                                            {{ ucfirst($opt) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @elseif ($childIsScalar)
                                                <input type="text"
                                                    name="sections[{{ $index }}][data][{{ $field }}][{{ $childKey }}]"
                                                    class="form-control" value="{{ $childDisplay }}">
                                            @else
                                                <textarea class="form-control"
                                                    name="sections[{{ $index }}][data][{{ $field }}][{{ $childKey }}]"
                                                    rows="3">{{ $childDisplay }}</textarea>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                {{-- REPEATER --}}
                            @elseif ($isRepeater)
                                <div class="mb-3 pt-1">
                                    <label class="form-label fw-bold">{{ formatSectionName($label) }} (Repeater)</label>

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
                                                        $itemIsAssocObject = $itemIsArray && array_keys($itemValue) !== range(0, count($itemValue) - 1);
                                                        $isNestedRepeater =
                                                            $itemIsArray &&
                                                            isset($itemValue[0]) &&
                                                            is_array($itemValue[0]) &&
                                                            $itemField !== 'multipleImages';
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
                                                        $itemIsInput = Str::contains(strtolower($itemField), 'input');
                                                        $itemIsTextarea = Str::contains(strtolower($itemField), 'textarea');
                                                        $itemIsEnabled = Str::contains(strtolower($itemField), 'enabled');
                                                        $itemIsSelect = Str::contains(strtolower($itemField), 'select') || Str::contains(strtolower($itemField), 'styleinput');
                                                        $itemIsButtonStyleSelect = strtolower($itemField) === 'buttonstyleselect';
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

                                                    @if ($isNestedRepeater) <div class=" nested-repeater mt-3 p-3 border rounded">
                                                            <label class="form-label fw-bold">
                                                                {{ formatSectionName($itemLabel) }} (Nested Repeater)
                                                            </label>

                                                            @foreach ($itemValue as $nestedIndex => $nestedItem)
                                                                <div class="nested-repeater-item border p-2 mb-2 rounded">
                                                                    <button type="button" class="btn btn-danger btn-sm float-end remove-nested-repeater">Remove</button>
                                                                    <h6>Item {{ $nestedIndex + 1 }}</h6>

                                                                    @foreach ($nestedItem as $nestedField => $nestedValue)
                                                                        @php
                                                                            $nestedFieldLower = strtolower($nestedField);
                                                                            $nestedPath =
                                                                                "sections[$index][data][$field][$itemIndex][$itemField][$nestedIndex][$nestedField]";
                                                                            $nestedIsInput = Str::contains($nestedFieldLower, 'input');
                                                                            $nestedIsTextarea = Str::contains($nestedFieldLower, 'textarea');
                                                                            $nestedIsEnabled = Str::contains($nestedFieldLower, 'enabled');
                                                                            $nestedIsSelect = Str::contains($nestedFieldLower, 'select') || Str::contains($nestedFieldLower, 'styleinput');
                                                                            $nestedIsImage = Str::contains($nestedFieldLower, [
                                                                                'img',
                                                                                'image',
                                                                                'src',
                                                                                'logo',
                                                                                'icon',
                                                                            ]);
                                                                            $nestedIsArray = is_array($nestedValue);
                                                                            $nestedIsGallery =
                                                                                $nestedIsArray &&
                                                                                !empty($nestedValue) &&
                                                                                Str::contains($nestedFieldLower, [
                                                                                    'images',
                                                                                    'gallery',
                                                                                    'photos',
                                                                                ]);
                                                                            $nestedIsVideo = Str::contains($nestedFieldLower, [
                                                                                'video',
                                                                                'mp4',
                                                                                'webm',
                                                                                'ogg',
                                                                            ]);
                                                                            $nestedIsHtml =
                                                                                is_string($nestedValue) &&
                                                                                Str::contains($nestedValue, ['<strong', '<p', '<br', '</']);
                                                                            $nestedIsPlainText =
                                                                                is_string($nestedValue) && !$nestedIsHtml && !$nestedIsImage && !$nestedIsVideo;

                                                                            $buttonStyleOptions = ['primary', 'secondary', 'default'];
                                                                            if ($nestedIsSelect && is_string($nestedValue) && $nestedValue !== '' && !in_array($nestedValue, $buttonStyleOptions, true)) {
                                                                                $buttonStyleOptions[] = $nestedValue;
                                                                            }
                                                                        @endphp

                                                                        <div class="mb-2">
                                                                            <label class="form-label fw-bold">
                                                                                {{ formatSectionName($nestedField) }}
                                                                            </label>

                                                                            @if ($nestedIsEnabled)
                                                                                <select class="form-select" name="{{ $nestedPath }}">
                                                                                    <option value="true" {{ $nestedValue ? 'selected' : '' }}>true</option>
                                                                                    <option value="false" {{ !$nestedValue ? 'selected' : '' }}>false</option>
                                                                                </select>
                                                                            @elseif ($nestedIsSelect)
                                                                                <select class="form-select" name="{{ $nestedPath }}">
                                                                                    @foreach ($buttonStyleOptions as $opt)
                                                                                        <option value="{{ $opt }}" {{ $nestedValue === $opt ? 'selected' : '' }}>
                                                                                            {{ ucfirst($opt) }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            @elseif ($nestedIsTextarea)
                                                                                <textarea class="form-control" name="{{ $nestedPath }}" rows="4">{{ $nestedValue }}</textarea>
                                                                            @elseif ($nestedIsInput)
                                                                                <input type="text" class="form-control" name="{{ $nestedPath }}"
                                                                                    value="{{ $nestedValue }}">
                                                                            @elseif ($nestedIsGallery)
                                                                                <div class="border p-2 rounded">
                                                                                    <div class="d-flex flex-wrap gap-2">
                                                                                        @foreach ($nestedValue as $nestedImgIndex => $nestedImgItem)
                                                                                            @php
                                                                                                $nestedImgMedia = is_numeric($nestedImgItem) ? Media::find($nestedImgItem) : null;
                                                                                                $nestedImgUrl = $nestedImgMedia ? $nestedImgMedia->file_url : (is_string($nestedImgItem) ? $nestedImgItem : null);
                                                                                                $nestedImgId = is_numeric($nestedImgItem) ? $nestedImgItem : null;
                                                                                            @endphp
                                                                                            @if ($nestedImgUrl)
                                                                                                <div class="gallery-item position-relative border rounded p-1"
                                                                                                    style="width:100px; height:100px; overflow:hidden;"
                                                                                                    data-img-id="{{ $nestedImgId }}">
                                                                                                    <img src="{{ $nestedImgUrl }}" class="w-100 h-100"
                                                                                                        style="object-fit:cover;">
                                                                                                    <button type="button"
                                                                                                        class="btn btn-sm btn-danger delete-gallery-image position-absolute"
                                                                                                        style="top:0px; right:0px; border-radius:50%; padding:1px 5px;">
                                                                                                        X
                                                                                                    </button>
                                                                                                    <input type="hidden"
                                                                                                        name="{{ $nestedPath }}[keep][]"
                                                                                                        value="{{ $nestedImgId }}">
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </div>
                                                                                    <hr>
                                                                                    <label class="fw-bold">Add More Images</label>
                                                                                    <input type="file" class="form-control" accept="image/*"
                                                                                        name="{{ $nestedPath }}[files][]" multiple>
                                                                                    <input type="hidden" name="{{ $nestedPath }}[old]"
                                                                                        value="{{ json_encode($nestedValue) }}">
                                                                                    <input type="hidden" name="{{ $nestedPath }}[_is_gallery]" value="1">
                                                                                </div>
                                                                            @elseif ($nestedIsImage)
                                                                                @php
                                                                                    $nestedMedia = is_numeric($nestedValue) ? Media::find($nestedValue) : null;
                                                                                    $nestedImgUrl = $nestedMedia ? $nestedMedia->file_url : (is_string($nestedValue) ? $nestedValue : null);
                                                                                @endphp
                                                                                @if ($nestedImgUrl)
                                                                                <div class="">
                                                                                    <a href="{{ $nestedImgUrl }}" target="_blank"
                                                                                        class="d-inline-block mb-2 text-primary">
                                                                                        <div class="border rounded d-flex align-items-center justify-content-center"
                                                                                            style="width:120px; height:120px; overflow:hidden; background:#f8f9fa;">
                                                                                            <img src="{{ $nestedImgUrl }}" alt=""
                                                                                                style="max-width:100%; max-height:100%; object-fit:contain;">
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                                @endif
                                                                                <input type="file" class="form-control" name="{{ $nestedPath }}" accept="image/*">
                                                                                <input type="hidden" name="{{ $nestedPath }}_old" value="{{ $nestedValue }}">
                                                                            @elseif ($nestedIsVideo)
                                                                                @if (is_string($nestedValue) && $nestedValue)
                                                                                    <div class="border rounded d-inline-block mb-2"
                                                                                        style="width:240px; height:135px; overflow:hidden; background:#f8f9fa;">
                                                                                        <video controls class="w-100 h-100">
                                                                                            <source src="{{ $nestedValue }}" type="video/mp4">
                                                                                        </video>
                                                                                    </div>
                                                                                @endif
                                                                                <input type="file" class="form-control" name="{{ $nestedPath }}"
                                                                                    accept="video/mp4,video/webm,video/ogg">
                                                                                <input type="hidden" name="{{ $nestedPath }}_old"
                                                                                    value="{{ $nestedValue }}">
                                                                            @elseif ($nestedIsHtml)
                                                                                <textarea class="form-control editor autosize" name="{{ $nestedPath }}" rows="3">{{ $nestedValue }}</textarea>
                                                                            @elseif ($nestedIsPlainText)
                                                                                <textarea class="form-control editor autosize" name="{{ $nestedPath }}" rows="3">{{ $nestedValue }}</textarea>
                                                                            @else
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    name="{{ $nestedPath }}"
                                                                                    value="{{ is_string($nestedValue) ? $nestedValue : json_encode($nestedValue) }}">
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endforeach
                                                        <button type="button"
                                                            class="btn btn-sm btn-primary add-nested-repeater"
                                                            data-path="sections[{{ $index }}][data][{{ $field }}][{{ $itemIndex }}][{{ $itemField }}]">
                                                            Add
                                                        </button>
                                                    </div>
                                                        @elseif ($itemIsInput) <div class="mb-3 pt-1">
                                                            <label class="form-label fw-bold">{{ formatSectionName($itemLabel) }}</label>
                                                            <input type="text" class="form-control" name="{{ $itemPath }}"
                                                                value="{{ $itemValue }}">
                                                        </div>
                                                        @elseif ($itemIsTextarea) <div class="mb-3 pt-1">
                                                            <label class="form-label fw-bold">{{ formatSectionName($itemLabel) }}</label>
                                                            <textarea class="form-control" name="{{ $itemPath }}" rows="4">{{ $itemValue }}</textarea>
                                                        </div>
                                                        @elseif ($itemIsEnabled) <div class="mb-3 pt-1">
                                                            <label class="form-label fw-bold">{{ formatSectionName($itemLabel) }}</label>
                                                            <select class="form-select" name="{{ $itemPath }}">
                                                                <option value="true" {{ $itemValue ? 'selected' : '' }}>true</option>
                                                                <option value="false" {{ !$itemValue ? 'selected' : '' }}>false</option>
                                                            </select>
                                                        </div>
                                                        @elseif ($itemIsSelect) <div class="mb-3 pt-1">
                                                            @php
                                                                $buttonStyleOptions = ['primary', 'secondary', 'default'];
                                                                if (is_string($itemValue) && $itemValue !== '' && !in_array($itemValue, $buttonStyleOptions, true)) {
                                                                    $buttonStyleOptions[] = $itemValue;
                                                                }
                                                            @endphp
                                                            <label class="form-label fw-bold">{{ formatSectionName($itemLabel) }}</label>
                                                            <select class="form-select" name="{{ $itemPath }}">
                                                                @foreach ($buttonStyleOptions as $opt)
                                                                    <option value="{{ $opt }}" {{ $itemValue === $opt ? 'selected' : '' }}>
                                                                        {{ ucfirst($opt) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @elseif ($itemIsAssocObject)
                                                            <div class="mb-3 pt-1 border p-3 rounded">
                                                                <label class="form-label fw-bold">{{ formatSectionName($itemLabel) }}</label>
                                                                @foreach ($itemValue as $childKey => $childVal)
                                                                    @php
                                                                        $childKeyLower = strtolower($childKey);
                                                                        $childIsEnabled =
                                                                            $childKeyLower === 'enabled' ||
                                                                            Str::contains($childKeyLower, 'enabled');
                                                                        $childIsSelect = Str::contains($childKeyLower, 'select') || Str::contains($childKeyLower, 'styleinput');
                                                                        $childIsScalar =
                                                                            is_string($childVal) ||
                                                                            is_numeric($childVal) ||
                                                                            is_bool($childVal) ||
                                                                            is_null($childVal);
                                                                        $childDisplay = $childIsScalar ? $childVal : json_encode($childVal);

                                                                        $buttonStyleOptions = ['primary', 'secondary', 'default'];
                                                                        if ($childIsSelect && is_string($childVal) && $childVal !== '' && !in_array($childVal, $buttonStyleOptions, true)) {
                                                                            $buttonStyleOptions[] = $childVal;
                                                                        }
                                                                    @endphp
                                                                    <div class="mb-3 pt-1">
                                                                        <label class="form-label fw-bold">{{ ucfirst($childKey) }}</label>
                                                                        @if ($childIsEnabled)
                                                                            <select class="form-select"
                                                                                name="sections[{{ $index }}][data][{{ $field }}][{{ $itemIndex }}][{{ $itemField }}][{{ $childKey }}]">
                                                                                <option value="true" {{ $childVal ? 'selected' : '' }}>true</option>
                                                                                <option value="false" {{ !$childVal ? 'selected' : '' }}>false</option>
                                                                            </select>
                                                                        @elseif ($childIsSelect)
                                                                            <select class="form-select"
                                                                                name="sections[{{ $index }}][data][{{ $field }}][{{ $itemIndex }}][{{ $itemField }}][{{ $childKey }}]">
                                                                                @foreach ($buttonStyleOptions as $opt)
                                                                                    <option value="{{ $opt }}" {{ $childVal === $opt ? 'selected' : '' }}>
                                                                                        {{ ucfirst($opt) }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        @elseif ($childIsScalar)
                                                                            <input type="text"
                                                                                name="sections[{{ $index }}][data][{{ $field }}][{{ $itemIndex }}][{{ $itemField }}][{{ $childKey }}]"
                                                                                class="form-control" value="{{ $childDisplay }}">
                                                                        @else
                                                                            <textarea class="form-control"
                                                                                name="sections[{{ $index }}][data][{{ $field }}][{{ $itemIndex }}][{{ $itemField }}][{{ $childKey }}]"
                                                                                rows="3">{{ $childDisplay }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                        {{-- LIST inside object --}}
                                                        @elseif ($itemIsListHtml) <div class="mb-3 pt-1">
                                                            <label class="form-label fw-bold">{{ $itemLabel }} (List)</label>
                                                            <textarea class="form-control editor autosize" name="{{ $itemPath }}" rows="5">{!! $itemValue !!}</textarea>
                                                        </div>

                                                    {{-- NESTED ARRAY / JSON --}}
                                                    @elseif ($itemIsArray && !$itemIsImageArray)
                                                        <div class="mb-3 pt-1">
                                                            <label class="form-label fw-bold">{{ formatSectionName($itemLabel) }} (JSON)</label>
                                                            <textarea class="form-control" name="{{ $itemPath }}" rows="4">{{ json_encode($itemValue, JSON_PRETTY_PRINT) }}</textarea>
                                                        </div>

                                                    @elseif ($itemIsImage)
                                                        @php
                                                            $itemMedia = is_numeric($itemValue) ? Media::find($itemValue) : null;
                                                        @endphp
                                                        <div class="mb-3 pt-1">
                                                            <label class="form-label fw-bold">{{ formatSectionName($itemLabel) }} (Image)</label>

                                                            @if ($itemMedia)
                                                                <div>
                                                                <a href="{{ $itemMedia->file_url }}" target="_blank"
                                                                class="d-inline-block mb-2 text-primary">
                                                                    <div class="border rounded d-flex align-items-center justify-content-center"
                                                                        style="width:120px; height:120px; overflow:hidden; background:#f8f9fa;">
                                                                        <img src="{{ $itemMedia->file_url }}" alt=""
                                                                            style="max-width:100%; max-height:100%; object-fit:contain;">
                                                                    </div>
                                                                </a>
                                                                </div>
                                                                 @endif

                                                    <input type="file" class="form-control"
                                                        name="{{ $itemPath }}" accept="image/*">
                                                    <input type="hidden" name="{{ $itemPath }}_old"
                                                        value="{{ $itemValue }}">
                                            </div>

                                            {{-- VIDEO --}}
                                        @elseif ($itemIsVideo)
                                            <div class="mb-3 pt-1">
                                                <label class="form-label fw-bold">{{ formatSectionName($itemLabel) }} (Video)</label>

                                                @if (is_string($itemValue) && $itemValue)
                                                    <div class="border rounded d-inline-block mb-2"
                                                        style="width:240px; height:135px; overflow:hidden; background:#f8f9fa;">
                                                        <video controls class="w-100 h-100">
                                                            <source src="{{ $itemValue }}" type="video/mp4">
                                                        </video>
                                                    </div>
                                                @endif

                                                <input type="file" class="form-control" name="{{ $itemPath }}"
                                                    accept="video/mp4,video/webm,video/ogg">
                                                <input type="hidden" name="{{ $itemPath }}_old"
                                                    value="{{ $itemValue }}">
                                            </div>

                                            {{-- HTML --}}
                                        @elseif ($itemIsHtml)
                                            <div class="mb-3 pt-1">
                                                <label class="form-label fw-bold">{{ formatSectionName($itemLabel) }}</label>
                                                <textarea class="form-control editor autosize" name="{{ $itemPath }}" rows="3">{{ $itemValue }}</textarea>
                                            </div>

                                            {{-- PLAIN TEXT --}}
                                        @elseif ($itemIsPlainText)
                                            <div class="mb-3 pt-1">
                                                <label class="form-label fw-bold">{{ formatSectionName($itemLabel) }}</label>
                                                <textarea class="form-control editor autosize" name="{{ $itemPath }}" rows="3">{{ $itemValue }}</textarea>
                                            </div>

                                            {{-- ICON --}}
                                        @elseif ($itemIsIcon)
                                            <div class="mb-3 pt-1">
                                                <label class="form-label fw-bold">{{ formatSectionName($itemLabel) }} (Icon)</label>
                                                <input type="text" class="form-control" name="{{ $itemPath }}"
                                                    value="{{ $itemValue }}">
                                            </div>

                                            {{-- FALLBACK --}}
                                        @else
                                            <div class="mb-3 pt-1">
                                                <label class="form-label fw-bold">{{ formatSectionName($itemLabel) }}</label>
                                                <textarea class="form-control editor autosize" name="{{ $itemPath }}" rows="3">{{ is_string($itemValue) ? $itemValue : json_encode($itemValue) }}</textarea>
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

        {{-- STRING LIST --}}
    @elseif ($isStringList)
        <div class="mb-3 pt-1">
            <label class="form-label fw-bold">{{ formatSectionName($label) }} (List)</label>
            <textarea class="form-control editor autosize" name="sections[{{ $index }}][data][{{ $field }}]"
                rows="6">{!! $value !!}</textarea>
        </div>

        {{-- SINGLE IMAGE --}}
    @elseif ($isImage)
        @php
            $fieldMedia = is_numeric($value) ? Media::find($value) : null;
            $imgUrl = $fieldMedia ? $fieldMedia->file_url : (is_string($value) ? $value : null);
        @endphp
        <div class="mb-3 pt-1">
            <label class="form-label fw-bold">{{ formatSectionName($label) }} (Image)</label>

            @if ($imgUrl)
                <div>
                    <a href="{{ $imgUrl }}" target="_blank" class="d-inline-block mb-2 text-primary">
                        <div class="border rounded d-flex align-items-center justify-content-center"
                            style="width:120px; height:120px; overflow:hidden; background:#f8f9fa;">
                            <img src="{{ $imgUrl }}" alt=""
                                style="max-width:100%; max-height:100%; object-fit:contain;">
                        </div>
                    </a>
                </div>
            @endif

            <input type="file" class="form-control"
                name="sections[{{ $index }}][data][{{ $field }}][file]" accept="image/*">
            <input type="hidden" name="sections[{{ $index }}][data][{{ $field }}][old]"
                value="{{ $value }}">
        </div>

        {{-- VIDEO --}}
    @elseif ($isVideo)
        @php
            $fieldMedia = is_numeric($value) ? Media::find($value) : null;
            $videoUrl = $fieldMedia ? $fieldMedia->file_url : (is_string($value) ? $value : null);
        @endphp
        <div class="mb-3 pt-1">
            <label class="form-label fw-bold">{{ formatSectionName($label) }} (Video)</label>

            @if ($videoUrl)
                <div class="border rounded d-inline-block mb-2"
                    style="width:240px; height:135px; overflow:hidden; background:#f8f9fa;">
                    <video controls class="w-100 h-100">
                        <source src="{{ $videoUrl }}" type="video/mp4">
                    </video>
                </div>
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

        {{-- HTML --}}
    @elseif ($isHtml)
        <div class="mb-3 pt-1">
            <label class="form-label fw-bold">{{ formatSectionName($label) }}</label>
            <textarea class="form-control editor autosize" name="sections[{{ $index }}][data][{{ $field }}]"
                rows="4">{{ $value }}</textarea>
        </div>

        {{-- LONG TEXT --}}
    @elseif ($isLongText)
        <div class="mb-3 pt-1">
            <label class="form-label fw-bold">{{ formatSectionName($label) }}</label>
            <textarea class="form-control editor autosize" name="sections[{{ $index }}][data][{{ $field }}]"
                rows="3">{{ $value }}</textarea>
        </div>

        {{-- ARRAY FALLBACK --}}
    @elseif ($isArray)
        <div class="mb-3 pt-1">
            <label class="form-label fw-bold">{{ formatSectionName($label) }} (JSON)</label>
            <textarea class="form-control" name="sections[{{ $index }}][data][{{ $field }}]" rows="5">{{ json_encode($value, JSON_PRETTY_PRINT) }}</textarea>
        </div>

        {{-- PLAIN STRING --}}
    @elseif ($isPlainString)
        <div class="mb-3 pt-1">
            <label class="form-label fw-bold">{{ formatSectionName($label) }}</label>
            <textarea class="form-control editor autosize" name="sections[{{ $index }}][data][{{ $field }}]"
                rows="3">{{ $value }}</textarea>
        </div>

        {{-- CATCH-ALL --}}
    @else
        <div class="mb-3 pt-1">
            <label class="form-label">{{ formatSectionName($label) }}</label>
            <textarea class="form-control editor autosize" name="sections[{{ $index }}][data][{{ $field }}]"
                rows="3">{{ is_string($value) ? $value : json_encode($value) }}</textarea>
        </div>
        @endif
        @endforeach
        </div>
        </div>
        @endforeach
        </div>
    </form>
    </div>
@endsection
