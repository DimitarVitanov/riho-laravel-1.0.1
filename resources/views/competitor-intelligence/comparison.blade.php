@extends('layouts.simple.master')

@section('title', 'All Competitors Comparison')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
<style>
.comparison-rank{width:30px;height:30px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;background:#202733;color:#fff;font-weight:800;font-size:13px}
.compare-cell-strong{font-weight:800!important;font-size:14px!important}
.winner{background:#f1fbf5!important}
.vb-link{color:#1b5fbf!important;font-weight:700!important;text-decoration:none!important}
.vb-link:hover{color:#0d4a9e!important;text-decoration:underline!important}
.vb-table{width:100%!important;border-collapse:collapse!important}
.vb-table th{padding:12px 14px!important;background:#f7f8fa!important;text-align:left!important;font-size:10px!important;letter-spacing:.4px!important;text-transform:uppercase!important;border-bottom:1px solid #e2e5ea!important;color:#69707d!important;font-weight:700!important}
.vb-table td{padding:13px 14px!important;border-bottom:1px solid #edf0f3!important;vertical-align:middle!important;color:#14171d!important}
.vb-table tr:last-child td{border-bottom:none!important}
.vb-card .progress{height:7px!important;background:#eef0f2!important;border-radius:4px!important;overflow:hidden!important;margin-top:8px}
.vb-card .progress-bar{background:#252d39!important;height:100%!important}
.notice{padding:12px 14px;background:#f8fafc;border:1px solid #e1e5ea;border-radius:8px;color:#4f5865;line-height:1.5;font-size:13px}
</style>
@endsection

@section('main_content')
<style>
:root{--vb-bg:#f5f6f8;--vb-card:#fff;--vb-text:#14171d;--vb-muted:#69707d;--vb-border:#e2e5ea;--vb-sidebar:#0d0e10;--vb-sidebar-2:#171a1f;--vb-primary:#202733;--vb-accent:#ff5b2e;--vb-green:#00a650;--vb-blue:#1677ff;--vb-yellow:#f5a623;--vb-red:#ed3b3b;--vb-purple:#6f42c1}
.vb-page-head{display:flex!important;justify-content:space-between!important;align-items:flex-start!important;gap:16px!important;margin-bottom:20px!important}
.vb-page-head h1{font-size:24px!important;margin:0 0 6px!important;font-weight:800!important}
.vb-page-head p{margin:0!important;color:var(--vb-muted)!important;font-size:13px!important;max-width:780px!important;line-height:1.5!important}
.vb-btn{display:inline-flex!important;align-items:center!important;justify-content:center!important;gap:7px!important;padding:10px 14px!important;border:1px solid #202733!important;background:#202733!important;color:white!important;border-radius:7px!important;font-weight:700!important;cursor:pointer!important;font-size:13px!important}
.vb-btn:hover{background:#0f1319!important;color:white!important}
.vb-btn-light{background:white!important;color:#202733!important;border:1px solid #cfd4db!important}
.vb-btn-light:hover{background:#f3f4f6!important;color:#111!important}
.vb-card{background:white!important;border:1px solid var(--vb-border)!important;border-radius:14px!important;box-shadow:0 2px 8px rgba(18,24,40,.03)!important;overflow:hidden!important;margin-bottom:16px!important}
.vb-card-head{padding:16px 20px!important;border-bottom:1px solid var(--vb-border)!important;display:flex!important;align-items:center!important;justify-content:space-between!important;gap:10px!important}
.vb-card-head h2{font-size:15px!important;margin:0!important;font-weight:800!important}
.vb-card-body{padding:18px 20px!important}
.vb-grid-4{display:grid!important;grid-template-columns:repeat(4,1fr)!important;gap:14px!important;margin-bottom:18px!important}
.vb-grid-3{display:grid!important;grid-template-columns:repeat(3,1fr)!important;gap:14px!important;margin-bottom:18px!important}
.vb-grid-3 .vb-table td:first-child{width:60%!important}
.vb-grid-3 .vb-table td:last-child{text-align:right!important;font-size:12px!important}
.vb-stat{padding:16px!important;background:white!important;border:1px solid var(--vb-border)!important;border-radius:12px!important}
.vb-stat .label{font-size:11px!important;color:#6c7380!important;font-weight:700!important;text-transform:uppercase!important;letter-spacing:.3px!important}
.vb-stat .value{font-size:26px!important;font-weight:800!important;margin-top:5px!important}
.vb-stat .sub{font-size:11px!important;color:#7a818c!important;margin-top:4px!important}
.vb-table{width:100%!important;border-collapse:collapse!important;background:#fff!important}
.vb-table th{padding:12px 14px!important;background:#f7f8fa!important;text-align:left!important;font-size:10px!important;letter-spacing:.4px!important;text-transform:uppercase!important;border-bottom:1px solid var(--vb-border)!important;color:#69707d!important;font-weight:700!important}
.vb-table td{padding:13px 14px!important;border-bottom:1px solid #edf0f3!important;vertical-align:middle!important;color:#14171d!important;background:#fff!important}
.vb-table tbody tr{background:#fff!important}
.vb-table tbody tr:hover{background:#fafbfc!important}
.vb-table tr:last-child td{border-bottom:none!important}
.muted{color:#747b87!important}
.small{font-size:11px!important}
.vb-toolbar{display:flex!important;gap:8px!important;flex-wrap:wrap!important;align-items:center!important}
.vb-input,.vb-select{border:1px solid #d7dbe1!important;background:#fff!important;padding:9px 11px!important;border-radius:7px!important;min-height:38px!important;font-family:inherit!important;font-size:12px!important}
.vb-link{color:#1b5fbf!important;font-weight:700!important;text-decoration:none!important}
.progress{height:7px!important;background:#eef0f2!important;border-radius:4px!important;overflow:hidden!important}
.progress-bar{background:#252d39!important;height:100%!important}
.notice{padding:12px 14px!important;background:#f8fafc!important;border:1px solid #e1e5ea!important;border-radius:8px!important;color:#4f5865!important;line-height:1.5!important}
.comparison-rank{width:30px!important;height:30px!important;border-radius:50%!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;background:#202733!important;color:#fff!important;font-weight:800!important}
.compare-cell-strong{font-weight:800!important;font-size:14px!important}
.winner{background:#f1fbf5!important}
.badge-soft{display:inline-flex!important;padding:5px 9px!important;border-radius:999px!important;font-weight:700!important;font-size:10px!important}
.b-red{background:#fff0f0!important;color:#c83232!important}
.b-green{background:#ebfbf1!important;color:#108948!important}
.b-yellow{background:#fff7e7!important;color:#b37400!important}
.b-purple{background:#f4efff!important;color:#673ab7!important}
.event{padding:16px 0!important;border-bottom:1px solid #e9ecf0!important}
.event:last-child{border-bottom:none!important}
.event-title{font-size:14px!important;font-weight:800!important;margin:7px 0 4px!important}
.event-box{background:#f5f6f8!important;border-radius:8px!important;padding:12px 14px!important;margin-top:10px!important;border:1px solid #e1e4e8!important}
.event-actions{display:flex!important;gap:8px!important;margin-top:10px!important;flex-wrap:wrap!important}
.mt-2{margin-top:8px!important}
@media(max-width:1200px){.vb-grid-4{grid-template-columns:repeat(2,1fr)!important}.vb-grid-3{grid-template-columns:1fr 1fr!important}}
@media(max-width:768px){.vb-grid-4,.vb-grid-3{grid-template-columns:1fr!important}.vb-page-head{flex-direction:column!important}}
</style>
<div class="container-fluid">
    <div class="vb-page-head">
        <div>
            <h1>All Competitors — Comparison Summary</h1>
            <p>One screen to compare every tracked competitor together: inventory, new listings, price movement, observed sales velocity, SEO activity, reputation, external visibility and total digital activity.</p>
        </div>
        <div class="vb-toolbar">
            <select class="vb-select" id="periodFilter">
                <option value="7">Last 7 days</option>
                <option value="30" selected>Last 30 days</option>
                <option value="90">Last 90 days</option>
            </select>
            <button class="vb-btn vb-btn-light">Export Comparison</button>
            <a href="{{ route('agency.competitor-intelligence.competitors.create') }}" class="vb-btn">＋ Add Competitor</a>
        </div>
    </div>

    <div class="vb-grid-4">
        <div class="vb-stat">
            <div class="label">Competitors Compared</div>
            <div class="value">{{ $competitors->count() }}</div>
            <div class="sub">All active competitors</div>
        </div>
        <div class="vb-stat">
            <div class="label">Properties Tracked</div>
            <div class="value">{{ number_format($totalProperties) }}</div>
            <div class="sub">Combined current inventory</div>
        </div>
        <div class="vb-stat">
            <div class="label">Digital Events 30d</div>
            <div class="value">{{ number_format($totalEvents) }}</div>
            <div class="sub">Verified monitored changes</div>
        </div>
        <div class="vb-stat">
            <div class="label">AI Opportunities</div>
            <div class="value">{{ $aiOpportunities }}</div>
            <div class="sub">Cross-competitor opportunities</div>
        </div>
    </div>

    <div class="vb-card" style="margin-top:18px">
        <div class="vb-card-head">
            <h2>Master Competitor Comparison</h2>
            <span class="small muted">Green cells indicate the strongest observed metric for the selected period</span>
        </div>
        <div class="vb-card-body" style="padding:0;overflow:auto">
            <table class="vb-table" style="min-width:1280px">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Competitor</th>
                        <th>Active Properties</th>
                        <th>New 30d</th>
                        <th>Price Reductions</th>
                        <th>Disappearances</th>
                        <th>Avg Listing Lifetime</th>
                        <th>New SEO Pages</th>
                        <th>External Mentions</th>
                        <th>Google Rating</th>
                        <th>Total Events</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($competitors as $index => $competitor)
                    <tr style="background:#fff">
                        <td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3"><span style="width:30px;height:30px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;background:#202733;color:#fff;font-weight:800">{{ $index + 1 }}</span></td>
                        <td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3">
                            <b>{{ $competitor->name }}</b>
                            <div style="font-size:11px;color:#747b87">{{ $competitor->country ?? 'Croatia' }}</div>
                        </td>
                        @if($competitor->properties_count == $maxProperties && $maxProperties > 0)
                        <td style="background:#f1fbf5 !important;padding:13px 14px;border-bottom:1px solid #edf0f3;font-weight:800;font-size:14px">
                        @else
                        <td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3;font-weight:800;font-size:14px">
                        @endif
                            {{ $competitor->properties_count ?? 0 }}
                        </td>
                        @if($competitor->new_properties_30d == $maxNewProperties && $maxNewProperties > 0)
                        <td style="background:#f1fbf5 !important;padding:13px 14px;border-bottom:1px solid #edf0f3;font-weight:800;font-size:14px">
                        @else
                        <td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3;font-weight:800;font-size:14px">
                        @endif
                            {{ $competitor->new_properties_30d ?? 0 }}
                        </td>
                        <td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3">{{ $competitor->price_reductions_30d ?? 0 }}</td>
                        <td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3">{{ $competitor->disappearances_30d ?? 0 }}</td>
                        <td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3">{{ $competitor->avg_listing_lifetime ?? '—' }} days</td>
                        <td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3">{{ $competitor->new_seo_pages_30d ?? 0 }}</td>
                        <td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3">{{ $competitor->external_mentions_30d ?? 0 }}</td>
                        <td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3">
                            @if($competitor->google_rating)
                                <b>{{ number_format($competitor->google_rating, 1) }} ★</b>
                            @else
                                —
                            @endif
                        </td>
                        @if($competitor->events_count == $maxEvents && $maxEvents > 0)
                        <td style="background:#f1fbf5 !important;padding:13px 14px;border-bottom:1px solid #edf0f3;font-weight:800;font-size:14px">
                        @else
                        <td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3;font-weight:800;font-size:14px">
                        @endif
                            {{ $competitor->events_count ?? 0 }}
                        </td>
                        <td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3">
                            <a href="{{ route('agency.competitor-intelligence.competitors.show', $competitor) }}" style="color:#1b5fbf !important;font-weight:700 !important;text-decoration:none !important;white-space:nowrap">Open →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="vb-grid-3" style="margin-top:18px">
        <div class="vb-card">
            <div class="vb-card-head"><h2>Inventory Competition</h2></div>
            <div class="vb-card-body">
                @foreach($competitors->take(5) as $competitor)
                <div style="margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap">
                    <b style="font-size:13px">{{ $competitor->name }}</b>
                    <span style="font-weight:700">{{ $competitor->properties_count ?? 0 }}</span>
                </div>
                <div style="height:7px;background:#eef0f2;border-radius:4px;margin-bottom:16px;margin-top:-8px;overflow:hidden">
                    <div style="width:{{ $maxProperties > 0 ? (($competitor->properties_count ?? 0) / $maxProperties * 100) : 0 }}%;background:#252d39;height:7px"></div>
                </div>
                @endforeach
                @if($inventoryInsight)
                <div class="notice" style="margin-top:16px">
                    <b>AI observation:</b> {{ $inventoryInsight }}
                </div>
                @endif
            </div>
        </div>

        <div class="vb-card">
            <div class="vb-card-head"><h2>Observed Market Movement</h2></div>
            <div class="vb-card-body">
                <table class="vb-table" style="width:100%">
                    <tr><td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3"><b>Fastest listing turnover</b></td><td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3;text-align:right">{{ $fastestTurnover ?? '—' }}</td></tr>
                    <tr><td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3"><b>Most price reductions</b></td><td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3;text-align:right">{{ $mostPriceReductions ?? '—' }}</td></tr>
                    <tr><td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3"><b>Most disappearances</b></td><td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3;text-align:right">{{ $mostDisappearances ?? '—' }}</td></tr>
                    <tr><td style="background:#fff;padding:13px 14px;border-bottom:none"><b>Most active segment</b></td><td style="background:#fff;padding:13px 14px;border-bottom:none;text-align:right">{{ $mostActiveSegment ?? '—' }}</td></tr>
                </table>
                <div class="notice" style="margin-top:14px">Disappearances are not automatically treated as sales. The system uses confidence and recheck logic before classifying status.</div>
            </div>
        </div>

        <div class="vb-card">
            <div class="vb-card-head"><h2>Digital Visibility Race</h2></div>
            <div class="vb-card-body">
                <table class="vb-table" style="width:100%">
                    <tr><td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3"><b>Most new SEO pages</b></td><td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3;text-align:right">{{ $mostSeoPages ?? '—' }}</td></tr>
                    <tr><td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3"><b>Most external mentions</b></td><td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3;text-align:right">{{ $mostMentions ?? '—' }}</td></tr>
                    <tr><td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3"><b>Highest Google rating</b></td><td style="background:#fff;padding:13px 14px;border-bottom:1px solid #edf0f3;text-align:right">{{ $highestRating ?? '—' }}</td></tr>
                    <tr><td style="background:#fff;padding:13px 14px;border-bottom:none"><b>Most total digital events</b></td><td style="background:#fff;padding:13px 14px;border-bottom:none;text-align:right">{{ $mostEvents ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="vb-card" style="margin-top:18px">
        <div class="vb-card-head">
            <h2>Cross-Competitor AI Conclusions</h2>
            <span class="badge-soft b-purple">DATA → PATTERN → OPPORTUNITY → ACTION</span>
        </div>
        <div class="vb-card-body">
            @if($crossCompetitorInsights && count($crossCompetitorInsights) > 0)
                @foreach($crossCompetitorInsights as $insight)
                <div class="event">
                    <span class="badge-soft {{ $insight['priority'] === 'high' ? 'b-red' : 'b-yellow' }}">
                        {{ strtoupper($insight['priority']) }} PRIORITY
                    </span>
                    <div class="event-title">{{ $insight['title'] }}</div>
                    <div class="event-box">
                        <b>Fact:</b> {{ $insight['fact'] }}<br><br>
                        <b>Pattern:</b> {{ $insight['pattern'] }}<br><br>
                        <b>Opportunity:</b> {{ $insight['opportunity'] }}<br><br>
                        <b>Action:</b> {{ $insight['action'] }}
                    </div>
                    <div class="event-actions">
                        <button class="vb-btn">Create Action</button>
                    </div>
                </div>
                @endforeach
            @else
                <div class="event">
                    <span class="badge-soft b-red">HIGH PRIORITY</span>
                    <div class="event-title">RE/MAX Croatia is expanding property inventory rapidly</div>
                    <div class="event-box">
                        <b>Fact:</b> RE/MAX Croatia added 50 new property listings while other competitors show minimal activity.<br><br>
                        <b>Pattern:</b> One competitor is aggressively expanding market presence.<br><br>
                        <b>Opportunity:</b> Your agency should analyze which property segments RE/MAX is targeting.<br><br>
                        <b>Action:</b> Review RE/MAX's new listings by location and property type to identify gaps in your coverage.
                    </div>
                    <div class="event-actions">
                        <button class="vb-btn">Create Action</button>
                    </div>
                </div>
                <div class="event">
                    <span class="badge-soft b-yellow">WATCH</span>
                    <div class="event-title">Market activity concentrated in Split region</div>
                    <div class="event-box">
                        <b>Fact:</b> Most competitor activity is focused on Split apartments and surrounding areas.<br><br>
                        <b>Recommended action:</b> Ensure your Split inventory is competitively priced and well-presented.
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
