@extends('layout.admin.index')

@section('page_title', isset($utmLink->id) ? 'Edit UTM Link' : 'Create UTM Link')

@section('admin-main-content')

@php
    $isEdit = isset($utmLink) && $utmLink->id;
@endphp

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ $isEdit ? 'Edit UTM Link' : 'Create UTM Link' }}</h5>
    </div>

    <div class="card-body">

        <form action="{{ $isEdit ? route('utm-links.update', $utmLink->id) : route('utm-links.store') }}" method="POST">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label">Name (internal label)</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $utmLink->name ?? '') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Base URL *</label>
                <input type="url" name="original_url" class="form-control"
                       value="{{ old('original_url', $utmLink->original_url ?? '') }}" required>

                <small class="text-muted">
                    Example: https://yourwebsite.com/landing-page
                </small>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">UTM Source</label>
                    <input type="text" name="utm_source" class="form-control"
                           value="{{ old('utm_source', $utmLink->utm_source ?? '') }}"
                           placeholder="facebook, google, newsletter">
                </div>

                <div class="col-md-4">
                    <label class="form-label">UTM Medium</label>
                    <input type="text" name="utm_medium" class="form-control"
                           value="{{ old('utm_medium', $utmLink->utm_medium ?? '') }}"
                           placeholder="cpc, social, email">
                </div>

                <div class="col-md-4">
                    <label class="form-label">UTM Campaign</label>
                    <input type="text" name="utm_campaign" class="form-control"
                           value="{{ old('utm_campaign', $utmLink->utm_campaign ?? '') }}"
                           placeholder="diwali_sale">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">UTM Term (optional)</label>
                    <input type="text" name="utm_term" class="form-control"
                           value="{{ old('utm_term', $utmLink->utm_term ?? '') }}"
                           placeholder="keyword">
                </div>

                <div class="col-md-6">
                    <label class="form-label">UTM Content (optional)</label>
                    <input type="text" name="utm_content" class="form-control"
                           value="{{ old('utm_content', $utmLink->utm_content ?? '') }}"
                           placeholder="adA, banner1">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes (optional)</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes', $utmLink->notes ?? '') }}</textarea>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                       value="1"
                       {{ old('is_active', $utmLink->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>

            <div class="text-end">
                <a href="{{ route('utm-links.index') }}" class="btn btn-secondary">Cancel</a>
                <button class="btn btn-primary">
                    {{ $isEdit ? 'Update UTM Link' : 'Save UTM Link' }}
                </button>
            </div>

        </form>
    </div>
</div>

@endsection