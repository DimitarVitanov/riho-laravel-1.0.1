@extends('layouts.simple.master')

@section('title', 'Property Detail')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    @php
        $snapshot = $property->latestSnapshot;
        $listingUrl = $property->canonical_url ?? $property->url?->url;
    @endphp
    <div class="vb-page-head">
        <div>
            <h1>{{ $property->display_name }}</h1>
            <p>Property tracked from {{ $competitor->name }}{{ $snapshot?->location_text ? ' — ' . $snapshot->location_text : '' }}</p>
        </div>
        <div class="vb-toolbar">
            <a href="{{ route('agency.competitor-intelligence.competitors.properties', $competitor) }}" class="vb-btn vb-btn-light">← Back</a>
            @if($listingUrl)
            <a href="{{ $listingUrl }}" target="_blank" rel="noopener noreferrer" class="vb-btn">Open Listing</a>
            @endif
        </div>
    </div>

    <div class="vb-grid-4">
        <div class="vb-stat">
            <div class="label">Current Price</div>
            <div class="value">{{ $snapshot?->price !== null ? '€' . number_format((float) $snapshot->price, 0) : '—' }}</div>
            <div class="sub">{{ $snapshot?->currency ?? 'EUR' }}</div>
        </div>
        <div class="vb-stat">
            <div class="label">First Seen</div>
            <div class="value">{{ (int) floor($property->display_first_detected_at?->diffInDays(now()) ?? 0) }}d</div>
            <div class="sub">{{ $property->display_first_detected_at?->format('d M Y') ?? '—' }}</div>
        </div>
        <div class="vb-stat">
            <div class="label">Snapshots</div>
            <div class="value">{{ $property->snapshots->count() }}</div>
            <div class="sub">{{ $property->events->count() }} detected events</div>
        </div>
        <div class="vb-stat">
            <div class="label">Status</div>
            <div class="value" style="font-size:18px"><span class="badge-soft b-{{ $property->current_status === 'active' ? 'green' : ($property->current_status === 'possibly_removed' ? 'yellow' : 'red') }}">{{ strtoupper(str_replace('_', ' ', $property->current_status)) }}</span></div>
            <div class="sub">Last seen {{ $property->last_seen_at?->diffForHumans() ?? '—' }}</div>
        </div>
    </div>

    <div class="vb-grid-2" style="margin-top:18px">
        <div class="vb-card">
            <div class="vb-card-head">
                <h2>Property Details</h2>
            </div>
            <div class="vb-card-body">
                <table class="vb-table">
                    <tr><td><b>Title</b></td><td>{{ $property->display_name }}</td></tr>
                    <tr><td><b>Location</b></td><td>{{ $snapshot?->location_text ?? (Str::contains($property->display_name, ' in ') ? Str::afterLast($property->display_name, ' in ') : '—') }}</td></tr>
                    <tr><td><b>Property Type</b></td><td>{{ $snapshot?->property_type ? ucfirst($snapshot->property_type) : '—' }}</td></tr>
                    <tr><td><b>Bedrooms</b></td><td>{{ $snapshot?->bedrooms ?? '—' }}</td></tr>
                    <tr><td><b>Bathrooms</b></td><td>{{ $snapshot?->bathrooms ?? '—' }}</td></tr>
                    <tr><td><b>Size</b></td><td>{{ $snapshot?->surface_m2 ? $snapshot->surface_m2 . ' m²' : '—' }}</td></tr>
                    <tr><td><b>Reference</b></td><td>{{ $property->external_reference ?? '—' }}</td></tr>
                    <tr><td><b>Source URL</b></td><td>@if($listingUrl)<a href="{{ $listingUrl }}" target="_blank" rel="noopener noreferrer" class="vb-link" style="color:#1b5fbf!important">{{ Str::limit($listingUrl, 60) }}</a>@else — @endif</td></tr>
                </table>
            </div>
        </div>

        <div class="vb-card">
            <div class="vb-card-head">
                <h2>Price History</h2>
            </div>
            <div class="vb-card-body">
                @if(isset($priceHistory) && $priceHistory->count() > 0)
                <div class="timeline">
                    @foreach($priceHistory as $priceSnapshot)
                    <div class="timeline-item">
                        <b>{{ $priceSnapshot->captured_at->format('d M Y H:i') }}</b>
                        <div class="small"><span class="change-new">€{{ number_format((float) $priceSnapshot->price, 0) }}</span></div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="muted">No price changes recorded.</p>
                @endif
            </div>
        </div>
    </div>

    @if($snapshot?->description)
    <div class="vb-card" style="margin-top:18px">
        <div class="vb-card-head"><h2>Description</h2></div>
        <div class="vb-card-body"><p>{{ $snapshot->description }}</p></div>
    </div>
    @endif

    <div class="vb-card" style="margin-top:18px">
        <div class="vb-card-head"><h2>Detected Changes & Evidence</h2></div>
        <div class="vb-card-body">
            @forelse($property->events as $event)
            <div class="event" style="margin:0;padding:14px 0;{{ !$loop->last ? 'border-bottom:1px solid #e2e5ea' : '' }}">
                <div class="event-top"><span class="badge-soft b-{{ $event->getEventColor() }}">{{ strtoupper($event->getEventTypeLabel()) }}</span><span class="event-meta">{{ $event->detected_at->format('d M Y H:i') }}</span></div>
                <div class="event-title">{{ $event->getDisplayTitle() }}</div>
                <div class="event-meta">{{ $event->getDescription() }}</div>
                @if($event->ai_interpretation)<div class="event-box"><b>AI interpretation:</b> {{ $event->ai_interpretation }}</div>@endif
                @if($event->ai_opportunity)<div class="event-box"><b>Opportunity:</b> {{ $event->ai_opportunity }}@if($event->ai_action) <b>Recommended action:</b> {{ $event->ai_action }}@endif</div>@endif
            </div>
            @empty
            <p class="muted">No property changes have been detected yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
