@extends('layout.admin.index')
@section('page_title', $program ? 'Edit Program' : 'Create Program')

@section('admin-main-content')
    @php
        $isEdit = isset($program) && $program;
        $existingImage = $isEdit && $program->product_image ? asset('storage/' . $program->product_image) : null;
    @endphp

    <div class="row mb-6 gy-6">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body">

                    <form method="POST"
                        action="{{ $isEdit ? route('programs.update', $program->id) : route('programs.store') }}"
                        enctype="multipart/form-data">

                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        {{-- PROGRAM TYPE --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Program Type</label>
                            <div class="col-sm-10">
                                <select class="form-control @error('type') is-invalid @enderror" name="type">
                                    <option value="">Select Type</option>
                                    <option value="degree"
                                        {{ old('type', $isEdit ? $program->type : '') == 'degree' ? 'selected' : '' }}>
                                        Degree Programs
                                    </option>
                                    <option value="certificate"
                                        {{ old('type', $isEdit ? $program->type : '') == 'certificate' ? 'selected' : '' }}>
                                        Certificate Programs
                                    </option>
                                </select>
                                @error('type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- PROGRAM NAME --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Program Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $isEdit ? $program->name : '') }}"
                                    placeholder="Program Name">
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- PRODUCT CODE --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Product Code</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('product_code') is-invalid @enderror"
                                    name="product_code"
                                    value="{{ old('product_code', $isEdit ? $program->product_code : '') }}"
                                    placeholder="Product Code">
                                @error('product_code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- PRODUCT IMAGE --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Product Image</label>
                            <div class="col-sm-10">
                                <div class="program-product-upload @error('product_image') is-invalid @enderror"
                                    id="productImageBox">
                                    <input type="file" name="product_image" id="product_image" accept="image/*"
                                        class="program-product-upload__input">
                                    <input type="hidden" name="remove_product_image" id="remove_product_image"
                                        value="0">

                                    <div class="program-product-upload__placeholder" id="productImagePlaceholder"
                                        style="{{ $existingImage ? 'display:none;' : '' }}">
                                        <i class="bx bx-image-add"></i>
                                        <span>Upload Image</span>
                                    </div>

                                    <img src="{{ $existingImage ?? '' }}" alt="Product preview"
                                        class="program-product-upload__preview" id="productImagePreview"
                                        style="{{ $existingImage ? '' : 'display:none;' }}">

                                    <button type="button" class="program-product-upload__remove"
                                        id="productImageRemove"
                                        style="{{ $existingImage ? '' : 'display:none;' }}"
                                        aria-label="Remove image">&times;</button>
                                </div>
                                @error('product_image')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- PRODUCT DESCRIPTION --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Product Description</label>
                            <div class="col-sm-10">
                                <textarea name="product_description" rows="4"
                                    class="form-control @error('product_description') is-invalid @enderror"
                                    placeholder="Product Description">{{ old('product_description', $isEdit ? $program->product_description : '') }}</textarea>
                                @error('product_description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="1"
                                        {{ old('status', $isEdit ? $program->status : 1) == 1 ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0"
                                        {{ old('status', $isEdit ? $program->status : 1) == 0 ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- SHOW IN MENU --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Show in Menu</label>
                            <div class="col-sm-10">
                                <select name="show_in_menu" class="form-control">
                                    <option value="1"
                                        {{ old('show_in_menu', $isEdit ? optional($program->pages->first())->show_in_menu : 1) == 1 ? 'selected' : '' }}>
                                        Yes
                                    </option>
                                    <option value="0"
                                        {{ old('show_in_menu', $isEdit ? optional($program->pages->first())->show_in_menu : 1) == 0 ? 'selected' : '' }}>
                                        No
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- MENU ORDER --}}
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label">Menu Order</label>
                            <div class="col-sm-10">
                                <input type="number" name="menu_order" class="form-control"
                                    value="{{ old('menu_order', $isEdit ? optional($program->pages->first())->menu_order : '') }}"
                                    placeholder="Menu sequence (1,2,3...)">
                            </div>
                        </div>

                        {{-- SUBMIT --}}
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">
                                    {{ $isEdit ? 'Update Program' : 'Add Program' }}
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .program-product-upload {
            position: relative;
            width: 140px;
            height: 140px;
            border: 2px dashed #c5cdd8;
            border-radius: 8px;
            background: #f8f9fb;
            cursor: pointer;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .program-product-upload:hover {
            border-color: #696cff;
            background: #f0f1ff;
        }

        .program-product-upload__input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            z-index: 1;
        }

        .program-product-upload__placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            color: #8592a3;
            font-size: 12px;
            pointer-events: none;
            text-align: center;
            padding: 8px;
        }

        .program-product-upload__placeholder i {
            font-size: 28px;
            line-height: 1;
        }

        .program-product-upload__preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .program-product-upload__remove {
            position: absolute;
            top: 6px;
            right: 6px;
            z-index: 2;
            width: 24px;
            height: 24px;
            border: 0;
            border-radius: 50%;
            background: #ea5455;
            color: #fff;
            font-size: 16px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .program-product-upload__remove:hover {
            background: #d43d3d;
        }
    </style>
    <script>
        (function() {
            const input = document.getElementById('product_image');
            const preview = document.getElementById('productImagePreview');
            const placeholder = document.getElementById('productImagePlaceholder');
            const removeBtn = document.getElementById('productImageRemove');
            const removeFlag = document.getElementById('remove_product_image');

            if (!input || !preview || !placeholder || !removeBtn || !removeFlag) return;

            function showPreview(src) {
                preview.src = src;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
                removeBtn.style.display = 'flex';
                removeFlag.value = '0';
            }

            function clearPreview() {
                input.value = '';
                preview.src = '';
                preview.style.display = 'none';
                placeholder.style.display = 'flex';
                removeBtn.style.display = 'none';
                removeFlag.value = '1';
            }

            input.addEventListener('change', function() {
                const file = this.files && this.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    showPreview(e.target.result);
                };
                reader.readAsDataURL(file);
            });

            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                clearPreview();
            });
        })();
    </script>
@endpush
