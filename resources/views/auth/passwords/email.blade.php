@extends('layouts.authentication.master')

@section('css')
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
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
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
                                @if (session('status'))
                                    <div class="alert alert-primary p-2" role="alert">
                                        {{ session('status') }} 
                                    </div>
                                @endif
                                <form class="theme-form" method="POST" action="{{ route('password.email') }}" id="resetPasswordLinkForm">
                                    @csrf
                                    <h4 class="mb-3">Reset Your Password</h4>
                                    <div class="form-group">
                                        <label class="col-form-label">Email Address</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <input class="form-control mb-1" type="email" name="email"  value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Enter Your Email">
                                                @error('email')
                                                    <span class="text-danger d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <div class="text-end mt-2">
                                                    <button class="btn btn-primary spinner-btn" type="submit" style="inline-size: -webkit-fill-available;">Send Password Reset Link</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <a href="{{ route('login') }}" class="text-center custom-btn-back">
                                    <p><i class="fa fa-long-arrow-left"></i>
                                    Back to Login Page</p>
                                </a>
                            </div>
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
@endsection
