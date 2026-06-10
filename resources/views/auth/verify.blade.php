@extends('layouts.authentication.master')

@section('main_content')
    <div class="loader-wrapper">
        <div class="loader"><div class="loader4"></div></div>
    </div>
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
                            <h4>Verify Your Email Address</h4>
                            <p>Almost there! Check your email and click the verification link we sent you. If you do not see it, please also check your spam or junk folder. You can request a new verification email by clicking the button below.</p>

                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    A fresh verification link has been sent to your email address.
                                </div>
                            @endif

                            <form method="POST" action="{{ route('verification.resend') }}" class="mt-4">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-block w-100">
                                    Resend Verification Email
                                </button>
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
        .login-card.login-dark {
            background: url('/assets/images/login/login_bg.jpg') center center / cover no-repeat !important;
            background-color: #f5f6fa !important;
        }
        .login-card .login-main {
            background-color: #ffffff !important;
        }
        .login-card .login-main .btn-primary {
            background-color: #0a0b0c !important;
            border-color: #0a0b0c !important;
            color: #ffffff !important;
        }
        .login-card .login-main .btn-primary:hover {
            background-color: #2a2b2c !important;
            border-color: #2a2b2c !important;
        }
    </style>
@endsection
