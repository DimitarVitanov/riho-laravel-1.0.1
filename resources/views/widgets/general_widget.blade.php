@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/fullcalender.css') }}">
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="page-title">
      <div class="row g-2">
        <div class="col-sm-6">
          <h4>General</h4>
        </div>
        <div class="col-sm-6"> 
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">                                       
                <svg class="stroke-icon">
                  <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                </svg></a></li>
            <li class="breadcrumb-item">Widgets </li>
            <li class="breadcrumb-item active">General</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row size-column">
            <div class="col-xxl-9 box-col-12">
                <div class="row">
                    <div class="col-xl-3 col-sm-6">
                        <div class="card o-hidden small-widget">
                            <div class="card-body total-project border-b-primary border-2"><span
                                    class="f-light f-w-500 f-14">Total Project</span>
                                <div class="project-details">
                                    <div class="project-counter">
                                        <h2 class="f-w-600">1,523</h2><span class="f-12 f-w-400">(This month)</span>
                                    </div>
                                    <div class="product-sub bg-primary-light">
                                        <svg class="invoice-icon">
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#color-swatch') }}"></use>
                                        </svg>
                                    </div>
                                </div>
                                <ul class="bubbles">
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
                    <div class="col-xl-3 col-sm-6">
                        <div class="card o-hidden small-widget">
                            <div class="card-body total-Progress border-b-warning border-2"> <span
                                    class="f-light f-w-500 f-14">In Progress</span>
                                <div class="project-details">
                                    <div class="project-counter">
                                        <h2 class="f-w-600">836 </h2><span class="f-12 f-w-400">(This month) </span>
                                    </div>
                                    <div class="product-sub bg-warning-light">
                                        <svg class="invoice-icon">
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#tick-circle') }}"></use>
                                        </svg>
                                    </div>
                                </div>
                                <ul class="bubbles">
                                    <li class="bubble"></li>
                                    <li class="bubble"> </li>
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
                    <div class="col-xl-3 col-sm-6">
                        <div class="card o-hidden small-widget">
                            <div class="card-body total-Complete border-b-secondary border-2"><span
                                    class="f-light f-w-500 f-14">Complete</span>
                                <div class="project-details">
                                    <div class="project-counter">
                                        <h2 class="f-w-600">475</h2><span class="f-12 f-w-400">(This month) </span>
                                    </div>
                                    <div class="product-sub bg-secondary-light">
                                        <svg class="invoice-icon">
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#add-square') }}"></use>
                                        </svg>
                                    </div>
                                </div>
                                <ul class="bubbles">
                                    <li class="bubble"> </li>
                                    <li class="bubble"></li>
                                    <li class="bubble"></li>
                                    <li class="bubble"> </li>
                                    <li class="bubble"></li>
                                    <li class="bubble"></li>
                                    <li class="bubble"></li>
                                    <li class="bubble"></li>
                                    <li class="bubble"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="card o-hidden small-widget">
                            <div class="card-body total-upcoming border-2"><span
                                    class="f-light f-w-500 f-14">Upcoming</span>
                                <div class="project-details">
                                    <div class="project-counter">
                                        <h2 class="f-w-600">189</h2><span class="f-12 f-w-400">(This month) </span>
                                    </div>
                                    <div class="product-sub bg-light-light">
                                        <svg class="invoice-icon">
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#edit-2') }}"></use>
                                        </svg>
                                    </div>
                                </div>
                                <ul class="bubbles">
                                    <li class="bubble"> </li>
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
                    <div class="col-xl-4 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <ul class="product-costing">
                                    <li class="product-cost">
                                        <div class="product-icon bg-primary-light">
                                            <svg>
                                                <use href="{{ asset('assets/svg/icon-sprite.svg#activity') }}"></use>
                                            </svg>
                                        </div>
                                        <div><span class="f-w-500 f-14 mb-0">Total Sales</span>
                                            <h2 class="f-w-600">₹98,459</h2>
                                        </div>
                                    </li>
                                    <li> <span class="f-light f-14 f-w-500">We have sale +18.2k this week.</span></li>
                                </ul>
                                <ul class="product-costing">
                                    <li class="product-cost">
                                        <div class="product-icon bg-warning-light">
                                            <svg>
                                                <use href="{{ asset('assets/svg/icon-sprite.svg#people') }}"></use>
                                            </svg>
                                        </div>
                                        <div><span class="f-w-500 f-14 mb-0">Total Visitors</span>
                                            <h2 class="f-w-600">54,156</h2>
                                        </div>
                                    </li>
                                    <li> <span class="f-light f-14 f-w-500">We have total +3.5k visitors this week.</span>
                                    </li>
                                </ul>
                                <ul class="product-costing">
                                    <li class="product-cost">
                                        <div class="product-icon bg-light">
                                            <svg>
                                                <use href="{{ asset('assets/svg/icon-sprite.svg#task-square') }}"></use>
                                            </svg>
                                        </div>
                                        <div><span class="f-w-500 f-14 mb-0">Total Orders</span>
                                            <h2 class="f-w-600">5,125</h2>
                                        </div>
                                    </li>
                                    <li> <span class="f-light f-14 f-w-500">We have total +5k orders this week.</span></li>
                                </ul>
                                <ul class="product-costing">
                                    <li class="product-cost">
                                        <div class="product-icon bg-danger-light">
                                            <svg>
                                                <use href="{{ asset('assets/svg/icon-sprite.svg#money-recive') }}"></use>
                                            </svg>
                                        </div>
                                        <div><span class="f-w-500 f-14 mb-0">Refunded</span>
                                            <h2 class="f-w-600">₹20,000</h2>
                                        </div>
                                    </li>
                                    <li> <span class="f-light f-14 f-w-500">We got +66k refund this week.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <div class="card">
                            <div class="card-header card-no-border total-revenue pb-0">
                                <h4>User Visits by Day</h4>
                            </div>
                            <div class="card-body pt-0 pb-0">
                                <div class="user-visitsCharts">
                                    <div id="visitswidgetCharts"></div>
                                </div>
                            </div>
                            <div class="card-footer p-0">
                                <div class="common-space px-3 py-2">
                                    <div> <a class="f-w-600 f-10 " href="{{ route('admin.default_dashboard') }}">Most Visited Day</a><span
                                            class="f-light f-w-400 f-10 d-block">Total 59.6k visits on Sunday </span></div>
                                    <div class="visited-dropdown">
                                        <svg class="mb-0">
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#arrow-down') }}"></use>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6">
                        <div class="row">
                            <div class="col-md-12 col-sm-6">
                                <div class="card boost-up-card overflow-hidden">
                                    <div class="p-4">
                                        <div class="boostup-name row">
                                            <h6 class="text-white f-28 f-w-700 mb-2 z-1 ">Boost up your sale </h6>
                                            <p class="text-white f-14 f-w-500 col-9 line-clamp">by upgrading your increase
                                                sale by 30% more.</p>
                                        </div>
                                        <div class="img-boostup"><img class="img-boostup-img-1"
                                                src="{{ asset('assets/images/dashboard-3/boostup1.png') }}"
                                                alt="boostup"></div>
                                        <div class="btn-showcase text-start">
                                            <button class="btn btn-pill btn-outline-light-2x b-r-8" type="button">Upgrade
                                                Now</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-6">
                                <div class="card">
                                    <div class="card-header card-no-border total-revenue pb-0">
                                        <h4>Deliveries</h4>
                                        <div class="icon-menu-header">
                                            <svg>
                                                <use href="{{ asset('assets/svg/icon-sprite.svg#more-horizontal') }}">
                                                </use>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="table-responsive custom-scrollbar deliveries-percentage">
                                            <table class="percentage-data w-100">
                                                <thead>
                                                    <tr>
                                                        <th class="f-light" scope="col">Particular</th>
                                                        <th class="f-light text-end" scope="col">Percentage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="f-w-400 f-10"> <a class="line-clamp"
                                                                href="{{ route('admin.cart') }}">On Time Delivery</a></td>
                                                        <td>
                                                            <div class="progress-value d-flex gap-2 align-items-center">
                                                                <div class="progress">
                                                                    <div class="progress-bar bg-primary"
                                                                        role="progressbar" style="width: 75%  "
                                                                        aria-valuenow="75" aria-valuemin="0"
                                                                        aria-valuemax="100"></div>
                                                                </div><span>80%</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="f-w-400 f-10"> <a class="line-clamp"
                                                                href="{{ route('admin.cart') }}">Delayed Delivery</a></td>
                                                        <td>
                                                            <div class="progress-value d-flex gap-2 align-items-center">
                                                                <div class="progress">
                                                                    <div class="progress-bar bg-primary"
                                                                        role="progressbar" style="width: 15%"
                                                                        aria-valuenow="15" aria-valuemin="0"
                                                                        aria-valuemax="100"></div>
                                                                </div><span>15% </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-header card-no-border total-revenue">
                                <h4>Time Line </h4>
                            </div>
                            <div class="card-body pt-0 overflow-auto theme-scrollbar custom-scrollbar">
                                <div class="timeline-calendar custom-scrollbar">
                                    <div class="custom-calendar" id="calendar-container">
                                        <div class="time-line" id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card height-equal">
                            <div class="card-header total-revenue">
                                <h4>Sales Chart </h4>
                                <div class="sales-chart-dropdown-select">
                                    <div class="card-header-right-icon">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle" id="dropdownMenuButtonmenu"
                                                type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">Today</button>
                                            <div class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="dropdownMenuButtonmenu"><span class="dropdown-item">Last
                                                    Month</span><span class="dropdown-item">Last Week</span><span
                                                    class="dropdown-item">Last Day </span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="sales-wrapper">
                                    <div id="saleschart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 d-xxl-block d-none box-col-none">
                <div class="col-xl-12 special-Offer-banner">
                    <div class="card">
                        <div class="special-Offer img-fluid">
                            <div class="offer-contain">
                                <h4>Today’s Special Offer </h4>
                                <p class="mb-0 mt-2">You can flat get 20% off on your next pro version </p>
                                <p class="text-align-center">if your sale break your last record.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body new-product">
                            <div class="product-cost">
                                <div class="add-product">
                                    <div class="product-icon bg-light-primary">
                                        <svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#box-add') }}"></use>
                                        </svg>
                                    </div>
                                    <h6>Add New Product</h6>
                                </div>
                                <div class="product-icon">
                                    <svg>
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#arrow-down') }}"></use>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body new-product">
                            <div class="product-cost">
                                <div class="add-product">
                                    <div class="product-icon bg-light-primary">
                                        <svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#receipt-disscount') }}"></use>
                                        </svg>
                                    </div>
                                    <h6>Add Disscount </h6>
                                </div>
                                <div class="product-icon">
                                    <svg>
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#arrow-down') }}"> </use>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-12 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="total-revenue mb-2"><span>Total Revenue </span><a href="{{ route('admin.default_dashboard') }}">View
                                            Report</a></div>
                                    <h4 class="f-w-600">₹97,250.89 </h4>
                                    <div class="total-chart">
                                        <div class="data-grow d-flex gap-2"><i class="font-primary"
                                                data-feather="arrow-up-right"></i><span class="f-15 f-w-500">60.00% from
                                                last year </span></div>
                                        <div class="total-revenue-chart">
                                            <div id="revenueWidget"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="total-revenue mb-2"> <span>Total Order </span><a href="{{ route('admin.default_dashboard') }}">View
                                            Report</a></div>
                                    <h4 class="f-w-600">35,452 </h4>
                                    <div class="total-chart">
                                        <div class="data-grow d-flex gap-2"><i class="font-secondary"
                                                data-feather="arrow-down-left"></i><span class="f-15 f-w-500">15.00% from
                                                last week</span></div>
                                        <div class="total-order">
                                            <div id="totalOrderWidget"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends -->
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/moment.min.js') }}"></script>
    <!-- calendar js-->
    <script src="{{ asset('assets/js/calendar/fullcalender.js') }}"></script>
    <script src="{{ asset('assets/js/calendar/custom-calendar.js') }}"></script>
    <script src="{{ asset('assets/js/general-widget.js') }}"></script>
    <script src="{{ asset('assets/js/height-equal.js') }}"></script>
@endsection
