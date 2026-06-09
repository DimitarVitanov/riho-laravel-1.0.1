@extends('layouts.authentication.master')

@section('css')
    <!-- Toastr css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/toastr.min.css') }}">
@endsection

@section('main_content')
    @use('App\Helpers\Helpers')
    @php
        $settings = Helpers::getSettings();
    @endphp
    <!-- loader starts-->
    <div class="loader-wrapper">
        <div class="loader">
            <div class="loader4"></div>
        </div>
    </div>
    <!-- loader ends-->
    <!-- login page start-->
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-12 p-0">
                <div class="login-card login-dark">
                    <div>
                        <div> 
                            <a class="logo" href="{{ route('admin.default_dashboard') }}">
                                <img class="img-fluid for-light" src="{{ asset($settings['general']['dark_logo']) }}"
                                    alt="looginpage">
                                <img class="img-fluid for-dark" src="{{ asset($settings['general']['light_logo']) }}"
                                    alt="looginpage">
                            </a>
                        </div>
                        <div class="login-main">
                            <form class="theme-form" method="POST" action="{{ route('login') }}" id="loginForm">
                                @csrf
                                <h4>Sign in to Villa Bit AI</h4>
                                <p>Enter your email & password to access your panel</p>

                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <div class="form-group">
                                    <label class="col-form-label">Email Address</label>
                                    <input id="email" type="email" class="form-control" name="email"
                                        value="{{ old('email') }}" placeholder="Enter your email" autocomplete="email"
                                        autofocus required>
                                    @error('email')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">Password</label>
                                    <div class="form-input position-relative">
                                        <input id="password" type="password" class="form-control" name="password"
                                            placeholder="Enter password" autocomplete="current-password" required>
                                        <div class="show-hide"><span class="show"></span></div>
                                        @error('password')
                                            <span class="text-danger d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-end">
                                    @if (Route::has('password.request'))
                                        <a class="checkbox1" href="{{ route('password.request') }}">Forgot Password?</a>
                                    @endif
                                    <div class="text-end mt-3">
                                        <button class="btn btn-primary btn-block w-100 spinner-btn" type="submit">Sign in</button>
                                    </div>
                                </div>

                                <p class="mt-4 mb-0 text-center">Don't have an account?
                                    <a class="ms-2" href="{{ route('register') }}">Create Account</a>
                                </p>
                            </form> 

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/bookmark/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom-validation/validation.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection
