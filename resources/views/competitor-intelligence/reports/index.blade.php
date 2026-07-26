@extends('layouts.simple.master')

@section('title', 'Daily Intelligence Reports')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-head">
        <div>
            <h1>Daily Intelligence Reports</h1>
            <p>One concise morning report: what competitors did yesterday, what changed, why it matters and what Villa Bit AI recommends doing today.</p>
        </div>
        <form action="{{ route('agency.competitor-intelligence.reports.generate') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="vb-btn">Generate Report Now</button>
        </form>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="vb-grid-3">
        <div class="vb-card" style="grid-column:span 2">
            @if($latestReport)
            <div class="vb-card-head">
                <h2>Daily Report — {{ $latestReport->report_date->format('d M Y') }}</h2>
                <div>
                    <a href="{{ route('agency.competitor-intelligence.reports.export', $latestReport) }}" class="vb-btn vb-btn-light"> <span class="text-dark">Download Report</span></a>
                    <a href="{{ route('agency.competitor-intelligence.reports.show', $latestReport) }}" class="vb-btn vb-btn-light"><span class="text-dark">View Full</span></a>
                </div>
            </div>
            <div class="vb-card-body">
                @if($latestReport->executive_summary)
                <div class="notice"><b>Executive summary:</b> {{ $latestReport->executive_summary }}</div>
                @endif

                <h3 style="font-size:15px;margin-top:20px">1. What Changed</h3>
                <div class="vb-kpi-row">
                    <div class="vb-kpi"><strong>{{ $latestReport->metrics?->new_properties ?? 0 }}</strong><span>New properties</span></div>
                    <div class="vb-kpi"><strong>{{ $latestReport->metrics?->getPriceChangesCount() ?? 0 }}</strong><span>Price changes</span></div>
                    <div class="vb-kpi"><strong>{{ $latestReport->metrics?->new_seo_pages ?? 0 }}</strong><span>SEO pages</span></div>
                    <div class="vb-kpi"><strong>{{ $latestReport->metrics?->new_mentions ?? 0 }}</strong><span>External mentions</span></div>
                </div>

                @php
                    $whyItMatters = $latestReport->items->where('item_type', 'why_it_matters');
                    $recommendedActions = $latestReport->items->where('item_type', 'recommended_action');
                @endphp
                @if($whyItMatters->isNotEmpty())
                <h3 style="font-size:15px;margin-top:24px">2. Why It Matters</h3>
                @foreach($whyItMatters as $section)
                <div class="event-box">{{ $section->content }}</div>
                @endforeach
                @endif

                @if($recommendedActions->isNotEmpty())
                <h3 style="font-size:15px;margin-top:24px">3. Recommended Actions Today</h3>
                <table class="vb-table">
                    @foreach($recommendedActions as $action)
                    <tr>
                        <td><b>{{ strtoupper($action->priority ?? 'medium') }}</b></td>
                        <td>{{ $action->content }}</td>
                        <td>{{ $action->reason }}</td>
                    </tr>
                    @endforeach
                </table>
                @endif
            </div>
            @else
            <div class="vb-card-head">
                <h2>No Reports Yet</h2>
            </div>
            <div class="vb-card-body">
                <div style="text-align:center;padding:40px">
                    <div style="font-size:48px;color:#ccc;margin-bottom:16px">📊</div>
                    <h3 style="margin:0 0 8px">No daily reports generated yet</h3>
                    <p class="muted">Reports are generated automatically each morning, or you can generate one now.</p>
                </div>
            </div>
            @endif
        </div>

        <div>
            <div class="vb-card">
                <div class="vb-card-head">
                    <h2>Latest Report</h2>
                </div>
                <div class="vb-card-body">
                    @if($latestReport)
                    <p class="muted">Download the report for {{ $latestReport->report_date->format('d M Y') }}.</p>
                    <a href="{{ route('agency.competitor-intelligence.reports.export', $latestReport) }}" class="vb-btn" style="display:block;width:100%;text-align:center">Download Latest Report</a>
                    @else
                    <p class="muted">Generate your first report to enable downloading.</p>
                    @endif
                </div>
            </div>

            <div class="vb-card" style="margin-top:16px">
                <div class="vb-card-head">
                    <h2>Archive</h2>
                </div>
                <div class="vb-card-body" style="padding:0">
                    @forelse($reports as $report)
                    <div style="padding:14px 16px;border-bottom:1px solid #e6e9ef">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px">
                            <div>
                                <a href="{{ route('agency.competitor-intelligence.reports.show', $report) }}" style="color:#17212b!important;font-weight:700;text-decoration:none">
                                    {{ $report->report_date->format('d M Y') }}
                                </a>
                                @if($latestReport && $report->is($latestReport))
                                <span class="badge-soft b-blue" style="margin-left:6px">LATEST</span>
                                @endif
                                <div class="muted" style="font-size:12px;margin-top:4px">
                                    {{ $report->metrics?->total_changes ?? 0 }} changes
                                    @if($report->created_at) · Generated {{ $report->created_at->format('d M Y H:i') }}@endif
                                </div>
                            </div>
                            <div style="display:flex;gap:8px;flex-shrink:0">
                                <a href="{{ route('agency.competitor-intelligence.reports.show', $report) }}" class="vb-btn vb-btn-light" style="padding:6px 10px"><span class="text-dark">View</span></a>
                                <a href="{{ route('agency.competitor-intelligence.reports.export', $report) }}" class="vb-btn vb-btn-light" style="padding:6px 10px"><span class="text-dark">Download</span></a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state" style="padding:24px 16px"><h3>No archived reports yet</h3><p>Generated daily reports will be stored here automatically.</p></div>
                    @endforelse
                </div>
                @if($reports->hasPages())
                <div class="vb-card-body" style="padding-top:14px">{{ $reports->onEachSide(1)->links('vendor.pagination.competitor') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
