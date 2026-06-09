@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid basic_table">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Tags</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Laravel Example</li>
                        <li class="breadcrumb-item active">Tags</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card role-management">
                    <div class="card-body">
                        <div class="blog-card">
                            <div class="blog-card-content">
                                <div class="role-details">
                                    <div class="role-profile">
                                        @php
                                            $image = auth()->user()->getFirstMedia('image');
                                        @endphp

                                        @isset($image)
                                            <img src="{{ $image->getUrl() }}" alt="Image">
                                        @else
                                            <img src="{{ asset('assets/images/avtar/4.jpg') }}" alt="Image">
                                        @endisset
                                    </div>
                                    <div class="role-profile-details">
                                        <h4>Welcome back {{ \Illuminate\Support\Str::title(auth()->user()->first_name ?? '') }} {{ \Illuminate\Support\Str::title(auth()->user()->last_name ?? '') }}!</h4>
                                    </div>
                                </div>
                                <div class="blog-tags">
                                    <div class="tags-icon bg-primary">
                                        <svg class="stroke-icon">
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-blog') }}"></use>
                                        </svg>
                                    </div>
                                    <div class="tag-details">
                                        <h2 class="total-num counter">{{ sprintf("%02d",\App\Models\Blog::all()->count()) }}</h2>
                                        <p data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Total Blogs" data-bs-original-title="Total Blogs">Total Blogs</p>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-card-image">
                                <img src="{{ asset('assets/images/user-management.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body border-b-primary border-2">
                        <div class="upcoming-box">
                            <div class="upcoming-icon bg-primary">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#tags') }}"></use>
                                </svg>
                            </div>
                            <p>Tag</p>
                            <a href="{{ route('admin.tag.create') }}" class="btn btn-primary">{{ __('Add tag') }}</a>
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
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body border-b-secondary border-2">
                        <div class="upcoming-box">
                            <div class="upcoming-icon bg-secondary">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-blog') }}"></use>
                                </svg>
                            </div>
                            <p>Blog</p>
                            <a href="{{ route('admin.blog.create') }}" class="btn btn-secondary">{{ __('Add blog') }}</a>
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
