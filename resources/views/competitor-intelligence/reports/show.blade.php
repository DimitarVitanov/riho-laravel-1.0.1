@extends('layouts.simple.master')

@section('title', 'Daily Report - ' . $report->report_date->format('M d, Y'))

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-head">
        <div>
            <h1>Daily Report — {{ $report->report_date->format('d M Y') }}</h1>
            <p>Complete intelligence report for this day.</p>
        </div>
        <div class="vb-toolbar">
            <a href="{{ route('agency.competitor-intelligence.reports.index') }}" class="vb-btn vb-btn-light">← Back to Reports</a>
            <a href="{{ route('agency.competitor-intelligence.reports.export', $report) }}" class="vb-btn">Download Report</a>
        </div>
    </div>

    <div class="vb-card">
        <div class="vb-card-body">
            @if($report->executive_summary)
            <div class="notice" style="margin-bottom:24px">
                <b>Executive summary:</b> {{ $report->executive_summary }}
            </div>
            @endif

            <h3 style="font-size:15px;margin-top:20px">1. What Changed</h3>
            <div class="vb-grid-4" style="margin-top:16px">
                <div class="vb-stat">
                    <div class="label">New Properties</div>
                    <div class="value">{{ $report->metrics?->new_properties ?? 0 }}</div>
                </div>
                <div class="vb-stat">
                    <div class="label">Price Changes</div>
                    <div class="value">{{ $report->metrics?->getPriceChangesCount() ?? 0 }}</div>
                </div>
                <div class="vb-stat">
                    <div class="label">Removed Properties</div>
                    <div class="value">{{ $report->metrics?->removed_properties ?? 0 }}</div>
                </div>
                <div class="vb-stat">
                    <div class="label">Total Changes</div>
                    <div class="value">{{ $report->metrics?->total_changes ?? 0 }}</div>
                </div>
            </div>

            @php
                $whyItMatters = $report->items->where('item_type', 'why_it_matters');
                $recommendedActions = $report->items->where('item_type', 'recommended_action');
            @endphp
            @if($whyItMatters->isNotEmpty())
            <h3 style="font-size:15px;margin-top:24px">2. Why It Matters</h3>
            @foreach($whyItMatters as $section)
            <div class="event-box" style="margin-bottom:12px">{{ $section->content }}</div>
            @endforeach
            @endif

            @if($recommendedActions->isNotEmpty())
            <h3 style="font-size:15px;margin-top:24px">3. Recommended Actions</h3>
            <table class="vb-table">
                @foreach($recommendedActions as $action)
                <tr>
                    <td style="width:80px"><b>{{ strtoupper($action->priority ?? 'medium') }}</b></td>
                    <td>{{ $action->content }}</td>
                </tr>
                @endforeach
            </table>
            @endif

        </div>
    </div>
</div>
@endsection
