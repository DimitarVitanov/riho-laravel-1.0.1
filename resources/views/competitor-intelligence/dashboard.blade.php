@extends('layouts.simple.master')

@section('title', 'Competitor Intelligence')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-head">
        <div>
            <h1>Competitor Intelligence — Today</h1>
            <p>What your competitors changed, published and promoted across their websites and the wider internet — converted into verified facts, AI interpretation, opportunities and actions.</p>
        </div>
        <div class="vb-toolbar">
            <a href="{{ route('agency.competitor-intelligence.reports.index') }}" class="vb-btn vb-btn-dark">View Daily Report</a>
            <a href="{{ route('agency.competitor-intelligence.scan-center') }}" class="vb-btn">▶ Run Scan Now</a>
        </div>
    </div>

    <div class="vb-grid-4">
        <div class="vb-stat">
            <div class="label">New Properties</div>
            <div class="value">{{ $todayMetrics['new_properties'] }}</div>
            <div class="sub">Across {{ $competitors->count() }} competitors</div>
        </div>
        <div class="vb-stat">
            <div class="label">Price Changes</div>
            <div class="value">{{ $todayMetrics['price_changes'] }}</div>
            <div class="sub">Reductions & increases</div>
        </div>
        <div class="vb-stat">
            <div class="label">Removed Properties</div>
            <div class="value">{{ $todayMetrics['removed_properties'] }}</div>
            <div class="sub">Pending status verification</div>
        </div>
        <div class="vb-stat">
            <div class="label">Total Changes</div>
            <div class="value">{{ $todayMetrics['total_changes'] }}</div>
            <div class="sub">Since yesterday 05:00</div>
        </div>
    </div>

    <div class="vb-grid-3" style="margin-top:18px">
        <div class="vb-card" style="grid-column:span 2">
            <div class="vb-card-head">
                <h2>Live Intelligence Feed</h2>
                <div class="vb-toolbar">
                    <select class="vb-select">
                        <option>All competitors</option>
                        @foreach($competitors as $comp)
                        <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                        @endforeach
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
                @forelse($recentEvents as $event)
                <div class="event">
                    <div class="event-top">
                        <div>
                            <span class="badge-soft b-{{ $event->getEventColor() }}">{{ strtoupper($event->getEventTypeLabel()) }}</span>
                            <span class="small muted">{{ $event->competitor->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="event-meta">{{ $event->detected_at->format('H:i') }} today</div>
                    </div>
                    <div class="event-title">{{ $event->getEventTypeLabel() }}</div>
                    <div class="event-meta">{{ $event->getDescription() }}</div>
                    @if($event->ai_interpretation)
                    <div class="event-box"><b>AI interpretation:</b> {{ $event->ai_interpretation }}</div>
                    @endif
                    <div class="event-actions">
                        @if($event->evidence_url)
                        <a href="{{ $event->evidence_url }}" target="_blank" class="vb-btn vb-btn-light"><span class="text-dark">View Evidence</span></a>
                        @endif
                        @if($event->ai_opportunity)
                        <button class="vb-btn" onclick="alert('{{ $event->ai_opportunity }}')">AI Action</button>
                        @endif
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:40px">
                    <div style="font-size:48px;color:#ccc;margin-bottom:16px">📭</div>
                    <h3 style="margin:0 0 8px">No events detected today</h3>
                    <p class="muted">Check back later or run a manual scan.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div>
            <div class="vb-card">
                <div class="vb-card-head">
                    <h2>Competitor Pulse</h2>
                    <span class="badge-soft b-green">LIVE</span>
                </div>
                <div class="vb-card-body">
                    @forelse($competitors as $competitor)
                    <div style="margin-bottom:18px">
                        <b>{{ $competitor->name }}</b>
                        <div class="small muted">{{ $competitor->today_events_count ?? 0 }} changes today</div>
                        <div class="progress mt-2">
                            <div class="progress-bar" style="width:{{ min(($competitor->today_events_count ?? 0) * 5, 100) }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="muted">No competitors tracked yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="vb-card" style="margin-top:16px">
                <div class="vb-card-head">
                    <h2>AI Priority Actions</h2>
                </div>
                <div class="vb-card-body">
                    @forelse($aiActions ?? [] as $action)
                    <div class="notice" style="margin-bottom:10px">
                        <b>{{ $loop->iteration }}. {{ $action['title'] }}</b><br>
                        <span class="small">{{ $action['description'] }}</span>
                    </div>
                    @empty
                    <div class="notice">
                        <b>No priority actions</b><br>
                        <span class="small">AI will suggest actions when opportunities are detected.</span>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
