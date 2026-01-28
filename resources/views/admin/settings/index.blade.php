@extends('layout.admin.index')

@section('page_title', 'Website Settings')
@section('admin-main-content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- BASIC SETTINGS -->
                <h5 class="fw-bold mb-3">Basic Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Site Name</label>
                        <input type="text" name="site_name" class="form-control" value="{{ $settings->site_name ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Site Title</label>
                        <input type="text" name="site_title" class="form-control"
                            value="{{ $settings->site_title ?? '' }}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Tagline</label>
                        <input type="text" name="tagline" class="form-control" value="{{ $settings->tagline ?? '' }}">
                    </div>
                </div>

                <!-- BRANDING -->
                <h5 class="fw-bold mt-4">Branding</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @if (!empty($settings->logo))
                            <img src="{{ asset('storage/' . $settings->logo) }}" height="60" class="mt-2">
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Favicon</label>
                        <input type="file" name="favicon" class="form-control" accept="image/*">
                        @if (!empty($settings->favicon))
                            <img src="{{ asset('storage/' . $settings->favicon) }}" height="60" class="mt-2">
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Footer Logo</label>
                        <input type="file" name="footer_logo" class="form-control" accept="image/*">
                        @if (!empty($settings->footer_logo))
                            <img src="{{ asset('storage/' . $settings->footer_logo) }}" height="60" class="mt-2">
                        @endif
                    </div>
                </div>

                <!-- CONTACT -->
                <h5 class="fw-bold mt-4">Contact Information</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $settings->email ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label>Support Email</label>
                        <input type="email" name="support_email" class="form-control"
                            value="{{ $settings->support_email ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ $settings->phone ?? '' }}">
                    </div>
                </div>
                <div class="row mb-3">
                    {{-- <div class="col-md-6">
                        <label>WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" class="form-control"
                            value="{{ $settings->whatsapp_number ?? '' }}">
                    </div> --}}
                    <div class="col-md-12">
                        <label>Google Map URL</label>
                        <input type="text" name="map_url" class="form-control" value="{{ $settings->map_url ?? '' }}">
                    </div>
                </div>

                <!-- SOCIAL LINKS -->
                <h5 class="fw-bold mt-4">Social Links</h5>
                <div class="row mb-3">
                    <div class="col-md-6 mb-3"><label>Facebook</label><input type="text" name="facebook"
                            class="form-control" value="{{ $settings->facebook ?? '' }}"></div>
                    <div class="col-md-6 mb-3"><label>Instagram</label><input type="text" name="instagram"
                            class="form-control" value="{{ $settings->instagram ?? '' }}"></div>
                    <div class="col-md-6 mb-3"><label>LinkedIn</label><input type="text" name="linkedin"
                            class="form-control" value="{{ $settings->linkedin ?? '' }}"></div>
                    <div class="col-md-6 mb-3"><label>Twitter</label><input type="text" name="twitter"
                            class="form-control" value="{{ $settings->twitter ?? '' }}"></div>
                    <div class="col-md-6 mb-3"><label>YouTube</label><input type="text" name="youtube"
                            class="form-control" value="{{ $settings->youtube ?? '' }}"></div>
                    <div class="col-md-6 mb-3"><label>WhatsApp</label><input type="text" name="whatsapp"
                            class="form-control" value="{{ $settings->whatsapp ?? '' }}"></div>
                </div>

                <!-- LINKEDIN INTEGRATION -->
                <h5 class="fw-bold mt-4">LinkedIn Integration</h5>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>LinkedIn Company ID</label>
                        <input type="text" name="linkedin_company_id" class="form-control"
                            value="{{ $settings->linkedin_company_id ?? '' }}">
                    </div>
                    {{-- <div class="col-md-6">
                        <label>Enable LinkedIn Share Button</label>
                        <select name="linkedin_share_enable" class="form-control">
                            <option value="1" @selected($settings->linkedin_share_enable == 1)>Yes</option>
                            <option value="0" @selected($settings->linkedin_share_enable == 0)>No</option>
                        </select>
                    </div> --}}
                </div>

                <!-- OG SETTINGS -->
                <h5 class="fw-bold mt-4">Open Graph (OG) Settings</h5>
                <div class="mb-3">
                    <label>OG Image</label>
                    <input type="file" name="og_image" class="form-control" accept="image/*">
                    @if (!empty($settings->og_image))
                        <img src="{{ asset('storage/' . $settings->og_image) }}" height="60" class="mt-2">
                    @endif
                </div>
                <div class="mb-3">
                    <label>OG Title</label>
                    <input type="text" name="og_title" class="form-control" value="{{ $settings->og_title ?? '' }}">
                </div>
                <div class="mb-3">
                    <label>OG Description</label>
                    <textarea name="og_description" class="form-control">{{ $settings->og_description ?? '' }}</textarea>
                </div>

                <!-- SEO -->
                <h5 class="fw-bold mt-4">SEO Settings</h5>
                <div class="mb-3">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" class="form-control"
                        value="{{ $settings->meta_title ?? '' }}">
                    <small class="text-muted">Recommended max 60 characters.</small>
                </div>
                <div class="mb-3">
                    <label>Meta Description</label>
                    <textarea name="meta_description" rows="2" class="form-control">{{ $settings->meta_description ?? '' }}</textarea>
                    <small class="text-muted">Recommended max 160 characters.</small>

                </div>
                <div class="mb-3">
                    <label>Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" id="TagifyBasic" value="{{ $settings->meta_keywords ?? '' }}">
                    <small class="text-muted">Comma separated keywords for SEO.</small>
                </div>

                <h5 class="fw-bold mt-4">SEM / Marketing Scripts</h5>

                <div class="mb-3">
                    <label class="form-label">Google Analytics (GA4) Code</label>
                    <textarea name="google_analytics_code" class="form-control" rows="3">{{ $settings->google_analytics_code ?? '' }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Google Tag Manager (GTM) Code</label>
                    <textarea name="google_tag_manager_code" class="form-control" rows="3">{{ $settings->google_tag_manager_code ?? '' }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Facebook Pixel Code</label>
                    <textarea name="facebook_pixel_code" class="form-control" rows="3">{{ $settings->facebook_pixel_code ?? '' }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">LinkedIn Insight Tag</label>
                    <textarea name="linkedin_insight_code" class="form-control" rows="3">{{ $settings->linkedin_insight_code ?? '' }}</textarea>
                </div>

                <!-- FOOTER -->
                <h5 class="fw-bold mt-4">Footer</h5>
                <div class="mb-3">
                    <label>Footer Text</label>
                    <textarea name="footer_text" class="form-control">{{ $settings->footer_text ?? '' }}</textarea>
                </div>

                @can('settings.edit')
                    <div class="text-end mt-4">
                        <button class="btn btn-primary">Save Settings</button>
                    </div>
                @endcan
            </form>
        </div>
    </div>
@endsection