@extends('layouts.simple.master')

@section('title', 'Agency Dashboard')

@section('main_content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="vb-page-header">
            <div>
                <h1>Real Estate Agency Panel</h1>
                <p>Agency panel is where AI does the work: local SEO, AI search, competitor scans, authority builder, invisible lead magnet, reports, usage, uniqueness checks, and affiliate earnings.</p>
            </div>
        </div>

        <!-- Usage Period Status Banner -->
        @if($currentUsage)
        <div class="vb-usage-status-title">Visible Usage Period Status</div>
        <div class="vb-usage-status-banner">
            <div class="vb-item">
                <span>Usage month</span>
                <b>{{ $currentUsage->usagePeriodMonth() }} {{ $currentUsage->usagePeriodYear() }}</b>
            </div>
            <div class="vb-item">
                <span>Current period</span>
                <b>{{ $currentUsage->period_start->format('M j') }}–{{ $currentUsage->period_end->format('j, Y') }}</b>
            </div>
            <div class="vb-item">
                <span>Account / usage status</span>
                <b>{{ $currentUsage->usagePeriodStatus() }}</b>
            </div>
            <div class="vb-item">
                <span>Next automatic reset</span>
                <b>{{ $currentUsage->nextResetDate() }}</b>
            </div>
        </div>
        @else
        <div class="vb-usage-status-banner">
            <div class="vb-item">
                <span>Welcome</span>
                <b>{{ $user->first_name }}</b>
            </div>
            <div class="vb-item">
                <span>Status</span>
                <b>Pending Setup</b>
            </div>
        </div>
        @endif

        <!-- Usage Metric Cards -->
        @if($currentUsage)
        <div class="vb-grid">
            <div class="vb-card">
                <div class="vb-label">Local SEO Pages</div>
                <div class="vb-metric">{{ $currentUsage->local_seo_pages_used }} / {{ $currentUsage->local_seo_pages_limit }}</div>
                <div class="vb-progress">
                    <div class="vb-progress-bar {{ ($currentUsage->local_seo_pages_limit > 0 && $currentUsage->local_seo_pages_used / $currentUsage->local_seo_pages_limit >= 0.9) ? 'vb-danger' : '' }}" style="width:{{ $currentUsage->local_seo_pages_limit > 0 ? ($currentUsage->local_seo_pages_used / $currentUsage->local_seo_pages_limit * 100) : 0 }}%"></div>
                </div>
                <div class="vb-period">Usage month: {{ $currentUsage->usagePeriodMonth() }} {{ $currentUsage->usagePeriodYear() }} · Usage status: {{ $currentUsage->usagePeriodStatus() }}</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">Competitor Scans</div>
                <div class="vb-metric">{{ $currentUsage->competitor_scans_used }} / {{ $currentUsage->competitor_scans_limit }}</div>
                <div class="vb-progress">
                    <div class="vb-progress-bar {{ ($currentUsage->competitor_scans_limit > 0 && $currentUsage->competitor_scans_used / $currentUsage->competitor_scans_limit >= 0.9) ? 'vb-danger' : '' }}" style="width:{{ $currentUsage->competitor_scans_limit > 0 ? ($currentUsage->competitor_scans_used / $currentUsage->competitor_scans_limit * 100) : 0 }}%"></div>
                </div>
                <div class="vb-period">Usage month: {{ $currentUsage->usagePeriodMonth() }} {{ $currentUsage->usagePeriodYear() }} · Usage status: {{ $currentUsage->usagePeriodStatus() }}</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">AI Freshness Updates</div>
                <div class="vb-metric">{{ $currentUsage->ai_search_freshness_updates_used }} / {{ $currentUsage->ai_search_freshness_updates_limit }}</div>
                <div class="vb-progress">
                    <div class="vb-progress-bar {{ ($currentUsage->ai_search_freshness_updates_limit > 0 && $currentUsage->ai_search_freshness_updates_used / $currentUsage->ai_search_freshness_updates_limit >= 0.9) ? 'vb-danger' : '' }}" style="width:{{ $currentUsage->ai_search_freshness_updates_limit > 0 ? ($currentUsage->ai_search_freshness_updates_used / $currentUsage->ai_search_freshness_updates_limit * 100) : 0 }}%"></div>
                </div>
                <div class="vb-period">Usage month: {{ $currentUsage->usagePeriodMonth() }} {{ $currentUsage->usagePeriodYear() }} · Usage status: {{ $currentUsage->usagePeriodStatus() }}</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">Authority Updates</div>
                <div class="vb-metric">{{ $currentUsage->authority_review_updates_used }} / {{ $currentUsage->authority_review_updates_limit }}</div>
                <div class="vb-progress">
                    <div class="vb-progress-bar {{ ($currentUsage->authority_review_updates_limit > 0 && $currentUsage->authority_review_updates_used / $currentUsage->authority_review_updates_limit >= 0.9) ? 'vb-danger' : '' }}" style="width:{{ $currentUsage->authority_review_updates_limit > 0 ? ($currentUsage->authority_review_updates_used / $currentUsage->authority_review_updates_limit * 100) : 0 }}%"></div>
                </div>
                <div class="vb-period">Usage month: {{ $currentUsage->usagePeriodMonth() }} {{ $currentUsage->usagePeriodYear() }} · Usage status: {{ $currentUsage->usagePeriodStatus() }}</div>
            </div>
        </div>
        @endif

        <!-- AI Features Status Table -->
        @if($aiFeatures->count())
        <div class="vb-card" style="margin-bottom: 28px;">
            <h2 class="vb-section-title">AI Features</h2>
            <table class="vb-table">
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th>Status</th>
                        <th>Frequency</th>
                        <th>Last Run</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aiFeatures as $feature)
                    <tr>
                        <td><strong>{{ ucfirst(str_replace('_', ' ', $feature->feature_key)) }}</strong></td>
                        <td>
                            @if($feature->is_enabled)
                                <span class="vb-badge vb-badge-success">ON</span>
                            @else
                                <span class="vb-badge vb-badge-muted">OFF</span>
                            @endif
                        </td>
                        <td>{{ ucfirst($feature->frequency) }}</td>
                        <td>{{ $feature->last_run_at ? $feature->last_run_at->diffForHumans() : '—' }}</td>
                        <td><a href="{{ route('agency.features.show', $feature->feature_key) }}" class="vb-btn vb-btn-sm">Open</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Uniqueness Rules -->
        <div class="vb-grid-2">
            <div class="vb-card">
                <h2 class="vb-section-title">Mandatory Internet Uniqueness Rules</h2>
                <div class="vb-form-grid">
                    <div class="vb-field">
                        <label>Uniqueness check before publish</label>
                        <div class="vb-fixed-value">Always required — locked in code</div>
                        <div class="vb-locked-note">No dropdown is needed. This rule is hard-coded for all AI-created public text.</div>
                    </div>
                    <div class="vb-field">
                        <label>If uniqueness fails</label>
                        <div class="vb-fixed-value">AI must rewrite and recheck automatically until PASSED</div>
                        <div class="vb-locked-note">Failed text cannot be published.</div>
                    </div>
                </div>
                <div style="margin-top: 18px;">
                    <label style="font-size:12px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:0.3px;">Content publish status options</label>
                    <div class="vb-publish-options" style="margin-top:10px;">
                        <div class="vb-option-card">
                            <b>Option 1</b>
                            <span>Auto publish after PASSED uniqueness check</span>
                        </div>
                        <div class="vb-option-card">
                            <b>Option 2</b>
                            <span>Keep as draft for manual review, edit, then publish</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vb-card">
                <h2 class="vb-section-title">Content Status Flow</h2>
                <p style="color:#6b7280;font-size:13px;margin-bottom:16px;">Every AI-created public text follows this process:</p>
                <div class="vb-status-flow">
                    <span class="vb-status-step">DRAFT</span>
                    <span class="vb-status-step">CHECKING</span>
                    <span class="vb-status-step">PASSED</span>
                    <span class="vb-status-step">FAILED</span>
                    <span class="vb-status-step">REWRITE_REQUIRED</span>
                    <span class="vb-status-step">READY_FOR_MANUAL_REVIEW</span>
                    <span class="vb-status-step active">PUBLISHED</span>
                </div>
                <div class="vb-notice">
                    AI creates draft → uniqueness check runs → if failed, AI rewrites and rechecks → if passed, system either auto-publishes or sends to manual review based on the selected publish workflow.
                </div>
            </div>
        </div>
    </div>
@endsection
