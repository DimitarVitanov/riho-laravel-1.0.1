@extends('layouts.invoice.master')

@section('css')
@endsection

@section('style')
    <style type="text/css">
        body {
            font-family: 'Montserrat', sans-serif;
            display: block;
            color: #000248;
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
                        <li class="breadcrumb-item active">Invoice-3</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts -->
    <div class="container-fluid">
        <table style="width:1160px;margin:0 auto;">
            <tbody>
                <tr>
                    <td>
                        <table
                            style="width: 100%;background-image: url({{ asset('assets/images/email-template/invoice-3/bg-0.png') }});background-position: center;background-size: cover;background-repeat: no-repeat; border-radius: 10px;">
                            <tbody>
                                <tr>
                                    <td style="padding: 30px 0px 30px 30px;">
                                        <img src="{{ asset($settings['general']['dark_logo']) }}"
                                            alt="logo">
                                        {{-- <img src="{{ asset('assets/images/logo/logo_dark.png') }}"
                                            alt="logo"> --}}
                                        <address class="invoice-text"
                                            style="opacity: 0.8; width: 36%; margin-top: 10px; font-style:normal;"><span
                                                style="font-size: 18px; line-height: 1.5; font-weight: 500;">
                                                1982 Harvest Lane New York, NY12210
                                                United State</span></address>
                                    </td>
                                    <td style="text-align:end; padding:30px 30px 30px 0;"><span
                                            style="display:block; line-height: 1.5; font-size:18px; color: #fff; font-weight:700;">Invoice</span><span
                                            style="display:block; line-height: 1.5; font-size:18px; color: #fff; font-weight:500;">Receipt
                                            #1923195</span><span
                                            style="display:block; line-height: 1.5; font-size:18px; color: #fff; font-weight:500;">May
                                            02, 2024</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="width: 100%;">
                            <tbody>
                                <tr style="padding: 28px 0; display: flex; justify-content: space-between;">
                                    <td><span style=" font-size: 16px; font-weight: 500; opacity: 0.8;">CLIENT
                                            DETAILS</span>
                                        <h4 style="font-weight:600; margin: 12px 0 5px 0; font-size: 18px; #006666;">
                                            Brooklyn Simmons</h4><span
                                            style="width: 54%; display:block; line-height: 1.5;  font-size: 18px; font-weight: 400;opacity: 0.8;">2118
                                            Thornridge Cir. Syracuse, Connecticut 35624, United State</span><span
                                            style="line-height:2;  font-size: 18px; font-weight: 400;opacity: 0.8;">Phone :
                                            (239) 555-0108</span>
                                    </td>
                                    <td><span style="font-size: 16px; font-weight: 500;opacity: 0.8;">SHIPPING
                                            ADDRESS</span>
                                        <h4 style="font-weight:600; margin: 12px 0 5px 0; font-size: 18px; #006666;">
                                            Brooklyn Simmons</h4><span
                                            style="display:block; line-height: 1.5;  font-size: 18px; font-weight: 400;opacity: 0.8;">2972
                                            Westheimer Rd. Santa Ana, Illinois 85486 </span><span
                                            style="line-height:2;  font-size: 18px; font-weight: 400;opacity: 0.8;">Phone :
                                            (219) 555-0114</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td> <span
                            style="display:block;background: rgba(82, 82, 108, 0.3);height: 1px;width: 100%;margin-bottom:20px;">
                        </span></td>
                </tr>
                <tr>
                    <td>
                        <table style="width: 100%;border-spacing:0;">
                            <thead>
                                <tr style="background: #006666;">
                                    <th style="padding: 18px 15px;text-align: left"><span
                                            style="color: #fff; font-size: 18px; font-weight: 600;">Description</span></th>
                                    <th style="padding: 18px 15px;text-align: center;border-inline: 3px solid #fff;"><span
                                            style="color: #fff; font-size: 18px; font-weight: 600;">Qty</span></th>
                                    <th style="padding: 18px 15px;text-align: center;border-right: 3px solid #fff;"><span
                                            style="color: #fff; font-size: 18px; font-weight: 600;">Price</span></th>
                                    <th style="padding: 18px 15px;text-align: center"><span
                                            style="color: #fff; font-size: 18px; font-weight: 600;">Subtotal</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td
                                        style="padding: 18px 15px 18px 0;display:flex;align-items: center;gap: 10px; border-bottom:1px solid #52526C4D;">
                                        <span style="width: 3px; height: 37px; background-color:#006666;"></span>
                                        <ul style="padding: 0;margin: 0;list-style: none;">
                                            <li class="invoice-title invoice-text">
                                                <h4 style="font-weight:600; margin:4px 0px; font-size: 18px;">HTML Admin
                                                    template</h4><span style="opacity: 0.8; font-size: 16px;">Regular
                                                    License</span>
                                            </li>
                                        </ul>
                                    </td>
                                    <td
                                        style="padding: 18px 15px; width: 12%; text-align: center; border-bottom:1px solid #52526C4D;">
                                        <span style="opacity: 0.8;">2</span></td>
                                    <td
                                        style="padding: 18px 15px; width: 12%; text-align: center; border-bottom:1px solid #52526C4D;">
                                        <span style="opacity: 0.8;">$35</span></td>
                                    <td
                                        style="padding: 18px 15px; width: 12%; text-align: center; border-bottom:1px solid #52526C4D;">
                                        <span style="opacity: 0.8;">$70</span></td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding: 18px 15px 18px 0;display:flex;align-items: center;gap: 10px; border-bottom:1px solid #52526C4D;">
                                        <span style="width: 3px; height: 37px; background-color:#FFAE46;"></span>
                                        <ul style="padding: 0;margin: 0;list-style: none;">
                                            <li class="invoice-title invoice-text">
                                                <h4 style="font-weight:600; margin:4px 0px; font-size: 18px;">React Admin
                                                    template</h4><span style="opacity: 0.8; font-size: 16px;">Regular
                                                    License</span>
                                            </li>
                                        </ul>
                                    </td>
                                    <td
                                        style="padding: 18px 15px; width: 12%; text-align: center; border-bottom:1px solid #52526C4D;">
                                        <span style="opacity: 0.8;">1</span></td>
                                    <td
                                        style="padding: 18px 15px; width: 12%; text-align: center; border-bottom:1px solid #52526C4D;">
                                        <span style="opacity: 0.8;">$25</span></td>
                                    <td
                                        style="padding: 18px 15px; width: 12%; text-align: center; border-bottom:1px solid #52526C4D;">
                                        <span style="opacity: 0.8;">$50</span></td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding: 18px 15px 18px 0;display:flex;align-items: center;gap: 10px; border-bottom:1px solid #52526C4D;">
                                        <span style="width: 3px; height: 37px; background-color:#54BA4A;"></span>
                                        <ul style="padding: 0;margin: 0;list-style: none;">
                                            <li class="invoice-title invoice-text">
                                                <h4 style="font-weight:600; margin:4px 0px; font-size: 18px;">Laravel Admin
                                                    template</h4><span style="opacity: 0.8; font-size: 16px;">Regular
                                                    License</span>
                                            </li>
                                        </ul>
                                    </td>
                                    <td
                                        style="padding: 18px 15px; width: 12%; text-align: center; border-bottom:1px solid #52526C4D;">
                                        <span style="opacity: 0.8;">2</span></td>
                                    <td
                                        style="padding: 18px 15px; width: 12%; text-align: center; border-bottom:1px solid #52526C4D;">
                                        <span style="opacity: 0.8;">$30</span></td>
                                    <td
                                        style="padding: 18px 15px; width: 12%; text-align: center; border-bottom:1px solid #52526C4D;">
                                        <span style="opacity: 0.8;">$60</span></td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding: 18px 15px 18px 0;display:flex;align-items: center;gap: 10px;border-bottom:1px solid #52526C4D;">
                                        <span style="width: 3px; height: 37px; background-color:#FF3364;"></span>
                                        <ul style="padding: 0;margin: 0;list-style: none;">
                                            <li class="invoice-title invoice-text">
                                                <h4 style="font-weight:600; margin:4px 0px; font-size: 18px;">Vuejs Admin
                                                    template</h4><span style="opacity: 0.8; font-size: 16px;">Regular
                                                    License</span>
                                            </li>
                                        </ul>
                                    </td>
                                    <td
                                        style="padding: 18px 15px; width: 12%; text-align: center; border-bottom:1px solid #52526C4D;">
                                        <span style="opacity: 0.8;">3</span></td>
                                    <td
                                        style="padding: 18px 15px; width: 12%; text-align: center; border-bottom:1px solid #52526C4D;">
                                        <span style="opacity: 0.8;">$20</span></td>
                                    <td
                                        style="padding: 18px 15px; width: 12%; text-align: center; border-bottom:1px solid #52526C4D;">
                                        <span style="opacity: 0.8;">$60</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="width:100%;">
                            <tbody>
                                <tr
                                    style="display:flex; justify-content: space-between; margin:28px 0; align-items: center;">
                                    <td> <span
                                            style=" font-size: 16px; font-weight: 500;opacity: 0.8; font-weight: 600;">BANK
                                            TRANSFER</span>
                                        <h4 style="font-weight:600; margin: 12px 0 5px 0; font-size: 18px; color:#006666;">
                                            Leslie Alexander</h4><span
                                            style=" display:block; line-height: 1.5;  font-size: 18px; font-weight: 400;">Bank
                                            Account : 0981234098765</span><span
                                            style="line-height:1.6;  font-size: 18px; font-weight: 400;">Code :
                                            LEF098756</span>
                                    </td>
                                    <td><span
                                            style="font-size: 16px; font-weight: 500;opacity: 0.8; font-weight: 600;">TOTAL
                                            AMOUNT</span>
                                        <h4 style="font-weight:600; margin: 12px 0 5px 0; font-size: 26px;color:#006666;">
                                            $175.00</h4><span
                                            style=" font-size: 16px; font-weight: 400; line-height: 1.5;">All Taxes
                                            included </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td> <span
                            style="display:block;background: rgba(82, 82, 108, 0.3);height: 1px;width: 100%;margin-bottom:24px;"></span>
                    </td>
                </tr>
                <tr>
                    <td> <span style="display: flex; justify-content: end; gap: 15px;"> <a
                                style="background: #006666 ; color:rgba(255, 255, 255, 1); border-radius: 10px;padding: 18px 27px;font-size: 16px;font-weight: 600;outline: 0;border: 0; text-decoration: none;"
                                href="#!" onclick="window.print();">Print Invoice<i class="icon-arrow-right"
                                    style="font-size:13px;font-weight:bold; margin-left: 10px;"></i></a><a
                                style="background: rgba(0, 102, 102, 0.2);color: #006666 ;  border-radius: 10px;padding: 18px 27px;font-size: 16px;font-weight: 600;outline: 0;border: 0; text-decoration: none;"
                                href="{{ route('admin.invoice_3') }}" download="">Download<i class="icon-arrow-right"
                                    style="font-size:13px;font-weight:bold; margin-left: 10px;"> </i></a></span></td>
                </tr>
            </tbody>
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
