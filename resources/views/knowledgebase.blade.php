@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h3>Knowledgebase</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Knowledgebase</li>
                        <li class="breadcrumb-item active">Knowledgebase</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="knowledgebase-bg"><img class="bg-img-cover bg-center"
                        src="{{ asset('assets/images/knowledgebase/bg_1.jpg') }}" alt="looginpage"></div>
                <div class="knowledgebase-search">
                    <div>
                        <h3>How Can I help you?</h3>
                        <form class="form-inline" action="#" method="get">
                            <div class="form-group w-100"><i data-feather="search"></i>
                                <input class="form-control-plaintext w-100" type="text" placeholder="Type question here"
                                    title="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="media faq-widgets">
                            <div class="media-body">
                                <h5>Articles</h5>
                                <p>How little experience or technical knowledge you currently have. The web is a very big
                                    place, and if you are the typical internet user, you probably visit several websites
                                    every day.</p>
                            </div><i data-feather="book-open"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="media faq-widgets">
                            <div class="media-body">
                                <h5>Knowledgebase</h5>
                                <p>A Website Designing course enables learners to use essential designing and programming
                                    tools copy required to do the job. The curriculum is a blend of various themes.</p>
                            </div><i data-feather="aperture"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="media faq-widgets">
                            <div class="media-body">
                                <h5>Support</h5>
                                <p>
                                    The customer support industry is renaissance. Customer support as a own, offering
                                    companies a competitive advantage that’s difficult to copy.</p>
                            </div><i data-feather="file-text"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="header-faq">
                    <h5 class="mb-0">Browse articles by category</h5>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="f-w-700">Browse Articles</h5>
                            </div>
                            <div class="card-body">
                                <div class="row browse g-sm-4 g-3">
                                    <div class="col-xl-4 xl-50 col-md-6">
                                        <div class="browse-articles">
                                            <h6> <span><i data-feather="archive"></i></span>Quick Questions are answered
                                            </h6>
                                            <ul>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Policies have wide
                                                            acceptance editors.</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Guidelines are
                                                            sets of best practices supported.</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Editors should
                                                            attempt to follow guidelines. </span><span
                                                            class="badge badge-primary pull-right">New</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>They are best
                                                            treated with common sense. </span></a></li>
                                                <li><a href="#"><span><i data-feather="arrow-right"></i></span><span
                                                            class="txt-primary">See More (40)</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 xl-50 col-md-6">
                                        <div class="browse-articles">
                                            <h6> <span><i data-feather="archive"></i></span> Integrating WordPress with Your
                                                Website</h6>
                                            <ul>
                                                <li> <a href="#"><span><i data-feather="file-text"></i></span><span>It
                                                            was popularised in the 1960s with the release</span><span
                                                            class="badge badge-primary pull-right">Review</span></a></li>
                                                <li> <a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Etraset sheets
                                                            containing Lorem Ipsum passages</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Desktop publishing
                                                            software like Aldus PageMaker </span><span
                                                            class="badge badge-primary pull-right">Articles</span></a></li>
                                                <li><a href="#"><span> <i
                                                                data-feather="file-text"></i></span><span>Making this the
                                                            first true generator on the Internet.</span></a></li>
                                                <li><a href="#"><span><i data-feather="arrow-right"></i></span><span
                                                            class="txt-primary">See More (90)</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 xl-50 col-md-6">
                                        <div class="browse-articles">
                                            <h6> <span><i data-feather="archive"></i></span>WordPress Site Maintenance</h6>
                                            <ul>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Make a backup of
                                                            your WordPress website.</span></a></li>
                                                <li><a href="#"><span><i data-feather="file-text"></i></span><span>Run
                                                            a Wordpress security Website check.</span><span
                                                            class="badge badge-primary pull-right">Closed</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Monitor your
                                                            search engine optimization.</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Repetition,
                                                            injected humour, or non words etc</span></a></li>
                                                <li><a href="#"><span><i data-feather="arrow-right"></i></span><span
                                                            class="txt-primary">See More (50)</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 xl-50 col-md-6">
                                        <div class="browse-articles browse-bottom">
                                            <h6><span><i data-feather="archive"></i> Meta Tags in WordPress</span></h6>
                                            <ul>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>When an unknown
                                                            printer took a galley</span><span
                                                            class="badge badge-primary pull-right">Popular</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>you’ll be charged
                                                            a prorated cover that upgrade.</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Sed quia non
                                                            numquam eius modi tempora incidunt</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>But also the leap
                                                            into electronic typesetting,</span></a></li>
                                                <li><a href="#"><span><i data-feather="arrow-right"></i></span><span
                                                            class="txt-primary">See More (90)</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 xl-50 col-md-6">
                                        <div class="browse-articles browse-bottom">
                                            <h6><span><i data-feather="archive"></i></span>WordPress in Your Language</h6>
                                            <ul>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Premium plans are
                                                            billed as a single yearly payment.</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Monthly plan are
                                                            bill every month of signup.</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>It was
                                                            popularised in the 1960s with the release</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Making this the
                                                            first true generator on the Internet</span><span
                                                            class="badge badge-primary pull-right">Closed</span></a></li>
                                                <li><a href="#"><span><i data-feather="arrow-right"></i></span><span
                                                            class="txt-primary">See More (50)</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 xl-50 col-md-6">
                                        <div class="browse-articles browse-bottom">
                                            <h6><span><i data-feather="archive"></i></span>Know Your Sources</h6>
                                            <ul>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Many new editors
                                                            rely on policy interpretations.</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Editor
                                                            continually attempts to find a different policy.</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Violation of
                                                            maintaining a neutral point of view.</span><span
                                                            class="badge badge-primary pull-right">Article</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Nam libero
                                                            tempore, cum soluta nobis est eligendi </span></a></li>
                                                <li><a href="#"><span><i data-feather="arrow-right"></i></span><span
                                                            class="txt-primary">See More (26)</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 xl-50 col-md-6">
                                        <div class="browse-articles browse-bottom">
                                            <h6><span><i data-feather="archive"></i></span>Validating a Website</h6>
                                            <ul>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>You can modify
                                                            some specific component settings.</span><span
                                                            class="badge badge-primary pull-right">Review</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Click Apply to
                                                            validate the set preferences. </span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>who fail in their
                                                            duty through weakness </span><span
                                                            class="badge badge-primary pull-right">Closed</span></a></li>
                                                <li><a href="#"><span><i data-feather="file-text"></i></span><span>
                                                            At vero eos et accusamus et iusto </span></a></li>
                                                <li><a href="#"><span><i data-feather="arrow-right"></i></span><span
                                                            class="txt-primary">See More (10)</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 xl-50 col-md-6">
                                        <div class="browse-articles browse-bottom">
                                            <h6><span><i data-feather="archive"></i></span>Quick Questions are answered
                                            </h6>
                                            <ul>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Quis autem vel
                                                            eum iure reprehenderit </span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Users can browse
                                                            by topic or use the visually.</span><span
                                                            class="badge badge-primary pull-right">Popular</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Omnis voluptas
                                                            assumenda est</span><span
                                                            class="badge badge-primary pull-right">Review</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Produces no
                                                            resultant pleasure</span></a></li>
                                                <li><a href="#"><span><i data-feather="arrow-right"></i></span><span
                                                            class="txt-primary">See More (21)</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 xl-100">
                                        <div class="browse-articles browse-bottom">
                                            <h6><span><i data-feather="archive"></i></span>Integrating WordPress with Your
                                                Website</h6>
                                            <ul>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Language regular
                                                            existing languages.</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>The first line of
                                                            Lorem Ipsum, Lorem ipsum </span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>Thus comes from a
                                                            line in section</span></a></li>
                                                <li><a href="#"><span><i
                                                                data-feather="file-text"></i></span><span>First true
                                                            generator on the Internet</span><span
                                                            class="badge badge-primary pull-right">On hold</span></a></li>
                                                <li><a href="#"><span><i data-feather="arrow-right"></i></span><span
                                                            class="txt-primary">See More (34)</span></a></li>
                                            </ul>
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
                    <div class="col-xl-3 xl-50 col-md-6 box-col-6">
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
                                <h6> Web Design</h6>
                                <p>
                                    A Web Designing course belongs to the field of Computer Science and IT. It enables
                                    students to learn</p>
                            </div>
                            <div class="card-footer"><span>Dec 16, 2024</span><span class="pull-right"><i
                                        class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                        class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                        class="fa fa-star font-primary"></i></span></div>
                        </div>
                    </div>
                    <div class="col-xl-3 xl-50 col-md-6 box-col-6">
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
                                <p> This course is designed to start you on a path toward future studies in web development
                                    and design.</p>
                            </div>
                            <div class="card-footer"><span>Dec 16, 2024</span><span class="pull-right"><i
                                        class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                        class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                        class="fa fa-star-o font-primary"></i></span></div>
                        </div>
                    </div>
                    <div class="col-xl-3 xl-50 col-md-6 box-col-6">
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
                                <p> User interface design (UI) is the design for machines and software, such as mobile
                                    devices, computers.</p>
                            </div>
                            <div class="card-footer"><span>Dec 16, 2024</span><span class="pull-right"><i
                                        class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                        class="fa fa-star font-primary"></i><i class="fa fa-star font-primary"></i><i
                                        class="fa fa-star font-primary"></i></span></div>
                        </div>
                    </div>
                    <div class="col-xl-3 xl-50 col-md-6 box-col-6">
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
                                <h6> UX Design</h6>
                                <p>A Web Designing course belongs to the field of Computer Science and IT. It enables
                                    students to learn</p>
                            </div>
                            <div class="card-footer"><span>Dec 16, 2024</span><span class="pull-right"><i
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
                                        <div class="media articles-icons"><i class="m-r-20" data-feather="codepen"></i>
                                            <div class="media-body">
                                                <h6 class="f-w-600">Using Video</h6>
                                                <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend
                                                    tellus.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="media articles-icons"><i class="m-r-20" data-feather="codepen"></i>
                                            <div class="media-body">
                                                <h6 class="f-w-600">Vel illum qu</h6>
                                                <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend
                                                    tellus.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="media articles-icons"><i class="m-r-20" data-feather="codepen"></i>
                                            <div class="media-body">
                                                <h6 class="f-w-600"> Cum sociis natoqu</h6>
                                                <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend
                                                    tellus. . </p>
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
                                        <div class="media articles-icons"><i class="m-r-20" data-feather="file-text"></i>
                                            <div class="media-body">
                                                <h6 class="f-w-600"> Donec pede justo</h6>
                                                <p> Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend
                                                    tellus. </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="media articles-icons"><i class="m-r-20" data-feather="file-text"></i>
                                            <div class="media-body">
                                                <h6 class="f-w-600"> Nam quam nunc</h6>
                                                <p> Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend
                                                    tellus.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="media articles-icons"><i class="m-r-20" data-feather="file-text"></i>
                                            <div class="media-body">
                                                <h6 class="f-w-600">Using Video </h6>
                                                <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend
                                                    tellus.</p>
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
                                        <div class="media articles-icons"> <i class="m-r-20" data-feather="youtube"></i>
                                            <div class="media-body">
                                                <h6 class="f-w-600"> Vel illum qu</h6>
                                                <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend
                                                    tellus.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="media articles-icons"><i class="m-r-20" data-feather="youtube"></i>
                                            <div class="media-body">
                                                <h6 class="f-w-600"> Cum sociis natoqu</h6>
                                                <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend
                                                    tellus.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="media articles-icons"><i class="m-r-20" data-feather="youtube"></i>
                                            <div class="media-body">
                                                <h6 class="f-w-600">Donec pede justo</h6>
                                                <p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend
                                                    tellus.</p>
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
@endsection
