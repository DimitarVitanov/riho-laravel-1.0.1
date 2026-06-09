@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/js-datatables/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/owlcarousel.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>
                        Display Category</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">ECommerce</li>
                        <li class="breadcrumb-item active">Category</li>
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
                    <div class="card-body">
                        <div class="list-product-header">
                            <div>
                                <div class="light-box"><a data-bs-toggle="collapse" href="#collapseProduct" role="button"
                                        aria-expanded="false" aria-controls="collapseProduct"><i class="filter-icon show"
                                            data-feather="filter"></i><i class="icon-close filter-close hide"></i></a></div>
                                <a class="btn btn-primary" href="{{ route('admin.list_wish') }}"><i class="fa fa-plus"></i>Add
                                    Category</a>
                            </div>
                            <div class="collapse" id="collapseProduct">
                                <div class="card card-body list-product-body">
                                    <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-2 g-3">
                                        <div class="col">
                                            <select class="form-select" aria-label="Default select example">
                                                <option selected="">Choose Product</option>
                                                <option value="1">Apple iphone 13 Pro</option>
                                                <option value="2">Wood Chair</option>
                                                <option value="3">M185 Compact Wireless Mouse</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select class="form-select" aria-label="Default select example">
                                                <option selected="">Choose Category</option>
                                                <option value="1">Furniture</option>
                                                <option value="2">Smart Gadgets</option>
                                                <option value="3">Electrics</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select class="form-select" aria-label="Default select example">
                                                <option selected="">Choose Sub Category</option>
                                                <option value="1">Smart Phones</option>
                                                <option value="2">Smart Watches</option>
                                                <option value="3">Wireless headphone</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select class="form-select" aria-label="Default select example">
                                                <option selected="">Status</option>
                                                <option value="1">Sold Out </option>
                                                <option value="2">In Stock</option>
                                                <option value="3">Pre Order</option>
                                                <option value="4">Limited Stock </option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select class="form-select" aria-label="Default select example">
                                                <option selected="">Price</option>
                                                <option value="1">56000.00</option>
                                                <option value="2">19000.00</option>
                                                <option value="3">10000.00</option>
                                                <option value="3">15000.00</option>
                                                <option value="3">99000.00</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-product list-category">
                            <table class="table" id="project-status">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </th>
                                        <th> <span class="f-light f-w-600">Category</span></th>
                                        <th> <span class="f-light f-w-600">Description</span></th>
                                        <th> <span class="f-light f-w-600">Category Type</span></th>
                                        <th> <span class="f-light f-w-600">Action</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/1.png') }}" alt="t-shirt">
                                                </div>
                                                <p>T-Shirts</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Special PriceGet at flat ₹229</p>
                                        </td>
                                        <td> <span class="badge badge-light-primary">Clothing</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"> </use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/category/1.png') }}"
                                                        alt="shoes">
                                                </div>
                                                <p>Shoes</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Up to ₹300, on orders of ₹1750 and above T&C</p>
                                        </td>
                                        <td> <span class="badge badge-light-primary">Footwear</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/category/2.png') }}"
                                                        alt="hand bags">
                                                </div>
                                                <p>Handbags</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Passed 30+ quality checks performed by experts for Comfort &
                                                Design.</p>
                                        </td>
                                        <td> <span class="badge badge-light-secondary">Accessories</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/dashboard-2/product/1.png') }}"
                                                        alt="chairs">
                                                </div>
                                                <p>Chairs</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Passed 30+ quality checks performed by experts for Comfort &
                                                Design.</p>
                                        </td>
                                        <td> <span class="badge badge-light-secondary">Accessories</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/category/3.png') }}"
                                                        alt="toys">
                                                </div>
                                                <p>Toys</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Assembling Shape Educational Toys (Multicolor)</p>
                                        </td>
                                        <td> <span class="badge badge-light-success">Electronic</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/category/4.png') }}"
                                                        alt="slipper">
                                                </div>
                                                <p>Slipper</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Easy to wear and comfortable slip on slippers</p>
                                        </td>
                                        <td> <span class="badge badge-light-primary">Footwear</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/category/5.png') }}"
                                                        alt="camera">
                                                </div>
                                                <p>Cameras</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Our computers and tablets include all the big brands.</p>
                                        </td>
                                        <td> <span class="badge badge-light-success">Electronic</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/2.png') }}" alt="t-shirt">
                                                </div>
                                                <p>T-Shirts</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Special PriceGet at flat ₹100</p>
                                        </td>
                                        <td> <span class="badge badge-light-primary">Clothing</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/ecommerce/product-categories/watch.png') }}"
                                                        alt="watch"></div>
                                                <p>Watches</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Experience luxury and precision with our meticulously
                                                crafted timepieces</p>
                                        </td>
                                        <td> <span class="badge badge-light-success">Electronic</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/12.png') }}" alt="t-shirt">
                                                </div>
                                                <p>T-Shirts</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Special PriceGet at flat ₹300</p>
                                        </td>
                                        <td> <span class="badge badge-light-primary">Clothing</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/category/1.png') }}"
                                                        alt="shoes">
                                                </div>
                                                <p>Shoes</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Up to ₹300, on orders of ₹1750 and above T&C</p>
                                        </td>
                                        <td> <span class="badge badge-light-primary">Footwear</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/category/3.png') }}"
                                                        alt="toys">
                                                </div>
                                                <p>Toys</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Assembling Shape Educational Toys (Multicolor)</p>
                                        </td>
                                        <td> <span class="badge badge-light-success">Electronic</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/category/4.png') }}"
                                                        alt="slipper">
                                                </div>
                                                <p>Slipper</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Easy to wear and comfortable slip on slippers</p>
                                        </td>
                                        <td> <span class="badge badge-light-primary">Footwear</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/category/5.png') }}"
                                                        alt="camera">
                                                </div>
                                                <p>Cameras</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Our computers and tablets include all the big brands.</p>
                                        </td>
                                        <td> <span class="badge badge-light-success">Electronic</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/ecommerce/product-categories/watch.png') }}"
                                                        alt="watch"></div>
                                                <p>Watches</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Experience luxury and precision with our meticulously
                                                crafted timepieces</p>
                                        </td>
                                        <td> <span class="badge badge-light-success">Electronic</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/1.png') }}" alt="t-shirt">
                                                </div>
                                                <p>T-Shirts</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Special PriceGet at flat ₹229</p>
                                        </td>
                                        <td> <span class="badge badge-light-primary">Clothing</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/category/1.png') }}"
                                                        alt="shoes">
                                                </div>
                                                <p>Shoes</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Up to ₹300, on orders of ₹1750 and above T&C</p>
                                        </td>
                                        <td> <span class="badge badge-light-primary">Footwear</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/product/category/2.png') }}"
                                                        alt="hand bags">
                                                </div>
                                                <p>Handbags</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Passed 30+ quality checks performed by experts for Comfort &
                                                Design.</p>
                                        </td>
                                        <td> <span class="badge badge-light-secondary">Accessories</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="product-removes">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-primary" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-names">
                                                <div class="light-product-box"><img class="img-fluid"
                                                        src="{{ asset('assets/images/dashboard-2/product/1.png') }}"
                                                        alt="chairs">
                                                </div>
                                                <p>Chairs</p>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="f-light">Passed 30+ quality checks performed by experts for Comfort &
                                                Design.</p>
                                        </td>
                                        <td> <span class="badge badge-light-secondary">Accessories</span></td>
                                        <td>
                                            <div class="product-action">
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#edit-content') }}">
                                                    </use>
                                                </svg>
                                                <svg>
                                                    <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                                </svg>
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
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <!-- calendar js-->
    <script src="{{ asset('assets/js/js-datatables/simple-datatables@latest.js') }}"></script>
    <script src="{{ asset('assets/js/custom-list-product.js') }}"></script>
    <script src="{{ asset('assets/js/owlcarousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('assets/js/ecommerce.js') }}"></script>
@endsection
