@extends('layouts.simple.master')

@section('title', 'Property Detail')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-head">
        <div>
            <h1>{{ $property->title }}</h1>
            <p>Property tracked from {{ $property->competitor->name ?? 'Unknown' }} — {{ $property->location }}</p>
        </div>
        <div class="vb-toolbar">
            <a href="{{ route('agency.competitor-intelligence.properties.index', $property->competitor) }}" class="vb-btn vb-btn-light">← Back</a>
            @if($property->source_url)
            <a href="{{ $property->source_url }}" target="_blank" class="vb-btn">View Original</a>
            @endif
        </div>
    </div>

    <div class="vb-grid-4">
        <div class="vb-stat">
            <div class="label">Current Price</div>
            <div class="value">€{{ number_format($property->current_price, 0) }}</div>
            @if($property->original_price && $property->original_price != $property->current_price)
            <div class="sub">Originally €{{ number_format($property->original_price, 0) }}</div>
            @endif
        </div>
        <div class="vb-stat">
            <div class="label">First Seen</div>
            <div class="value">{{ $property->first_seen_at->diffInDays() }}d</div>
            <div class="sub">{{ $property->first_seen_at->format('d M Y') }}</div>
        </div>
        <div class="vb-stat">
            <div class="label">Price Changes</div>
            <div class="value">{{ $property->price_changes_count ?? 0 }}</div>
            <div class="sub">Since tracking started</div>
        </div>
        <div class="vb-stat">
            <div class="label">Status</div>
            <div class="value" style="font-size:18px">
                @if($property->status == 'active')
                <span class="badge-soft b-green">ACTIVE</span>
                @elseif($property->status == 'removed')
                <span class="badge-soft b-red">REMOVED</span>
                @else
                <span class="badge-soft b-yellow">{{ strtoupper($property->status) }}</span>
                @endif
            </div>
            <div class="sub">Last checked {{ $property->last_checked_at ? $property->last_checked_at->diffForHumans() : 'Never' }}</div>
        </div>
    </div>

    <div class="vb-grid-2" style="margin-top:18px">
        <div class="vb-card">
            <div class="vb-card-head">
                <h2>Property Details</h2>
            </div>
            <div class="vb-card-body">
                <table class="vb-table">
                    <tr><td><b>Title</b></td><td>{{ $property->title }}</td></tr>
                    <tr><td><b>Location</b></td><td>{{ $property->location }}</td></tr>
                    <tr><td><b>Property Type</b></td><td>{{ $property->property_type ?? 'Unknown' }}</td></tr>
                    <tr><td><b>Bedrooms</b></td><td>{{ $property->bedrooms ?? '—' }}</td></tr>
                    <tr><td><b>Bathrooms</b></td><td>{{ $property->bathrooms ?? '—' }}</td></tr>
                    <tr><td><b>Size</b></td><td>{{ $property->size_sqm ? $property->size_sqm . ' m²' : '—' }}</td></tr>
                    <tr><td><b>Reference</b></td><td>{{ $property->external_id ?? '—' }}</td></tr>
                    <tr><td><b>Source URL</b></td><td><a href="{{ $property->source_url }}" target="_blank" class="vb-link">{{ Str::limit($property->source_url, 50) }}</a></td></tr>
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
                    @foreach($priceHistory as $change)
                    <div class="timeline-item">
                        <b>{{ $change->detected_at->format('d M Y H:i') }}</b>
                        <div class="small">
                            @if($change->old_price)
                            <span class="change-old">€{{ number_format($change->old_price, 0) }}</span>
                            →
                            @endif
                            <span class="change-new">€{{ number_format($change->new_price, 0) }}</span>
                            @if($change->percent_change)
                            ({{ $change->percent_change > 0 ? '+' : '' }}{{ $change->percent_change }}%)
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="muted">No price changes recorded.</p>
                @endif
            </div>
        </div>
    </div>

    @if($property->description)
    <div class="vb-card" style="margin-top:18px">
        <div class="vb-card-head">
            <h2>Description</h2>
        </div>
        <div class="vb-card-body">
            <p>{{ $property->description }}</p>
        </div>
    </div>
    @endif
</div>
@endsection
