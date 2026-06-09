<!DOCTYPE html>
<html lang="en">
@use('App\Helpers\Helpers')
@php
    $settings = Helpers::getSettings();
@endphp
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="{{ $settings['general']['site_name'] }} admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, {{ $settings['general']['site_name'] }} admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset($settings['general']['favicon']) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset($settings['general']['favicon']) }}" type="image/x-icon">
    <title>{{$settings['general']['site_name']}} - Premium Admin Template</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800&amp;display=swap"
        rel="stylesheet">
    @include('layouts.authentication.css')
</head>

<body>
    @yield('main_content')
    @include('layouts.authentication.scripts')
    @include('admin.inc.alerts')
</body>

</html>
