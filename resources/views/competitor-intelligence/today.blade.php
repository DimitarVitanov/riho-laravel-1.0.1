@extends('layouts.simple.master')

@section('title', 'Today')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    @if(session('success'))
    <div style="padding:12px 16px;background:#ebfbf1;color:#108948;border:1px solid #b6e6c8;border-radius:8px;margin-bottom:16px;font-weight:600">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="padding:12px 16px;background:#fff0f0;color:#c83232;border:1px solid #f3c0c0;border-radius:8px;margin-bottom:16px;font-weight:600">{{ session('error') }}</div>
    @endif
    <div class="vb-page-head">
        <div>
            <h1>Today</h1>
            <p>What your competitors changed, published and promoted across their websites and the wider internet — converted into verified facts, AI interpretation, opportunities and actions.</p>
        </div>
        <div class="vb-toolbar">
            <a href="{{ route('agency.competitor-intelligence.reports.index') }}" style="display:inline-flex;align-items:center;padding:10px 14px;background:#202733;color:#fff;border:1px solid #202733;border-radius:7px;font-weight:700;font-size:13px;text-decoration:none">View Daily Report</a>
            <form action="{{ route('agency.competitor-intelligence.scan.run-full') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" style="display:inline-flex;align-items:center;padding:10px 14px;background:#202733;color:#fff;border:none;border-radius:7px;font-weight:700;font-size:13px;cursor:pointer">▶ Run Scan Now</button>
            </form>
        </div>
    </div>

    <div class="vb-grid-4">
        <div class="vb-stat">
            <div class="label">New Properties</div>
            <div class="value">{{ $todayMetrics['new_properties'] }}</div>
            <div class="sub">Across all competitors</div>
        </div>
        <div class="vb-stat">
            <div class="label">Price Changes</div>
            <div class="value">{{ $todayMetrics['price_changes'] }}</div>
            <div class="sub">Reductions & increases</div>
        </div>
        <div class="vb-stat">
            <div class="label">Removed Properties</div>
            <div class="value">{{ $todayMetrics['removed_properties'] }}</div>
            <div class="sub">Pending verification</div>
        </div>
        <div class="vb-stat">
            <div class="label">Total Changes</div>
            <div class="value">{{ $todayMetrics['total_changes'] }}</div>
            <div class="sub">Since yesterday</div>
        </div>
    </div>

    <div class="vb-grid-4" style="margin-top:14px">
        <div class="vb-stat">
            <div class="label">New SEO Pages</div>
            <div class="value">{{ $todayMetrics['new_seo_pages'] ?? 0 }}</div>
            <div class="sub">New indexed landing pages</div>
        </div>
        <div class="vb-stat">
            <div class="label">New Content</div>
            <div class="value">{{ $todayMetrics['new_content'] ?? 0 }}</div>
            <div class="sub">Blog / buyer guide</div>
        </div>
        <div class="vb-stat">
            <div class="label">Review Signals</div>
            <div class="value">{{ $todayMetrics['review_signals'] ?? 0 }}</div>
            <div class="sub">New review count activity</div>
        </div>
        <div class="vb-stat">
            <div class="label">External Mentions</div>
            <div class="value">{{ $todayMetrics['external_mentions'] ?? 0 }}</div>
            <div class="sub">Portals, news, backlinks</div>
        </div>
    </div>

    <div class="vb-grid-3" style="margin-top:18px">
        <div class="vb-card" style="grid-column:span 2">
        <div class="vb-card-head">
            <h2>Intelligence Events by Date</h2>
            <div class="vb-toolbar">
                <select class="vb-select">
                    <option>All competitors</option>
                </select>
                <select class="vb-select">
                    <option>All event types</option>
                    <option>Properties</option>
                    <option>Price</option>
                    <option>SEO</option>
                    <option>Reviews</option>
                </select>
            </div>
        </div>
        <div class="vb-card-body">
            @if($todayEventCount === 0)
            <div class="notice" style="margin-bottom:20px"><b>No competitor changes were found for {{ today()->format('j F Y') }}.</b> Monitoring is active and no verified changes have been detected today.</div>
            @endif
            @php $currentEventDate = null; @endphp
            @forelse($events as $event)
            @php $eventDate = $event->detected_at->toDateString(); @endphp
            @if($currentEventDate !== $eventDate)
                @php $currentEventDate = $eventDate; @endphp
                <h3 style="font-size:14px;margin:20px 0 8px;padding-bottom:10px;border-bottom:1px solid #e2e5ea">
                    {{ $event->detected_at->isToday() ? 'Today' : ($event->detected_at->isYesterday() ? 'Yesterday' : $event->detected_at->format('l, d M Y')) }}
                </h3>
            @endif
            <div class="event">
                <div class="event-top">
                    <div>
                        <span class="badge-soft b-{{ $event->getEventColor() }}">{{ strtoupper($event->getEventTypeLabel()) }}</span>
                        @if($secondaryBadge = $event->getSecondaryBadge())
                        <span class="badge-soft b-{{ $secondaryBadge['color'] }}">{{ $secondaryBadge['label'] }}</span>
                        @endif
                        <span class="small muted">{{ $event->competitor->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="event-meta">{{ $event->detected_at->format('H:i') }}{{ $event->detected_at->isToday() ? ' today' : '' }}</div>
                </div>
                <div class="event-title">{{ $event->getDisplayTitle() }}</div>
                <div class="event-meta">{{ $event->getDescription() }}</div>
                @if($event->ai_interpretation)
                <div class="event-box"><b>AI interpretation:</b> {{ $event->ai_interpretation }}</div>
                @endif
                @if($event->ai_opportunity)
                <div class="event-box"><b>{{ in_array($event->event_type, ['new_url', 'new_seo_page', 'seo_move']) ? 'AI opportunity:' : 'Opportunity:' }}</b> {{ $event->ai_opportunity }}@if($event->ai_action) <b>Recommended action:</b> {{ $event->ai_action }}@endif</div>
                @elseif($event->ai_action)
                <div class="event-box"><b>Recommended action:</b> {{ $event->ai_action }}</div>
                @endif
                <div class="event-actions" style="display:flex;gap:10px;flex-wrap:wrap">
                    <a href="{{ route('agency.competitor-intelligence.events.evidence', $event) }}" style="display:inline-flex;align-items:center;padding:10px 14px;background:#202733;color:#fff;border-radius:7px;font-weight:700;font-size:13px;text-decoration:none">View Evidence</a>
                    @if(in_array($event->event_type, ['price_increase', 'price_decrease']))
                    <a href="{{ route('agency.competitor-intelligence.events.evidence', $event) }}#detected-changes" style="display:inline-flex;align-items:center;padding:10px 14px;background:#202733;color:#fff;border-radius:7px;font-weight:700;font-size:13px;text-decoration:none">AI Action</a>
                    @elseif($event->canCreateBetterPage())
                    <form action="{{ route('agency.competitor-intelligence.events.create-better-page', $event) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" style="display:inline-flex;align-items:center;padding:10px 14px;background:#202733;color:#fff;border:0;border-radius:7px;font-weight:700;font-size:13px;cursor:pointer">
                            {{ $event->created_page_id ? 'Open Better Page' : 'Create Better Page' }} · {{ $event->getSuggestedPageFeatureLabel() }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:60px">
                <div style="font-size:48px;color:#ccc;margin-bottom:16px">📭</div>
                <h3 style="margin:0 0 8px">No competitor intelligence events yet</h3>
                <p class="muted" style="margin-bottom:16px">Monitoring is active. Events will appear here automatically when verified competitor changes are detected.</p>
                <form action="{{ route('agency.competitor-intelligence.scan.run-full') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" style="display:inline-flex;align-items:center;padding:12px 20px;background:#202733;color:#fff;border:none;border-radius:7px;font-weight:700;font-size:14px;cursor:pointer">▶ Run Scan Now</button>
                </form>
            </div>
            @endforelse

            @if($events->hasPages())
            <div style="margin-top:20px;text-align:center">
                {{ $events->onEachSide(1)->links('vendor.pagination.competitor') }}
            </div>
            @endif
        </div>
    </div>

    <div>
        <div class="vb-card">
            <div class="vb-card-head"><h2>Competitor Pulse</h2><span style="display:inline-flex;padding:5px 9px;border-radius:999px;font-weight:700;font-size:10px;background:#ebfbf1;color:#108948">LIVE</span></div>
            <div class="vb-card-body">
                @foreach($competitors->take(3) as $competitor)
                <div style="margin-bottom:18px">
                    <b>{{ $competitor->name }}</b>
                    <div class="small muted">{{ $competitor->events_count ?? 0 }} changes today</div>
                    <div style="height:7px;background:#eef0f2;border-radius:4px;margin-top:8px;overflow:hidden">
                        <div style="width:{{ $maxEvents > 0 ? (($competitor->events_count ?? 0) / $maxEvents * 100) : 0 }}%;background:#252d39;height:7px"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="vb-card" style="margin-top:16px">
            <div class="vb-card-head"><h2>AI Priority Actions</h2></div>
            <div class="vb-card-body">
                @forelse($aiActions ?? [] as $action)
                <div class="notice" style="margin-bottom:10px"><b>{{ $loop->iteration }}. {{ $action['title'] }}</b><br><span class="small">{{ $action['description'] }}</span></div>
                @empty
                <div class="notice"><b>No priority actions</b><br><span class="small">Run a scan to generate AI recommendations.</span></div>
                @endforelse
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
