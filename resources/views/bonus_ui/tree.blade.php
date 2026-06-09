@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/tree.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Tree View</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item"> Bonus Ui</li>
                        <li class="breadcrumb-item active">Tree View</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Basic Tree</h4>
                        <p class="f-m-light mt-1">
                            Use the dynamic tree view with checkboxes.</p>
                    </div>
                    <div class="card-body">
                        <div class="tree-container"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Disabled Tree</h4>
                        <p class="f-m-light mt-1">
                            Use the dynamic tree view with checkboxes.</p>
                    </div>
                    <div class="card-body">
                        <div class="disabled-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <!-- calendar js-->
    <script src="{{ asset('assets/js/tree/jstree.min.js') }}"></script>
    <script src="{{ asset('assets/js/tree/tree.min.js') }}"></script>
    <script src="{{ asset('assets/js/tree/tree.js') }}"></script>
@endsection
