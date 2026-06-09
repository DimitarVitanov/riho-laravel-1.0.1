@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/quill.snow.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollable.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid basic_table landing_page">
        <div class="page-title">
            <div class="row g-2"> 
                <div class="col-sm-6">
                    <h4>Landing Page</h4> 
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li> 
                        <li class="breadcrumb-item">Settings</li>
                        <li class="breadcrumb-item active">Landing Page</li>
                    </ol>
                </div>
            </div> 
        </div>

        <div class="card tab2-card">
            <div class="card-body">
                <div class="row landing-page-con">
                    <div class="col-xxl-3 box-col-12 left-sidebar">
                        <div class="md-sidebar sticky-sidebar">
                            <div class="job-left-aside custom-scrollbar">
                                <div class="file-sidebar">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                                aria-orientation="vertical"> 
                                                <ul class="custom-scrollbar"> 
                                                    <li>
                                                        <a class="nav-link main btn active" id="Header_Section_tab" data-bs-toggle="pill"
                                                            data-bs-target="#Header_Section" type="button" role="tab"
                                                            aria-controls="App_Settings" aria-selected="true">
                                                            <i data-feather="sliders"></i>Header
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link main btn" id="Home_Section_tab" data-bs-toggle="pill"
                                                            data-bs-target="#Home_Section" type="button" role="tab"
                                                            aria-controls="Home_Settings" aria-selected="true">
                                                            <i data-feather="home"></i>Home
                                                        </a>
                                                    </li>
                                                    <li> 

                                                        <a class="nav-link main btn" id="Page_Section_tab" data-bs-toggle="pill"
                                                            data-bs-target="#Page_Section" type="button" role="tab"
                                                            aria-controls="page_Settings" aria-selected="true">
                                                            <i data-feather="layout"></i>Page
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link main btn" id="feature_Section_tab" data-bs-toggle="pill"
                                                            data-bs-target="#feature_Section" type="button" role="tab"
                                                            aria-controls="feature_Settings" aria-selected="true">
                                                            <i data-feather="list"></i>Feature
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link main btn" id="footer_Section_tab" data-bs-toggle="pill"
                                                            data-bs-target="#footer_Section" type="button" role="tab"
                                                            aria-controls="footer_Settings" aria-selected="true">
                                                            <i data-feather="settings"></i>Footer
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
                        <form method="POST" class="needs-validation user-add" id="landing_PageForm"
                            action="{{ route('admin.landing.update', $id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="tab-content w-100" id="v-pills-tabContent">
                                <div class="tab-pane main fade show active p-3" id="Header_Section" role="tabpanel"
                                    aria-labelledby="app_settings" tabindex="0">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2" for="logo">
                                                    Logo<span class="text-danger">*</span>
                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom" data-bs-html="true"
                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/header-section/logo.png') }}' height=150px;>"></i>
                                                </label>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <input type="file" class="form-control fileInput"
                                                            name="header[logo]">
                                                        @error('header[logo]')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                        <span class="help-text">*Upload image size 120×35px
                                                            recommended</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @if (isset($content['header']['logo']) && !empty($content['header']['logo']))
                                                <div class="form-group row mb-3">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-10 mb-3">
                                                        <div class="image-list">
                                                            <div class="image-list-detail">
                                                                <div class="position-relative">
                                                                    <img src="{{ asset($content['header']['logo']) }}"
                                                                        height="40px"
                                                                        id="{{ $content['header']['logo'] }}"
                                                                        alt="Logo Image" class="image-list-item">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2" for="btn_link">Header Menus<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10">
                                                    <div class="mb-3 d-flex flex-column-reverse">
                                                        <select class="js-menus-placeholder-multiple"
                                                            multiple="multiple" name="header[menus][]">
                                                            <option value="Home"
                                                                @if (in_array('Home', @$content['header']['menus'] ?? [])) selected @endif>Home
                                                            </option>
                                                            <option value="Page"
                                                                @if (in_array('Page', @$content['header']['menus'] ?? [])) selected @endif>
                                                                Page
                                                            </option>
                                                            <option value="Feature"
                                                                @if (in_array('Feature', @$content['header']['menus'] ?? [])) selected @endif>
                                                                Feature
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title txt-primary">
                                                Link 1
                                                <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-html="true"
                                                    data-bs-title="<img src='{{ asset('assets/images/tooltip/header-section/link1-img.png') }}' height=150px;>"></i>
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2" for="link1_text">Text<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10">
                                                    <input class="form-control" type="text"
                                                        name="header[links][link1][text]"
                                                        value="{{ @$content['header']['links']['link1']['text'] ?? old('header[links][link1][text]') }}"
                                                        placeholder="Enter Link Text">
                                                    @error('header[links][link1][text]')
                                                        <span class="invalid-feedback d-block"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2" for="link1_url">URL<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10">
                                                    <input class="form-control" type="text"
                                                        name="header[links][link1][url]"
                                                        value="{{ @$content['header']['links']['link1']['url'] ?? old('header[links][link1][url]') }}"
                                                        placeholder="Enter Redirect Url">
                                                    @error('header[links][link1][url]')
                                                        <span class="invalid-feedback d-block"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h5 class="card-title txt-primary">
                                                Link 2
                                                <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-html="true"
                                                    data-bs-title="<img src='{{ asset('assets/images/tooltip/header-section/link2-img.png') }}' height=150px;>"></i>
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2" for="link2_text">Text<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10">
                                                    <input class="form-control" type="text"
                                                        name="header[links][link2][text]"
                                                        value="{{ @$content['header']['links']['link2']['text'] ?? old('header[links][link2][text]') }}"
                                                        placeholder="Enter Link Text">
                                                    @error('header[links][link2][text]')
                                                        <span class="invalid-feedback d-block"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2" for="link2_url">URL<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10">
                                                    <input class="form-control" type="text"
                                                        name="header[links][link2][url]"
                                                        value="{{ @$content['header']['links']['link2']['url'] ?? old('header[links][link2][url]') }}"
                                                        placeholder="Enter Redirect Url">
                                                    @error('header[links][link2][url]')
                                                        <span class="invalid-feedback d-block"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h5 class="card-title txt-primary">
                                                Link 3
                                                <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-html="true"
                                                    data-bs-title="<img src='{{ asset('assets/images/tooltip/header-section/link3-img.png') }}' height=150px;>"></i>
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2" for="link3_text">Text<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10">
                                                    <input class="form-control" type="text"
                                                        name="header[links][link3][text]"
                                                        value="{{ @$content['header']['links']['link3']['text'] ?? old('header[links][link3][text]') }}"
                                                        placeholder="Enter Link Text">
                                                    @error('header[links][link3][text]')
                                                        <span class="invalid-feedback d-block"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2" for="link3_url">URL<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10">
                                                    <input class="form-control" type="text"
                                                        name="header[links][link3][url]"
                                                        value="{{ @$content['header']['links']['link3']['url'] ?? old('header[links][link3][url]') }}"
                                                        placeholder="Enter Redirect Url">
                                                    @error('header[links][link3][url]')
                                                        <span class="invalid-feedback d-block"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title txt-primary">
                                                Button
                                                <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-html="true"
                                                    data-bs-title="<img src='{{ asset('assets/images/tooltip/header-section/button-img.png') }}' height=150px;>"></i>
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2" for="btn_text">Text<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10">
                                                    <input class="form-control" type="text"
                                                        name="header[btn_text]" id=""
                                                        value="{{ @$content['header']['btn_text'] ?? old('header[btn_text]') }}"
                                                        placeholder="Enter Button Text">
                                                    @error('header[btn_text]')
                                                        <span class="text-danger invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2" for="btn_link">Url<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-10">
                                                    <input class="form-control" type="text" name="header[btn_url]"
                                                        id=""
                                                        value="{{ @$content['header']['btn_url'] ?? old('header[btn_url]') }}"
                                                        placeholder="Enter Redirect Url">
                                                    @error('header[btn_url]')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary spinner-btn nextBtn">Save</button>
                                    </div>
                                </div>

                                <div class="tab-pane main fade" id="Home_Section" role="tabpanel"
                                    aria-labelledby="home_Settings" tabindex="1">
                                    <div class="col-sm-12 ">
                                        <div class="card height-equal">
                                            <div class="card-body"> 
                                                <ul class="simple-wrapper nav nav-tabs border-tab border-1 nav-primary mb-5 custom-scrollbar" id="pills-tab" role="tablist">
                                                    <li class="nav-item" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom" data-bs-html="true"
                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/home-section/home.png') }}' height=150px;>">
                                                        <a class="nav-link active" id="home_tab"
                                                            data-bs-toggle="pill" href="#home" role="tab"
                                                            aria-controls="pills-aboutus"
                                                            aria-selected="true">Home</a></li>
                                                    <li class="nav-item" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom" data-bs-html="true"
                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/home-section/crud.png') }}' height=150px;>">
                                                        <a class="nav-link" id="crud_tab" data-bs-toggle="pill"
                                                            href="#crud" role="tab"
                                                            aria-controls="pills-contactus"
                                                            aria-selected="false">Laravel Crud</a></li>
                                                </ul>
                                                <div class="tab-content" id="pills-tabContent">
                                                    <div class="tab-pane fade show active" id="home"
                                                        role="tabpanel" aria-labelledby="home_tab">
                                                        <div class="card-body">
                                                            <div class="form-group row mb-3">
                                                                <label for="home[title]" class="col-md-2">Title<span
                                                                        class="text-danger">*</span>
                                                                    <i class="icon-help-alt"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/home-section/home-title.png') }}' height=250px;>"></i>    
                                                                </label>
                                                                <div class="col-md-10 mb-3 d-flex flex-column">
                                                                    <textarea class="form-control email-content" placeholder="Enter Title" rows="4" id="home_title"
                                                                        name="home[title]" cols="50">{{ @$content['home']['title'] ?? old('home[title]') }}</textarea>
                                                                    @error('home[title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2"
                                                                    for="portfolio_text">Description</label>
                                                                <div class="col-md-10">
                                                                    <input type="text" class="form-control"
                                                                        name="home[description]"
                                                                        value="{{ $content['home']['description'] ?? old('home[description]') }}"
                                                                        placeholder="Enter Description">
                                                                    @error('home[description]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="logo">
                                                                    Image<span class="text-danger">* </span>
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/home-section/main.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <div class="form-group">
                                                                        <input type="file"
                                                                            class="form-control fileInput"
                                                                            name="home[main_img]">
                                                                        @error('home[main_img]')
                                                                            <span class="invalid-feedback d-block"
                                                                                role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                        <span class="help-text">*Upload image size
                                                                            960×762px
                                                                            recommended</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                @if (isset($content['home']['main_img']) && !empty($content['home']['main_img']))
                                                                    <div class="col-md-2"></div>
                                                                    <div class="col-md-10 mb-3">
                                                                    <div class="image-list">
                                                                        <div class="image-list-detail">
                                                                            <div class="position-relative">
                                                                                <img src="{{ asset(@$content['home']['main_img']) }}"
                                                                                    height="80px"
                                                                                    id="{{ @$content['home']['main_img'] }}"
                                                                                    alt="Screen Image">
                                                                            </div>    
                                                                        </div>
                                                                    </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="logo">
                                                                    Image<span class="text-danger">*
                                                                    </span><i class="icon-help-alt"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/home-section/sidebar.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <div class="form-group">
                                                                        <input type="file"
                                                                            class="form-control fileInput"
                                                                            name="home[sidebar_cuts_image]">
                                                                        @error('home[sidebar_cuts_image]')
                                                                            <span class="invalid-feedback d-block"
                                                                                role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                        <span class="help-text">*Upload image size
                                                                            191×318px
                                                                            recommended</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                @if (isset($content['home']['sidebar_cuts_image']) && !empty($content['home']['sidebar_cuts_image']))
                                                                    <div class="col-md-2"></div>
                                                                    <div class="col-md-10">
                                                                        <div class="image-list">
                                                                                <div class="image-list-detail">
                                                                                    <div class="position-relative">
                                                                                        <img height="80px"
                                                                                        src="{{ asset(@$content['home']['sidebar_cuts_image']) }}"
                                                                                        alt="Current Logo">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                      
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="logo">
                                                                    Image<span class="text-danger">*
                                                                    </span><i class="icon-help-alt"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/home-section/left_top_img.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <div class="form-group">
                                                                        <input type="file"
                                                                        class="form-control fileInput"
                                                                            name="home[left_top_image]">
                                                                        @error('home[left_top_image]')
                                                                            <span class="invalid-feedback d-block"
                                                                                role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                        <span class="help-text">*Upload image size
                                                                            216×234px
                                                                            recommended</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                    <div class="form-group row mb-3">
                                                                    @if (isset($content['home']['left_top_image']) && !empty($content['home']['left_top_image']))
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                            <div class="image-list">
                                                                                <div class="image-list-detail">
                                                                                    <div class="position-relative">
                                                                                        <img height="80px"
                                                                                            src="{{ asset(@$content['home']['left_top_image']) }}"
                                                                                            alt="Current Logo">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="logo">
                                                                    Image<span class="text-danger">*
                                                                    </span><i class="icon-help-alt"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/home-section/left_bottom_img.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <div class="form-group">
                                                                        <input type="file"
                                                                            class="form-control fileInput"
                                                                            name="home[left_bottom_image]">
                                                                        @error('home[left_bottom_image]')
                                                                            <span class="invalid-feedback d-block"
                                                                                role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                        <span class="help-text">*Upload image size
                                                                            214×112px
                                                                            recommended</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="form-group row mb-3">
                                                                    @if (isset($content['home']['left_bottom_image']) && !empty($content['home']['left_bottom_image']))
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                        <div class="image-list">
                                                                            <div class="image-list-detail">
                                                                                    <div class="position-relative">
                                                                                        <img height="80px"
                                                                                        src="{{ asset(@$content['home']['left_bottom_image']) }}"
                                                                                        alt="Left Side Bottom Logo">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="logo">
                                                                    Image<span class="text-danger">*
                                                                    </span><i class="icon-help-alt"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/home-section/right_top_img.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <div class="form-group">
                                                                        <input type="file"
                                                                            class="form-control fileInput"
                                                                            name="home[right_top_image]">
                                                                        @error('home[right_top_image]')
                                                                            <span class="invalid-feedback d-block"
                                                                                role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                        <span class="help-text">*Upload image size
                                                                            379×228px
                                                                            recommended</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                <div class="form-group row mb-3">
                                                                    @if (isset($content['home']['right_top_image']) && !empty($content['home']['right_top_image']))
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                            <div class="image-list">
                                                                                <div class="image-list-detail">
                                                                                    <div class="position-relative">           
                                                                                        <img height="80px"
                                                                                            src="{{ asset(@$content['home']['right_top_image']) }}"
                                                                                            alt="Current Logo">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="logo">
                                                                    Image<span class="text-danger">*
                                                                    </span><i class="icon-help-alt"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/home-section/right_bottom_img.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <div class="form-group">
                                                                        <input type="file"
                                                                            class="form-control fileInput"
                                                                            name="home[right_bottom_image]">
                                                                        @error('home[right_bottom_image]')
                                                                            <span class="invalid-feedback d-block"
                                                                                role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                        <span class="help-text">*Upload image size
                                                                            221×289px
                                                                            recommended</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                <div class="form-group row mb-3">
                                                                    @if (isset($content['home']['right_bottom_image']) && !empty($content['home']['right_bottom_image']))
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                            <div class="image-list">
                                                                                <div class="image-list-detail">
                                                                                    <div class="position-relative">
                                                                                        <img height="80px" src="{{ asset(@$content['home']['right_bottom_image']) }}" alt="Current Logo">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                    
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="text-end">
                                                                    <button type="submit" class="btn btn-primary spinner-btn nextBtn">Save</button>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="crud" role="tabpanel"
                                                        aria-labelledby="pills-contactus-tab">
                                                        <div class="card mt-3">
                                                            <div class="card-body">
                                                                <div class="form-group row mb-3">
                                                                    <label class="col-md-2" for="title">Title<span
                                                                            class="text-danger">*</span>
                                                                        <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/home-section/crud-title.png') }}' height=250px;>"></i>    
                                                                    </label>
                                                                    <div class="col-md-10">
                                                                        <input class="form-control" type="text"
                                                                            name="home[laravel_crud][title]"
                                                                            value="{{ @$content['home']['laravel_crud']['title'] ?? old('home[laravel_crud][title]') }}"
                                                                            placeholder="Enter Title">
                                                                        @error('home[laravel_crud][title]')
                                                                            <span class="invalid-feedback d-block"
                                                                                role="alert"><strong>{{ $message }}</strong></span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-3">
                                                                    <label class="col-md-2" for="portfolio_url">Sub
                                                                        Title
                                                                        <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/home-section/crud-sub-title.png') }}' height=250px;>"></i>
                                                                    </label>
                                                                    <div class="col-md-10">
                                                                        <input class="form-control" type="text"
                                                                            name="home[laravel_crud][sub_title]"
                                                                            value="{{ @$content['home']['laravel_crud']['sub_title'] ?? old('home[laravel_crud][sub_title]') }}"
                                                                            placeholder="Enter Sub Title">
                                                                        @error('home[laravel_crud][sub_title]')
                                                                            <span class="invalid-feedback d-block"
                                                                                role="alert"><strong>{{ $message }}</strong></span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="accordion" id="crudBannersAccordion">
                                                            @foreach ($content['home']['laravel_crud']['crud_banners'] ?? [] as $index => $banner)
                                                                @include(
                                                                    'admin.landing-page.crud_banner',
                                                                    [
                                                                        'index' => $index,
                                                                        'banner' => $banner,
                                                                    ]
                                                                )
                                                            @endforeach
                                                        </div>

                                                        <div class="text-end">
                                                            <button type="button" id="addCrudBanner"
                                                                class="btn btn-primary mt-3">Add
                                                                Crud Banner</button>
                                                        </div>   
                                                        <div class="text-end mt-2">
                                                            <button type="submit" class="btn btn-primary spinner-btn nextBtn">Save</button>
                                                        </div>            
                                                        <template id="crudBannerTemplate">
                                                            @include('admin.landing-page.crud_banner', [
                                                                'index' => '__INDEX__',
                                                                'banner' => null,
                                                            ])
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane main fade" id="Page_Section" role="tabpanel"
                                    aria-labelledby="home_Settings" tabindex="0">
                                    <div class="col-sm-12 ">
                                        <div class="card height-equal">
                                            <div class="card-body">
                                                <ul class="simple-wrapper nav nav-tabs border-tab border-1 nav-primary mb-5 custom-scrollbar" id="pills-tab" role="tablist">
                                                    <li class="nav-item" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom" data-bs-html="true"
                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/layouts-section/dash.png') }}' height=150px;>"><a class="nav-link active" id="layout_tab"
                                                            data-bs-toggle="pill" href="#layout" role="tab"
                                                            aria-controls="pills-aboutus"
                                                            aria-selected="true">Dashboard</a></li>
                                                    <li class="nav-item" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom" data-bs-html="true"
                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/layouts-section/framework.png') }}' height=150px;>"><a class="nav-link" id="framework_tab"
                                                            data-bs-toggle="pill" href="#framework" role="tab"
                                                            aria-controls="pills-contactus"
                                                            aria-selected="false">Frameworks</a></li>
                                                    <li class="nav-item" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom" data-bs-html="true"
                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/layouts-section/app.png') }}' height=150px;>"><a class="nav-link" id="app_tab"
                                                            data-bs-toggle="pill" href="#app" role="tab"
                                                            aria-controls="pills-contactus"
                                                            aria-selected="false">Applications</a></li>
                                                </ul>
                                                <div class="tab-content" id="pills-tabContent">
                                                    <div class="tab-pane fade show active" id="layout"
                                                        role="tabpanel" aria-labelledby="layout_tab">
                                                        <div class="card-body p-0">
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2"
                                                                    for="portfolio_text">Title<span
                                                                        class="text-danger">*</span>
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/layouts-section/layout-title.png') }}' height=250px;>"></i>    
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="page[title]"
                                                                        value="{{ @$content['page']['title'] ?? old('page[title]') }}"
                                                                        placeholder="Enter Title">
                                                                    @error('page[title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="portfolio_text">Sub
                                                                    Title
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/layouts-section/layout-sub-title.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="page[sub_title]"
                                                                        value="{{ @$content['page']['sub_title'] ?? old('page[sub_title]') }}"
                                                                        placeholder="Enter Sub Title">
                                                                    @error('page[sub_title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="accordion" id="dashBannersAccordion">
                                                                @foreach ($content['page']['dashboard'] ?? [] as $index => $banner)
                                                                    @include(
                                                                        'admin.landing-page.dashboard_banner',
                                                                        [
                                                                            'index' => $index,
                                                                            'banner' => $banner,
                                                                        ]
                                                                    )
                                                                @endforeach
                                                            </div>
                                                            <div class="text-end">
                                                                <button type="button" id="addDashBanner"
                                                                    class="btn btn-primary mt-3">Add
                                                                    Dashboard Layout</button>
                                                            </div>
                                                            <div class="text-end mt-2">
                                                                <button type="submit" class="btn btn-primary spinner-btn nextBtn">Save</button>
                                                            </div>     
                                                            <template id="dashBannerTemplate">
                                                                @include(
                                                                    'admin.landing-page.dashboard_banner',
                                                                    [
                                                                        'index' => '__INDEX__',
                                                                        'banner' => null,
                                                                    ]
                                                                )
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="framework" role="tabpanel"
                                                        aria-labelledby="framework_tab">
                                                        <div class="card-body">
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2"
                                                                    for="portfolio_text">Title<span
                                                                        class="text-danger">*</span>
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/layouts-section/framework-title.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="page[frameworks][title]"
                                                                        value="{{ @$content['page']['frameworks']['title'] ?? old('page[frameworks][title]') }}"
                                                                        placeholder="Enter Title">
                                                                    @error('page[frameworks][title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="portfolio_text">
                                                                    Sub
                                                                    Title
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/layouts-section/frmework-sub-title.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="page[frameworks][sub_title]"
                                                                        value="{{ @$content['page']['frameworks']['sub_title'] ?? old('page[frameworks][sub_title]') }}"
                                                                        placeholder="Enter Sub Title">
                                                                    @error('page[frameworks][sub_title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="accordion" id="LayoutFrameworkAccordion">
                                                            @foreach ($content['page']['frameworks']['poster'] ?? [] as $index => $banner)
                                                                @include(
                                                                    'admin.landing-page.layout_framework',
                                                                    [
                                                                        'index' => $index,
                                                                        'banner' => $banner,
                                                                    ]
                                                                )
                                                            @endforeach
                                                        </div>
                                                        <div class="text-end">
                                                            <button type="button" id="addFrameworkPoster"
                                                                class="btn btn-primary mt-3">Add
                                                                Framework Banner</button>
                                                        </div>
                                                        <div class="text-end mt-2">
                                                            <button type="submit" class="btn btn-primary spinner-btn nextBtn">Save</button>
                                                        </div>     
                                                        <template id="layoutFrameworks">
                                                            @include(
                                                                'admin.landing-page.layout_framework',
                                                                [
                                                                    'index' => '__INDEX__',
                                                                    'banner' => null,
                                                                ]
                                                            )
                                                        </template>
                                                    </div>
                                                    <div class="tab-pane fade" id="app" role="tabpanel"
                                                        aria-labelledby="app_tab">
                                                        <div class="card-body p-0">
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2"
                                                                    for="portfolio_text">Title<span
                                                                        class="text-danger">*</span>
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/layouts-section/application-title.png') }}' height=250px;>"></i>    
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="page[applications][title]"
                                                                        value="{{ @$content['page']['applications']['title'] ?? old('page[applications][title]') }}"
                                                                        placeholder="Enter Title">
                                                                    @error('page[applications][title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="portfolio_text">Sub
                                                                    Title
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/layouts-section/application-sub-title.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="page[applications][sub_title]"
                                                                        value="{{ @$content['page']['applications']['sub_title'] ?? old('page[applications][sub_title]') }}"
                                                                        placeholder="Enter Sub Title">
                                                                    @error('page[applications][sub_title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="accordion" id="layoutApplicationAccordion">
                                                                @foreach ($content['page']['applications']['poster'] ?? [] as $index => $banner)
                                                                    @include(
                                                                        'admin.landing-page.layout_application',
                                                                        [
                                                                            'index' => $index,
                                                                            'banner' => $banner,
                                                                        ]
                                                                    )
                                                                @endforeach
                                                            </div>
                                                            <div class="text-end"> 
                                                                <button type="button" id="addApplicationPoster"
                                                                    class="btn btn-primary mt-3">Add
                                                                    Application Banner</button>
                                                            </div>
                                                            <div class="text-end mt-2">
                                                                <button type="submit" class="btn btn-primary spinner-btn nextBtn">Save</button>
                                                            </div>     
                                                            <template id="layoutApplication">
                                                                @include(
                                                                    'admin.landing-page.layout_application',
                                                                    [
                                                                        'index' => '__INDEX__',
                                                                        'banner' => null,
                                                                    ]
                                                                )
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane main fade" id="feature_Section" role="tabpanel"
                                    aria-labelledby="feature_Settings" tabindex="0">
                                    <div class="col-sm-12 ">
                                        <div class="card height-equal">
                                            <div class="card-body">
                                                <ul class="simple-wrapper nav nav-tabs border-tab border-1 nav-primary mb-5 custom-scrollbar" id="pills-tab" role="tablist">
                                                    <li class="nav-item" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom" data-bs-html="true"
                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/fetaure-section/laravel-feature.png') }}' height=150px;>"><a class="nav-link active" id="laravel_tab"
                                                            data-bs-toggle="pill" href="#laravel" role="tab"
                                                            aria-controls="pills-aboutus" aria-selected="true">Laravel
                                                            Features</a></li>
                                                    <li class="nav-item" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom" data-bs-html="true"
                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/fetaure-section/Premium.png') }}' height=150px;>"><a class="nav-link" id="support_tab"
                                                            data-bs-toggle="pill" href="#support" role="tab"
                                                            aria-controls="pills-contactus"
                                                            aria-selected="false">Premium Support</a></li>
                                                </ul>
                                                <div class="tab-content" id="pills-tabContent">
                                                    <div class="tab-pane fade show active" id="laravel"
                                                        role="tabpanel" aria-labelledby="laravel_tab">
                                                        <div class="card-body">
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2"
                                                                    for="portfolio_text">Title<span
                                                                        class="text-danger">*</span>
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/fetaure-section/feature-title.png') }}' height=250px;>"></i>        
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="feature[laravel_feature][title]"
                                                                        value="{{ @$content['feature']['laravel_feature']['title'] ?? old('feature[laravel_feature][title]') }}"
                                                                        placeholder="Enter Title">
                                                                    @error('feature[laravel_feature][title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="portfolio_text">Sub
                                                                    Title
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/fetaure-section/feature-sub-title.png') }}' height=250px;>"></i>    
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="feature[laravel_feature][sub_title]"
                                                                        value="{{ @$content['feature']['laravel_feature']['sub_title'] ?? old('feature[laravel_feature][sub_title]') }}"
                                                                        placeholder="Enter Sub Title">
                                                                    @error('feature[laravel_feature][sub_title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="accordion" id="laravelFeatureAccordion">
                                                            @foreach ($content['feature']['laravel_feature']['poster'] ?? [] as $index => $banner)
                                                                @include(
                                                                    'admin.landing-page.laravel_feature',
                                                                    [
                                                                        'index' => $index,
                                                                        'banner' => $banner,
                                                                    ]
                                                                )
                                                            @endforeach
                                                        </div>
                                                        <div class="text-end">
                                                            <button type="button" id="addLaravelFeature"
                                                                class="btn btn-primary mt-3">Add
                                                                Laravel Feature Banner</button>
                                                        </div>
                                                        <div class="text-end mt-2">
                                                            <button type="submit" class="btn btn-primary spinner-btn nextBtn">Save</button>
                                                        </div>     
                                                        <template id="laravelFeature">
                                                            @include(
                                                                'admin.landing-page.laravel_feature',
                                                                [
                                                                    'index' => '__INDEX__',
                                                                    'banner' => null,
                                                                ]
                                                            )
                                                        </template>
                                                    </div>
                                                    <div class="tab-pane fade" id="support" role="tabpanel"
                                                        aria-labelledby="support_tab">
                                                        <div class="card-body p-0">
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="portfolio_url">TItle<span
                                                                        class="text-danger">*</span>
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/fetaure-section/premium-title.png') }}' height=250px;>"></i>        
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="feature[premium_support][title]"
                                                                        value="{{ @$content['feature']['premium_support']['title'] ?? old('feature[premium_support][title]') }}"
                                                                        placeholder="Enter Title">
                                                                    @error('feature[premium_support][title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2"
                                                                    for="description">Description</label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="feature[premium_support][description]"
                                                                        value="{{ @$content['feature']['premium_support']['description'] ?? old('feature[premium_support][description]') }}"
                                                                        placeholder="Enter Description">
                                                                    @error('feature[premium_support][description]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="description">Left
                                                                    Side Title<span class="text-danger">*</span>
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/fetaure-section/left-side-title.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="feature[premium_support][left_side_title]"
                                                                        value="{{ @$content['feature']['premium_support']['left_side_title'] ?? old('feature[premium_support][left_side_title]') }}"
                                                                        placeholder="Enter Left Side Title">
                                                                    @error('feature[premium_support][left_side_title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2"
                                                                    for="portfolio_text">Image<span
                                                                        class="text-danger">* </span>
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/fetaure-section/premium.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input type="file"
                                                                        class="form-control fileInput"
                                                                        name="feature[premium_support][main_image]">
                                                                    @error('feature[premium_support][main_image]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                    <span class="help-text">*Upload image
                                                                        size 1033×699px
                                                                        recommended</span>
                                                                </div>
                                                            </div>
                                                            @if (isset($content['feature']['premium_support']['main_image']) &&
                                                                    !empty($content['feature']['premium_support']['main_image']))
                                                                <div class="form-group row mb-3">
                                                                    <div class="col-md-2"></div>
                                                                    <div class="col-md-10">
                                                                        <div class="image-list">
                                                                            <div class="image-list-detail">
                                                                                <div class="position-relative">
                                                                                    <img height="100px" src="{{ asset(@$content['feature']['premium_support']['main_image']) }}" alt="Current Logo">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="description">Right
                                                                    Side Title<span class="text-danger">*</span>
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/fetaure-section/right-side-title.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="feature[premium_support][right_side_title]"
                                                                        value="{{ @$content['feature']['premium_support']['right_side_title'] ?? old('feature[premium_support][right_side_title]') }}"
                                                                        placeholder="Enter Right Side Title">
                                                                    @error('feature[premium_support][right_side_title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label class="col-md-2" for="description">Marquee
                                                                    Title<span class="text-danger">*</span>
                                                                    <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" data-bs-html="true"
                                                                        data-bs-title="<img src='{{ asset('assets/images/tooltip/fetaure-section/marquee-title.png') }}' height=250px;>"></i>
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" type="text"
                                                                        name="feature[premium_support][marquee_title]"
                                                                        value="{{ @$content['feature']['premium_support']['marquee_title'] ?? old('feature[premium_support][marquee_title]') }}"
                                                                        placeholder="Enter Marquee Title">
                                                                    @error('feature[premium_support][marquee_title]')
                                                                        <span class="invalid-feedback d-block"
                                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h5 class="txt-primary card-title">Button
                                                                        <i class="icon-help-alt"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="bottom"
                                                                            data-bs-html="true"
                                                                            data-bs-title="<img src='{{ asset('assets/images/tooltip/fetaure-section/premium-btn.png') }}' height=250px;>"></i>
                                                                    </h5>

                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-md-2"
                                                                            for="description">Button
                                                                            Text<span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-md-10">
                                                                            <input class="form-control" type="text"
                                                                                name="feature[premium_support][button_text]"
                                                                                value="{{ @$content['feature']['premium_support']['button_text'] ?? old('feature[premium_support][button_text]') }}"
                                                                                placeholder="Enter Text">
                                                                            @error('feature[premium_support][button_text]')
                                                                                <span class="invalid-feedback d-block"
                                                                                    role="alert"><strong>{{ $message }}</strong></span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-md-2"
                                                                            for="description">Button
                                                                            Url<span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-md-10">
                                                                            <input class="form-control" type="text"
                                                                                name="feature[premium_support][button_url]"
                                                                                value="{{ @$content['feature']['premium_support']['button_url'] ?? old('feature[premium_support][button_url]') }}"
                                                                                placeholder="Enter Redirect Url">
                                                                            @error('feature[premium_support][button_url]')
                                                                                <span class="invalid-feedback d-block"
                                                                                    role="alert"><strong>{{ $message }}</strong></span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-end mt-2">
                                                                <button type="submit" class="btn btn-primary spinner-btn nextBtn">Save</button>
                                                            </div>     
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane main fade" id="footer_Section" role="tabpanel"
                                    aria-labelledby="footer_Settings" tabindex="0">
                                    <div class="col-sm-12 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group row mb-3">
                                                    <label class="col-md-2" for="portfolio_text">Logo<span
                                                            class="text-danger">* </span>
                                                        <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                            data-bs-placement="bottom" data-bs-html="true"
                                                            data-bs-title="<img src='{{ asset('assets/images/tooltip/footer-section/footer_logo.png') }}' height=300px;>"></i>
                                                    </label>
                                                    <div class="col-md-10">
                                                        <input type="file" class="form-control fileInput"
                                                            name="footer[logo]">
                                                        @error('footer[logo]')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                        <span class="help-text">*Upload image size
                                                            115×36px
                                                            recommended</span>
                                                    </div>
                                                </div>
                                                @if (isset($content['footer']['logo']) && !empty($content['footer']['logo']))
                                                    <div class="form-group row mb-3">
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-10">
                                                            <div class="image-list d-inline-block">
                                                                <div class="image-list-detail">
                                                                    <div class="position-relative">
                                                                        <img height="40px"
                                                                            src="{{ asset(@$content['footer']['logo']) }}"
                                                                            alt="Current Logo"> 
                                                                    </div> 
                                                                </div> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="form-group row mb-3">
                                                    <label class="col-md-2" for="portfolio_url">Text</label>
                                                    <div class="col-md-10">
                                                        <input class="form-control" type="text"
                                                            name="footer[description]"
                                                            value="{{ @$content['footer']['description'] ?? old('footer[description]') }}"
                                                            placeholder="Enter Description">
                                                        @error('footer[description]')
                                                            <span class="invalid-feedback d-block"
                                                                role="alert"><strong>{{ $message }}</strong></span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label class="col-md-2" for="portfolio_text">Copyright
                                                        Text<span class="text-danger">* </span></label>
                                                    <div class="col-md-10">
                                                        <input class="form-control" type="text"
                                                            name="footer[copyright_text]"
                                                            value="{{ @$content['footer']['copyright_text'] ?? old('footer[copyright_text]') }}"
                                                            placeholder="Enter Copyright Text">
                                                        @error('footer[copyright_text]')
                                                            <span class="invalid-feedback d-block"
                                                                role="alert"><strong>{{ $message }}</strong></span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="txt-primary card-title">Left Side Button
                                                            <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                data-bs-placement="bottom" data-bs-html="true"
                                                                data-bs-title="<img src='{{ asset('assets/images/tooltip/footer-section/left-btn-img.png') }}' height=200px;>"></i>
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group row mb-3">
                                                            <label class="col-md-2" for="portfolio_text">Text<span
                                                                    class="text-danger">* </span></label>
                                                            <div class="col-md-10">
                                                                <input class="form-control" type="text"
                                                                    name="footer[left_button_text]"
                                                                    value="{{ @$content['footer']['left_button_text'] ?? old('footer[left_button_text]') }}"
                                                                    placeholder="Enter Left Side Button Text">
                                                                @error('footer[left_button_text]')
                                                                    <span class="invalid-feedback d-block"
                                                                        role="alert"><strong>{{ $message }}</strong></span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-3">
                                                            <label class="col-md-2" for="portfolio_text">Url<span
                                                                    class="text-danger">* </span></label>
                                                            <div class="col-md-10">
                                                                <input class="form-control" type="text"
                                                                    name="footer[left_button_url]"
                                                                    value="{{ @$content['footer']['left_button_url'] ?? old('footer[left_button_url]') }}"
                                                                    placeholder="Enter Redirect Url">
                                                                @error('footer[left_button_url]')
                                                                    <span class="invalid-feedback d-block"
                                                                        role="alert"><strong>{{ $message }}</strong></span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="txt-primary card-title">Middle Button
                                                            <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                data-bs-placement="bottom" data-bs-html="true"
                                                                data-bs-title="<img src='{{ asset('assets/images/tooltip/footer-section/middle-btn-img.png') }}' height=200px;>"></i>
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group row mb-3">
                                                            <label class="col-md-2" for="portfolio_text">Text<span
                                                                    class="text-danger">* </span></label>
                                                            <div class="col-md-10">
                                                                <input class="form-control" type="text"
                                                                    name="footer[middle_button_text]"
                                                                    value="{{ @$content['footer']['middle_button_text'] ?? old('footer[middle_button_text]') }}"
                                                                    placeholder="Enter Middle Button Text">
                                                                @error('footer[middle_button_text]')
                                                                    <span class="invalid-feedback d-block"
                                                                        role="alert"><strong>{{ $message }}</strong></span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-3">
                                                            <label class="col-md-2" for="portfolio_text">Url<span
                                                                    class="text-danger">* </span></label>
                                                            <div class="col-md-10">
                                                                <input class="form-control" type="text"
                                                                    name="footer[middle_button_url]"
                                                                    value="{{ @$content['footer']['middle_button_url'] ?? old('footer[middle_button_url]') }}"
                                                                    placeholder="Enter Redirect Url">
                                                                @error('footer[middle_button_url]')
                                                                    <span class="invalid-feedback d-block"
                                                                        role="alert"><strong>{{ $message }}</strong></span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="txt-primary card-title">Right Side Button
                                                            <i class="icon-help-alt" data-bs-toggle="tooltip"
                                                                data-bs-placement="bottom" data-bs-html="true"
                                                                data-bs-title="<img src='{{ asset('assets/images/tooltip/footer-section/right-btn-img.png') }}' height=200px;>"></i>
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group row mb-3">
                                                            <label class="col-md-2" for="portfolio_text">Text<span
                                                                    class="text-danger">* </span></label>
                                                            <div class="col-md-10">
                                                                <input class="form-control" type="text"
                                                                    name="footer[right_button_text]"
                                                                    value="{{ @$content['footer']['right_button_text'] ?? old('footer[right_button_text]') }}"
                                                                    placeholder="Enter Right Side Button Text">
                                                                @error('footer[right_button_text]')
                                                                    <span class="invalid-feedback d-block"
                                                                        role="alert"><strong>{{ $message }}</strong></span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-3">
                                                            <label class="col-md-2" for="portfolio_text">Url<span
                                                                    class="text-danger">* </span></label>
                                                            <div class="col-md-10">
                                                                <input class="form-control" type="text"
                                                                    name="footer[right_button_url]"
                                                                    value="{{ @$content['footer']['right_button_url'] ?? old('footer[right_button_url]') }}"
                                                                    placeholder="Enter Redirect Url">
                                                                @error('footer[right_button_url]')
                                                                    <span class="invalid-feedback d-block"
                                                                        role="alert"><strong>{{ $message }}</strong></span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-end mt-2">
                                                    <button type="submit" class="btn btn-primary spinner-btn nextBtn">Save</button>
                                                </div>     
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
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script src="{{ asset('assets/js/editors/quill.js') }}"></script>
    <script src="{{ asset('assets/js/bookmark/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    <script>
        $(document).ready(function() {

            "use strict";

            function addImageMimeValidation(methodName, mimeTypes, errorMessage) {
                $.validator.addMethod(methodName, function(value, element) {
                    const file = element.files && element.files[0];
                    if (file) {
                        const mime = file.type;
                        return mimeTypes.includes(mime);
                    }
                    return true;
                }, errorMessage);
            }

            addImageMimeValidation("imageMime", ["image/png", "image/jpeg", "image/jpg"],
                "Please enter a value with a valid MIME type. (JPG, PNG, JPEG).");

            addImageMimeValidation("svgImageMime", ["image/svg+xml"],
                "Please enter a value with a valid mimetype. (SVG).");

            $.validator.addMethod("ignorHtml", function(value, element) {
                var plainText = tinymce.get(element.id).getContent({
                    format: 'text'
                }).trim();
                return plainText.length > 0;
            }, "This field is required");

            $('#landing_PageForm').validate({
                ignore: [],
                rules: {
                    "header[logo]": {
                        required: isLogo,
                        imageMime: true
                    },
                    "header[menus][]": "required",
                    "header[links][link1][text]": "required",
                    "header[links][link1][url]": "required",
                    "header[links][link2][text]": "required",
                    "header[links][link2][url]": "required",
                    "header[links][link3][text]": "required",
                    "header[links][link3][url]": "required",
                    "header[btn_text]": "required",
                    "header[btn_url]": "required",

                    "home[title]": {
                        ignorHtml: true
                    },
                    "home[main_img]": {
                        required: isMainImg,
                        imageMime: true
                    },
                    "home[sidebar_cuts_image]": {
                        required: isSidebarImg,
                        imageMime: true
                    },
                    "home[left_bottom_image]": {
                        required: isLeftBottomImg,
                        imageMime: true
                    },
                    "home[left_top_image]": {
                        required: isLeftTopImg,
                        imageMime: true
                    },
                    "home[right_top_image]": {
                        required: isRightTopImg,
                        imageMime: true
                    },
                    "home[right_bottom_image]": {
                        required: isRightBottomImg,
                        imageMime: true
                    },

                    "home[laravel_crud][title]": "required",
                    "page[title]": "required",
                    "page[frameworks][title]": "required",
                    "page[applications][title]": "required",

                    "feature[laravel_feature][title]": "required",
                    "feature[premium_support][title]": "required",
                    "feature[premium_support][left_side_title]": "required",
                    "feature[premium_support][right_side_title]": "required",
                    "feature[premium_support][marquee_title]": "required",
                    "feature[premium_support][button_text]": "required",
                    "feature[premium_support][button_url]": "required",
                    "feature[premium_support][main_image]": {
                        required: isFeatureImg,
                        imageMime: true
                    },

                    "footer[logo]": {
                        required: isFooterLogo,
                        imageMime: true
                    },
                    "footer[left_button_text]": "required",
                    "footer[right_button_text]": "required",
                    "footer[middle_button_text]": "required",
                    "footer[left_button_url]": "required",
                    "footer[right_button_url]": "required",
                    "footer[middle_button_url]": "required",
                    "footer[copyright_text]": "required",
                    "footer[left_button_text]": "required",

                    @foreach ($content['home']['laravel_crud']['crud_banners'] ?? [] as $index => $crud)
                        "home[laravel_crud][crud_banners][{{ $index }}][bg_color]": "required",
                        "home[laravel_crud][crud_banners][{{ $index }}][title]": "required",
                        "home[laravel_crud][crud_banners][{{ $index }}][url]": "required",
                        "home[laravel_crud][crud_banners][{{ $index }}][image]": {
                            required: function() {
                                return @if (isset($crud['image'])) false @else true @endif ;
                            },
                            imageMime: true
                        },
                    @endforeach

                    @foreach ($content['page']['dashboard'] ?? [] as $index => $dash)
                        "page[dashboard][{{ $index }}][title]": "required",
                        "page[dashboard][{{ $index }}][url]": "required",
                        "page[dashboard][{{ $index }}][image]": {
                            required: function() {
                                return @if (isset($dash['image'])) false @else true @endif ;
                            },
                            imageMime: true
                        },
                    @endforeach

                    @foreach ($content['page']['frameworks']['poster'] ?? [] as $index => $framework)
                        "page[frameworks][poster][{{ $index }}][title]": "required",
                        "page[frameworks][poster][{{ $index }}][image]": {
                            required: function() {
                                return @if (isset($framework['image'])) false @else true @endif ;
                            },
                            imageMime: true
                        },
                    @endforeach

                    @foreach ($content['page']['applications']['poster'] ?? [] as $index => $app)
                        "page[applications][poster][{{ $index }}][title]": "required",
                        "page[applications][poster][{{ $index }}][url]": "required",
                        "page[applications][poster][{{ $index }}][image]": {
                            required: function() {
                                return @if (isset($app['image'])) false @else true @endif ;
                            },
                            imageMime: true
                        },
                    @endforeach

                    @foreach ($content['feature']['laravel_feature']['poster'] ?? [] as $index => $feature)
                        "feature[laravel_feature][poster][{{ $index }}][title]": "required",
                        "feature[laravel_feature][poster][{{ $index }}][svg_icon]": {
                            required: function() {
                                return @if (isset($feature['svg_icon'])) false @else true @endif ;
                            },
                            svgImageMime: true
                        },
                    @endforeach
                },
                invalidHandler: function(event, validator) {
                    let invalidMainTabs = [];
                    let invalidSubTabs = new Map(); // Store invalid sub-tabs per main tab

                    // Collect all invalid tabs
                    $.each(validator.errorList, function(index, error) {
                        var $collapse = $(error.element).closest('.accordion-collapse');
                        if ($collapse.length && !$collapse.hasClass('show')) {
                            $collapse.addClass('show');
                        }

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

            function isLogo() {
                @if (isset($content['header']['logo']))
                    return false;
                @else
                    return true;
                @endif
            }

            function isMainImg() {
                @if (isset($content['home']['main_img']))
                    return false;
                @else
                    return true;
                @endif
            }

            function isSidebarImg() {
                @if (isset($content['home']['sidebar_cuts_image']))
                    return false;
                @else
                    return true;
                @endif
            }

            function isLeftBottomImg() {
                @if (isset($content['home']['left_bottom_image']))
                    return false;
                @else
                    return true;
                @endif
            }

            function isLeftTopImg() {
                @if (isset($content['home']['left_top_image']))
                    return false;
                @else
                    return true;
                @endif
            }

            function isRightTopImg() {
                @if (isset($content['home']['right_top_image']))
                    return false;
                @else
                    return true;
                @endif
            }

            function isRightBottomImg() {
                @if (isset($content['home']['right_bottom_image']))
                    return false;
                @else
                    return true;
                @endif
            }

            function isFeatureImg() {
                @if (isset($content['feature']['premium_support']['main_image']))
                    return false;
                @else
                    return true;
                @endif
            }

            function isFooterLogo() {
                @if (isset($content['footer']['logo']))
                    return false;
                @else
                    return true;
                @endif
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            "use strict";

            function addImageMimeValidations() {
                $.validator.addMethod("imageMime", function(value, element) {
                    const file = element.files && element.files[0];
                    if (file) {
                        return ["image/png", "image/jpeg", "image/jpg"].includes(file.type);
                    }
                    return true;
                }, "Please enter a value with a valid MIME type. (JPG, PNG, JPEG).");

                $.validator.addMethod("svgImageMime", function(value, element) {
                    const file = element.files && element.files[0];
                    if (file) {
                        return ["image/svg+xml"].includes(file.type);
                    }
                    return true;
                }, "Please enter a value with a valid mimetype. (SVG).");
            }

            addImageMimeValidations();

            function initBannerGroup(options) {
                let bannerIndex = options.index;
                const formSelector = options.formSelector || '#landing_PageForm';
                const formValidator = $(formSelector).validate({
                    ignore: [],
                    rules: {}
                });

                $(options.addBtn).click(function() {
                    let lastBanner = $(options.accordion).find('.accordion-item').last();
                    if (lastBanner.length > 0) {
                        let isValid = true;
                        lastBanner.find(':input').each(function () {
                            if (!$(this).valid()) {
                                isValid = false;
                                var collapse = $(this).closest('.accordion-collapse');
                                if (collapse.length && !collapse.hasClass('show')) {
                                    collapse.addClass('show');
                                }
                            }
                        });
                        if (!isValid) {
                            return;
                        }
                    }

                    const template = $(options.template).html().replace(/__INDEX__/g, bannerIndex);
                    $(options.accordion).append(template);

                    options.fieldRules.forEach(function(field) {
                    const fieldName = field.fieldTemplate.replace(/__INDEX__/g, bannerIndex);
                    formValidator.settings.rules[fieldName] = field.rules;
                    });
                    bannerIndex++;
                });

                $(document).on('click', '.remove-banner', function() {
                    $(this).closest('.accordion-item').remove();
                });
            }

            initBannerGroup({
                index: {{ count($content['home']['laravel_crud']['crud_banners'] ?? []) }},
                addBtn: '#addCrudBanner',
                accordion: '#crudBannersAccordion',
                template: '#crudBannerTemplate',
                fieldRules: [
                    { fieldTemplate: "home[laravel_crud][crud_banners][__INDEX__][bg_color]", rules: { required: true } },
                    { fieldTemplate: "home[laravel_crud][crud_banners][__INDEX__][title]", rules: { required: true } },
                    { fieldTemplate: "home[laravel_crud][crud_banners][__INDEX__][url]", rules: { required: true } },
                    { fieldTemplate: "home[laravel_crud][crud_banners][__INDEX__][image]", rules: { required: true, imageMime: true } }
                ]
            });

            initBannerGroup({
                index: {{ count($content['page']['dashboard'] ?? []) }},
                addBtn: '#addDashBanner',
                accordion: '#dashBannersAccordion',
                template: '#dashBannerTemplate',
                fieldRules: [
                    { fieldTemplate: "page[dashboard][__INDEX__][title]", rules: { required: true } },
                    { fieldTemplate: "page[dashboard][__INDEX__][url]", rules: { required: true } },
                    { fieldTemplate: "page[dashboard][__INDEX__][image]", rules: { required: true, imageMime: true } }
                ]
            });

            initBannerGroup({
                index: {{ count($content['page']['frameworks']['poster'] ?? []) }},
                addBtn: '#addFrameworkPoster',
                accordion: '#LayoutFrameworkAccordion',
                template: '#layoutFrameworks',
                fieldRules: [
                    { fieldTemplate: "page[frameworks][poster][__INDEX__][title]", rules: { required: true } },
                    { fieldTemplate: "page[frameworks][poster][__INDEX__][image]", rules: { required: true, imageMime: true } }
                ]
            });

            initBannerGroup({
                index: {{ count($content['page']['applications']['poster'] ?? []) }},
                addBtn: '#addApplicationPoster',
                accordion: '#layoutApplicationAccordion',
                template: '#layoutApplication',
                fieldRules: [
                    { fieldTemplate: "page[applications][poster][__INDEX__][title]", rules: { required: true } },
                    { fieldTemplate: "page[applications][poster][__INDEX__][url]", rules: { required: true } },
                    { fieldTemplate: "page[applications][poster][__INDEX__][image]", rules: { required: true, imageMime: true } }
                ]
            });

            initBannerGroup({
                index: {{ count($content['feature']['laravel_feature']['poster'] ?? []) }},
                addBtn: '#addLaravelFeature',
                accordion: '#laravelFeatureAccordion',
                template: '#laravelFeature',
                fieldRules: [
                    { fieldTemplate: "feature[laravel_feature][poster][__INDEX__][title]", rules: { required: true } },
                    { fieldTemplate: "feature[laravel_feature][poster][__INDEX__][svg_icon]", rules: { required: true, svgImageMime: true } }
                ]
            });

        });
    </script>
    <script>
        (function ($) {
            "use strict";

            $(document).ready(function () {
                $('.email-content').each(function () {
                    const editorId = $(this).attr('id');
                    if (tinymce.get(editorId)) {
                        tinymce.get(editorId).remove();
                    }
                });

                // Initialize TinyMCE
                tinymce.init({
                    selector: '.email-content',
                    setup: function (editor) {
                        editor.on('init change input', function () {
                            const contentId = editor.id;
                            const language = contentId.split('_').pop();
                            const content = editor.getContent();
                        });
                    },
                    plugins: [
                        "advlist autolink lists link image charmap print preview anchor",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table paste"
                    ],
                    toolbar: [
                            "insertfile undo redo | styleselect | bold italic underline strikethrough | formatselect | forecolor backcolor code table | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
                        ],
                    image_title: true,
                    file_picker_types: 'image',
                    relative_urls: false,
                    remove_script_host: false,
                    menubar: false,
                    branding: false,
                    placeholder: 'Enter your text here...',
                    height: 300
                });
            });
        })(jQuery);
    </script>
@endsection
