@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Alert</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Ui Kits</li>
                        <li class="breadcrumb-item active">Alert</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Link color in dark theme</h4>
                        <p class="f-m-light mt-1">Use the <code>.alert-link</code> utility class to quickly provide matching
                            colored links within any alert.</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <p class="mb-0">Primary Alert</p>
                                <div class="alert alert-primary dark" role="alert">
                                    <p>This is a <a class="alert-link text-white" href="#">primary alert</a> with an
                                        example link.Check it out.</p>
                                </div>
                                <p class="mb-0">Secondary Alert</p>
                                <div class="alert alert-secondary dark" role="alert">
                                    <p>This is a <a class="alert-link text-white" href="#">secondary alert</a> with an
                                        example link. Check it out.</p>
                                </div>
                                <p class="mb-0">Success Alert</p>
                                <div class="alert alert-success dark" role="alert">
                                    <p>This is a <a class="alert-link text-white" href="#">success alert </a> with an
                                        example link. Check it out.</p>
                                </div>
                                <p class="mb-0">Info Alert</p>
                                <div class="alert alert-info dark" role="alert">
                                    <p>This is a <a class="alert-link text-white" href="#">info alert </a> with an
                                        example link. Check it out.</p>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <p class="mb-0">Warning Alert</p>
                                <div class="alert alert-warning dark" role="alert">
                                    <p>This is a <a class="alert-link text-white" href="#">warning alert </a> with an
                                        example link. Check it out.</p>
                                </div>
                                <p class="mb-0">Danger Alert</p>
                                <div class="alert alert-danger dark" role="alert">
                                    <p>This is a <a class="alert-link text-white" href="#">danger alert </a> with an
                                        example link. Check it out.</p>
                                </div>
                                <p class="mb-0">Light Alert</p>
                                <div class="alert alert-light dark" role="alert">
                                    <p>This is a <a class="alert-link" href="#">light alert </a> with an example link.
                                        Check it out.</p>
                                </div>
                                <p class="mb-0">Dark Alert</p>
                                <div class="alert alert-dark dark" role="alert">
                                    <p>This is a <a class="alert-link text-white" href="#">dark alert </a> with an
                                        example link. Check it out.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Link color in light theme</h4>
                        <p class="f-m-light mt-1">Use the <code>.alert-link</code> utility class to quickly provide matching
                            colored links within any alert.</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <p class="mb-0">Primary Light Alert</p>
                                <div class="alert alert-light-primary" role="alert">
                                    <p class="txt-primary">This is a <a class="alert-link txt-primary"
                                            href="#">primary alert</a> with an example link.Check it out.</p>
                                </div>
                                <p class="mb-0">Secondary Light Alert</p>
                                <div class="alert alert-light-secondary" role="alert">
                                    <p class="txt-secondary">This is a <a class="alert-link txt-secondary"
                                            href="#">secondary alert</a> with an example link. Check it out.</p>
                                </div>
                                <p class="mb-0">Success Light Alert</p>
                                <div class="alert alert-light-success" role="alert">
                                    <div class="txt-success">This is a <a class="alert-link txt-success"
                                            href="#">success alert </a> with an example link. Check it out.</div>
                                </div>
                                <p class="mb-0">Info Light Alert</p>
                                <div class="alert alert-light-info" role="alert">
                                    <div class="txt-info">This is a <a class="alert-link txt-info" href="#">info alert
                                        </a> with an example link. Check it out.</div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <p class="mb-0">Warning Light Alert</p>
                                <div class="alert alert-light-warning" role="alert">
                                    <p class="txt-warning">This is a <a class="alert-link txt-warning"
                                            href="#">warning alert </a> with an example link. Check it out.</p>
                                </div>
                                <p class="mb-0">Danger Light Alert</p>
                                <div class="alert alert-light-danger" role="alert">
                                    <p class="txt-danger">This is a <a class="alert-link txt-danger"
                                            href="#">danger
                                            alert </a> with an example link. Check it out.</p>
                                </div>
                                <p class="mb-0">White Light Alert</p>
                                <div class="alert alert-light-light" role="alert">
                                    <p class="txt-dark">This is a <a class="alert-link txt-dark" href="#">light
                                            alert </a> with an example link. Check it out.</p>
                                </div>
                                <p class="mb-0">Dark Light Alert</p>
                                <div class="alert alert-light-dark" role="alert">
                                    <p class="txt-dark">This is a <a class="alert-link txt-dark" href="#">dark
                                            alert </a> with an example link. Check it out.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Outline dark and light alerts</h4>
                        <p class="f-m-light mt-1">Use the <code>.border-* </code>utility class to quickly provide matching
                            border and border-width within any alert.</p>
                    </div>
                    <div class="card-body">
                        <div class="alert txt-primary border-primary alert-dismissible fade show" role="alert"><i
                                data-feather="clock"></i>
                            <p>One of <strong>YouTube's</strong>most crucial ranking factors is Watch Time.</p>
                            <button class="btn-close" type="button" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                        <div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons"
                            role="alert"><i data-feather="thumbs-up"></i>
                            <p><b> Well done! </b>You successfully read this important message.</p>
                            <button class="btn-close" type="button" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Alerts with icons and text actions</h4>
                        <p class="f-m-light mt-1">Use the <code>.dismiss-text</code> class to add dismiss text instead of
                            icon</p>
                    </div>
                    <div class="card-body dark-txt">
                        <div class="alert border-warning alert-dismissible fade show p-0" role="alert">
                            <div class="alert-arrow bg-warning"><i class="icon-timer"></i></div>
                            <p>Your time Over after <strong class="txt-dark">5</strong> minute</p>
                            <button class="p-0 border-0 me-2 ms-auto" type="button" data-bs-dismiss="alert"
                                aria-label="Close"><span class="bg-warning px-3 py-1"
                                    aria-hidden="true">Check</span></button>
                        </div>
                        <div class="alert border-danger alert-dismissible fade show p-0 mb-0" role="alert">
                            <div class="alert-arrow bg-danger"><i class="icon-heart"></i></div>
                            <p>Oh snap! Change a few things up <strong class="txt-dark"> Notification check</strong></p>
                            <button class="p-0 border-0 me-2 ms-auto" type="button" data-bs-dismiss="alert"
                                aria-label="Close"><span class="bg-danger text-white px-3 py-1"
                                    aria-hidden="true">Alert</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card height-equal">
                    <div class="card-header">
                        <h4>Dismissing dark alert</h4>
                        <p class="f-m-light mt-1">Use the <code>.alert-dismissible</code> utility class to quickly remove
                            the alerts.</p>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-secondary dark alert-dismissible fade show" role="alert"><i
                                data-feather="heart"></i>
                            <p>Check your update! You should check in on some of those fields below.</p>
                            <button class="btn-close" type="button" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card height-equal">
                    <div class="card-header">
                        <h4>Dismissing light alert</h4>
                        <p class="f-m-light mt-1">Use the <code>.alert-dismissible</code> utility class to quickly remove
                            the alerts.</p>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert"><i
                                data-feather="bell"></i>
                            <p>Hidden content You should check in on some of those fields below.</p>
                            <button class="btn-close" type="button" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card height-equal">
                    <div class="card-header">
                        <h4>Live alert</h4>
                        <p class="f-m-light mt-1">
                            Click the button below to show an alert,then dismiss it with the built-in close button.</p>
                    </div>
                    <div class="card-body live-dark">
                        <div id="liveAlertPlaceholder"></div>
                        <button class="btn btn-dark" id="liveAlertBtn" type="button">Show live alert</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card height-equal">
                    <div class="card-header">
                        <h4>Left border alert</h4>
                        <p class="f-m-light mt-1">
                            Use the <code>.border-left-wrapper </code>to change border-left radius.</p>
                    </div>
                    <div class="card-body live-dark">
                        <div class="alert alert-light-dark light alert-dismissible fade show text-dark border-left-wrapper"
                            role="alert"><i data-feather="help-circle"></i>
                            <p>You can check in on some of those fields below.</p>
                            <button class="btn-close" type="button" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Additional content</h4>
                        <p class="f-m-light mt-1">
                            Use the <code>.alert </code> utility class to quickly provide additional content within any
                            alerts.</p>
                    </div>
                    <div class="card-body dark-alert">
                        <div class="alert alert-light-primary" role="alert">
                            <h5 class="alert-heading pb-2 txt-primary">Please! Check your notifications</h5>
                            <p>The duty of notification is imposed upon the head of the family, and also upon the medical
                                practitioner who may be in attendance on the patient.</p>
                            <hr>
                            <p class="mb-0">The emergency notification system is free and is available in all 50 states.
                            </p>
                        </div>
                        <div class="alert alert-light-secondary" role="alert">
                            <h5 class="alert-heading pb-2 txt-secondary">Something went wrong! Please, chrome update.</h5>
                            <p>The duty of notification is imposed upon the head of the family, and also upon the medical
                                practitioner who may be in attendance on the patient.</p>
                            <hr>
                            <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and
                                tidy.</p>
                        </div>
                        <div class="alert alert-light-success" role="alert">
                            <h5 class="alert-heading pb-2 txt-success">Please! Hidden cameras were not installed.</h5>
                            <p>Due to increasingly accessible technology, hidden cameras have grown in popularity among
                                regular people in recent years.</p>
                            <hr>
                            <p class="mb-0">Consider moving clocks and plush animals from your area if you think they may
                                be concealing cameras because they are both portable items.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <!-- calendar js-->
    <script src="{{ asset('assets/js/height-equal.js') }}"></script>
    <script src="{{ asset('assets/js/alert.js') }}"></script>
@endsection
