@extends('layout.admin.index')

@section('page_title', 'Blogs')

@section('admin-main-content')

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <form action="{{ route('blog.index') }}" method="GET" class="w-25 position-relative search-wrapper">
                <input type="text" name="search" class="form-control autoSearch" value="{{ request('search') }}"
                    data-delay="1000" data-min="3" placeholder="Search Blogs..." />
                <span class="clear-search d-none"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; color:#999;">✖</span>
            </form>
            @can('blog.create')
                <a href="{{ route('blog.create') }}" class="btn btn-primary">Add Blog</a>
            @endcan
        </div>

        <div class="card-datatable">
            @if ($blogs->count() > 0)
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="border-top">
                        <tr>
                            <th>Sr.</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Featured</th>
                            <th>Blog Category</th>
                            <th>Publish Date</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($blogs as $blog)
                            <tr>
                                <td class="py-1">{{ $loop->iteration }}</td>
                                <td class="py-1">
                                    <span class="truncate-text" data-bs-toggle="tooltip" title="{{ $blog->title }}">
                                        {{ Str::limit($blog->title, 20, '...') }}
                                    </span>
                                </td>

                                <td class="py-1">
                                    @if ($blog->author)
                                        <span class="truncate-text" data-bs-toggle="tooltip" title="{{ $blog->author }}">
                                            {{ Str::limit($blog->author, 100, '...') }}
                                        </span>
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="py-1">
                                    {!! $blog->feature_content ? '<span class="badge bg-label-info rounded-pill">Featured</span>' : '-' !!}
                                </td>

                                <td class="py-1">{{ $blog->category->name ?? '-' }}</td>

                                <td class="py-1">
                                    {{ $blog->publish_date ? \Carbon\Carbon::parse($blog->publish_date)->format('d M Y') : '—' }}
                                </td>

                                <td class="py-1">
                                    <span
                                        class="badge {{ $blog->status ? 'bg-label-success' : 'bg-label-danger' }} rounded-pill">
                                        {{ $blog->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="">{{ $blog->created_at->format('d M Y, h:i A') ?? '-'}}</td>
                                <td class="py-1">
                                    <div class="d-inline-block text-nowrap">
                                        @php
                                            $canEdit = auth()->user()->can('blog.edit');
                                            $canShow = auth()->user()->can('blog.view');
                                        @endphp

                                        @if ($canEdit || $canShow)
                                            @can('blog.edit')
                                                <a href="{{ route('blog.edit', $blog->id) }}"
                                                    class="btn btn-text-secondary rounded-pill btn-icon"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-original-title="Edit Blog">
                                                    <i class="icon-base ti tabler-edit icon-22px"></i>
                                                </a>
                                            @endcan
                                            @can('blog.view')
                                                <a href="{{ route('blog.show', $blog->id) }}"
                                                    class="btn btn-text-secondary rounded-pill btn-icon"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-original-title="View Blog">
                                                    <i class="icon-base ti tabler-eye icon-22px"></i>
                                                </a>
                                            @endcan
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No blogs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3 me-3">
                    {{ $blogs->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">No Blogs found.</h5>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
