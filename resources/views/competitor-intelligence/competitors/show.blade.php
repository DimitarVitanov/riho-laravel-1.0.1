@extends('layouts.simple.master')

@section('title', $competitor->name . ' - Digital Twin')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-head">
        <div>
            <h1>{{ $competitor->name }}</h1>
            <p>Competitor Digital Twin — consolidated view of properties, pricing, SEO, content, reviews, external mentions and activity trends.</p>
        </div>
        <div class="vb-toolbar">
            <form action="{{ route('agency.competitor-intelligence.competitors.toggle-status', $competitor) }}" method="POST" style="display:inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="vb-btn vb-btn-light">{{ $competitor->is_active ? 'Pause' : 'Resume' }}</button>
            </form>
            <a href="{{ route('agency.competitor-intelligence.scan-center') }}" class="vb-btn">Run Competitor Scan</a>
        </div>
    </div>

    <div class="vb-grid-4">
        <div class="vb-stat">
            <div class="label">Tracked Properties</div>
            <div class="value">{{ $statistics['properties_count'] ?? 0 }}</div>
            <div class="sub">{{ $statistics['new_properties_30d'] ?? 0 }} discovered in last 30 days</div>
        </div>
        <div class="vb-stat">
            <div class="label">90-Day Disappearances</div>
            <div class="value">{{ $statistics['disappeared_90d'] ?? 0 }}</div>
            <div class="sub">Status confidence varies</div>
        </div>
        <div class="vb-stat">
            <div class="label">Avg. Listing Lifetime</div>
            <div class="value">{{ $statistics['avg_listing_days'] ?? '—' }}d</div>
            <div class="sub">Observed inventory only</div>
        </div>
        <div class="vb-stat">
            <div class="label">Digital Events 30d</div>
            <div class="value">{{ $statistics['events_30d'] ?? 0 }}</div>
            <div class="sub">All monitored sources</div>
        </div>
    </div>

    <div class="vb-grid-3" style="margin-top:18px">
        <div class="vb-card" style="grid-column:span 2">
            <div class="vb-card-head">
                <h2>Activity Timeline</h2>
                <select class="vb-select">
                    <option>Last 30 days</option>
                    <option>90 days</option>
                </select>
            </div>
            <div class="vb-card-body">
                @if(isset($recentEvents) && $recentEvents->count() > 0)
                <div class="timeline">
                    @foreach($recentEvents->take(10) as $event)
                    <div class="timeline-item">
                        <b>{{ $event->detected_at->format('M d H:i') }} — {{ $event->getEventTypeLabel() }}</b>
                        <div class="small muted">{{ $event->getDescription() }}</div>
                        @if($event->evidence_url)
                        <a href="{{ $event->evidence_url }}" target="_blank" style="color:#1b5fbf;font-weight:700;font-size:11px;text-decoration:none">View source →</a>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div style="text-align:center;padding:40px">
                    <p class="muted">No recent activity recorded.</p>
                </div>
                @endif
            </div>
        </div>

        <div class="vb-card">
            <div class="vb-card-head">
                <h2>Digital Twin Coverage</h2>
            </div>
            <div class="vb-card-body">
                <div style="margin-bottom:13px">
                    <b>Website discovery</b>
                    <span class="badge-soft b-green" style="float:right">Healthy</span>
                    <div class="small muted">{{ $competitor->urls_count ?? 0 }} URLs known</div>
                </div>
                <div style="margin-bottom:13px">
                    <b>Property extraction</b>
                    <span class="badge-soft b-green" style="float:right">Healthy</span>
                    <div class="small muted">{{ $competitor->properties_count ?? 0 }} active records</div>
                </div>
                <div style="margin-bottom:13px">
                    <b>Google signals</b>
                    @if($competitor->google_place_id)
                    <span class="badge-soft b-green" style="float:right">Connected</span>
                    <div class="small muted">Place matched</div>
                    @else
                    <span class="badge-soft b-dark" style="float:right">Not set</span>
                    <div class="small muted">Add Google Place ID</div>
                    @endif
                </div>
                <div style="margin-bottom:13px">
                    <b>Internet discovery</b>
                    <span class="badge-soft b-yellow" style="float:right">Partial</span>
                    <div class="small muted">Source adapters enabled</div>
                </div>
                <div>
                    <b>Social sources</b>
                    <span class="badge-soft b-dark" style="float:right">{{ $competitor->social_sources_count ?? 0 }} connected</span>
                    <div class="small muted">Public-source monitoring</div>
                </div>
            </div>
        </div>
    </div>

    <div class="vb-grid-3" style="margin-top:18px">
        <div class="vb-card">
            <div class="vb-card-head">
                <h2>Property Strategy</h2>
            </div>
            <div class="vb-card-body">
                <b>Fastest observed movement</b>
                <p class="muted">{{ $competitor->fastest_segment ?? 'Not enough data' }}</p>
                <b>Slowest observed movement</b>
                <p class="muted">{{ $competitor->slowest_segment ?? 'Not enough data' }}</p>
                <a class="vb-link" href="{{ route('agency.competitor-intelligence.properties.index', $competitor) }}">Open property intelligence →</a>
            </div>
        </div>
        <div class="vb-card">
            <div class="vb-card-head">
                <h2>SEO Strategy</h2>
            </div>
            <div class="vb-card-body">
                <b>New location pages (30d)</b>
                <p class="muted">{{ $competitor->new_seo_pages ?? 'None detected' }}</p>
                <b>Observed focus</b>
                <p class="muted">{{ $competitor->seo_focus ?? 'Analyzing...' }}</p>
                <a class="vb-link" href="#">Open website changes →</a>
            </div>
        </div>
        <div class="vb-card">
            <div class="vb-card-head">
                <h2>Reputation & Mentions</h2>
            </div>
            <div class="vb-card-body">
                <b>Google rating</b>
                <p class="muted">{{ $competitor->google_rating ?? '—' }} · {{ $competitor->google_review_count ?? 0 }} reviews</p>
                <b>External mentions 30d</b>
                <p class="muted">{{ $competitor->external_mentions_30d ?? 0 }} discovered</p>
                <a class="vb-link" href="{{ route('agency.competitor-intelligence.reputation.index', ['competitor_id' => $competitor->id]) }}">Open reputation →</a>
            </div>
        </div>
    </div>

    <div class="vb-card" style="margin-top:18px">
        <div class="vb-card-head">
            <h2>Competitor Settings</h2>
            <a href="{{ route('agency.competitor-intelligence.competitors.edit', $competitor) }}" class="vb-btn vb-btn-light">Edit Settings</a>
        </div>
        <div class="vb-card-body">
            <div class="vb-grid-3">
                <div>
                    <label>Website URL</label>
                    <p>{{ $competitor->website_url }}</p>
                </div>
                <div>
                    <label>Status</label>
                    <p>
                        @if($competitor->is_active)
                        <span class="status-dot dot-green"></span>Active
                        @else
                        <span class="status-dot dot-yellow"></span>Paused
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
