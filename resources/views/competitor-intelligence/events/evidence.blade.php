@extends('layouts.simple.master')

@section('title', 'Event Evidence')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-head">
        <div>
            <h1>{{ $event->getEventTypeLabel() }} — Evidence</h1>
            <p>Verified change record, stored values and AI analysis for {{ $event->competitor->name ?? 'Unknown competitor' }}.</p>
        </div>
        <div class="vb-toolbar">
            <a href="{{ url()->previous() }}" class="vb-btn vb-btn-light" style="color:#202733!important">← Back</a>
            @if($event->evidence_url)
            <a href="{{ $event->evidence_url }}" target="_blank" rel="noopener noreferrer" class="vb-btn">Open Live Source</a>
            @endif
        </div>
    </div>

    <div class="vb-grid-4">
        <div class="vb-stat">
            <div class="label">Event</div>
            <div class="value" style="font-size:18px">
                <span class="badge-soft b-{{ $event->getEventColor() }}">{{ strtoupper($event->getEventTypeLabel()) }}</span>
                @if($secondaryBadge = $event->getSecondaryBadge())
                <span class="badge-soft b-{{ $secondaryBadge['color'] }}">{{ $secondaryBadge['label'] }}</span>
                @endif
            </div>
            <div class="sub">{{ $event->entity_type ? ucfirst($event->entity_type) : 'Observed change' }}</div>
        </div>
        <div class="vb-stat">
            <div class="label">Detected</div>
            <div class="value" style="font-size:18px">{{ $event->detected_at->format('d M Y') }}</div>
            <div class="sub">{{ $event->detected_at->format('H:i') }}</div>
        </div>
        <div class="vb-stat">
            <div class="label">Confidence</div>
            <div class="value">{{ $event->confidence ?? 0 }}%</div>
            <div class="sub">{{ $event->verified_at ? 'Verified ' . $event->verified_at->format('d M Y H:i') : 'Awaiting verification' }}</div>
        </div>
        <div class="vb-stat">
            <div class="label">Importance</div>
            <div class="value">{{ $event->importance_score ?? 0 }}</div>
            <div class="sub">Priority score out of 100</div>
        </div>
    </div>

    <div class="vb-card" style="margin-top:18px">
        <div class="vb-card-head"><h2>Evidence Record</h2></div>
        <div class="vb-card-body">
            <table class="vb-table">
                <tr><td><b>Competitor</b></td><td>{{ $event->competitor->name ?? 'Unknown' }}</td></tr>
                <tr><td><b>Event ID</b></td><td>#{{ $event->id }}</td></tr>
                <tr><td><b>Entity</b></td><td>{{ $event->entity_type ? ucfirst($event->entity_type) : '—' }}{{ $event->entity_id ? ' #' . $event->entity_id : '' }}</td></tr>
                <tr><td><b>Source</b></td><td>{{ $event->source?->source_type ?? $event->source?->name ?? 'Direct website scan' }}</td></tr>
                <tr><td><b>Evidence URL</b></td><td style="word-break:break-all"><a href="<?php echo $event->evidence_url?>"><span class="text-primary"> {{ $event->evidence_url ?? '—' }}</span></a></td></tr>
            </table>
            <div class="notice" style="margin-top:16px"><b>Detected change:</b> {{ $event->getDescription() }}</div>
        </div>
    </div>

    <div class="vb-card" id="detected-changes" style="margin-top:18px">
        <div class="vb-card-head"><h2>Detected Changes & Evidence</h2></div>
        <div class="vb-card-body">
            @foreach($relatedEvents as $evidenceEvent)
            @php
                $oldValue = $evidenceEvent->old_value_json ?? [];
                $newValue = $evidenceEvent->new_value_json ?? [];
                $oldDisplay = !empty($oldValue) ? collect($oldValue)->map(fn($value, $key) => Str::headline($key) . ': ' . (is_array($value) ? json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $value))->implode("\n") : null;
                $newDisplay = !empty($newValue) ? collect($newValue)->map(fn($value, $key) => Str::headline($key) . ': ' . (is_array($value) ? json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $value))->implode("\n") : null;
                if (in_array($evidenceEvent->event_type, ['price_increase', 'price_decrease'])) {
                    $oldDisplay = isset($oldValue['price']) ? '€' . number_format((float) $oldValue['price'], 0) : $oldDisplay;
                    $newDisplay = isset($newValue['price']) ? '€' . number_format((float) $newValue['price'], 0) : $newDisplay;
                }
            @endphp
            <div class="event" style="margin:0;padding:16px 0;{{ !$loop->last ? 'border-bottom:1px solid #e2e5ea' : '' }}">
                <div class="event-top">
                    <div><span class="badge-soft b-{{ $evidenceEvent->getEventColor() }}">{{ strtoupper($evidenceEvent->getEventTypeLabel()) }}</span></div>
                    <div class="event-meta">{{ $evidenceEvent->detected_at->format('d M Y H:i') }}</div>
                </div>
                <div class="event-title">{{ $evidenceEvent->getDisplayTitle() }}</div>
                @if($oldDisplay || $newDisplay)
                <div class="vb-grid-2" style="margin-top:10px">
                    <div class="change-old" style="padding:12px;border-radius:7px;background:#fff3f3;border:1px solid #f1cccc">
                        <b>Old value</b>
                        <div style="white-space:pre-wrap;word-break:break-word;margin-top:4px">{{ $oldDisplay ?? 'No previous value — first observation' }}</div>
                    </div>
                    <div class="change-new" style="padding:12px;border-radius:7px;background:#effbf4;border:1px solid #bce5cc">
                        <b>New value</b>
                        <div style="white-space:pre-wrap;word-break:break-word;margin-top:4px">{{ $newDisplay ?? 'No new value stored' }}</div>
                    </div>
                </div>
                @else
                <div class="event-meta">{{ $evidenceEvent->getDescription() }}</div>
                @endif
                @if($evidenceEvent->ai_interpretation)
                <div class="event-box"><b>AI interpretation:</b> {{ $evidenceEvent->ai_interpretation }}</div>
                @endif
                @if($evidenceEvent->ai_opportunity)
                <div class="event-box"><b>Opportunity:</b> {{ $evidenceEvent->ai_opportunity }}@if($evidenceEvent->ai_action) <b>Recommended action:</b> {{ $evidenceEvent->ai_action }}@endif</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
