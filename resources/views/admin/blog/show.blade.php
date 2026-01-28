@extends('layout.admin.index')

@section('page_title', 'Blog Details : ' . $blog->title)

@section('admin-main-content')

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-end align-items-center mb-4">
                <a href="{{ route('blog.edit', $blog->id) }}" class="btn btn-text-secondary rounded-pill btn-icon"
                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit Blog">
                    <i class="icon-base ti tabler-edit icon-22px"></i>
                </a>
            </div>

            <div class="mb-4">
                <div class="row g-3">
                    @if ($blog->images)
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header fw-bold text-center">
                                    Desktop Image
                                </div>
                                <img src="{{ asset('storage/' . $blog->images) }}" class="card-img-bottom img-fluid"
                                    style="max-height: 300px; object-fit: cover;" alt="Desktop Blog Image">
                            </div>
                        </div>
                    @endif

                    @if ($blog->mobile_image)
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header fw-bold text-center">
                                    Mobile Image
                                </div>
                                <img src="{{ asset('storage/' . $blog->mobile_image) }}" class="card-img-bottom img-fluid"
                                    style="max-height: 300px; object-fit: cover;" alt="Mobile Blog Image">
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Slug:</strong> {{ $blog->slug }}</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Status:</strong>
                        {!! $blog->status == 1
                            ? '<span class="badge bg-label-success rounded-pill">Active</span>'
                            : '<span class="badge bg-label-danger rounded-pill">Inactive</span>' !!}
                    </p>
                </div>

                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Blog Category:</strong> {{ $blog->category->name ?? '-' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Author:</strong> {{ $blog->author }}
                    </p>
                </div>

                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Publish Date:</strong>
                        {{ $blog->publish_date ? $blog->publish_date->format('d M, Y') : 'Not published' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Featured:</strong>
                        {!! $blog->feature_content
                            ? '<span class="badge bg-label-info rounded-pill">Featured</span>'
                            : '<span class="badge bg-secondary">No</span>' !!}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 fw-semibold">Content</h5>
        </div>
        <div class="card-body">
            {!! $blog->content !!}
        </div>
    </div>

@endsection
