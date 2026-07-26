@extends('layouts.simple.master')

@section('title', 'Property Intelligence')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-head">
        <div>
            <h1>Property Intelligence — {{ $competitor->name }}</h1>
            <p>Permanent competitor property history: new listings, price movement, disappearance signals, listing lifetime, image and description changes.</p>
        </div>
        <div class="vb-toolbar">
            <a href="{{ route('agency.competitor-intelligence.competitors.show', $competitor) }}" class="vb-btn vb-btn-light">← Back to Competitor</a>
        </div>
    </div>

    <div class="vb-grid-4">
        <div class="vb-stat">
            <div class="label">Active Tracked</div>
            <div class="value">{{ $properties->total() }}</div>
            <div class="sub">For {{ $competitor->name }}</div>
        </div>
        <div class="vb-stat">
            <div class="label">New 7 Days</div>
            <div class="value">{{ $properties->where('first_detected_at', '>=', now()->subDays(7))->count() }}</div>
            <div class="sub">Recently discovered</div>
        </div>
        <div class="vb-stat">
            <div class="label">Price Changes</div>
            <div class="value">{{ $properties->where('current_status', 'price_changed')->count() }}</div>
            <div class="sub">Recent price movement</div>
        </div>
        <div class="vb-stat">
            <div class="label">Possibly Removed</div>
            <div class="value">{{ $properties->where('current_status', 'possibly_removed')->count() }}</div>
            <div class="sub">Needs verification</div>
        </div>
    </div>

    <div class="vb-card" style="margin-top:18px">
        <div class="vb-card-head">
            <h2>Tracked Property Inventory</h2>
            <div class="vb-toolbar">
                <input class="vb-input" placeholder="Search title, location, reference...">
                <select class="vb-select">
                    <option>All statuses</option>
                    <option>Active</option>
                    <option>Price changed</option>
                    <option>Possibly removed</option>
                </select>
            </div>
        </div>
        <div class="vb-card-body" style="padding:0">
            @if(isset($properties) && $properties->count() > 0)
            <table class="vb-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Competitor</th>
                        <th>Location</th>
                        <th>Current Price</th>
                        <th>First Seen</th>
                        <th>Last Change</th>
                        <th>Signal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($properties as $property)
                    <tr>
                        <td>
                            <span class="thumb">IMG</span>
                            <b style="margin-left:8px">{{ Str::limit($property->title, 30) }}</b>
                        </td>
                        <td>{{ $property->competitor->name ?? 'Unknown' }}</td>
                        <td>{{ $property->location }}</td>
                        <td>
                            <b>€{{ number_format($property->current_price, 0) }}</b>
                            @if($property->price_change)
                            <div class="small" style="color:{{ $property->price_change < 0 ? '#c83232' : '#108948' }}">
                                {{ $property->price_change > 0 ? '+' : '' }}€{{ number_format($property->price_change, 0) }}
                            </div>
                            @endif
                        </td>
                        <td>{{ $property->first_seen_at->diffForHumans() }}</td>
                        <td>{{ $property->last_change_at ? $property->last_change_at->diffForHumans() : '—' }}</td>
                        <td>
                            @if($property->status == 'new')
                            <span class="badge-soft b-green">NEW</span>
                            @elseif($property->status == 'price_changed')
                            <span class="badge-soft b-red">PRICE DROP</span>
                            @elseif($property->status == 'removed')
                            <span class="badge-soft b-yellow">POSSIBLY REMOVED</span>
                            @elseif($property->status == 'changed')
                            <span class="badge-soft b-blue">CHANGED</span>
                            @endif
                            <a class="vb-link" style="margin-left:8px" href="{{ route('agency.competitor-intelligence.properties.show', [$competitor, $property]) }}">Open</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($properties->hasPages())
            <div style="padding:20px;text-align:center">
                {{ $properties->onEachSide(1)->links('vendor.pagination.competitor') }}
            </div>
            @endif
            @else
            <div style="text-align:center;padding:60px">
                <div style="font-size:48px;color:#ccc;margin-bottom:16px">🏠</div>
                <h3 style="margin:0 0 8px">No properties tracked yet</h3>
                <p class="muted">Properties will appear here once competitors are scanned.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
