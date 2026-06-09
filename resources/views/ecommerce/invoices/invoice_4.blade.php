@extends('layouts.invoice.master')

@section('css')
@endsection

@section('style')
    <style type="text/css">
        body {
            font-family: 'Montserrat', sans-serif;
            display: block;

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
                        <li class="breadcrumb-item active">Invoice-4</li>
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
                        <table style="width: 100%;">
                            <tbody>
                                <tr style="padding: 28px 0 5px; display: flex; justify-content: space-between;">
                                    <td>
                                        <h4 style="font-size:42px; font-weight: 600; margin:0;">INVOICE</h4>
                                        <ul style="list-style: none; display: flex; gap:15px; padding: 0; margin: 20px 0;">
                                            <li class="invoice-text"><span
                                                    style=" font-size: 18px; font-weight: 600; opacity: 0.8;">Invoice to
                                                    :</span></li>
                                            <li class="invoice-text"><span
                                                    style="font-weight:600;font-size: 18px;  display: block; margin-bottom: 8px;">Brooklyn
                                                    Simmons</span><span
                                                    style="width: 75%; display:block; line-height: 1.5;  font-size: 18px; font-weight: 400;opacity: 0.8;">2972
                                                    Westheimer Rd. Santa Ana, Illinois 85486 </span><span
                                                    style="line-height:1.9;  font-size: 18px; font-weight: 400;opacity: 0.8; display:block;">Phone
                                                    : (219) 555-0114</span><span
                                                    style="line-height:1.7;  font-size: 18px; font-weight: 400;opacity: 0.8; display:block;">Email
                                                    : yourmail@themesforest.com</span><span
                                                    style="line-height:1.7;  font-size: 18px; font-weight: 400;opacity: 0.8; display:block;">Website:
                                                    www.websites.com</span></li>
                                        </ul>
                                    </td>
                                    <td>
                                        <img class="img-fluid for-light"
                                            src="{{ asset($settings['general']['dark_logo']) }}" alt="logo-light">
                                        <img class="img-fluid for-dark" src="{{ asset($settings['general']['light_logo']) }}"
                                            alt="logo-dark">
                                        <span
                                            style="display:block; line-height: 1.5;  font-size: 18px; font-weight: 400;opacity: 0.8;">2118
                                            Thornridge Cir. Syracuse, Connecticut 35624, United State</span><span
                                            style="display:block; line-height: 1.5;  font-size: 18px; font-weight: 400;opacity: 0.8;">Phone
                                            : (239) 555-0108</span><span
                                            style="display:block; line-height: 1.5;  font-size: 18px; font-weight: 400;opacity: 0.8;">Email
                                            : {{ $settings['general']['site_name'] }}@themesforest.com</span><span
                                            style="display:block; line-height: 1.5;  font-size: 18px; font-weight: 400;opacity: 0.8;">Website:
                                            www.{{ $settings['general']['site_name'] }}themes.com</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="width: 100%; border-spacing: 4px; margin-bottom: 20px;">
                            <tbody>
                                <tr>
                                    <td style="padding: 15px 25px;">
                                        <p style="font-size:16px; font-weight:500; opacity:0.8; margin:0;line-height: 2;">
                                            Date :</p><span style="font-size: 18px; font-weight: 600;">10 Mar, 2024</span>
                                    </td>
                                    <td style="padding: 15px 25px;">
                                        <p style="font-size:16px; font-weight:500; opacity:0.8; margin:0;line-height: 2;">
                                            Invoice No :</p><span
                                            style="font-size: 18px; font-weight: 600;">#VL25000365</span>
                                    </td>
                                    <td style="padding: 15px 25px;">
                                        <p style="font-size:16px; font-weight:500; opacity:0.8; margin:0;line-height: 2;">
                                            Account No :</p><span
                                            style="font-size: 18px; font-weight: 600;">0981234098765</span>
                                    </td>
                                    <td style="padding: 15px 25px;">
                                        <p style="font-size:16px; font-weight:500; opacity:0.8; margin:0;line-height: 2;">
                                            Due Amount :</p><span style="font-size: 18px; font-weight: 600;">$7860.00
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="width: 100%; border-spacing:0;">
                            <thead>
                                <tr style="background: #006666;">
                                    <th
                                        style="padding: 18px 15px;text-align: left; position: relative; border-top-left-radius: 10px;">
                                        <span style="color: #fff; font-size: 18px; font-weight: 600;">Description</span>
                                    </th>
                                    <th style="padding: 18px 15px;text-align: center"><span
                                            style="color: #fff; font-size: 18px; font-weight: 600;">Unite Price</span></th>
                                    <th style="padding: 18px 15px;text-align: center"><span
                                            style="color: #fff; font-size: 18px; font-weight: 600;">Quantity</span></th>
                                    <th
                                        style="padding: 18px 15px;text-align: center;position: relative; border-top-right-radius: 10px;">
                                        <span style="color: #fff; font-size: 18px; font-weight: 600;">Subtotal</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding: 30px;">
                                        <h4 style="font-weight:600; margin:4px 0px; font-size: 18px; ">Proposal & Brochure
                                            Design</h4><span style="opacity: 0.8; font-size: 16px;">Regular License</span>
                                    </td>
                                    <td style="width: 12%; text-align: center;"><span style="opacity: 0.8;">$300.00</span>
                                    </td>
                                    <td style="width: 12%; text-align: center;"> <span style="opacity: 0.8;">1</span></td>
                                    <td style="width: 12%; text-align: center;"> <span
                                            style=" font-weight: 600;opacity: 0.9;">$300.00</span></td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 30px 30px;">
                                        <h4 style="font-weight:600; margin:4px 0px; font-size: 18px; ">Web design and
                                            development</h4><span style="opacity: 0.8; font-size: 16px;">Regular
                                            License</span>
                                    </td>
                                    <td style="width: 12%; text-align: center;"><span style="opacity: 0.8;">$400.00</span>
                                    </td>
                                    <td style="width: 12%; text-align: center;"> <span style="opacity: 0.8;">2</span></td>
                                    <td style="width: 12%; text-align: center;"> <span
                                            style=" font-weight: 600;opacity: 0.9;">$800.00</span></td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 30px 30px;">
                                        <h4 style="font-weight:600; margin:4px 0px; font-size: 18px; ">Logo & Brand design
                                        </h4><span style="opacity: 0.8; font-size: 16px;">Regular License</span>
                                    </td>
                                    <td style="width: 12%; text-align: center;"><span style="opacity: 0.8;">$240.00</span>
                                    </td>
                                    <td style="width: 12%; text-align: center;"> <span style="opacity: 0.8;">2</span></td>
                                    <td style="width: 12%; text-align: center;"> <span
                                            style=" font-weight: 600;opacity: 0.9;">$4800.00</span></td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 30px 30px;">
                                        <h4 style="font-weight:600; margin:4px 0px; font-size: 18px; ">Stationary Design
                                        </h4><span style="opacity: 0.8; font-size: 16px;">Regular License</span>
                                    </td>
                                    <td style="width: 12%; text-align: center;"><span style="opacity: 0.8;">$100.00</span>
                                    </td>
                                    <td style="width: 12%; text-align: center;"> <span style="opacity: 0.8;">1</span></td>
                                    <td style="width: 12%; text-align: center;"> <span
                                            style=" font-weight: 600;opacity: 0.9;">$100.00</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td
                        style="height:3px; width: 100%; background: linear-gradient(90deg, #006666 20.61%, #54BA4A 103.6%); display:block;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="width:100%;">
                            <tbody>
                                <tr
                                    style="display:flex; justify-content: space-between; margin:16px 0 24px 0; align-items: end;">
                                    <td style="display: flex; gap: 10px;"><span
                                            style=" font-size: 16px; font-weight: 500; font-weight: 600;">Payment Teams :
                                        </span><span
                                            style=" display:block; line-height: 1.5;  font-size: 16px; font-weight: 400; width: 55%;">This
                                            denotes a payment credit for a full month's supply.</span></td>
                                    <td>
                                        <ul style="padding: 0; margin: 0; list-style: none;">
                                            <li style="display:flex; padding-bottom: 16px;"> <span
                                                    style="display: block; width: 95px; ">Subtotal </span><span
                                                    style="display: block; width:25px;">:</span><span
                                                    style="display: block;  width: 95px;  opacity: 0.9; font-weight:600;">$6100.00</span>
                                            </li>
                                            <li style="display:flex; padding-bottom: 16px;"> <span
                                                    style="display: block; width: 95px; ">Tax</span><span
                                                    style="display: block; width:25px;"> :</span><span
                                                    style="display: block; width: 95px; opacity: 0.9; font-weight:600;">$50.00</span>
                                            </li>
                                            <li style="display:flex; padding-bottom: 20px;"> <span
                                                    style="display: block; width: 95px; ">Discount </span><span
                                                    style="display: block; width:25px;"> :</span><span
                                                    style="display: block; width: 95px; opacity: 0.9; font-weight:600;">$30.00</span>
                                            </li>
                                            <li style="display:flex; align-items: center;"> <span
                                                    style="display: block; width: 95px; ">Total Due</span><span
                                                    style="display: block;  opacity: 0.9; font-weight:600; padding: 12px 25px; border-radius: 5px;  font-size: 18px;">$6120.00</span>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr style="width: 100%; display: flex; justify-content: space-between;">
                    <td> <img src="{{ asset('assets/images/email-template/invoice-3/sign.png') }}" alt="sign"><span
                            style="display: block;font-size: 18px;font-weight: 600;">Laurine T. Ebbert</span><span
                            style=" display: block; font-size: 14px; padding-top: 5px;">( Designer )</span></td>
                    <td> <span style="display: flex; justify-content: end; gap: 15px;"><a
                                style="background: #006666 ; color:rgba(255, 255, 255, 1); border-radius: 10px;padding: 18px 27px;font-size: 16px;font-weight: 600;outline: 0;border: 0; text-decoration: none;"
                                href="#!" onclick="window.print();">Print Invoice<i class="icon-arrow-right"
                                    style="font-size:13px;font-weight:bold; margin-left: 10px;"></i></a><a
                                style="background: rgba(0, 102, 102, 0.2);color: #006666 ; border-radius: 10px;padding: 18px 27px;font-size: 16px;font-weight: 600;outline: 0;border: 0; text-decoration: none;"
                                href="{{ route('admin.invoice_4') }}" download="">Download<i class="icon-arrow-right"
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
