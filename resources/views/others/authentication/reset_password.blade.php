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
                            <div><a class="logo" href="{{ route('admin.default_dashboard') }}">
                                    <img class="img-fluid for-light" src="{{ asset($settings['general']['dark_logo']) }}"
                                        alt="looginpage">
                                    <img class="img-fluid for-dark" src="{{ asset($settings['general']['light_logo']) }}"
                                        alt="loginpage">
                                </a></div>
                            <div class="login-main">
                                <form class="theme-form">
                                    <h4>Create Your Password</h4>
                                    <div class="form-group">
                                        <label class="col-form-label">New Password</label>
                                        <div class="form-input position-relative">
                                            <input class="form-control" type="password" name="password" required=""
                                                placeholder="Enter password">
                                            <div class="show-hide"><span class="show"></span></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Retype Password</label>
                                        <input class="form-control" type="password" name="password" required=""
                                            placeholder="Enter password">
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="checkbox p-0">
                                            <input id="checkbox1" type="checkbox">
                                            <label class="text-muted" for="checkbox1">Remember password</label>
                                        </div>
                                        <button class="btn btn-primary btn-block w-100" type="submit">Done </button>
                                    </div>
                                    <p class="mt-4 mb-0">Don't have account?<a class="ms-2"
                                            href="{{ route('admin.sign_up') }}">Create
                                            Account</a></p>
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
@endsection
