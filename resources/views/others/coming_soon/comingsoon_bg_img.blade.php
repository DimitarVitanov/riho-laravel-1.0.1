@extends('layouts.coming_soon.master')

@section('css')
@endsection

@section('main_content')
    @use('App\Helpers\Helpers')
    @php
        $settings = Helpers::getSettings();
    @endphp
    <div class="container-fluid p-0 m-0">
        <div class="comingsoon comingsoon-bgimg">
            <div class="comingsoon-inner text-center">
                <img src="{{ asset($settings['general']['dark_logo']) }}" alt="looginpage">
                <h5>WE ARE COMING SOON</h5>
                <div class="countdown" id="clock-arrival" data-hours="1" data-minutes="2" data-seconds="3">
                    <ul>
                        <li><span class="days time"></span><span class="title">days</span></li>
                        <li><span class="hours time"></span><span class="title">Hours</span></li>
                        <li><span class="minutes time"></span><span class="title">Minutes</span></li>
                        <li><span class="seconds time"></span><span class="title">Seconds</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
