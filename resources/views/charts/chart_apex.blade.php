@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Apex Chart</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Charts</li>
                        <li class="breadcrumb-item active">Apex Chart</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            Basic Area Chart</h4>
                    </div>
                    <div class="card-body">
                        <div id="basic-apex"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Area Spaline Chart </h4>
                    </div>
                    <div class="card-body">
                        <div id="area-spaline"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Bar chart</h4>
                    </div>
                    <div class="card-body">
                        <div id="basic-bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Column Chart </h4>
                    </div>
                    <div class="card-body">
                        <div id="column-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            3d Bubble Chart </h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-bubble"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Stepline Chart </h4>
                    </div>
                    <div class="card-body">
                        <div id="stepline"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-12 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Column Chart</h4>
                    </div>
                    <div class="card-body">
                        <div id="annotationchart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Pie Chart </h4>
                    </div>
                    <div class="card-body apex-chart">
                        <div id="piechart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Donut Chart</h4>
                    </div>
                    <div class="card-body apex-chart">
                        <div id="donutchart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-12 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Mixed Chart</h4>
                    </div>
                    <div class="card-body">
                        <div id="mixedchart"> </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-12 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Candlestick Chart </h4>
                    </div>
                    <div class="card-body">
                        <div id="candlestick"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Radar Chart </h4>
                    </div>
                    <div class="card-body">
                        <div id="radarchart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Radial Bar Chart</h4>
                    </div>
                    <div class="card-body">
                        <div id="circlechart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/chart-custom.js') }}"></script>
@endsection
