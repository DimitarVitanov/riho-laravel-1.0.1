@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollable.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid setting-page basic_table">
        <div class="page-title"> 
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Settings</h4>  
                </div> 
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Settings</li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row setting-page-con"> 
                    <div class="col-xxl-3 box-col-12 left-sidebar">
                        <div class="md-sidebar sticky-sidebar">
                            <div class="job-left-aside custom-scrollbar">
                                <div class="file-sidebar">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                <ul class="custom-scrollbar">
                                                    <li>
                                                        <a class="nav-link btn main active" id="General_tab" data-bs-toggle="pill" href="#General"
                                                            role="tab" aria-controls="v-pills-settings" aria-selected="false"><i data-feather="settings"></i> General
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link main btn" id="Google_Recaptcha_tab" data-bs-toggle="pill"
                                                            href="#Google_Recaptcha" role="tab" aria-controls="v-pills-settings"
                                                            aria-selected="false"><img src="{{ asset('assets/svg/captcha.svg') }}" alt="">Google reCAPTCHA
                                                        </a>
                                                    </li>
                                                    <li> 
                                                        <a class="nav-link main btn" id="Social_Login_tab" data-bs-toggle="pill"
                                                            href="#Social_Login" role="tab" aria-controls="v-pills-settings"
                                                            aria-selected="false">
                                                            <img src="{{ asset('assets/svg/social-login.svg') }}" alt="">Social Login
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-9 col-md-12 box-col-12">
                        <form method="POST" class="needs-validation user-add" id="settingsForm"
                            action="{{ route('admin.update.settings', $settingsId) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="tab-content w-100" id="v-pills-tabContent">

                                <div class="tab-pane main fade show active" id="General" role="tabpanel"
                                    aria-labelledby="v-pills-settings-tab" tabindex="1">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="image" class="col-md-2">Light Logo<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10 mb-3">
                                                    <input class="form-control" type="file" id="general[light_logo]"
                                                        name="general[light_logo]">
                                                    @error('general[light_logo]')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <span class="help-text">*Upload image size 115×36px
                                                        recommended</span>
                                                </div>
                                            </div>
                                            @isset($settings['general']['light_logo'])
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-10 mb-3">
                                                            <div class="image-list d-inline-block">
                                                                <div class="image-list-detail">
                                                                    <div class="position-relative">
                                                                        <img src="{{ asset($settings['general']['light_logo']) }}"
                                                                            height="40px"
                                                                            id="{{ $settings['general']['light_logo'] }}"
                                                                            alt="Light Logo" class="image-list-item">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endisset
                                            <div class="form-group row">
                                                <label for="image" class="col-md-2">Dark Logo<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10 mb-3">
                                                    <input class="form-control" type="file" id="general[dark_logo]"
                                                        name="general[dark_logo]">
                                                    @error('general[dark_logo]')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <span class="help-text">*Upload image size 120×35px
                                                        recommended</span>
                                                </div>
                                            </div>
                                            @isset($settings['general']['dark_logo'])
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-10 mb-3">
                                                            <div class="image-list">
                                                                <div class="image-list-detail">
                                                                    <div class="position-relative">
                                                                        <img src="{{ asset($settings['general']['dark_logo']) }}"
                                                                            height="40px"
                                                                            id="{{ $settings['general']['dark_logo'] }}"
                                                                            alt="Dark Logo" class="image-list-item">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endisset
                                            <div class="form-group row">
                                                <label for="image" class="col-md-2">Favicon<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10 mb-3">
                                                    <input class="form-control" type="file" id="general[favicon]"
                                                        name="general[favicon]">
                                                    @error('general[favicon]')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <span class="help-text">*Upload image size 36×36px
                                                        recommended</span>
                                                </div>
                                            </div>
                                            @isset($settings['general']['favicon'])
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-10 mb-3">
                                                            <div class="image-list">
                                                                <div class="image-list-detail">
                                                                    <div class="position-relative">
                                                                        <img src="{{ asset($settings['general']['favicon']) }}"
                                                                            height="50px"
                                                                            id="{{ $settings['general']['favicon'] }}"
                                                                            alt="favicon" class="image-list-item">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endisset
                                            <div class="form-group row">
                                                <label class="col-md-2" for="general[site_name]">Site Name<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10 mb-3">
                                                    <input class="form-control" type="text" id="general[site_name]"
                                                        name="general[site_name]"
                                                        value="{{ $settings['general']['site_name'] ?? old('site_name') }}"
                                                        placeholder="Enter Site Name">
                                                    @error('general[site_name]')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2" for="general[footer]">Footer<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10 mb-3">
                                                    <input class="form-control" type="text" id="general[footer]"
                                                        name="general[footer]"
                                                        value="{{ $settings['general']['footer'] ?? old('footer') }}"
                                                        placeholder="Enter Footer">
                                                    @error('general[footer]')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button class="btn btn-primary spinner-btn" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane main fade" id="Google_Recaptcha" role="tabpanel"
                                    aria-labelledby="v-pills-settings-tab" tabindex="2">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label class="col-md-2" for="google_reCaptcha[secret]">Secret Key</label>
                                                <div class="col-md-10 mb-3">
                                                    <input class="form-control" type="password"
                                                        id="google_reCaptcha[secret]" name="google_reCaptcha[secret]"
                                                        value="{{ $settings['google_reCaptcha']['secret'] ?? old('secret') }}"
                                                        placeholder="Enter Secret Key">
                                                    @error('google_reCaptcha[secret]')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2" for="google_reCaptcha[site_key]">Site
                                                    Key</label>
                                                <div class="col-md-10 mb-3">
                                                    <input class="form-control" type="password"
                                                        id="google_reCaptcha[site_key]" name="google_reCaptcha[site_key]"
                                                        value="{{ $settings['google_reCaptcha']['site_key'] ?? old('site_key') }}"
                                                        placeholder="Enter Site Key">
                                                    @error('google_reCaptcha[site_key]')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2" for="google_reCaptcha[status]">Status</label>
                                                <div class="col-md-10 mb-3">
                                                    <div class="editor-space">
                                                        <label class="switch">
                                                            @if (isset($settings['google_reCaptcha']['status']))
                                                                <input class="form-control" type="hidden"
                                                                    name="google_reCaptcha[status]" value="0">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="google_reCaptcha[status]" value="1"
                                                                    {{ $settings['google_reCaptcha']['status'] ? 'checked' : '' }}>
                                                            @else
                                                                <input class="form-control" type="hidden"
                                                                    name="google_reCaptcha[status]" value="0">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="google_reCaptcha[status]" value="1">
                                                            @endif
                                                            <span class="switch-state"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button class="btn btn-primary spinner-btn" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane main fade" id="Social_Login" role="tabpanel"
                                    aria-labelledby="v-pills-settings-tab" tabindex="3">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="simple-wrapper nav nav-tabs border-tab border-1 nav-primary mb-5 custom-scrollbar"
                                                id="pills-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="google_tab" data-bs-toggle="pill"
                                                        href="#google" role="tab" aria-controls="google"
                                                        aria-selected="true">Google</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="facebook_tab" data-bs-toggle="pill"
                                                        href="#facebook" role="tab" aria-controls="facebook"
                                                        aria-selected="false">Facebook</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="google" role="tabpanel"
                                                    aria-labelledby="google_tab">
                                                    <div class="form-group row">
                                                        <label class="col-md-2"
                                                            for="social_login[google][google_client_id]">
                                                            Client
                                                            Id</label>
                                                        <div class="col-md-10 mb-3">
                                                            <input class="form-control" type="password"
                                                                id="social_login[google][google_client_id]"
                                                                name="social_login[google][google_client_id]"
                                                                value="{{ $settings['social_login']['google']['google_client_id'] ?? old('social_login[google][google_client_id]') }}"
                                                                placeholder="Enter Client Id">
                                                            @error('social_login[google][google_client_id]')
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2"
                                                            for="social_login[google][google_client_secret]">
                                                            Client
                                                            Secret</label>
                                                        <div class="col-md-10 mb-3">
                                                            <input class="form-control" type="password"
                                                                id="social_login[google][google_client_secret]"
                                                                name="social_login[google][google_client_secret]"
                                                                value="{{ $settings['social_login']['google']['google_client_secret'] ?? old('social_login[google][google_client_secret]') }}"
                                                                placeholder="Enter Client Secret">
                                                            @error('social_login[google][google_client_secret]')
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2"
                                                            for="social_login[google][google_redirect_url]">
                                                            Redirect
                                                            Url</label>
                                                        <div class="col-md-10 mb-3">
                                                            <input class="form-control" type="password"
                                                                id="social_login[google][google_redirect_url]"
                                                                name="social_login[google][google_redirect_url]"
                                                                value="{{ $settings['social_login']['google']['google_redirect_url'] ?? old('social_login[google][google_redirect_url]') }}"
                                                                placeholder="Enter Redirect Url">
                                                            @error('social_login[google][google_redirect_url]')
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2"
                                                            for="social_login[google][google_login_status]">Status</label>
                                                        <div class="col-md-10 mb-3">
                                                            <div class="editor-space">
                                                                <label class="switch">
                                                                    @if (isset($settings['social_login']['google']['google_login_status']))
                                                                        <input class="form-control" type="hidden"
                                                                            name="social_login[google][google_login_status]"
                                                                            value="0">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="social_login[google][google_login_status]"
                                                                            value="1"
                                                                            {{ $settings['social_login']['google']['google_login_status'] ? 'checked' : '' }}>
                                                                    @else
                                                                        <input class="form-control" type="hidden"
                                                                            name="social_login[google][google_login_status]"
                                                                            value="0">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="social_login[google][google_login_status]"
                                                                            value="1">
                                                                    @endif
                                                                    <span class="switch-state"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="facebook" role="tabpanel"
                                                    aria-labelledby="facebook_tab">
                                                    <div class="form-group row">
                                                        <label class="col-md-2"
                                                            for="social_login[facebook][facebook_client_id]">
                                                            App
                                                            Id</label>
                                                        <div class="col-md-10 mb-3">
                                                            <input class="form-control" type="password"
                                                                id="social_login[facebook][facebook_client_id]"
                                                                name="social_login[facebook][facebook_client_id]"
                                                                value="{{ $settings['social_login']['facebook']['facebook_client_id'] ?? old('social_login[facebook][facebook_client_id]') }}"
                                                                placeholder="Enter App Id">
                                                            @error('social_login[facebook][facebook_client_id]')
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2"
                                                            for="social_login[facebook][facebook_client_secret]">
                                                            App Secret</label>
                                                        <div class="col-md-10 mb-3">
                                                            <input class="form-control" type="password"
                                                                id="social_login[facebook][facebook_client_secret]"
                                                                name="social_login[facebook][facebook_client_secret]"
                                                                value="{{ $settings['social_login']['facebook']['facebook_client_secret'] ?? old('social_login[facebook][facebook_client_secret]') }}"
                                                                placeholder="Enter App Secret">
                                                            @error('social_login[facebook][facebook_client_secret]')
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2"
                                                            for="social_login[facebook][facebook_redirect_url]">
                                                            Redirect Url</label>
                                                        <div class="col-md-10 mb-3">
                                                            <input class="form-control" type="password"
                                                                id="social_login[facebook][facebook_redirect_url]"
                                                                name="social_login[facebook][facebook_redirect_url]"
                                                                value="{{ $settings['social_login']['facebook']['facebook_redirect_url'] ?? old('social_login[facebook][facebook_redirect_url]') }}"
                                                                placeholder="Enter Redirect Url">
                                                            @error('social_login[facebook][facebook_redirect_url]')
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2"
                                                            for="social_login[facebook][facebook_login_status]">Status</label>
                                                        <div class="col-md-10 mb-3">
                                                            <div class="editor-space">
                                                                <label class="switch">
                                                                    @if (isset($settings['social_login']['facebook']['facebook_login_status']))
                                                                        <input class="form-control" type="hidden"
                                                                            name="social_login[facebook][facebook_login_status]"
                                                                            value="0">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="social_login[facebook][facebook_login_status]"
                                                                            value="1"
                                                                            {{ $settings['social_login']['facebook']['facebook_login_status'] ? 'checked' : '' }}>
                                                                    @else
                                                                        <input class="form-control" type="hidden"
                                                                            name="social_login[facebook][facebook_login_status]"
                                                                            value="0">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="social_login[facebook][facebook_login_status]"
                                                                            value="1">
                                                                    @endif
                                                                    <span class="switch-state"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button class="btn btn-primary spinner-btn" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/bookmark/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            "use strict";

            function addImageMimeValidation(methodName, mimeType, errorMessage) {
                $.validator.addMethod(methodName, function(value, element) {
                    const file = element.files && element.files[0];
                    if (file) {
                        const mime = file.type;
                        return mime === mimeType;
                    }
                    return true;
                }, errorMessage);
            }

            // Add image MIME validation methods
            addImageMimeValidation("imageMime", "image/png",
                "Please enter a value with a valid mimetype. (JPG, PNG, JPEG).");

            $('#settingsForm').validate({
                ignore: [],
                rules: {
                    'general[dark_logo]': {
                        required: isDarkLogo,
                        imageMime: true
                    },
                    'general[light_logo]': {
                        required: isLightLogo,
                        imageMime: true
                    },
                    'general[favicon]': {
                        required: isFavicon,
                        imageMime: true
                    },
                    'general[site_name]': "required",
                    'general[footer]': "required",
                },
                invalidHandler: function(event, validator) {
                    let invalidMainTabs = [];
                    let invalidSubTabs = new Map(); // Store invalid sub-tabs per main tab

                    // Collect all invalid tabs
                    $.each(validator.errorList, function(index, error) {
                        const subTabId = $(error.element).closest('.tab-pane').attr('id');
                        const mainTabId = $("#" + subTabId).closest('.main').attr('id');

                        if (mainTabId && !invalidMainTabs.includes(mainTabId)) {
                            invalidMainTabs.push(mainTabId);
                        }

                        if (subTabId) {
                            if (!invalidSubTabs.has(mainTabId)) {
                                invalidSubTabs.set(mainTabId, []);
                            }
                            if (!invalidSubTabs.get(mainTabId).includes(subTabId)) {
                                invalidSubTabs.get(mainTabId).push(subTabId);
                            }
                        }
                    });

                    if (invalidMainTabs.length > 0) {
                        let firstMainTab = invalidMainTabs[0];
                        let firstSubTab = invalidSubTabs.get(firstMainTab)?.[0];

                        // If no invalid sub-tabs exist, activate the first available sub-tab inside this main tab
                        if (!firstSubTab) {
                            firstSubTab = $("#" + firstMainTab).find(".tab-pane").first().attr('id');
                        }

                        activateTab(firstMainTab, firstSubTab);
                    }

                    function activateTab(mainTabId, subTabId) {
                        // Reset only main tabs, sub-tabs are handled separately
                        $(".nav-link.main.active").removeClass("active");
                        $(".tab-pane.main.show").removeClass("show active");

                        // Activate the first invalid main tab
                        if (mainTabId) {
                            $("#" + mainTabId + "_tab").addClass("active");
                            $("#" + mainTabId).addClass("show active");
                        }

                        // Activate the first invalid sub-tab OR the first available sub-tab
                        if (subTabId) {
                            $("#" + subTabId + "_tab").tab("show"); // Bootstrap handles proper switching
                        }
                    }
                }
            });

            function isDarkLogo() {
                @if (isset($settings['general']['dark_logo']))
                    return false;
                @else
                    return true;
                @endif
            }

            function isLightLogo() {
                @if (isset($settings['general']['light_logo']))
                    return false;
                @else
                    return true;
                @endif
            }

            function isFavicon() {
                @if (isset($settings['general']['favicon']))
                    return false;
                @else
                    return true;
                @endif
            }
        });
    </script>
@endsection
