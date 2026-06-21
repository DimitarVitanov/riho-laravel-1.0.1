@extends('layouts.simple.master')
@section('title', $project->project_name)

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>{{ $project->project_name }}</h1>
            <p>{{ $project->project_code }} · {{ $project->project_location ?? '' }}{{ $project->project_country ? ', ' . $project->project_country : '' }}</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            @php
                $pCls = match($project->project_status) {
                    'active' => 'vb-badge-success',
                    'completed' => 'vb-badge-info',
                    'on_hold' => 'vb-badge-warning',
                    default => 'vb-badge-muted'
                };
            @endphp
            <span class="vb-badge {{ $pCls }}" style="font-size:13px;padding:8px 14px;">{{ ucfirst(str_replace('_', ' ', $project->project_status)) }}</span>
            <a href="{{ route('investor.projects.index') }}" class="vb-btn vb-btn-secondary">Back to Reports</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 20px;">
        <div>
            <div class="vb-card" style="margin-bottom: 20px;">
                <h2 class="vb-section-title">Project Summary</h2>
                <p style="color: #374151; line-height: 1.6;">{{ $project->summary ?? 'No summary provided.' }}</p>
                @if($project->full_description)
                <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                    <h3 style="font-size: 15px; margin: 0 0 10px;">Full Description</h3>
                    <p style="color: #374151; line-height: 1.6;">{{ $project->full_description }}</p>
                </div>
                @endif
                @if($project->risk_notes)
                <div style="margin-top: 16px; padding: 14px; background: #fff7ed; border-radius: 10px; border: 1px solid #fdba74;">
                    <h3 style="font-size: 14px; margin: 0 0 6px; color: #9a3412;">Risk Notes</h3>
                    <p style="color: #9a3412; font-size: 14px; margin: 0;">{{ $project->risk_notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <div>
            <div class="vb-card" style="margin-bottom: 20px;">
                <h2 class="vb-section-title">Investment Terms</h2>
                <div style="display: grid; gap: 14px;">
                    <div>
                        <div class="vb-label">Target Raise</div>
                        <div style="font-size: 20px; font-weight: 800;">€{{ number_format($project->target_raise_amount, 0) }}</div>
                    </div>
                    <div>
                        <div class="vb-label">Preferred Return</div>
                        <div style="font-size: 20px; font-weight: 800;">{{ $project->preferred_return_percent }}% <span style="font-size:13px;font-weight:500;color:#6b7280;">{{ $project->preferred_return_type }}</span></div>
                    </div>
                    <div>
                        <div class="vb-label">Rental Profit Share</div>
                        <div style="font-size: 20px; font-weight: 800;">{{ $project->rental_profit_share_percent ?? '—' }}%</div>
                    </div>
                    <div>
                        <div class="vb-label">Exit Profit Share</div>
                        <div style="font-size: 20px; font-weight: 800;">{{ $project->project_exit_profit_share_percent ?? '—' }}%</div>
                    </div>
                    <div>
                        <div class="vb-label">Min. Investment</div>
                        <div style="font-size: 20px; font-weight: 800;">€{{ number_format($project->minimum_investment_amount, 0) }}</div>
                    </div>
                    @if($project->estimated_duration_months)
                    <div>
                        <div class="vb-label">Estimated Duration</div>
                        <div style="font-size: 20px; font-weight: 800;">{{ $project->estimated_duration_months }} months</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
