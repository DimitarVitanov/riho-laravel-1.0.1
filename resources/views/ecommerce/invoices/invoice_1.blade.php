@extends('layouts.invoice.master')

@section('css')
@endsection

@section('style')
    <style type="text/css">
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .invoice-1.table-wrapper {
            width: 1160px;
            margin: 0 auto;
        }

        .invoice-1 h2 {
            margin: 0;
            font-weight: 500;
            font-size: 40px;
        }

        .invoice-1 h6 {
            font-size: 18px;
            font-weight: 400;
            line-height: 1.5;
            margin: 0;
        }

        .invoice-1 span {
            font-size: 18px;
            line-height: 1.5;
            font-weight: 400;
        }

        .invoice-1 .banner-image {
            margin: 13px 0 10px;
        }

        .invoice-1 .order-details td span {
            margin-bottom: -4px;
            display: block;
        }

        .invoice-1 .order-details th:first-child,
        .order-details td:first-child {
            min-width: 490px;
        }

        .invoice-1 .order-details th:nth-child(2),
        .order-details td:nth-child(2) {
            width: 20%;
        }

        @media (max-width: 1199px) {
            .invoice-1 .table-wrapper {
                width: 930px;
            }

            .invoice-1 .address {
                width: 21% !important;
            }
        }
    </style>
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
                    <h4>Ecommerce</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"> <a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Invoice</li>
                        <li class="breadcrumb-item active">Invoice-1</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts -->
    <div class="container-fluid">
        <table class="table-wrapper invoice-1">
            <tbody>
                <tr>
                    <td>
                        <table class="logo-wrappper" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td>
                                        <img class="img-fluid for-light"
                                            src="{{ asset($settings['general']['dark_logo']) }}" alt="logo-light">
                                        <img class="img-fluid for-dark" src="{{ asset($settings['general']['light_logo']) }}"
                                            alt="logo-dark">
                                        <span style="opacity: 0.8;display:block;margin-top: 10px;">202-555-0258</span>
                                    </td>
                                    <td class="address" style="text-align: right; opacity: 0.8; width: 16%;"><span>
                                            1982 Harvest Lane New York, NY12210
                                            United State</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><img class="banner-image" src="{{ asset('assets/images/email-template/invoice-1/1.png') }}"
                            alt="background">
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="bill-content" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 36%"><span style="opacity: 0.8;">Billed To</span>
                                        <h6 style="width: 46%">{{ $settings['general']['site_name'] }} Matchett Vandelay Group LTD </h6>
                                    </td>
                                    <td style="width: 21%;"> <span style="opacity: 0.8;">Invoice Date</span>
                                        <h6>09/03/2024</h6>
                                    </td>
                                    <td><span style="opacity: 0.8;">Invoice Number</span>
                                        <h6>#VL25000365</h6>
                                    </td>
                                    <td style="text-align: right;"><span style="opacity: 0.8;">Amount Dus (USD)</span>
                                        <h2>$10,908.00</h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 36%">
                                        <h6 style="width: 63%;padding-top: 20px;">2118 Thornridge Cir. Syracuse, Connecticut
                                            35624, United State</h6>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="order-details" style="width: 100%;border-collapse: separate;border-spacing: 0 10px;">
                            <thead>
                                <tr
                                    style="background: #006666 ;border-radius: 8px;overflow: hidden;box-shadow: 0px 10.9412px 10.9412px rgba(82, 77, 141, 0.04), 0px 9.51387px 7.6111px rgba(82, 77, 141, 0.06), 0px 5.05275px 4.0422px rgba(82, 77, 141, 0.0484671);border-radius: 5.47059px;">
                                    <th
                                        style="padding: 18px 15px;border-top-left-radius: 8px;border-bottom-left-radius: 8px;text-align: left">
                                        <span style="color: #fff;">Description</span>
                                    </th>
                                    <th style="padding: 18px 15px;text-align: left"><span style="color: #fff;">Rate</span>
                                    </th>
                                    <th style="padding: 18px 15px;text-align: left"><span style="color: #fff;">Qty</span>
                                    </th>
                                    <th
                                        style="padding: 18px 15px;border-top-right-radius: 8px;border-bottom-right-radius: 8px;text-align: right">
                                        <span style="color: #fff;">Line Total</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    style="box-shadow: 0px 10.9412px 10.9412px rgba(82, 77, 141, 0.04), 0px 9.51387px 7.6111px rgba(82, 77, 141, 0.06), 0px 5.05275px 4.0422px rgba(82, 77, 141, 0.0484671);border-radius: 5.47059px;">
                                    <td style="padding: 18px 15px;display:flex;align-items: center;gap: 10px;"><span
                                            style="min-width: 7px;height: 7px;border: 4px solid #006666;background: #fff;border-radius: 100%;display: inline-block;"></span><span>Project</span>
                                    </td>
                                    <td style="padding: 18px 15px;"><span>$4,000.00</span></td>
                                    <td style="padding: 18px 15px;"> <span>1</span></td>
                                    <td style="padding: 18px 15px;text-align: right"><span>$4,000.00</span></td>
                                </tr>
                                <tr
                                    style="box-shadow: 0px 10.9412px 10.9412px rgba(82, 77, 141, 0.04), 0px 9.51387px 7.6111px rgba(82, 77, 141, 0.06), 0px 5.05275px 4.0422px rgba(82, 77, 141, 0.0484671);border-radius: 5.47059px;">
                                    <td style="padding: 18px 15px;display:flex;align-items: center;gap: 10px;"><span
                                            style="min-width: 7px;height: 7px;border: 4px solid #FE6A49;background: #fff;border-radius: 100%;display: inline-block;"></span><span>Creative
                                            Design</span></td>
                                    <td style="padding: 18px 15px;"> <span>$8,000.00</span></td>
                                    <td style="padding: 18px 15px;"> <span>2</span></td>
                                    <td style="padding: 18px 15px;text-align: right"> <span>$16,000.00</span></td>
                                </tr>
                                <tr
                                    style="box-shadow: 0px 10.9412px 10.9412px rgba(82, 77, 141, 0.04), 0px 9.51387px 7.6111px rgba(82, 77, 141, 0.06), 0px 5.05275px 4.0422px rgba(82, 77, 141, 0.0484671);border-radius: 5.47059px;">
                                    <td style="padding: 18px 15px;display:flex;align-items: center;gap: 10px;"><span
                                            style="min-width: 7px;height: 7px;border: 4px solid #00AC46;background: #fff;border-radius: 100%;display: inline-block;"></span><span>Web
                                            Development</span></td>
                                    <td style="padding: 18px 15px;"> <span>$2,000.00</span></td>
                                    <td style="padding: 18px 15px;"> <span>2 </span></td>
                                    <td style="padding: 18px 15px;text-align: right"> <span>$4,000.00</span></td>
                                </tr>
                                <tr
                                    style="box-shadow: 0px 10.9412px 10.9412px rgba(82, 77, 141, 0.04), 0px 9.51387px 7.6111px rgba(82, 77, 141, 0.06), 0px 5.05275px 4.0422px rgba(82, 77, 141, 0.0484671);border-radius: 5.47059px;">
                                    <td style="padding: 18px 15px;display:flex;align-items: center;gap: 10px;"><span
                                            style="min-width: 7px;height: 7px;border: 4px solid #FFAE1A;background: #fff;border-radius: 100%;display: inline-block;"></span><span>Graphics
                                            Design</span></td>
                                    <td style="padding: 18px 15px;"> <span>$2,000.00</span></td>
                                    <td style="padding: 18px 15px;"> <span>1</span></td>
                                    <td style="padding: 18px 15px;text-align: right"> <span>$2,000.00</span></td>
                                </tr>
                                <tr>
                                    <td> </td>
                                    <td> </td>
                                    <td style="padding: 5px 0; padding-top: 15px;"> <span>Subtotal</span></td>
                                    <td style="padding: 5px 0;text-align: right;padding-top: 15px;"><span>$26,000.00</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td> </td>
                                    <td> </td>
                                    <td style="padding: 5px 0;padding-top: 0;"> <span>Tax(5%)</span></td>
                                    <td style="padding: 5px 0;text-align: right;padding-top: 0;"><span>$1,000.00</span></td>
                                </tr>
                                <tr>
                                    <td> </td>
                                    <td> </td>
                                    <td style="padding: 10px 0;"> <span style="font-weight: 600;">Amount Due (USD)</span>
                                    </td>
                                    <td style="padding: 10px 0;text-align: right"><span
                                            style="font-weight: 600;">$27,000.00</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
            <tr style="width: 100%; display: flex; justify-content: space-between; margin-top: 12px;">
                <td> <img src="{{ asset('assets/images/email-template/invoice-1/sign.png') }}" alt="sign"><span
                        style="display:block;background: rgba(82, 82, 108, 0.3);height: 1px;width: 200px;margin-bottom:10px;"></span><span
                        style="color: rgba(82, 82, 108, 0.8);">Authorized Sign</span></td>
                <td><span style="display: flex; justify-content: end; gap: 15px;"><a
                            style="background: #006666 ; color:rgba(255, 255, 255, 1); border-radius: 10px;padding: 18px 27px;font-size: 16px;font-weight: 600;outline: 0;border: 0; text-decoration: none;"
                            href="#!" onclick="window.print();">Print Invoice<i class="icon-arrow-right"
                                style="font-size:13px;font-weight:bold; margin-left: 10px;"></i></a><a
                            style="background: rgba(0, 102, 102, 0.2);color: #006666 ; border-radius: 10px;padding: 18px 27px;font-size: 16px;font-weight: 600;outline: 0;border: 0; text-decoration: none;"
                            href="{{ route('admin.invoice_1') }}" download="">Download<i class="icon-arrow-right"
                                style="font-size:13px;font-weight:bold; margin-left: 10px;"></i></a></span>
                    <!-- Container-fluid Ends-->
                </td>
            </tr>
        </table>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/counter/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
    <!-- calendar js-->
    <script src="{{ asset('assets/js/print.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
@endsection
