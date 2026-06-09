@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>FAQ</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">FAQ</li>
                        <li class="breadcrumb-item active">FAQ</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="faq-wrap">
            <div class="row">
                <div class="col-xl-4 box-col-6">
                    <div class="card bg-primary">
                        <div class="card-body">
                            <div class="media faq-widgets">
                                <div class="media-body">
                                    <h5>Articles</h5>
                                    <p>My knowledge is limited to what I was trained on, which has a cutoff date of
                                        September 2021. Therefore, I can't provide you with the latest articles from the
                                        internet. However, I can still offer general information and answer any questions
                                        you might have based.</p>
                                </div><i data-feather="file-text"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 box-col-6">
                    <div class="card bg-primary">
                        <div class="card-body">
                            <div class="media faq-widgets">
                                <div class="media-body">
                                    <h5>Knowledgebase</h5>
                                    <p>
                                        It's important to note that while I strive to provide accurate and up-to-date
                                        information, I may not have knowledge of events or developments that occurred after
                                        September 2024.</p>
                                </div><i data-feather="book-open"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 box-col-12">
                    <div class="card bg-primary">
                        <div class="card-body">
                            <div class="media faq-widgets">
                                <div class="media-body">
                                    <h5>Support</h5>
                                    <p>
                                        my best to provide you with the information you seek. Please keep in mind that my
                                        knowledge is based on data up until September 2021, so I may not have information on
                                        recent events or developments.</p>
                                </div><i data-feather="aperture"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="header-faq">
                        <h5 class="mb-0">Quick Questions are answered</h5>
                    </div>
                    <div class="row default-according style-1 faq-accordion" id="accordionoc">
                        <div class="col-xl-8 xl-60 col-lg-6 col-md-7">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon" aria-expanded="false"
                                            aria-controls="collapseicon"><i data-feather="help-circle"></i> Integrating
                                            WordPress with Your Website?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon" aria-labelledby="collapseicon"
                                    data-bs-parent="#accordionoc">
                                    <div class="card-body">How you approach this process will depend on which web host you
                                        use. For example, a lot of hosting providers use cPanel, which includes an option to
                                        set up subdomains with just a few clicks.</div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon2" aria-expanded="false"
                                            aria-controls="collapseicon2"><i data-feather="help-circle"></i> WordPress Site
                                            Maintenance ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon2" data-bs-parent="#accordionoc">
                                    <div class="card-body">We’ve just published a detailed on how to backup your WordPress
                                        website, however, if you’re in a hurry, here’s a quick solution.</div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon3" aria-expanded="false"
                                            aria-controls="collapseicon2"><i data-feather="help-circle"></i>Meta Tags in
                                            WordPress ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon3" data-bs-parent="#accordionoc">
                                    <div class="card-body"> Manually adding meta tags in WordPress is relatively simple. For
                                        this demo, we’ll use the example from the WordPress Codex. Imagine you’re Harriet
                                        Smith, a veterinarian who blogs about their animal stories on WordPress.</div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon4" aria-expanded="false"
                                            aria-controls="collapseicon2"><i data-feather="help-circle"></i> WordPress in
                                            Your Language ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon4" data-bs-parent="#accordionoc">
                                    <div class="card-body">As of version 4.0, you can have WordPress automatically install
                                        the language of your choice during the installation process.</div>
                                </div>
                            </div>
                            <div class="faq-title">
                                <h6>Intellectual Property</h6>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon5" aria-expanded="false"><i
                                                data-feather="help-circle"></i> WordPress Site Maintenance ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon5" aria-labelledby="collapseicon5"
                                    data-bs-parent="#accordionoc">
                                    <div class="card-body">We’ve just published a detailed on how to backup your WordPress
                                        website, however, if you’re in a hurry, here’s a quick solution.</div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon7" aria-expanded="false"
                                            aria-controls="collapseicon2"><i data-feather="help-circle"></i> WordPress in
                                            Your Language ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon7" data-bs-parent="#accordionoc">
                                    <div class="card-body">As of version 4.0, you can have WordPress automatically install
                                        the language of your choice during the installation process.</div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon8" aria-expanded="false"
                                            aria-controls="collapseicon2"><i data-feather="help-circle"></i>Integrating
                                            WordPress with Your Website ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon8" data-bs-parent="#accordionoc">
                                    <div class="card-body">How you approach this process will depend on which web host you
                                        use. For example, a lot of hosting providers use cPanel, which includes an option to
                                        set up subdomains with just a few clicks.</div>
                                </div>
                            </div>
                            <div class="faq-title">
                                <h6>Selling And Buying</h6>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon9" aria-expanded="false"><i
                                                data-feather="help-circle"></i> WordPress Site Maintenance ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon9" aria-labelledby="collapseicon9"
                                    data-bs-parent="#accordionoc">
                                    <div class="card-body">We’ve just published a detailed on how to backup your WordPress
                                        website, however, if you’re in a hurry, here’s a quick solution.</div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon10" aria-expanded="false"
                                            aria-controls="collapseicon2"><i data-feather="help-circle"></i>Meta Tags in
                                            WordPress ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon10" data-bs-parent="#accordionoc">
                                    <div class="card-body">Manually adding meta tags in WordPress is relatively simple. For
                                        this demo, we’ll use the example from the WordPress Codex. Imagine you’re Harriet
                                        Smith, a veterinarian who blogs about their animal stories on WordPress.</div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon11" aria-expanded="false"
                                            aria-controls="collapseicon2"><i data-feather="help-circle"></i>Validating a
                                            Website ? </button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon11" data-bs-parent="#accordionoc">
                                    <div class="card-body">
                                        Validating a website is the process of ensuring that the pages on the website
                                        conform to the norms or standards defined by various organizations.</div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon12" aria-expanded="false"
                                            aria-controls="collapseicon2"><i data-feather="help-circle"></i>Know Your
                                            Sources ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon12" data-bs-parent="#accordionoc">
                                    <div class="card-body">A book or set of books giving information on many subjects or on
                                        many aspects of one subject. Some are intended as an entry point into research for a
                                        general audience, some provide detailed information.</div>
                                </div>
                            </div>
                            <div class="faq-title">
                                <h6>User Accounts</h6>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon13" aria-expanded="false"><i
                                                data-feather="help-circle"></i>Integrating WordPress with Your Website
                                            ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon13" aria-labelledby="collapseicon13"
                                    data-bs-parent="#accordionoc">
                                    <div class="card-body">How you approach this process will depend on which web host you
                                        use. For example, a lot of hosting providers use cPanel, which includes an option to
                                        set up subdomains with just a few clicks.</div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon14" aria-expanded="false"
                                            aria-controls="collapseicon2"><i data-feather="help-circle"></i>WordPress Site
                                            Maintenance ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon14" data-bs-parent="#accordionoc">
                                    <div class="card-body"> We’ve just published a detailed on how to backup your WordPress
                                        website, however, if you’re in a hurry, here’s a quick solution.</div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon16" aria-expanded="false"
                                            aria-controls="collapseicon2"><i data-feather="help-circle"></i> WordPress in
                                            Your Language ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon16" data-bs-parent="#accordionoc">
                                    <div class="card-body">As of version 4.0, you can have WordPress automatically install
                                        the language of your choice during the installation process.</div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon17" aria-expanded="false"
                                            aria-controls="collapseicon2"><i data-feather="help-circle"></i> Validating a
                                            Website ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon17" data-bs-parent="#accordionoc">
                                    <div class="card-body">Validating a website is the process of ensuring that the pages
                                        on the website conform to the norms or standards defined by various organizations.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed ps-0" data-bs-toggle="collapse"
                                            data-bs-target="#collapseicon18" aria-expanded="false"
                                            aria-controls="collapseicon2"><i data-feather="help-circle"></i>Meta Tags in
                                            WordPress ?</button>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseicon18" data-bs-parent="#accordionoc">
                                    <div class="card-body">Manually adding meta tags in WordPress is relatively simple. For
                                        this demo, we’ll use the example from the WordPress Codex. Imagine you’re Harriet
                                        Smith, a veterinarian who blogs about their animal stories on WordPress.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 xl-40 col-lg-6 col-md-5">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-mb-faq xs-mt-search">
                                        <div class="card-header faq-header pb-0">
                                            <h5>Search articles</h5><i data-feather="help-circle"></i>
                                        </div>
                                        <div class="card-body faq-body">
                                            <div class="faq-form">
                                                <input class="form-control" type="text" placeholder="Search.."><i
                                                    class="search-icon" data-feather="search"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="card card-mb-faq">
                                        <div class="card-header faq-header pb-0">
                                            <h5>Navigation</h5><i data-feather="settings"></i>
                                        </div>
                                        <div class="card-body faq-body">
                                            <div class="navigation-btn"><a class="btn btn-primary" href="#"><i
                                                        class="m-r-10" data-feather="message-square"></i>Ask Question</a>
                                            </div>
                                            <div class="navigation-option">
                                                <ul>
                                                    <li><a href="#"><i data-feather="edit"></i>Tutorials</a></li>
                                                    <li><a href="#"><i data-feather="globe"></i>Help center</a></li>
                                                    <li><a href="#"><i
                                                                data-feather="book-open"></i>Knowledgebase</a></li>
                                                    <li><a href="#"><i
                                                                data-feather="file-text"></i>Articles</a><span
                                                            class="badge badge-primary rounded-pill pull-right">42</span>
                                                    </li>
                                                    <li><a href="#"><i data-feather="youtube"></i>Video
                                                            Tutorials</a><span
                                                            class="badge badge-primary rounded-pill pull-right">648</span>
                                                    </li>
                                                    <li><a href="#"><i data-feather="message-circle"></i>Ask our
                                                            community</a></li>
                                                    <li><a href="#"><i data-feather="mail"></i>Contact us</a></li>
                                                </ul>
                                                <hr>
                                                <ul>
                                                    <li><a href="#"><i data-feather="message-circle"></i>Ask our
                                                            community</a></li>
                                                    <li><a href="#"><i data-feather="mail"></i>Contact us</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header faq-header pb-0">
                                            <h5 class="d-inline-block">Latest Updates</h5><span
                                                class="pull-right d-inline-block">See All</span>
                                        </div>
                                        <div class="card-body faq-body">
                                            <div class="d-flex updates-faq-main">
                                                <div class="updates-faq"><i class="font-primary"
                                                        data-feather="rotate-cw"></i></div>
                                                <div class="flex-grow-1 updates-bottom-time">
                                                    <p><a href="#">David Linner </a> requested money back for a
                                                        double debit card charge</p>
                                                    <p>10 minutes ago</p>
                                                </div>
                                            </div>
                                            <div class="d-flex updates-faq-main">
                                                <div class="updates-faq"><i class="font-primary"
                                                        data-feather="dollar-sign"></i></div>
                                                <div class="flex-grow-1 updates-bottom-time">
                                                    <p>All sellers have received monthly payouts</p>
                                                    <p>2 hours ago</p>
                                                </div>
                                            </div>
                                            <div class="d-flex updates-faq-main">
                                                <div class="updates-faq"><i class="font-primary" data-feather="link"></i>
                                                </div>
                                                <div class="flex-grow-1 updates-bottom-time">
                                                    <p>User Christopher <a href="#">Wallace</a> is on hold and
                                                        awaiting for staff reply</p>
                                                    <p>45 minutes ago</p>
                                                </div>
                                            </div>
                                            <div class="d-flex updates-faq-main">
                                                <div class="updates-faq"><i class="font-primary"
                                                        data-feather="check"></i></div>
                                                <div class="flex-grow-1 updates-bottom-time">
                                                    <p>
                                                        Ticket #43683 has been closed by <a href="#">Victoria Wilson
                                                        </a></p>
                                                    <p>Dec 7, 11:48</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="header-faq">
                        <h5 class="mb-0">Featured Tutorials</h5>
                    </div>
                    <div class="row">
                        <div class="col-xxl-3 col-md-6 box-col-6">
                            <div class="card features-faq product-box">
                                <div class="faq-image product-img"><img class="img-fluid"
                                        src="{{ asset('assets/images/faq/1.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><i class="icon-link"></i></li>
                                            <li><i class="icon-import"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6>Web Design</h6>
                                    <p>A Web Designing course belongs to the field of Computer Science and IT. It enables
                                        students to learn.</p>
                                </div>
                                <div class="card-footer"><span>Dec 15, 2022</span><span class="pull-right"><i
                                            class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                            class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                            class="fa fa-star font-primary"></i></span></div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6 box-col-6">
                            <div class="card features-faq product-box">
                                <div class="faq-image product-img"><img class="img-fluid"
                                        src="{{ asset('assets/images/faq/2.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><i class="icon-link"></i></li>
                                            <li><i class="icon-import"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6>Web Development</h6>
                                    <p>This course is designed to start you on a path toward future studies in web
                                        development and design.</p>
                                </div>
                                <div class="card-footer"><span>Dec 15, 2022</span><span class="pull-right"><i
                                            class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                            class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                            class="fa fa-star-o font-primary"></i></span></div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6 box-col-6">
                            <div class="card features-faq product-box">
                                <div class="faq-image product-img"><img class="img-fluid"
                                        src="{{ asset('assets/images/faq/3.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><i class="icon-link"></i></li>
                                            <li><i class="icon-import"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6>UI Design</h6>
                                    <p>User interface design (UI) is the design for machines and software, such as mobile
                                        devices, computers.</p>
                                </div>
                                <div class="card-footer"><span>Dec 15, 2022</span><span class="pull-right"><i
                                            class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                            class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                            class="fa fa-star font-primary"></i></span></div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6 box-col-6">
                            <div class="card features-faq product-box">
                                <div class="faq-image product-img"><img class="img-fluid"
                                        src="{{ asset('assets/images/faq/4.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><i class="icon-link"></i></li>
                                            <li><i class="icon-import"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6>UX Design</h6>
                                    <p>A Web Designing course belongs to the field of Computer Science and IT. It enables
                                        students to learn.</p>
                                </div>
                                <div class="card-footer"><span>Dec 15, 2022</span><span class="pull-right"><i
                                            class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                            class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                            class="fa fa-star-half-o font-primary"></i></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="header-faq">
                        <h5 class="mb-0">Latest articles and videos</h5>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-md-6">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="media"><i class="m-r-30" data-feather="codepen"></i>
                                                <div class="media-body">
                                                    <h6 class="f-w-600 mb-2">Using Video</h6>
                                                    <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate
                                                        eleifend tellus.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="media"><i class="m-r-30" data-feather="codepen"></i>
                                                <div class="media-body">
                                                    <h6 class="f-w-600 mb-2">Vel illum qu</h6>
                                                    <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate
                                                        eleifend tellus.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="media"><i class="m-r-30" data-feather="codepen"></i>
                                                <div class="media-body">
                                                    <h6 class="f-w-600 mb-2">Cum sociis natoqu</h6>
                                                    <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate
                                                        eleifend tellus. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="media"><i class="m-r-30" data-feather="file-text"></i>
                                                <div class="media-body">
                                                    <h6 class="f-w-600 mb-2">Donec pede justo</h6>
                                                    <p> Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate
                                                        eleifend tellus. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="media"><i class="m-r-30" data-feather="file-text"></i>
                                                <div class="media-body">
                                                    <h6 class="f-w-600 mb-2">Nam quam nunc</h6>
                                                    <p> Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate
                                                        eleifend tellus.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="media"><i class="m-r-30" data-feather="file-text"></i>
                                                <div class="media-body">
                                                    <h6 class="f-w-600 mb-2">Using Video </h6>
                                                    <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate
                                                        eleifend tellus.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="row">
                                <div class="col-xl-12 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="media"><i class="m-r-30" data-feather="youtube"></i>
                                                <div class="media-body">
                                                    <h6 class="f-w-600 mb-2">Vel illum qu</h6>
                                                    <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate
                                                        eleifend tellus.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="media"><i class="m-r-30" data-feather="youtube"></i>
                                                <div class="media-body">
                                                    <h6 class="f-w-600 mb-2">Cum sociis natoqu</h6>
                                                    <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate
                                                        eleifend tellus.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="media"><i class="m-r-30" data-feather="youtube"></i>
                                                <div class="media-body">
                                                    <h6 class="f-w-600 mb-2">Donec pede justo</h6>
                                                    <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate
                                                        eleifend tellus.</p>
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
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <!-- calendar js-->
    <script src="{{ asset('assets/js/height-equal.js') }}"></script>
@endsection
