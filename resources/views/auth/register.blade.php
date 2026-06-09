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
                                <a class="logo" href="{{ route('admin.default_dashboard') }}">
                                    <img class="img-fluid for-light" src="{{ asset($settings['general']['dark_logo']) }}"
                                        alt="looginpage">
                                    <img class="img-fluid for-dark" src="{{ asset($settings['general']['light_logo']) }}"
                                        alt="looginpage">
                                </a>
                            </a>
                        </div>
                        <div class="login-main">
                            <form class="theme-form" method="POST" action="{{ route('register') }}" id="registerForm">
                                @csrf
                                <h4>Create your account</h4>
                                <p>Join Villa Bit AI — select your account type and enter your details</p>

                                <div class="form-group">
                                    <label class="col-form-label">Account Type <span class="text-danger">*</span></label>
                                    <select id="account_type" name="account_type" class="form-control">
                                        <option value="">-- Select Account Type --</option>
                                        <option value="real_estate_agency" {{ old('account_type') == 'real_estate_agency' ? 'selected' : '' }}>Real Estate Agency</option>
                                        <option value="investor" {{ old('account_type') == 'investor' ? 'selected' : '' }}>Real Estate Investor</option>
                                    </select>
                                    @error('account_type')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input id="first_name" type="text" class="form-control" name="first_name"
                                                value="{{ old('first_name') }}" placeholder="First name" required>
                                            @error('first_name')
                                                <span class="text-danger d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-6">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input id="last_name" type="text" class="form-control" name="last_name"
                                                value="{{ old('last_name') }}" placeholder="Last name" required>
                                            @error('last_name')
                                                <span class="text-danger d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group agency-fields" style="display: none;">
                                    <label class="col-form-label">Company / Agency Name <span class="text-danger">*</span></label>
                                    <input id="company_name" type="text" class="form-control" name="company_name"
                                        value="{{ old('company_name') }}" placeholder="Your agency name">
                                    @error('company_name')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group agency-fields" style="display: none;">
                                    <label class="col-form-label">Agency Website URL</label>
                                    <input id="agency_website_url" type="url" class="form-control" name="agency_website_url"
                                        value="{{ old('agency_website_url') }}" placeholder="https://youragency.com">
                                    @error('agency_website_url')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group agency-fields" style="display: none;">
                                    <label class="col-form-label">Target City</label>
                                    <input id="target_city" type="text" class="form-control" name="target_city"
                                        value="{{ old('target_city') }}" placeholder="Main city you operate in">
                                    @error('target_city')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">Country <span class="text-danger">*</span></label>
                                    <input id="country" type="text" class="form-control" name="country"
                                        value="{{ old('country') }}" placeholder="Your country" required>
                                    @error('country')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">Phone</label>
                                    <input id="phone" type="text" class="form-control" name="phone"
                                        value="{{ old('phone') }}" placeholder="Phone number">
                                    @error('phone')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">Email Address <span class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control" name="email"
                                        value="{{ old('email') }}" placeholder="Enter your email" required>
                                    @error('email')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">Password <span class="text-danger">*</span></label>
                                    <div class="form-input position-relative">
                                        <input id="password" type="password" class="form-control" name="password"
                                            placeholder="Minimum 8 characters" required>
                                        <div class="show-hide"><span class="show"></span></div>
                                        @error('password')
                                            <span class="text-danger d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="form-input position-relative">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" placeholder="Repeat password" required>
                                        <div class="show-hide"><span class="show"></span></div>
                                    </div>
                                </div>

                                <input type="hidden" name="referral_code" value="{{ request()->cookie('referral_code') }}">

                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms_acceptance" id="terms_acceptance" value="1" {{ old('terms_acceptance') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="terms_acceptance">
                                            I agree to the <a href="/terms" target="_blank">Terms of Service</a>
                                        </label>
                                    </div>
                                    @error('terms_acceptance')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="privacy_acceptance" id="privacy_acceptance" value="1" {{ old('privacy_acceptance') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="privacy_acceptance">
                                            I agree to the <a href="/privacy" target="_blank">Privacy Policy</a>
                                        </label>
                                    </div>
                                    @error('privacy_acceptance')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-0">
                                    <button class="btn btn-primary btn-block w-100 spinner-btn" type="submit">Create Account</button>
                                </div>

                                <p class="mt-4 mb-0 text-center">Already have an account?<a class="ms-2"
                                        href="{{ route('login') }}">Sign in</a>
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
    <script>
        $(document).ready(function() {
            function toggleAgencyFields() {
                var accountType = $('#account_type').val();
                if (accountType === 'real_estate_agency') {
                    $('.agency-fields').show();
                } else {
                    $('.agency-fields').hide();
                }
            }

            toggleAgencyFields();
            $('#account_type').on('change', toggleAgencyFields);
        });
    </script>
@endsection
