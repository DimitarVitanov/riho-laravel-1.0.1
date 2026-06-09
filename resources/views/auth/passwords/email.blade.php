@extends('layouts.authentication.master')

@section('css')
@endsection

@section('main_content')
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
                            <a class="logo" href="{{ route('login') }}">
                                <img class="img-fluid" src="{{ asset('assets/images/logo/villabit-logo.png') }}" alt="Villa Bit AI" style="height:80px;width:auto;">
                            </a>
                        </div>
                        <div class="login-main">
                            @if (session('status'))
                                <div class="alert alert-success p-2" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form class="theme-form" method="POST" action="{{ route('password.email') }}" id="resetPasswordLinkForm">
                                @csrf
                                <h4>Reset Your Password</h4>
                                <p>Enter your email address and we'll send you a link to reset your password.</p>

                                <div class="form-group">
                                    <label class="col-form-label">Email Address</label>
                                    <input class="form-control" type="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Enter your email" required>
                                    @error('email')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-0">
                                    <div class="text-end mt-3">
                                        <button class="btn btn-primary btn-block w-100 spinner-btn" type="submit">Send Password Reset Link</button>
                                    </div>
                                </div>

                                <p class="mt-4 mb-0 text-center">
                                    <a href="{{ route('login') }}"><i class="fa fa-long-arrow-left"></i> Back to Login</a>
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
        .login-card .login-main a {
            color: #0a0b0c !important;
        }
        .login-card .login-main a:hover {
            color: #444 !important;
        }
        .login-card .login-main {
            background-color: #ffffff !important;
        }
        .login-card.login-dark {
            background: url('/assets/images/login/login_bg.jpg') center center / cover no-repeat !important;
            background-color: #f5f6fa !important;
        }
    </style>
    <script src="{{ asset('assets/js/bookmark/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom-validation/validation.js') }}"></script>
@endsection
