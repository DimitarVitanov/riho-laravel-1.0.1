@extends('layouts.simple.master')
@section('title', 'Generated Pages')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Generated Pages</h1>
            <p>All AI-generated content pages for your agency across all features.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4" id="generatedPagesTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="local-seo-tab" data-bs-toggle="tab" data-bs-target="#local-seo" type="button" role="tab">
                <i class="fa fa-map-marker me-1"></i> Local SEO
                <span class="badge bg-secondary ms-1">{{ $localSeoPages->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ai-search-tab" data-bs-toggle="tab" data-bs-target="#ai-search" type="button" role="tab">
                <i class="fa fa-search me-1"></i> AI Search Ranking
                <span class="badge bg-secondary ms-1">{{ $aiSearchPages->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="live-urls-tab" data-bs-toggle="tab" data-bs-target="#live-urls" type="button" role="tab">
                <i class="fa fa-link me-1"></i> Live URLs
                <span class="badge bg-secondary ms-1">{{ $liveUrlPages->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="generatedPagesTabContent">
        {{-- Local SEO Tab --}}
        <div class="tab-pane fade show active" id="local-seo" role="tabpanel">
            <div class="vb-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Local SEO Pages</h5>
                    <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}" class="vb-btn vb-btn-sm vb-btn-primary">+ Create Campaign</a>
                </div>
                @if(!$profile || !$profile->server_ip || !$profile->sftp_username || !$profile->sftp_password)
                <div class="alert alert-warning d-flex align-items-center mb-3" style="color:black; background:#fff8e6;border:1px solid #ffc107;border-radius:8px;padding:12px 16px;">
                    <i class="fa fa-exclamation-triangle me-2" style="color:#ffc107;"></i>
                    <div>
                        <strong>SFTP not configured.</strong> Pages will be marked as published but won't be uploaded to your domain. 
                        <a href="{{ route('agency.settings.domain') }}" class="fw-bold smtp-link" style=" color:blue !important;">Configure SFTP settings →</a>
                    </div>
                </div>
                @endif
                <table class="vb-table">
                    <thead>
                        <tr>
                            <th>USE</th>
                            <th>Campaign</th>
                            <th>Market</th>
                            <th>Coverage</th>
                            <th>Uniqueness</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($localSeoPages as $campaign)
                        <tr>
                            <td>
                                <form action="{{ route('agency.local-seo.campaigns.toggle', $campaign) }}" method="POST">
                                    @csrf
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" {{ $campaign->status === 'published' ? 'checked' : '' }} onchange="this.form.submit()">
                                    </div>
                                </form>
                            </td>
                            <td><strong>{{ Str::limit($campaign->name, 35) }}</strong></td>
                            <td>{{ $campaign->primary_city }}</td>
                            <td>{{ $campaign->coverage_area }} {{ $campaign->coverage_unit }}</td>
                            <td>
                                @php
                                    $uClass = match($campaign->content_uniqueness_status) {
                                        'passed' => 'vb-badge-success',
                                        'failed' => 'vb-badge-danger',
                                        'checking' => 'vb-badge-warning',
                                        default => 'vb-badge-info'
                                    };
                                @endphp
                                <span class="vb-badge {{ $uClass }}">{{ strtoupper($campaign->content_uniqueness_status ?? 'draft') }}</span>
                            </td>
                            <td>
                                <span class="vb-badge {{ $campaign->status === 'published' ? 'vb-badge-success' : 'vb-badge-muted' }}">{{ ucfirst($campaign->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('agency.local-seo.campaigns.preview', $campaign) }}" class="vb-btn vb-btn-sm" target="_blank">Preview</a>
                                <a href="{{ route('agency.features.show', ['feature' => 'local_seo_presence_boost', 'edit' => $campaign->id]) }}" class="vb-btn vb-btn-sm vb-btn-dark">Edit</a>
                                <form action="{{ route('agency.local-seo.campaigns.toggle', $campaign) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="vb-btn vb-btn-sm {{ $campaign->status === 'published' ? 'vb-btn-warning' : 'vb-btn-success' }}">
                                        {{ $campaign->status === 'published' ? 'Unpublish' : 'Publish' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="vb-empty">
                                    <h3>No Local SEO pages yet</h3>
                                    <p>Create a Local SEO campaign to generate pages.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- AI Search Ranking Tab --}}
        <div class="tab-pane fade" id="ai-search" role="tabpanel">
            <div class="vb-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">AI Search Ranking Pages</h5>
                    <a href="{{ route('agency.features.show', 'ai_search_ranking') }}" class="vb-btn vb-btn-sm vb-btn-primary">+ Create Page</a>
                </div>
                <table class="vb-table">
                    <thead>
                        <tr>
                            <th>USE</th>
                            <th>Page Name</th>
                            <th>Property/Listing</th>
                            <th>Location</th>
                            <th>Uniqueness</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aiSearchPages as $page)
                        <tr>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" {{ $page->status === 'published' ? 'checked' : '' }} disabled>
                                </div>
                            </td>
                            <td><strong>{{ Str::limit($page->name, 35) }}</strong></td>
                            <td>{{ $page->listing->title ?? '—' }}</td>
                            <td>{{ $page->target_city ?? $page->target_neighborhood ?? '—' }}</td>
                            <td>
                                <span class="vb-badge vb-badge-info">{{ strtoupper($page->status ?? 'draft') }}</span>
                            </td>
                            <td>
                                <span class="vb-badge {{ $page->status === 'published' ? 'vb-badge-success' : 'vb-badge-muted' }}">{{ ucfirst($page->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('agency.ai-search-ranking.preview', $page) }}" class="vb-btn vb-btn-sm" target="_blank">Preview</a>
                                <a href="{{ route('agency.features.show', ['feature' => 'ai_search_ranking', 'edit_page_id' => $page->id]) }}" class="vb-btn vb-btn-sm vb-btn-dark">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="vb-empty">
                                    <h3>No AI Search Ranking pages yet</h3>
                                    <p>Create an AI Search Ranking page to optimize for AI search engines.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Live URLs Tab --}}
        <div class="tab-pane fade" id="live-urls" role="tabpanel">
            <div class="vb-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Live URL Pages</h5>
                    <a href="{{ route('agency.features.show', 'live_url_optimizer') }}" class="vb-btn vb-btn-sm vb-btn-primary">+ Add Live URL</a>
                </div>
                <table class="vb-table">
                    <thead>
                        <tr>
                            <th>USE</th>
                            <th>Page Title</th>
                            <th>URL</th>
                            <th>Uniqueness</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($liveUrlPages as $page)
                        <tr>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" {{ $page->status === 'published' ? 'checked' : '' }} disabled>
                                </div>
                            </td>
                            <td><strong>{{ Str::limit($page->title ?? $page->name, 35) }}</strong></td>
                            <td><a href="{{ $page->url }}" target="_blank" class="text-muted small">{{ Str::limit($page->url, 40) }}</a></td>
                            <td>
                                @php
                                    $uClass = match($page->content_uniqueness_status ?? 'draft') {
                                        'passed' => 'vb-badge-success',
                                        'failed' => 'vb-badge-danger',
                                        'checking' => 'vb-badge-warning',
                                        default => 'vb-badge-info'
                                    };
                                @endphp
                                <span class="vb-badge {{ $uClass }}">{{ strtoupper($page->content_uniqueness_status ?? 'draft') }}</span>
                            </td>
                            <td>
                                <span class="vb-badge {{ $page->status === 'published' ? 'vb-badge-success' : 'vb-badge-muted' }}">{{ ucfirst($page->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('agency.live-urls.preview', $page) }}" class="vb-btn vb-btn-sm" target="_blank">Preview</a>
                                <a href="{{ route('agency.features.show', ['feature' => 'live_url_optimizer', 'edit' => $page->id]) }}" class="vb-btn vb-btn-sm vb-btn-dark">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="vb-empty">
                                    <h3>No Live URL pages yet</h3>
                                    <p>Add a live URL to optimize existing pages.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .smtp-link{
        text-decoration:underline;
    }
    .smtp-link:hover{
        text-decoration:none;
    }
</style>