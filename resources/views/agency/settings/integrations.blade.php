@extends('layouts.simple.master')
@section('title', 'Integrations')
@section('breadcrumb-title')<h3>Integrations</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency</a></li>
    <li class="breadcrumb-item active">Integrations</li>
@endsection
@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Copyscape --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">🔍 Copyscape — Content Uniqueness Check</h5>
            <small class="text-muted">Enter your own Copyscape credentials to use your account for uniqueness checking on your generated content.</small>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('agency.settings.integrations.update') }}">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-5">
                        <label class="form-label">Copyscape Username</label>
                        <input type="text" name="copyscape_username" class="form-control"
                               value="{{ $profile->copyscape_username ?? '' }}"
                               placeholder="your_copyscape_username">
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Copyscape API Key</label>
                        <input type="text" name="copyscape_api_key" class="form-control font-monospace"
                               value="{{ $profile->copyscape_api_key ?? '' }}"
                               placeholder="Your Copyscape API key">
                        <small class="text-muted">Get your API key at <a href="https://www.copyscape.com/api/" target="_blank">copyscape.com/api</a></small>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save Copyscape Settings</button>
                    @if($profile && $profile->copyscape_api_key)
                        <span class="badge bg-success ms-2">✓ Copyscape configured</span>
                    @else
                        <span class="badge bg-secondary ms-2">Using system-wide key</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- XML Sitemap --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">🗺️ XML Sitemap</h5>
            <small class="text-muted">Submit this sitemap to Google Search Console and Bing Webmaster Tools to index all your AI-generated pages.</small>
        </div>
        <div class="card-body">
            @if($profile)
            @php $sitemapUrl = route('agency.sitemap', ['agencyId' => $profile->id]); @endphp
            <div class="d-flex align-items-center gap-3 mb-3">
                <input type="text" class="form-control font-monospace" value="{{ $sitemapUrl }}" readonly id="sitemapUrl">
                <button type="button" class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText('{{ $sitemapUrl }}'); this.textContent='Copied!'; setTimeout(()=>this.textContent='Copy',2000);">Copy</button>
                <a href="{{ $sitemapUrl }}" target="_blank" class="btn btn-outline-primary">View XML</a>
            </div>
            <small class="text-muted">
                Sitemap contains all <strong>published</strong> pages for your agency.
                Total pages: <strong>{{ $pageCount }}</strong>
            </small>
            @endif
        </div>
    </div>

</div>
@endsection
