@extends('layouts.simple.master')

@section('title', 'Language Settings')

@section('breadcrumb-title')
    <h3>Language Settings</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency</a></li>
    <li class="breadcrumb-item active">Language Settings</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8 col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-header pb-0"><h5>Language Settings</h5></div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('agency.settings.language.update') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">Panel Language</label>
                                    <p class="text-muted small">The language used for the admin panel interface.</p>
                                    <select name="panel_language" class="form-select">
                                        @foreach(\App\Http\Controllers\Agency\AgencySettingsController::supportedPanelLanguages() as $code => $name)
                                            <option value="{{ $code }}" {{ ($user->preferred_language ?? 'en') === $code ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('panel_language')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">AI Content Language</label>
                                    <p class="text-muted small">The language in which AI creates and posts content on your website.</p>
                                    <select name="ai_content_language" class="form-select">
                                        @foreach(\App\Http\Controllers\Agency\AgencySettingsController::supportedAiContentLanguages() as $lang)
                                            <option value="{{ $lang }}" {{ ($profile->ai_content_language ?? 'English') === $lang ? 'selected' : '' }}>{{ $lang }}</option>
                                        @endforeach
                                    </select>
                                    @error('ai_content_language')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Save Language Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
