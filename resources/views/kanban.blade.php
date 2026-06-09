@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jkanban.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>
                        Kanban Board</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item"> Apps</li>
                        <li class="breadcrumb-item active"> Kanban Board</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid jkanban-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Default Demo </h4>
                    </div>
                    <div class="card-body">
                        <div id="demo1"></div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Custom Board </h4>
                        <p class="mb-0">| colors, gutter, click on board&apos;s item and restricting which boards to drag
                            items to </p>
                    </div>
                    <div class="card-body">
                        <div id="demo2"></div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>API</h4>
                        <p class="mb-0">add item, add board, delete board: </p>
                    </div>
                    <div class="card-body">
                        <div id="demo3"></div>
                        <button class="btn btn-success" id="addDefault">Add &quot;Default&quot; board</button>
                        <button class="btn btn-success" id="addToDo">Add element in &quot;To Do&quot; Board</button>
                        <button class="btn btn-danger" id="removeBoard">Remove &quot;Done&quot; Board</button>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card note p-20">jKanban is Pure agnostic Javascript plugin for Kanban boards for more options
                    please refer <a href="http://www.riccardotartaglia.it/jkanban/" target="_blank">jkanban Official site
                    </a>And <a href="https://github.com/riktar/jkanban" target="_blank">githup link</a></div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jkanban/jkanban.js') }}"></script>
    <script src="{{ asset('assets/js/jkanban/custom.js') }}"></script>
    <!-- calendar js-->
    <script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
@endsection
