@extends('layouts.simple.master')

@section('title', 'Reviews & Reputation')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-head">
        <div>
            <h1>Reviews & Reputation</h1>
            <p>Monitor public Google rating movement, review-count changes, and external reputation signals across tracked competitors.</p>
        </div>
        <div class="vb-toolbar">
            <a href="{{ route('agency.competitor-intelligence.competitors.index') }}" class="vb-btn vb-btn-light"><span class="text-dark">Manage Competitors </span></a>
            <form method="POST" action="{{ route('agency.competitor-intelligence.reputation.refresh') }}">
                @csrf
                @if($selectedCompetitorId)<input type="hidden" name="competitor_id" value="{{ $selectedCompetitorId }}">@endif
                <button type="submit" class="vb-btn">Refresh Google Profiles</button>
            </form>
        </div>
    </div>

    @if(session('reputation_error'))
    <div class="notice" style="margin-top:18px;background:#fff7e7;border-color:#f5d497;color:#7a4d00">
        <b>Google profile unavailable.</b> {{ session('reputation_error') }}
    </div>
    @endif

    @if(session('reputation_success'))
    <div class="notice" style="margin-top:18px;background:#effbf3;border-color:#b9e8ca;color:#116832">
        {{ session('reputation_success') }}
    </div>
    @endif

    <form method="GET" class="vb-card" style="margin-top:18px">
        <div class="vb-card-body" style="display:flex;gap:12px;align-items:end;flex-wrap:wrap">
            <div style="min-width:260px">
                <label for="competitor_id">Competitor</label>
                <select id="competitor_id" class="form-control" name="competitor_id">
                    <option value="">All competitors</option>
                    @foreach($competitors as $competitor)
                    <option value="{{ $competitor->id }}" {{ $selectedCompetitorId === $competitor->id ? 'selected' : '' }}>{{ $competitor->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="vb-btn">Apply Filter</button>
        </div>
    </form>

    <div class="vb-grid-4" style="margin-top:18px">
        <div class="vb-stat">
            <div class="label">Google Profiles Tracked</div>
            <div class="value">{{ $stats['profiles'] }}</div>
            <div class="sub">Profiles with a captured metric</div>
        </div>
        <div class="vb-stat">
            <div class="label">Average Rating</div>
            <div class="value">{{ $stats['average_rating'] !== null ? number_format((float) $stats['average_rating'], 1) . ' ★' : '—' }}</div>
            <div class="sub">Across available profiles</div>
        </div>
        <div class="vb-stat">
            <div class="label">Total Reviews</div>
            <div class="value">{{ $stats['review_profiles'] > 0 ? number_format($stats['total_reviews']) : '—' }}</div>
            <div class="sub">{{ $stats['review_profiles'] > 0 ? 'Observed public review count' : 'Review count not exposed' }}</div>
        </div>
        <div class="vb-stat">
            <div class="label">New Review Signals</div>
            <div class="value">{{ $stats['new_review_signals'] }}</div>
            <div class="sub">Detected in the last 30 days</div>
        </div>
    </div>

    <div class="vb-card" style="margin-top:18px">
        <div class="vb-card-head"><h2>Google Business Profiles</h2></div>
        <div class="vb-card-body" style="padding:0">
            @if($profileCompetitors->isNotEmpty())
            <table class="vb-table">
                <thead>
                    <tr>
                        <th>Competitor</th>
                        <th>Business Profile</th>
                        <th>Rating</th>
                        <th>Reviews</th>
                        <th>Last Captured</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profileCompetitors as $competitor)
                    @php
                        $metric = $competitor->latestGoogleMetric;
                        $googleProfileUrl = $competitor->google_place_id
                            ? 'https://www.google.com/maps/place/?q=place_id:' . urlencode($competitor->google_place_id)
                            : $competitor->google_maps_url;
                    @endphp
                    <tr>
                        <td><b>{{ $competitor->name }}</b></td>
                        <td>{{ $metric?->business_name ?? 'No metric captured yet' }}</td>
                        <td>{{ $metric?->rating !== null ? number_format((float) $metric->rating, 1) . ' ★' : '—' }}</td>
                        <td>{{ !$metric ? '—' : ($metric->review_count !== null ? number_format($metric->review_count) : 'Not exposed') }}</td>
                        <td>{{ $metric?->captured_at?->diffForHumans() ?? '—' }}</td>
                        <td>
                            @if($googleProfileUrl)
                            <a class="vb-link" href="{{ $googleProfileUrl }}" target="_blank" rel="noopener noreferrer" style="color:#1b5fbf!important">Open Google Profile</a>
                            @else
                            <a class="vb-link" href="{{ route('agency.competitor-intelligence.competitors.edit', $competitor) }}" style="color:#1b5fbf!important">Add Profile URL</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state"><h3>No active competitors</h3><p>Add a competitor before monitoring public reputation signals.</p></div>
            @endif
        </div>
    </div>

    <div class="vb-grid-2" style="margin-top:18px">
        <div class="vb-card">
            <div class="vb-card-head"><h2>Review & Rating Activity</h2></div>
            <div class="vb-card-body">
                @forelse($reviewEvents as $event)
                <div class="event">
                    <div class="event-top"><span class="badge-soft b-{{ $event->getEventColor() }}">{{ strtoupper($event->getEventTypeLabel()) }}</span><span class="event-meta">{{ $event->detected_at->format('d M Y H:i') }}</span></div>
                    <div class="event-title">{{ $event->competitor?->name }}</div>
                    <div class="event-meta">{{ $event->getDescription() }}</div>
                    @if($event->ai_interpretation)<div class="event-box"><b>AI interpretation:</b> {{ $event->ai_interpretation }}</div>@endif
                    @if($event->ai_opportunity)<div class="event-box"><b>Opportunity:</b> {{ $event->ai_opportunity }}@if($event->ai_action) <b>Recommended action:</b> {{ $event->ai_action }}@endif</div>@endif
                </div>
                @empty
                    @forelse($recentMetrics as $metric)
                    <div class="event">
                        <div class="event-top"><span class="badge-soft b-blue">PROFILE SNAPSHOT</span><span class="event-meta">{{ $metric->captured_at->format('d M Y H:i') }}</span></div>
                        <div class="event-title">{{ $metric->competitor?->name }} baseline captured</div>
                        <div class="event-meta">
                            Rating: {{ $metric->rating !== null ? number_format((float) $metric->rating, 1) . ' ★' : 'Not exposed' }}
                            · Reviews: {{ $metric->review_count !== null ? number_format($metric->review_count) : 'Not exposed' }}
                        </div>
                    </div>
                    @empty
                    <div class="empty-state"><h3>No reputation snapshots yet</h3><p>Configure a Google profile and run Refresh Google Profiles to capture the first baseline.</p></div>
                    @endforelse
                @endforelse
            </div>
            @if($reviewEvents->hasPages())
            <div class="vb-card-body" style="padding-top:0">{{ $reviewEvents->onEachSide(1)->links('vendor.pagination.competitor') }}</div>
            @endif
        </div>

        <div class="vb-card">
            <div class="vb-card-head"><h2>External Mentions</h2></div>
            <div class="vb-card-body">
                @forelse($mentions as $mention)
                <div class="event">
                    <div class="event-top"><span class="badge-soft b-blue">{{ strtoupper(str_replace('_', ' ', $mention->source_type)) }}</span><span class="event-meta">{{ $mention->first_detected_at->format('d M Y H:i') }}</span></div>
                    <div class="event-title">{{ $mention->title ?? $mention->competitor?->name }}</div>
                    @if($mention->snippet)<div class="event-meta">{{ $mention->snippet }}</div>@endif
                    <a class="vb-link" href="{{ $mention->url }}" target="_blank" rel="noopener noreferrer" style="color:#1b5fbf!important">Open source →</a>
                </div>
                @empty
                <div class="empty-state"><h3>No external mentions detected</h3><p>No portal, directory, news, backlink, or public social-source mentions have been discovered for the selected competitors.</p></div>
                @endforelse
            </div>
            @if($mentions->hasPages())
            <div class="vb-card-body" style="padding-top:0">{{ $mentions->onEachSide(1)->links('vendor.pagination.competitor', ['pageName' => 'mentions_page']) }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
