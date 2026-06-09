@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('main_content')
@use('App\Helpers\Helpers')
@php
    $settings = Helpers::getSettings();
@endphp
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Draggable Card</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Bonus Ui</li>
                        <li class="breadcrumb-item active">Draggable Card</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row ui-sortable" id="draggableMultiple">
            <div class="col-xxl-4 col-md-6">
                <div class="card height-equal">
                    <div class="card-header">
                        <h4>Basic Card</h4>
                        <p class="mt-1 f-m-light">You can draggable cards anywhere.</p>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"> <i class="icofont icofont-arrow-right"></i>Logo Design</li>
                            <li class="list-group-item"> <i class="icofont icofont-arrow-right"></i>Web Designer </li>
                            <li class="list-group-item"> <i class="icofont icofont-arrow-right"></i>E-Commerce</li>
                            <li class="list-group-item"> <i class="icofont icofont-arrow-right"></i>UX Designer</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-6">
                <div class="card height-equal">
                    <div class="card-header">
                        <h4>Default Card</h4>
                        <p class="mt-1 f-m-light">You can draggable cards anywhere.</p>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item active bg-warning-light" aria-current="true"><i
                                    class="icofont icofont-arrow-right"> </i>UI Kits</li>
                            <li class="list-group-item"><i class="icofont icofont-arrow-right"> </i>Wow Animations</li>
                            <li class="list-group-item"> <i class="icofont icofont-arrow-right"> </i>Apex Charts</li>
                            <li class="list-group-item"><i class="icofont icofont-arrow-right"> </i>Starter Kits</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-6">
                <div class="card b-r-0 height-equal">
                    <div class="card-header">
                        <h4>Flat Card</h4>
                        <p class="f-m-light mt-1">You can draggable cards anywhere.</p>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="icofont icofont-arrow-right"> </i>PRODUCT</li>
                            <li class="list-group-item"><i class="icofont icofont-arrow-right"> </i>PRODUCT DETAILS </li>
                            <li class="list-group-item"><i class="icofont icofont-arrow-right"> </i>CART </li>
                            <li class="list-group-item pb-0"><i class="icofont icofont-arrow-right"> </i>CHECKOUT</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-6">
                <div class="card height-equal">
                    <div class="card-header bg-primary">
                        <h4 class="text-white">Info Color Header</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="pb-2"> Web Designer </h5>
                        <p class="mb-0">By following instructions and adding your own unique twist and style, you may
                            apply your imagination as a web designer. The majority of the tasks you'll be assigned will have
                            a specific due date and work description, but the reason you were employed was because they need
                            a specialist to inject some much-needed creativity.</p>
                    </div>
                    <div class="card-footer">
                        <h6 class="mb-0 text-end">{{ $settings['general']['site_name'] }} Theme</h6>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-6">
                <div class="card height-equal">
                    <div class="card-header">
                        <h4>Info Color Body</h4>
                    </div>
                    <div class="card-body bg-primary">
                        <h5 class="pb-2 txt-light">UX Designer </h5>
                        <p class="mb-0">
                            It is the responsibility of the UX designer to make a product or service useful, pleasurable,
                            and accessible. The word "user experience" is used by various industries, although it is most
                            frequently related to digital design for websites and mobile applications.There is no need for
                            coding in user experience design.</p>
                    </div>
                    <div class="card-footer">
                        <h6 class="mb-0 text-end">{{ $settings['general']['site_name'] }} Theme</h6>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-6">
                <div class="card height-equal">
                    <div class="card-header">
                        <h4>Info Color Footer</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="pb-2">Web Developer </h5>
                        <p class="mb-0">
                            A programmer who uses the client-server architecture to create World Wide Web applications is
                            known as a web developer. The apps generally employ any general-purpose programming language on
                            the server and HTML, CSS, and JavaScript on the client.</p>
                    </div>
                    <div class="card-footer bg-primary">
                        <h6 class="mb-0 text-end txt-light">{{ $settings['general']['site_name'] }} Theme </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jquery.ui.min.js') }}"></script>
    <!-- calendar js-->
    <script src="{{ asset('assets/js/dragable/sortable.js') }}"></script>
    <script src="{{ asset('assets/js/dragable/sortable-custom.js') }}"></script>
    <script src="{{ asset('assets/js/height-equal.js') }}"></script>
@endsection
