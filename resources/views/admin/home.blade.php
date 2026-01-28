@extends('layout.admin.index')
@section('page_title', 'Dashboard')

@section('admin-main-content')

<!-- Welcome Card -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body d-flex align-items-center gap-3">
        <div>
            <h4 class="mb-1">Welcome to the CRM</h4>
            <p class="text-muted mb-0">
                Manage campuses, programs, blogs.
            </p>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3">

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Campuses</h6>
                    <h3 class="mb-0">{{ $stats['campus_count'] }}</h3>
                </div>
                <i class="bi bi-building fs-1 text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Programs</h6>
                    <h3 class="mb-0">{{ $stats['program_count'] }}</h3>
                </div>
                <i class="bi bi-journal-bookmark fs-1 text-success"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Blogs</h6>
                    <h3 class="mb-0">{{ $stats['blog_count'] }}</h3>
                </div>
                <i class="bi bi-newspaper fs-1 text-warning"></i>
            </div>
        </div>
    </div>

</div>

@endsection