@extends('layouts.simple.master')
@section('title', 'Integrations')
@section('breadcrumb-title')<h3>Integrations</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency</a></li>
    <li class="breadcrumb-item active">Integrations</li>
@endsection
@section('content')
<div class="container-fluid">

    {{-- XML Sitemap --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">🗺️ XML Sitemap</h5>
            <small class="text-muted">Submit this sitemap to Google Search Console and Bing Webmaster Tools to index all your AI-generated pages.</small>
        </div>
        <div class="card-body">
            @if($profile && $profile->custom_domain)
            @php $sitemapUrl = 'https://' . $profile->custom_domain . '/sitemap.xml'; @endphp
            <div class="d-flex align-items-center gap-3 mb-3">
                <input type="text" class="form-control font-monospace" value="{{ $sitemapUrl }}" readonly id="sitemapUrl">
                <button type="button" class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText('{{ $sitemapUrl }}'); this.textContent='Copied!'; setTimeout(()=>this.textContent='Copy',2000);">Copy</button>
                <a href="{{ $sitemapUrl }}" target="_blank" class="btn btn-outline-primary">View XML</a>
            </div>
            <small class="text-muted">
                Sitemap contains all <strong>published</strong> pages for your agency.
                Total pages: <strong>{{ $pageCount }}</strong>
            </small>
            @elseif($profile)
            <div class="alert alert-warning mb-0">
                Your sitemap URL will appear here once you set your domain in
                <a href="{{ route('agency.settings.domain') }}"><strong>Domain Settings</strong></a>.
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
