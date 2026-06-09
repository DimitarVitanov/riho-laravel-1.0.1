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
                                <form class="theme-form" method="POST" action="{{ route('password.update') }}"
                                    id="resetPasswordFrom">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <h4>Reset Password</h4>
                                    <div class="form-group">
                                        <label class="col-form-label">Email</label>
                                        <div class="form-input position-relative">
                                            <input id="email" type="email" class="form-control" name="email"
                                                value="{{ $email ?? old('email') }}" autocomplete="email" autofocus
                                                placeholder="Enter Your Email">
                                            @error('email')
                                                <span class="text-danger d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">New Password</label>
                                        <div class="form-input position-relative">
                                            <input id="password" type="password" class="form-control" name="password"
                                                autocomplete="new-password" placeholder="Enter Password">
                                            @error('password')
                                                <span class="text-danger d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="show-hide"><span class="show"></span></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Retype Password</label>
                                        <div class="form-input position-relative">
                                            <input id="password-confirm" type="password" class="form-control"
                                                name="password_confirmation" autocomplete="new-password"
                                                placeholder="Enter Confirm Password">
                                            <div class="show-hide"><span class="show"></span></div>
                                            @error('password_confirmation')
                                                <span class="text-danger d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <button class="btn btn-primary btn-block w-100 spinner-btn" type="submit">
                                            {{ __('Reset Password') }} </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- page-wrapper Ends-->
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/bookmark/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom-validation/validation.js') }}"></script>
@endsection
