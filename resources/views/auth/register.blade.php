@extends('layouts.authentication.master')

@section('css')
    <!-- Toastr css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/toastr.min.css') }}">
    <style>
        .vb-terms-link { text-decoration: underline !important; color: #111827 !important; font-weight: 600 !important; }
        .vb-terms-link:hover { text-decoration: none !important; color: #111827 !important; }
    </style>
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

                                <div class="form-group agency-fields" id="agency_server_type_group" style="display: none;">
                                    <label class="col-form-label">Account Sub-Type <span class="text-danger">*</span></label>
                                    <select id="agency_server_type" name="agency_server_type" class="form-control">
                                        <option value="">--- Select Account Sub-Type ---</option>
                                        <option value="subdomain_ai_server" {{ old('agency_server_type') == 'subdomain_ai_server' ? 'selected' : '' }}>Subdomain AI Server</option>
                                        <option value="domain_folder_ai_server" {{ old('agency_server_type') == 'domain_folder_ai_server' ? 'selected' : '' }}>Domain Folder AI Server</option>
                                    </select>
                                    @error('agency_server_type')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group agency-fields" id="agency_price_group" style="display: none;">
                                    <label class="col-form-label">Monthly Price</label>
                                    <div class="form-control" style="background-color:#f8f9fa; font-weight:600; border:1px solid #d8d8d8;">
                                        <span id="price_display">—</span>
                                    </div>
                                    <input type="hidden" id="agency_server_price" name="agency_server_price" value="{{ old('agency_server_price') }}">
                                    @error('agency_server_price')
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


                                <div class="form-group">
                                    <label class="col-form-label">Country <span class="text-danger">*</span></label>
                                    <select id="country" name="country" class="form-control" required>
                                        <option value="">-- Select Country --</option>
                                        @foreach($countries as $c)
                                            <option value="{{ $c->name }}" {{ old('country') == $c->name ? 'selected' : '' }}>
                                                {{ $c->iso_3166_2 }} — {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <span class="text-danger d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">Phone</label>
                                    <div class="input-group">
                                        <select id="phone_code" name="phone_code" class="form-control" style="max-width:150px; flex-shrink:0;">
                                            @foreach($countries->filter(fn($c) => $c->calling_code)->sortBy('iso_3166_2')->values() as $c)
                                                    @php($code = '+'.ltrim($c->calling_code, '+'))
                                                    <option value="{{ $code }}" {{ old('phone_code') == $code ? 'selected' : '' }}>
                                                        {{ $c->iso_3166_2 }} {{ $code }}
                                                    </option>
                                            @endforeach
                                        </select>
                                        <input id="phone" type="text" class="form-control" name="phone"
                                            value="{{ old('phone') }}" placeholder="Phone number">
                                    </div>
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
                                        <input class="form-check-input" type="checkbox" name="terms_acceptance" id="terms_acceptance" value="1" {{ old('terms_acceptance') ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="terms_acceptance">
                                            I agree to the <a href="/terms" style="text-decoration: underline;" target="_blank" class="vb-terms-link">Terms of Service</a> and <a href="/privacy" style="text-decoration: underline;" target="_blank" class="vb-terms-link">Privacy Policy</a>
                                        </label>
                                    </div>
                                    @error('terms_acceptance')
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
    <style>
        /* ── Brand color overrides ── */
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
        /* Kill teal on inputs — theme uses rgba(primary,0.1) */
        .login-card .login-main .form-control,
        .login-card .login-main input,
        .login-card .login-main select,
        .login-card .login-main textarea {
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
        /* Kill teal on show/hide icon */
        .login-card .login-main .show-hide {
            color: #666 !important;
        }
        .login-card .login-main a {
            color: #0a0b0c !important;
        }
        .login-card .login-main a:hover {
            color: #444 !important;
        }
        /* Form card white background */
        .login-card .login-main {
            background-color: #ffffff !important;
        }
        /* Force background image to show (overrides .login-dark which hides it) */
        .login-card.login-dark {
            background: url('/assets/images/login/login_bg.jpg') center center / cover no-repeat !important;
            background-color: #f5f6fa !important;
        }
        /* ── Tight form spacing to match login page ── */
        .login-card .login-main .theme-form .form-group { margin-bottom: 16px !important; }
        .login-card .login-main .theme-form .form-group label { margin-bottom: 6px !important; line-height: 1.2 !important; }
        .login-card .login-main .theme-form .form-group .form-control { margin-bottom: 0 !important; }
        .login-card .login-main .theme-form .form-group .text-danger[role="alert"] { font-size: 12px !important; display: block; margin-top: 4px !important; margin-bottom: 0 !important; line-height: 1.2 !important; }
        .login-card .login-main .theme-form .form-group .text-danger[role="alert"] strong { font-weight: 600; }
        .login-card .login-main .theme-form label.error { font-size: 12px !important; color: #dc3545 !important; display: block; margin-top: 4px !important; margin-bottom: 0 !important; line-height: 1.2 !important; font-weight: 600; }
        /* ── Checkbox fixes ── */
        .login-main .form-check {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            padding-left: 0 !important;
        }
        .login-main .form-check-input[type="checkbox"] {
            position: relative !important;
            float: none !important;
            opacity: 1 !important;
            visibility: visible !important;
            width: 18px !important;
            height: 18px !important;
            margin: 0 !important;
            flex-shrink: 0 !important;
            cursor: pointer !important;
            pointer-events: auto !important;
            z-index: 10 !important;
            appearance: auto !important;
            -webkit-appearance: checkbox !important;
            accent-color: #0a0b0c !important;
        }
        .login-main .form-check-label {
            margin: 0 !important;
            cursor: pointer !important;
        }
    </style>
    <script src="{{ asset('assets/js/bookmark/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var prices = {
                'subdomain_ai_server': 99.00,
                'domain_folder_ai_server': 125.00
            };

            function updatePrice() {
                var serverType = $('#agency_server_type').val();
                var price = prices[serverType];
                if (price) {
                    $('#price_display').text('$' + price.toFixed(2) + ' per month');
                    $('#agency_server_price').val(price.toFixed(2));
                    $('#agency_price_group').show();
                } else {
                    $('#price_display').text('—');
                    $('#agency_server_price').val('');
                    $('#agency_price_group').hide();
                }
            }

            function toggleAgencyFields() {
                var accountType = $('#account_type').val();
                if (accountType === 'real_estate_agency') {
                    $('.agency-fields').show();
                    updatePrice();
                } else {
                    $('.agency-fields').hide();
                    $('#agency_server_type').val('');
                    $('#agency_server_price').val('');
                    updatePrice();
                }
            }

            function preselectFromUrl() {
                var params = new URLSearchParams(window.location.search);
                var accountType = params.get('account_type');
                var serverType = params.get('agency_server_type');
                if (accountType && $('#account_type option[value="' + accountType + '"]').length) {
                    $('#account_type').val(accountType).trigger('change');
                }
                if (serverType && $('#agency_server_type option[value="' + serverType + '"]').length) {
                    $('#agency_server_type').val(serverType).trigger('change');
                }
            }

            toggleAgencyFields();
            $('#account_type').on('change', toggleAgencyFields);
            $('#agency_server_type').on('change', updatePrice);
            preselectFromUrl();
        });
    </script>
@endsection
