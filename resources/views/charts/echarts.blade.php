@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/echart.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Echarts</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Charts</li>
                        <li class="breadcrumb-item active">Echarts</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Basic Area Chart </h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-cotainer" id="area-echart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Area pie chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-cotainer" id="echart-pie"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Bar polar chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-cotainer" id="echart-polar"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Bar chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-cotainer-large" id="echart-bar"> </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Bar chart 2</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-cotainer-large" id="echart-bar2"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Boxplot chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-cotainer boxplot-cotainer" id="echart-boxplot"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>lines clip chart</h4>
                    </div>
                    <div class="card-body clip-chart-wrapper">
                        <div class="apache-cotainer" id="lines-clip"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4>line visual chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-cotainer" id="line-visual"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4>angle chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-cotainer" id="texture-bar-by-clipPath"> </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4>gauge chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-cotainer" id="echart-gauge"> </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4>line visual chart 2</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-cotainer" id="line-visulH"> </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4>pictorial repeat chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-cotainer" id="dynamic-data"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 xl-100 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>radar chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-cotainer" id="echart-radar"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 xl-100 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>polar line chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-container polar-line" id="main0"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4>pie rich text chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-container" id="echart-pierich"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4>pictorial single chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="apache-container" id="dotted"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card note p-20 d-inline-block w-100"> Echarts for more options please refer <a
                        href="https://echarts.apache.org/examples/en/index.html" target="_blank">echart Official site
                    </a>And <a href="https://github.com/apache/echarts" target="_blank">githup link</a></div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/chart/echart/esl.js') }}"></script>
    <script src="{{ asset('assets/js/chart/echart/config.js') }}"></script>
    <script src="{{ asset('assets/js/chart/echart/pie-chart/facePrint.js') }}"></script>
    <script src="{{ asset('assets/js/chart/echart/pie-chart/testHelper.js') }}"></script>
    <script src="{{ asset('assets/js/chart/echart/pie-chart/custom-transition-texture.js') }}"></script>
    <script src="{{ asset('assets/js/chart/echart/data/symbols.js') }}"></script>
    <script src="{{ asset('assets/js/chart/echart/custom.js') }}"></script>
@endsection
