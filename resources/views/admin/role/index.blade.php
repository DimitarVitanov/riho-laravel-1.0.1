@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollable.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid basic_table">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Roles</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Laravel Example</li>
                        <li class="breadcrumb-item active">Roles</li>
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
                                <div class="role-details">
                                    <div class="role-profile">
                                        @php
                                            $image = auth()->user()->getFirstMedia('image');
                                        @endphp

                                        @isset($image)
                                            <img src="{{ $image->getUrl() }}" alt="Image">
                                        @else
                                            <img src="{{ asset('assets/images/avtar/3.jpg') }}" alt="Image">
                                        @endisset
                                    </div>
                                    <div class="role-profile-details">
                                        <h4>Welcome back
                                            {{ \Illuminate\Support\Str::title(auth()->user()->first_name ?? '') }}
                                            {{ \Illuminate\Support\Str::title(auth()->user()->last_name ?? '') }}!</h4>
                                    </div>
                                </div>
                                <div class="blog-tags">
                                    <div class="tags-icon bg-primary">
                                        <svg class="stroke-icon">
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#multi-user') }}"></use>
                                        </svg>
                                    </div>
                                    <div class="tag-details">
                                        <h2 class="total-num counter">
                                            {{ sprintf('%02d', \App\Models\User::count()) }}
                                        </h2>
                                        <p data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Total Users" data-bs-original-title="Total Users">Total Users</p>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-card-image">
                                <img src="{{ asset('assets/images/role-management.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6 box-col-6">
                <div class="card">
                    <div class="card-body border-b-primary border-2">
                        <div class="upcoming-box">
                            <div class="upcoming-icon bg-primary">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-social') }}"></use>
                                </svg>
                            </div>
                            <p>Role</p>
                            <a href="{{ route('admin.role.create') }}" class="btn btn-primary">{{ __('Add Role') }}</a>
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
            <div class="col-xxl-3 col-sm-6 box-col-6">
                <div class="card">
                    <div class="card-body border-b-secondary border-2">
                        <div class="upcoming-box">
                            <div class="upcoming-icon bg-secondary">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#user-plus') }}"></use>
                                </svg>
                            </div>
                            <p>User</p>
                            <a href="{{ route('admin.user.create') }}" class="btn btn-secondary">{{ __('Add User') }}</a>
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
            <!-- Container-fluid starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-block row">
                        <div class="role-table">
                            <div class="table-responsive p-3">
                                {!! $dataTable->table() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Container-fluid Ends-->
        </div>
    </div>
@endsection

@section('scripts')
    <!-- calendar js-->
    <script src="{{ asset('assets/js/scrollable/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/scrollable/scrollable-custom.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>    
    {!! $dataTable->scripts() !!}
@endsection
