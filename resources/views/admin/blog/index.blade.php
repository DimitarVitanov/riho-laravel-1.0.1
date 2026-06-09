@extends('layouts.simple.master')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('main_content')
@use('App\Helpers\Helpers')
    @php
        $settings = Helpers::getSettings();
    @endphp
    <div class="container-fluid basic_table">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Blogs</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Laravel Example</li>
                        <li class="breadcrumb-item active">Blogs</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-6 box-col-12">
                <div class="card role-management">
                    <div class="card-body">
                        <div class="blog-card">
                            <div class="blog-card-content">
                                <h4>Good day, {{ \Illuminate\Support\Str::title(auth()->user()->first_name ?? '') }} {{ \Illuminate\Support\Str::title(auth()->user()->last_name ?? '') }}</h4>
                                <p>Welcome to the {{ $settings['general']['site_name'] }} ! We are glad that you have visited our dashboard.</p>
                                <div class="d-flex">
                                    <div class="blog-tags m-0">
                                        <div class="tags-icon bg-primary">
                                            <svg class="stroke-icon">
                                                <use href="{{ asset('assets/svg/icon-sprite.svg#multi-user') }}"></use>
                                            </svg>
                                        </div>
                                        <div class="tag-details">
                                            <h2 class="total-num counter">{{ sprintf("%02d",\App\Models\Category::all()->count()) }}</h2>
                                            <p data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Total Categories" data-bs-original-title="Total Categories">Total Categories</p>
                                        </div>
                                    </div>
                                    <div class="blog-tags m-0">
                                        <div class="tags-icon bg-secondary">
                                            <svg class="stroke-icon">
                                                <use href="{{ asset('assets/svg/icon-sprite.svg#tags') }}"></use>
                                            </svg>
                                        </div>
                                        <div class="tag-details">
                                            <h2 class="total-num counter txt-secondary">{{ sprintf("%02d",\App\Models\Tag::all()->count()) }}</h2>
                                            <p data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Total Tags" data-bs-original-title="Total Tags">Total Tags</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-card-image">
                                <img src="{{ asset('assets/images/blog-management.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-4 box-col-4">
                <div class="card">
                    <div class="card-body border-b-primary border-2">
                        <div class="upcoming-box">
                            <div class="upcoming-icon bg-primary">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-blog') }}"></use>
                                </svg>
                            </div>
                            <p>Blog</p>
                            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">{{ __('Add Blog') }}</a>
                        </div>
                        <ul class="bubbles role">
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-4 box-col-4">
                <div class="card">
                    <div class="card-body border-b-secondary border-2">
                        <div class="upcoming-box">
                            <div class="upcoming-icon bg-secondary">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#tags') }}"></use>
                                </svg>
                            </div>
                            <p>Tag</p>
                            <a href="{{ route('admin.tag.create') }}" class="btn btn-secondary">{{ __('Add Tag') }}</a>
                        </div>
                        <ul class="bubbles role role-user">
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-4 box-col-4">
                <div class="card">
                    <div class="card-body border-b-warning border-2">
                        <div class="upcoming-box">
                            <div class="upcoming-icon bg-warning">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-widget') }}"></use>
                                </svg>
                            </div>
                            <p>Category</p>
                            <a href="{{ route('admin.category.index') }}" class="btn btn-warning">{{ __('Add Category') }}</a>
                        </div>
                        <ul class="bubbles role role-category">
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body row">
                        <div class="role-table">
                            <div class="table-responsive">
                                {!! $dataTable->table() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/datatables.min.js') }}"></script>

<script src="{{ asset('assets/js/tooltip-init.js') }}"></script>

{!! $dataTable->scripts() !!}
@endsection
