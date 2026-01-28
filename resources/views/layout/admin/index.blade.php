<!doctype html>

<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-skin="default"
    data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template" data-bs-theme="light">

@include('partials.admin.header')

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            @include('partials.admin.sidebar')

            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('partials.admin.navbar')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                       @yield('admin-main-content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('partials.admin.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- @include('partials.admin.toast') -->
    @include('partials.admin.scripts')
    @stack('scripts')
    <!-- Page JS -->
    {{-- <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script> --}}
</body>

</html>
