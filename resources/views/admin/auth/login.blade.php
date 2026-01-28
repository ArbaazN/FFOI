@include('partials.admin.header')

<body>
    <!-- Content -->
    <div class="authentication-wrapper authentication-cover">
        <!-- Logo -->
        <span class="app-brand-logo demo mt-2">
            <span class="text-primary p-4">
                <img src="{{ asset('assets/logo/nav_logo.png') }}" alt="" srcset="" height="200"
                    width="200" class="img-fluid">
            </span>
        </span>
        <!-- /Logo -->

        <div class="authentication-inner row m-0">
            <!-- Left side image -->
            <div class="d-none d-xl-flex col-xl-7 p-0">
                <div class="auth-cover-bg d-flex justify-content-center align-items-center">
                    {{-- <img src="{{ asset('assets/img/illustrations/auth-login-illustration-light.png') }}"
                        alt="auth-login-cover" class="my-5 auth-illustration"
                        data-app-light-img="illustrations/auth-login-illustration-light.png"
                        data-app-dark-img="illustrations/auth-login-illustration-dark.png" />

                    <img src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}" alt="auth-login-cover"
                        class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.png"
                        data-app-dark-img="illustrations/bg-shape-image-dark.png" /> --}}
                    
                        <img src="{{ asset('assets/img/illustrations/auth-two-step-illustration-light.png') }}"
                        alt="auth-login-cover" class="my-5 auth-illustration"
                        data-app-light-img="illustrations/auth-two-step-illustration-light.png"
                        data-app-dark-img="illustrations/auth-login-illustration-dark.png" />

                    <img src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}" alt="auth-login-cover"
                        class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.png"
                        data-app-dark-img="illustrations/bg-shape-image-dark.png" />
                </div>
            </div>
            <!-- /Left -->
            <!-- Login -->
            <div class="d-flex col-12 col-xl-5 align-items-center authentication-bg p-sm-12 p-6">
                <div class="w-px-400 mx-auto mt-12 pt-5">
                    <h4 class="mb-1">Welcome to {{ env('APP_NAME') }}! 👋</h4>
                    <p class="mb-6">Please sign-in to your account...</p>

                    <form action="{{ route('login.submit') }}" method="POST" class="mb-6">
                        @csrf
                        <div class="mb-6 form-control-validation">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" autofocus />
                        </div>

                        <div class="mb-6 form-password-toggle form-control-validation">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" aria-describedby="password" value="{{ old('password') }}" />
                                <span class="input-group-text cursor-pointer">
                                  <i class="icon-base ti tabler-eye-off"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary d-grid w-100">Sign in</button>
                    </form>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
    <!-- /Content -->

    @include('partials.admin.scripts')
</body>

</html>