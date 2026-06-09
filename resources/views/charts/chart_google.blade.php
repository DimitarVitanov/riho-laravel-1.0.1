@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Google Chart</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Charts</li>
                        <li class="breadcrumb-item active">Google Chart</li>
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
                        <h4>Area Chart <span>1</span></h4>
                    </div>
                    <div class="card-body p-0 chart-block">
                        <div class="chart-overflow" id="area-chart1"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Area Chart <span>2</span></h4>
                    </div>
                    <div class="card-body p-0 chart-block">
                        <div class="chart-overflow" id="area-chart2"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Column Chart <span>1 </span></h4>
                    </div>
                    <div class="card-body chart-block">
                        <div class="chart-overflow" id="column-chart1"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Column Chart <span>2</span></h4>
                    </div>
                    <div class="card-body chart-block">
                        <div class="chart-overflow" id="column-chart2"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Gantt Chart</h4>
                    </div>
                    <div class="card-body chart-block">
                        <div class="chart-overflow" id="gantt_chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Line Chart</h4>
                    </div>
                    <div class="card-body chart-block">
                        <div class="chart-overflow" id="line-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Combo Chart</h4>
                    </div>
                    <div class="card-body chart-block">
                        <div class="chart-overflow" id="combo-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>bar-chart2</h4>
                    </div>
                    <div class="card-body chart-block">
                        <div id="bar-chart2"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>word tree</h4>
                    </div>
                    <div class="card-body chart-block">
                        <div class="word-tree" id="wordtree_basic"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Pie Chart <span>1</span></h4>
                    </div>
                    <div class="card-body p-0 chart-block">
                        <div class="chart-overflow" id="pie-chart1"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Pie Chart <span>2</span></h4>
                    </div>
                    <div class="card-body p-0 chart-block">
                        <div class="chart-overflow" id="pie-chart3"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Pie Chart <span>3</span></h4>
                    </div>
                    <div class="card-body p-0 chart-block">
                        <div class="chart-overflow" id="pie-chart4"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Pie Chart <span>4</span></h4>
                    </div>
                    <div class="card-body p-0 chart-block">
                        <div class="chart-overflow" id="pie-chart2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/chart/google/google-chart-loader.js') }}"></script>
    <script src="{{ asset('assets/js/chart/google/google-chart.js') }}"></script>
@endsection
