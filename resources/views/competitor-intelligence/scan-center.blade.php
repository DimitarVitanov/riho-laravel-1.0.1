@extends('layouts.simple.master')

@section('title', 'Scan Center')

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
            <h1>Scan Center</h1>
            <p>Three-level monitoring keeps scanning efficient: cheap discovery checks, targeted deep scans only when something changes, then AI analysis of verified events.</p>
        </div>
        <form action="{{ route('agency.competitor-intelligence.scan.run-full') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="vb-btn">▶ Run Full Scan</button>
        </form>
    </div>

    <div class="vb-grid-3">
        <div class="vb-card">
            <div class="vb-card-head">
                <h2>Level 1 — Discovery</h2>
                <span class="badge-soft b-green">Every 3 hours</span>
            </div>
            <div class="vb-card-body">
                <p class="muted">Cheap change signals.</p>
                <label class="check"><input type="checkbox" checked disabled> Sitemaps / sitemap indexes</label>
                <label class="check"><input type="checkbox" checked disabled> RSS / feeds</label>
                <label class="check"><input type="checkbox" checked disabled> Homepage</label>
                <label class="check"><input type="checkbox" checked disabled> Important category pages</label>
                <label class="check"><input type="checkbox" checked disabled> Google metrics</label>
            </div>
        </div>

        <div class="vb-card">
            <div class="vb-card-head">
                <h2>Level 2 — Deep Scan</h2>
                <span class="badge-soft b-yellow">On change</span>
            </div>
            <div class="vb-card-body">
                <p class="muted">Only changed or newly discovered entities.</p>
                <label class="check"><input type="checkbox" checked disabled> New/modified URLs</label>
                <label class="check"><input type="checkbox" checked disabled> New properties</label>
                <label class="check"><input type="checkbox" checked disabled> Changed properties</label>
                <label class="check"><input type="checkbox" checked disabled> Removed URL verification</label>
                <label class="check"><input type="checkbox" checked disabled> Snapshot + hash comparison</label>
            </div>
        </div>

        <div class="vb-card">
            <div class="vb-card-head">
                <h2>Level 3 — AI Analysis</h2>
                <span class="badge-soft b-blue">Daily 05:00</span>
            </div>
            <div class="vb-card-body">
                <p class="muted">AI reads events, not thousands of unchanged pages.</p>
                <label class="check"><input type="checkbox" checked disabled> Fact extraction</label>
                <label class="check"><input type="checkbox" checked disabled> Pattern detection</label>
                <label class="check"><input type="checkbox" checked disabled> Opportunity analysis</label>
                <label class="check"><input type="checkbox" checked disabled> Recommended actions</label>
                <label class="check"><input type="checkbox" checked disabled> Daily report generation</label>
            </div>
        </div>
    </div>

    <div class="vb-grid-2" style="margin-top:18px">
        <div class="vb-card">
            <div class="vb-card-head">
                <h2>Run Custom Scan</h2>
            </div>
            <div class="vb-card-body">
                <form action="{{ route('agency.competitor-intelligence.scan.run-custom') }}" method="POST">
                    @csrf
                    <label>Competitors</label>
                    <select class="form-control" name="competitor_id">
                        <option value="">All active competitors</option>
                        @foreach($competitors ?? [] as $competitor)
                        <option value="{{ $competitor->id }}">{{ $competitor->name }}</option>
                        @endforeach
                    </select>

                    <label style="margin-top:14px">Scan types</label>
                    <div class="check-grid">
                        <label class="check"><input type="checkbox" name="scan_types[]" value="new_properties" checked> New properties</label>
                        <label class="check"><input type="checkbox" name="scan_types[]" value="price_changes" checked> Price movement</label>
                        <label class="check"><input type="checkbox" name="scan_types[]" value="removed" checked> Removed properties</label>
                        <label class="check"><input type="checkbox" name="scan_types[]" value="seo_pages" checked> SEO pages</label>
                        <label class="check"><input type="checkbox" name="scan_types[]" value="blog" checked> Blog & content</label>
                        <label class="check"><input type="checkbox" name="scan_types[]" value="reviews" checked> Reviews</label>
                        <label class="check"><input type="checkbox" name="scan_types[]" value="mentions" checked> Internet mentions</label>
                        <label class="check"><input type="checkbox" name="scan_types[]" value="schema" checked> Schema changes</label>
                        <label class="check"><input type="checkbox" name="scan_types[]" value="cta" checked> CTA / page changes</label>
                    </div>

                    <button type="submit" class="vb-btn" style="width:100%;margin-top:18px">▶ Run Selected Scan</button>
                </form>
            </div>
        </div>

        <div class="vb-card">
            <div class="vb-card-head">
                <h2>Queue & Worker Status</h2>
                <span class="badge-soft b-green">HEALTHY</span>
            </div>
            <div class="vb-card-body">
                <table class="vb-table">
                    <tr>
                        <td><b>Discovery queue</b></td>
                        <td>{{ $queueStats['discovery'] ?? 0 }} waiting</td>
                        <td><span class="status-dot dot-green"></span>Running</td>
                    </tr>
                    <tr>
                        <td><b>Deep scan queue</b></td>
                        <td>{{ $queueStats['deep_scan'] ?? 0 }} waiting</td>
                        <td><span class="status-dot dot-green"></span>Running</td>
                    </tr>
                    <tr>
                        <td><b>AI analysis queue</b></td>
                        <td>{{ $queueStats['ai_analysis'] ?? 0 }} waiting</td>
                        <td><span class="status-dot dot-green"></span>Idle</td>
                    </tr>
                    <tr>
                        <td><b>Failed jobs (24h)</b></td>
                        <td>{{ $queueStats['failed'] ?? 0 }}</td>
                        <td><a class="vb-link" href="#">Review</a></td>
                    </tr>
                </table>
                <div class="notice" style="margin-top:14px">
                    <b>Efficiency:</b> {{ $scanStats['urls_monitored'] ?? 0 }} URLs monitored today → {{ $scanStats['urls_scanned'] ?? 0 }} changed/new URLs deep-scanned → {{ $scanStats['events_created'] ?? 0 }} verified events sent to AI.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
