@extends('layouts.simple.master')

@section('title', 'Property Intelligence')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-head">
        <div>
            <h1>Property Intelligence{{ isset($competitor) ? ' — ' . $competitor->name : '' }}</h1>
            <p>Tracked competitor listings with current property data, status history, and direct listing access.</p>
        </div>
        <div class="vb-toolbar">
            @if(isset($competitor))
            <a href="{{ route('agency.competitor-intelligence.competitors.show', $competitor) }}" class="vb-btn vb-btn-light">← Back to Competitor</a>
            @endif
            <a href="{{ route('agency.competitor-intelligence.properties.export', array_merge(request()->query(), isset($competitor) ? ['competitor_id' => $competitor->id] : [])) }}" class="vb-btn">Export CSV</a>
        </div>
    </div>

    <div class="vb-grid-4">
        <div class="vb-stat">
            <div class="label">Tracked Listings</div>
            <div class="value">{{ $stats['total'] }}</div>
            <div class="sub">{{ isset($competitor) ? $competitor->name : 'All competitors' }}</div>
        </div>
        <div class="vb-stat">
            <div class="label">New 7 Days</div>
            <div class="value">{{ $stats['new_7d'] }}</div>
            <div class="sub">Recently discovered</div>
        </div>
        <div class="vb-stat">
            <div class="label">Active</div>
            <div class="value">{{ $stats['active'] }}</div>
            <div class="sub">Currently visible listings</div>
        </div>
        <div class="vb-stat">
            <div class="label">Possibly Removed</div>
            <div class="value">{{ $stats['possibly_removed'] }}</div>
            <div class="sub">Needs verification</div>
        </div>
    </div>

    <div class="vb-card" style="margin-top:18px">
        <div class="vb-card-head">
            <h2>Tracked Property Inventory</h2>
            <form method="GET" class="vb-toolbar">
                <input class="vb-input" name="search" value="{{ request('search') }}" placeholder="Search title, location, reference...">
                <select class="vb-select" name="status" onchange="this.form.submit()">
                    <option value="">All statuses</option>
                    @foreach(['active' => 'Active', 'possibly_removed' => 'Possibly removed', 'removed' => 'Removed', 'sold' => 'Sold', 'unlisted' => 'Unlisted'] as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="vb-btn vb-btn-light" type="submit">Filter</button>
            </form>
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
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($properties as $property)
                    <tr>
                        @php
                            $snapshot = $property->latestSnapshot;
                            $listingUrl = $property->canonical_url ?? $property->url?->url;
                        @endphp
                        <td><b>{{ Str::limit($property->display_name, 48) }}</b></td>
                        <td>{{ $property->competitor?->name ?? 'Unknown' }}</td>
                        <td>{{ $snapshot?->location_text ?? (Str::contains($property->display_name, ' in ') ? Str::afterLast($property->display_name, ' in ') : '—') }}</td>
                        <td><b>{{ $snapshot?->price !== null ? '€' . number_format((float) $snapshot->price, 0) : '—' }}</b></td>
                        <td>{{ $property->display_first_detected_at?->diffForHumans() ?? '—' }}</td>
                        <td>{{ $property->last_seen_at?->diffForHumans() ?? '—' }}</td>
                        <td><span class="badge-soft b-{{ $property->current_status === 'active' ? 'green' : ($property->current_status === 'possibly_removed' ? 'yellow' : 'red') }}">{{ strtoupper(str_replace('_', ' ', $property->current_status)) }}</span></td>
                        <td>
                            @if($listingUrl)
                            <a class="vb-link" href="{{ $listingUrl }}" target="_blank" rel="noopener noreferrer" style="color:#1b5fbf!important">Open listing</a>
                            @endif
                            <a class="vb-link" style="margin-left:8px;color:#1b5fbf!important" href="{{ route('agency.competitor-intelligence.properties.show', [$property->competitor, $property]) }}">Details</a>
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
