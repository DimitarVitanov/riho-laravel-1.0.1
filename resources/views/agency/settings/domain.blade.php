@extends('layouts.simple.master')
@section('title', 'Domain Settings')
@section('breadcrumb-title')<h3>Domain Settings</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency</a></li>
    <li class="breadcrumb-item active">Domain</li>
@endsection
@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">🌐 Your Domain</h5>
            <small class="text-muted">Enter the domain where your Villa Bit AI Server is hosted. This is used to generate your sitemap, SEO pages, and public links.</small>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('agency.settings.domain.update') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Custom Domain</label>
                    <div class="input-group">
                        <span class="input-group-text">https://</span>
                        <input type="text" name="custom_domain" class="form-control @error('custom_domain') is-invalid @enderror"
                            placeholder="yourdomain.com or anyname.yourdomain.com"
                            value="{{ old('custom_domain', $profile->custom_domain ?? '') }}">
                        @error('custom_domain')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted mt-1 d-block">
                        For <strong>Subdomain AI Server</strong>: enter <code>anyname.yourdomain.com</code><br>
                        For <strong>Domain Folder AI Server</strong>: enter <code>yourdomain.com</code>
                    </small>
                </div>

                @if($profile && $profile->custom_domain)
                <div class="alert alert-info mb-3">
                    <strong>Current domain:</strong> {{ $profile->custom_domain }}<br>
                    <strong>Sitemap URL:</strong>
                    <a href="https://{{ $profile->custom_domain }}/sitemap.xml" target="_blank">
                        https://{{ $profile->custom_domain }}/sitemap.xml
                    </a>
                </div>
                @else
                <div class="alert alert-warning mb-3">
                    No domain set yet. Your sitemap URL will be generated once you save your domain.
                </div>
                @endif

                <button type="submit" class="btn btn-dark">Save Domain</button>
            </form>
        </div>
    </div>

</div>
@endsection
