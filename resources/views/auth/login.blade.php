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
                            <a class="logo" href="https://villabit.ai/" target="_blank">
                                <img class="img-fluid" src="{{ asset('assets/images/logo/villabit-logo.png') }}" alt="Villa Bit AI" style="height:80px;width:auto;">
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
    <style>
        .login-card .login-main .btn-primary,
        .login-card .login-main button[type="submit"] {
            background-color: #0a0b0c !important;
            border-color: #0a0b0c !important;
            color: #ffffff !important;
        }
        .login-card .login-main .btn-primary:hover,
        .login-card .login-main button[type="submit"]:hover {
            background-color: #2a2b2c !important;
            border-color: #2a2b2c !important;
        }
        .login-card .login-main .form-control,
        .login-card .login-main input {
            background-color: #f0f0f1 !important;
            border: 1px solid #d8d8d8 !important;
            color: #0a0b0c !important;
        }
        .login-card .login-main .form-control::placeholder,
        .login-card .login-main input::placeholder {
            color: #999 !important;
        }
        .login-card .login-main .form-control:focus,
        .login-card .login-main input:focus {
            background-color: #f0f0f1 !important;
            border-color: #0a0b0c !important;
            box-shadow: 0 0 0 0.15rem rgba(10,11,12,0.12) !important;
        }
        .login-card .login-main .show-hide {
            color: #666 !important;
        }
        .login-card .login-main a {
            color: #0a0b0c !important;
        }
        .login-card .login-main a:hover {
            color: #444 !important;
        }
        .login-card .login-main {
            background-color: #ffffff !important;
        }
        /* Force background image to show (overrides .login-dark which hides it) */
        .login-card.login-dark {
            background: url('/assets/images/login/login_bg.jpg') center center / cover no-repeat !important;
            background-color: #f5f6fa !important;
        }
    </style>
    <script src="{{ asset('assets/js/bookmark/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom-validation/validation.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection
