@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/leaflet.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Map JS</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Maps</li>
                        <li class="breadcrumb-item active"> Map JS</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Map at a specified location</h4><span class="f-light">Display a map at a specified location and
                            zoom level.</span>
                    </div>
                    <div class="card-body z-1">
                        <div class="map-js-height" id="weathermap"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Map using View Bounds</h4><span class="f-light">Display a map of a given area.</span>
                    </div>
                    <div class="card-body z-1">
                        <div class="map-js-height" id="map2"> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <!-- calendar js-->
    <script src="{{ asset('assets/js/map-js/leaflet.js') }}"></script>
    <script src="{{ asset('assets/js/map-js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/map-js/map-custom2.js') }}"></script>
@endsection
