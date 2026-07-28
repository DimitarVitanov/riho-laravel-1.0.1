@extends('layouts.simple.master')

@section('title', 'Tracked Competitors')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-head">
        <div>
            <h1>Tracked Competitors</h1>
            <p>Add agencies once. Villa Bit AI continuously discovers their properties, website changes, SEO pages, reviews and external activity.</p>
        </div>
        <a href="{{ route('agency.competitor-intelligence.competitors.create') }}" class="vb-btn">＋ Add Competitor</a>
    </div>

    <div class="vb-card">
        <div class="vb-card-head">
            <h2>Competitors ({{ $competitors->count() }})</h2>
            <div class="vb-toolbar">
                <input class="vb-input" placeholder="Search competitor...">
                <select class="vb-select">
                    <option>All statuses</option>
                    <option>Active</option>
                    <option>Paused</option>
                </select>
            </div>
        </div>
        <div class="vb-card-body" style="padding:0">
            @if($competitors->isEmpty())
            <div style="text-align:center;padding:60px 20px">
                <div style="font-size:48px;color:#ccc;margin-bottom:16px">👥</div>
                <h3 style="margin:0 0 8px;font-weight:800">No competitors tracked yet</h3>
                <p class="muted" style="margin:0 0 20px">Start tracking your competitors to get intelligence on their properties and digital activities.</p>
                <a href="{{ route('agency.competitor-intelligence.competitors.create') }}" class="vb-btn">＋ Add Your First Competitor</a>
            </div>
            @else
            <table class="vb-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Website</th>
                        <th>Sources</th>
                        <th>Last Scan</th>
                        <th>Today</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($competitors as $competitor)
                    <tr>
                        <td>
                            <b>{{ $competitor->name }}</b>
                        </td>
                        <td>{{ $competitor->normalized_domain }}</td>
                        <td>
                            <span class="badge-soft b-dark">Website</span>
                            @if($competitor->google_maps_url)
                            <span class="badge-soft b-blue">Google</span>
                            @endif
                        </td>
                        <td>{{ $competitor->last_scan_at ? $competitor->last_scan_at->diffForHumans() : 'Never' }}</td>
                        <td><b>{{ $competitor->today_events_count ?? 0 }} events</b></td>
                        <td>
                            @if($competitor->is_active)
                            <span class="status-dot dot-green"></span>Active
                            @else
                            <span class="status-dot dot-yellow"></span>Paused
                            @endif
                        </td>
                        <td>
                            <a class="vb-link text-dark btn btn-light" href="{{ route('agency.competitor-intelligence.competitors.show', $competitor) }}"><span class="text-dark">Open</span></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection
