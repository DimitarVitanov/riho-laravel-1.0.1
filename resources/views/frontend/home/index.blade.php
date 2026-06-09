@extends('frontend.layouts.master')
@section('content')
    @use('App\Helpers\Helpers')
    @php
        $content = Helpers::getLandingPage();
    @endphp
    <!-- crud -->
    <section class="section-py-space riho-page-section" id="home">
        <div class="container-fluid fluid-space">
            <div class="title text-center">
                <h5>{{ @$content['home']['laravel_crud']['sub_title'] }}</h5>
                <h2 class="mb-lg-2 mb-0">{{ @$content['home']['laravel_crud']['title'] }}</h2>
            </div>
            <div class="container">
                <div class="main-page show-more-items show-more-content hideContent">
                    <div class="row gy-4 justify-content-center">
                        @foreach ($content['home']['laravel_crud']['crud_banners'] as $key => $crud)
                            @if (is_array($crud))
                                <div class="col-xl-4 col-lg-4 col-md-6 demo-content fadeInUp wow"
                                    style="visibility: visible; animation-name: fadeInUp;">
                                    <div class="riho-demo-img bg-light-{{ $crud['bg_color'] }}">
                                        <div class="overflow-hidden">
                                            <a href="{{ @$crud['url'] }}" target="_blank">
                                                <div class="laravel-content">
                                                    <h3>{{ @$crud['title'] }}</h3>
                                                    <p class="f-light">{{ @$crud['description'] }}</p>
                                                </div>
                                                <div class="tilt-image">
                                                    <img class="img-fluid js-tilt" src="{{ asset(@$crud['image']) }}"
                                                        alt="project" data-tilt-perspective="300" data-tilt-speed="400"
                                                        data-tilt-max="5"
                                                        style="will-change: transform; transform: perspective(300px) rotateX(0deg) rotateY(0deg);">
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="show-more btn mt-3"></div>
                </div>
            </div> 
        </div>
    </section> 
 
    <!-- demo section  -->
    <section class="demo-section section-py-space" id="page">
        <div class="title text-center">
            <h5>{{ @$content['page']['sub_title'] }}</h5>
            <h2 class="mb-lg-2 mb-0">{{ @$content['page']['title'] }}</h2>
        </div>
        <div class="demo-Section container  container-fluid"> 
            <div class="col-xxl-12 layout-wrap demo-imgs demo-block">
                <div class="layout-wrapper">
                    <div class="swiper layout-slider">
                        <div class="swiper-wrapper">
                            @forelse($content['page']['dashboard'] ?? [] as $dashboard)
                                <div class="swiper-slide"> 
                                    <div class="col">
                                        <div class="demo-box dashboard-images">
                                            <div class="layout-name">
                                                <div class="riho-demo-img">
                                                    <ul class="dot-group">
                                                        <li></li>
                                                        <li></li>
                                                        <li> </li>
                                                    </ul>
                                                </div><a href="{{ $dashboard['url'] }}" target="_blank">{{ $dashboard['title'] }}</a>
                                            </div>
                                            <div class="img-wrraper">
                                                <a href="{{ $dashboard['url'] }}" target="_blank">
                                                    <img class="img-fluid"
                                                        src="{{ asset($dashboard['image']) }}"
                                                        alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>No Data Found</p>
                            @endforelse
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--frameworks start-->
    <section class="framework section-py-space light-bg">
        <div class="container-lg container-fluid">
            <div class="row"> 
                <div class="col-sm-12 wow pulse">
                    <div class="title text-center">
                        <h5>{{ $content['page']['frameworks']['sub_title'] }}</h5>
                        <h2 class="mb-lg-2 mb-0">{{ $content['page']['frameworks']['title'] }}</h2>
                    </div> 
                </div>
                <div class="col-sm-12 framworks"> 
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane custom-frameworks fade show active" id="pills-home" role="tabpanel">
                            <ul class="frameworks-list show-more-content hideContent">
                                @forelse($content['page']['frameworks']['poster'] ?? [] as $framework)
                                <li class="box wow fadeInUp">
                                    <div class="d-flex align-items-center gap-2">    
                                        <div class="frameworks-img"><img src="{{ asset($framework['image']) }}" alt="Framework Image"></div>
                                        <h5 class="mb-0 f-w-600">{{ $framework['title'] }}</h5>
                                    </div>
                                </li>
                                @empty
                                    <p>No Data Found</p>
                                @endforelse
                            </ul> 
                            <div class="show-more btn mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </section>

    <!--Applications start -->
    <section class="application-section section-py-space application-sec" id="Applications">
        <div class="title text-center">
            <h5>{{ @$content['page']['applications']['sub_title'] }}</h5>
            <h2 class="mb-lg-2 mb-0">{{ @$content['page']['applications']['title'] }} </h2>
        </div>
        <div class="container custom-app-btn show-more-items container-fluid">
            <div class="row demo-block show-more-content hideContent">
                @forelse(@$content['page']['applications']['poster'] ?? [] as $application)
                    <div class="col-xl-4 col-lg-4 col-sm-6 wow pulse">
                        <div class="app-box">
                            <div class="img-wrraper">
                                <a href="{{ $application['url'] }}" target="_blank">
                                    <img class="img-fluid" src="{{ asset($application['image']) }}" alt="">
                                </a>
                            </div>
                            <div class="demo-detail">
                                <div class="demo-title">
                                    <a class="btn btn-primary" href="{{ $application['url'] }}"
                                        target="_blank">{{ $application['title'] }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>No Data Found</p> 
                @endforelse 
            </div>
            <div class="show-more btn mt-3"></div>
        </div>
    </section>
 
    <!--choose Riho -->
    <section class="section-py-space feature-section" id="feature">
        <div class="container-fluid  container">
            <div class="row">
                <div class="col-sm-12 wow pulse">
                    <div class="title text-center">
                        <h5>{{ @$content['feature']['laravel_feature']['sub_title'] }}</h5>
                        <h2 class="mb-lg-2 mb-0">{{ @$content['feature']['laravel_feature']['title'] }} </h2>
                    </div>
                </div>
            </div> 
            <div class="custom-feature-btn">
                <div class="row g-4 show-more-content hideContent">
                    @foreach (@$content['feature']['laravel_feature']['poster'] as $feature)
                        <div class="col-xxl-3 col-lg-4 col-sm-6 mb-4">
                            <div class="feature-box common-card bg-feature">
                                <div class="feature-icon bg-white">
                                    <div><img src="{{ asset($feature['svg_icon']) }}" height="26px" width="26px" alt="feature-icon"></div>
                                </div>
                                <h5 class="text-center">{{ $feature['title'] }}</h5>
                                <p class="mb-0 f-light">{{ $feature['description'] }}</p>
                            </div>
                        </div>  
                    @endforeach
                </div>
                <div class="show-more btn mt-3"></div>
            </div>
        </div>
    </section> 

    <section class="unique-cards section-py-space light-bg position-relative">
        <div class="support-title">
            <h2>{{ $content['feature']['premium_support']['title'] }}</h2>
            <p>“{{ $content['feature']['premium_support']['description'] }}”</p>
            <div class="premium-button"> <a class="txt-light btn bg-primary f-w-700 support-button"
                    href="{{ $content['feature']['premium_support']['button_url'] }}"
                    target="_blank">{{ $content['feature']['premium_support']['button_text'] }}</a></div>
        </div>
        <div class="pricing-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12 support-img"> <img class="img-fluid"
                            src="{{ asset(@$content['feature']['premium_support']['main_image']) }}" alt="">
                        <h4>{{ $content['feature']['premium_support']['right_side_title'] }}</h4>
                        <div class="license-title">
                            <h3>{{ $content['feature']['premium_support']['left_side_title'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="marquee">
            <div class="marquee-name">
                <p class="big-title">{{ $content['feature']['premium_support']['marquee_title'] }}</p>
            </div>
        </div>
    </section>

    <section class="landing-footer section-py-space" id="footer">
        <div class="custom-container">
            <div class="row p-0 m-0">
                <div class="col-12">
                    <div class="footer-contain">
                        <div class="rating-wrraper"><img class="img-fluid" src="{{ asset($content['footer']['logo']) }}"
                                alt="logo"></div>
                        <h2 class="f-w-600">"{{ $content['footer']['description'] }}"</h2>
                        <p class="f-w-500">{{ $content['footer']['copyright_text'] }}</p>
                        <ul class="star-rate">
                            <li><i class="fa fa-star font-warning"></i></li>
                            <li><i class="fa fa-star font-warning"></i></li>
                            <li><i class="fa fa-star font-warning"></i></li>
                            <li><i class="fa fa-star font-warning"> </i></li>
                            <li> <i class="fa fa-star font-warning"> </i></li>
                        </ul>
                        <div class="btn-footer">
                            <a class="btn btn-lg btn-primary" target="_blank"
                                href="{{ $content['footer']['left_button_url'] }}" data-bs-original-title=""
                                title="">{{ $content['footer']['left_button_text'] }}</a>
                            <a class="btn btn-lg btn-secondary" target="_blank"
                                href="{{ $content['footer']['middle_button_url'] }}" data-bs-original-title=""
                                title="">{{ $content['footer']['middle_button_text'] }}</a>
                            <a class="btn btn-lg btn-success" target="_blank"
                                href="{{ $content['footer']['right_button_url'] }}" data-bs-original-title=""
                                title="">{{ $content['footer']['right_button_text'] }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
