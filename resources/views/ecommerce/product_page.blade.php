@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/owlcarousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/rating.css') }}">
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="page-title">
      <div class="row g-2">
        <div class="col-sm-6">
          <h4>Product Page</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">                                       
                <svg class="stroke-icon">
                  <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                </svg></a></li>
            <li class="breadcrumb-item">ECommerce</li>
            <li class="breadcrumb-item active">Product Page</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div>
            <div class="row product-page-main p-0">
                <div class="col-xxl-4 col-md-6 box-col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="product-slider owl-carousel owl-theme" id="sync1">
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt="">
                                </div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt="">
                                </div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/03.jpg') }}" alt="">
                                </div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/04.jpg') }}" alt="">
                                </div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/05.jpg') }}" alt="">
                                </div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/06.jpg') }}" alt="">
                                </div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/07.jpg') }}" alt="">
                                </div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/08.jpg') }}"
                                        alt=""></div>
                            </div>
                            <div class="owl-carousel owl-theme" id="sync2">
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/01.jpg') }}"
                                        alt=""></div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/02.jpg') }}"
                                        alt=""></div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/03.jpg') }}"
                                        alt=""></div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/04.jpg') }}"
                                        alt=""></div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/05.jpg') }}"
                                        alt=""></div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/06.jpg') }}"
                                        alt=""></div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/07.jpg') }}"
                                        alt=""></div>
                                <div class="item"><img src="{{ asset('assets/images/ecommerce/08.jpg') }}"
                                        alt=""></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-5 box-col-6 order-xxl-0 order-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="product-page-details">
                                <h3>Women Pink shirt.</h3>
                            </div>
                            <div class="product-price">$26.00
                                <del>$350.00 </del>
                            </div>
                            <ul class="product-color">
                                <li class="bg-primary"></li>
                                <li class="bg-secondary"></li>
                                <li class="bg-success"></li>
                                <li class="bg-info"></li>
                                <li class="bg-warning"></li>
                            </ul>
                            <hr>
                            <p>It is a long established fact that a reader will be distracted by the readable content of a
                                page when looking at its layout. The point of using Lorem Ipsum is that.</p>
                            <hr>
                            <div>
                                <table class="product-page-width">
                                    <tbody>
                                        <tr>
                                            <td> <b>Brand &nbsp;&nbsp;&nbsp;:</b></td>
                                            <td>Pixelstrap</td>
                                        </tr>
                                        <tr>
                                            <td> <b>Availability &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
                                            <td class="txt-success">In stock</td>
                                        </tr>
                                        <tr>
                                            <td> <b>Seller &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
                                            <td>ABC</td>
                                        </tr>
                                        <tr>
                                            <td> <b>Fabric &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
                                            <td>Cotton</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <h6 class="product-title">share it</h6>
                                </div>
                                <div class="col-md-8">
                                    <div class="product-icon">
                                        <ul class="product-social">
                                            <li class="d-inline-block"><a href="https://www.facebook.com/"
                                                    target="_blank"><i class="fa fa-facebook"></i></a></li>
                                            <li class="d-inline-block"><a href="https://accounts.google.com/"
                                                    target="_blank"><i class="fa fa-google-plus"></i></a></li>
                                            <li class="d-inline-block"><a href="https://twitter.com/" target="_blank"><i
                                                        class="fa fa-twitter"></i></a></li>
                                            <li class="d-inline-block"><a href="https://www.instagram.com/"
                                                    target="_blank"><i class="fa fa-instagram"></i></a></li>
                                            <li class="d-inline-block"><a href="https://rss.app/" target="_blank"><i
                                                        class="fa fa-rss"></i></a></li>
                                        </ul>
                                        <form class="d-inline-block f-right"></form>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <h6 class="product-title">Rate Now</h6>
                                </div>
                                <div class="col-md-8">
                                    <div class="d-flex gap-2">
                                        <div><i class="fa fa-star font-warning"></i><i
                                                class="fa fa-star font-warning"></i><i
                                                class="fa fa-star font-warning"></i><i
                                                class="fa fa-star font-warning"></i><i
                                                class="fa fa-star font-warning"></i></div><span>(250 review)</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="m-t-15 btn-showcase"><a class="btn btn-primary" href="{{ route('admin.cart') }}" title="">
                                    <i class="fa fa-shopping-basket me-1"></i>Add To Cart</a><a class="btn btn-success"
                                    href="{{ route('admin.checkout') }}" title=""> <i class="fa fa-shopping-cart me-1"></i>Buy
                                    Now</a><a class="btn btn-secondary" href="{{ route('admin.list_wish') }}"> <i
                                        class="fa fa-heart me-1"></i>Add To WishList</a></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6 box-col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- side-bar colleps block stat-->
                            <div class="filter-block">
                                <h4>Brand</h4>
                                <ul>
                                    <li><a class="f-w-500" href="{{ route('admin.product') }}">Clothing</a></li>
                                    <li><a class="f-w-500" href="{{ route('admin.product') }}">Bags</a></li>
                                    <li><a class="f-w-500" href="{{ route('admin.product') }}">Footwear</a></li>
                                    <li><a class="f-w-500" href="{{ route('admin.product') }}">Watches</a></li>
                                    <li><a class="f-w-500" href="{{ route('admin.product') }}">ACCESSORIES</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="collection-filter-block">
                                <ul class="pro-services">
                                    <li>
                                        <div class="media"><i data-feather="truck"></i>
                                            <div class="media-body">
                                                <h5>Free Shipping </h5>
                                                <p>Free Shipping World Wide</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media"><i data-feather="clock"></i>
                                            <div class="media-body">
                                                <h5>24 X 7 Service </h5>
                                                <p>Online Service For New Customer</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media"><i data-feather="gift"></i>
                                            <div class="media-body">
                                                <h5>Festival Offer </h5>
                                                <p>New Online Special Festival</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media"><i data-feather="credit-card"></i>
                                            <div class="media-body">
                                                <h5>Online Payment </h5>
                                                <p>Contrary To Popular Belief. </p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- silde-bar colleps block end here-->
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="row product-page-main">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs border-tab nav-primary mb-0" id="top-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-bs-toggle="tab"
                                href="#top-home" role="tab" aria-controls="top-home"
                                aria-selected="false">Febric</a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-bs-toggle="tab"
                                href="#top-profile" role="tab" aria-controls="top-profile"
                                aria-selected="false">Video</a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                href="#top-contact" role="tab" aria-controls="top-contact"
                                aria-selected="true">Details</a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="brand-top-tab" data-bs-toggle="tab"
                                href="#top-brand" role="tab" aria-controls="top-brand"
                                aria-selected="true">Brand</a>
                            <div class="material-border"></div>
                        </li>
                    </ul>
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade active show" id="top-home" role="tabpanel"
                            aria-labelledby="top-home-tab">
                            <p class="mb-0 m-t-20">Refresh your wardrobe with this chic top. With an eye-catching square
                                neck, this top also features pretty puff sleeves. Stunning pink colour Classic solid pattern
                                Square neck Elasticated puff sleeves Belt included, Polyester fabric, machine wash.."</p>
                            <p class="mb-0 m-t-20">Tee Stores is an Indian contemporary clothing brand. The product pages
                                display a fine quality fabric with colorful description. We offer many vivid designs, art,
                                styles that "combine heritage with modernity, simplicity, playfulness and street style"."
                            </p>
                        </div>
                        <div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="profile-top-tab">
                            <p class="mb-0 m-t-20">Lorate Solid Men's Fashion Full Sleeves Latest Jacket for Men With</p>
                        </div>
                        <div class="tab-pane fade" id="top-contact" role="tabpanel" aria-labelledby="contact-top-tab">
                            <p class="mb-0 m-t-20">Button Closure Long Sleeve Casual Torn Lycra Denim Jacket</p>
                        </div>
                        <div class="tab-pane fade" id="top-brand" role="tabpanel" aria-labelledby="brand-top-tab">
                            <p class="mb-0 m-t-20">Product Dimensions : 18 x 18 x 4 cm <br>Date First Available : 31 March
                                2024<br>Manufacturer : Tee Stores<br>Item part number : TS-WT721-XS-WHITE</p>
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
    <script src="{{ asset('assets/js/rating/jquery.barrating.js') }}"></script>
    <script src="{{ asset('assets/js/rating/rating-script.js') }}"></script>
    <script src="{{ asset('assets/js/owlcarousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('assets/js/ecommerce.js') }}"></script>
@endsection
