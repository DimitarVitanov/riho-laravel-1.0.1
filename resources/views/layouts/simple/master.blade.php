<!DOCTYPE html>
<html lang="en" @if (Route::currentRouteName() == 'admin.rtl_layout') dir="rtl" @endif>

<head>
    @include('layouts.simple.head')
    @include('layouts.simple.css')
</head>

@switch(Route::currentRouteName())
    @case('admin.box_layout')
        <body class="box-layout">
        @break

    @case('admin.rtl_layout')
        <body class="rtl">
        @break

    @case('admin.dark_layout')
        <body class="dark-only">
        @break

    @default
        <body>
@endswitch
                <!-- loader starts-->
                <div class="loader-wrapper">
                    <div class="loader">
                        <div class="loader4"></div>
                    </div>
                </div>
                <!-- loader ends-->

                <!-- tap on top starts-->
                <div class="tap-top"><i data-feather="chevrons-up"></i></div>
                <!-- tap on tap ends-->

                <!-- page-wrapper Start-->
                <div class="page-wrapper compact-wrapper" id="pageWrapper">

                    <!-- Page header start -->
                    @include('layouts.simple.header')
                    <!-- Page header end-->

                    <!-- Page Body Start-->
                    <div class="page-body-wrapper">

                        <!-- Page sidebar start-->
                        @if(auth()->check())
                            @include('layouts.simple.sidebar-villabit')
                        @else
                            @include('layouts.simple.sidebar')
                        @endif
                        <!-- Page sidebar end-->

                        <div class="page-body">
                                @if(auth()->check() && auth()->user()->role === 'manager' && auth()->user()->managerProfile?->can_view_agency_readonly)
                                    <div style="background:#fef3c7;border:1px solid #fbbf24;border-radius:8px;padding:12px 20px;margin:15px 15px 0;display:flex;align-items:center;gap:10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="#92400e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <span style="color:#92400e;font-weight:600;">You are viewing this panel in <strong>view-only mode</strong>. You cannot submit any data.</span>
                                    </div>
                                @endif
                                @yield('main_content')
                                @yield('content')
                            </div>

                            <!-- footer start-->
                            @include('layouts.simple.footer')
                            <!-- footer end-->
                        </div>
                    </div>
                    <!-- page-wrapper Ends-->

                    {{-- scripts --}}
                    @include('layouts.simple.script')
                    @include('admin.inc.alerts')
                    {{-- end scripts --}}

            </body>

</html>
