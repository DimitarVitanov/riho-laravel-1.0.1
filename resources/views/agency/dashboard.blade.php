@extends('layouts.simple.master')

@section('title', __('messages.dashboard'))

@section('main_content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="vb-page-header">
            <div>
                <h1>{{ __('messages.real_estate_agency_panel') }}</h1>
                <p>{{ __('messages.agency_panel_description') }}</p>
            </div>
        </div>

        <!-- Usage Period Status Banner -->
        @if($currentUsage)
        <div class="vb-usage-status-title">{{ __('messages.usage_period_status') }}</div>
        <div class="vb-usage-status-banner">
            <div class="vb-item">
                <span>{{ strtoupper(__('messages.current_period')) }}</span>
                <b>{{ $currentUsage->period_start->format('M j, Y') }} – {{ $currentUsage->period_end->format('M j, Y') }}</b>
            </div>
            <div class="vb-item">
                <span>{{ strtoupper(__('messages.account_usage_status')) }}</span>
                <b>{{ $currentUsage->usagePeriodStatus() }}</b>
            </div>
            <div class="vb-item">
                <span>{{ strtoupper(__('messages.next_automatic_reset')) }}</span>
                <b>{{ $currentUsage->period_end->addDay()->format('F j, Y') }}</b>
            </div>
        </div>
        @else
        <div class="vb-usage-status-title">{{ __('messages.usage_period_status') }}</div>
        <div class="vb-usage-status-banner">
            <div class="vb-item">
                <span>{{ strtoupper(__('messages.account_usage_status')) }}</span>
                <b>{{ $user->agencyProfile && $user->agencyProfile->subscription_status === 'active' ? 'Active' : 'On Hold' }}</b>
            </div>
            <div class="vb-item">
                <span>{{ strtoupper(__('messages.current_period')) }}</span>
                <b>—</b>
            </div>
            <div class="vb-item">
                <span>{{ strtoupper(__('messages.next_automatic_reset')) }}</span>
                <b>—</b>
            </div>
        </div>
        @endif

        <!-- Usage Metric Cards -->
        @if($currentUsage)
        <div class="vb-grid">
            <div class="vb-card">
                <div class="vb-label">{{ __('messages.local_seo_pages') }}</div>
                <div class="vb-metric">{{ $currentUsage->local_seo_pages_used }} / {{ $currentUsage->local_seo_pages_limit }}</div>
                <div class="vb-progress">
                    <div class="vb-progress-bar {{ ($currentUsage->local_seo_pages_limit > 0 && $currentUsage->local_seo_pages_used / $currentUsage->local_seo_pages_limit >= 0.9) ? 'vb-danger' : '' }}" style="width:{{ $currentUsage->local_seo_pages_limit > 0 ? ($currentUsage->local_seo_pages_used / $currentUsage->local_seo_pages_limit * 100) : 0 }}%"></div>
                </div>
                <div class="vb-period">{{ __('messages.usage_month') }}: {{ $currentUsage->usagePeriodMonth() }} {{ $currentUsage->usagePeriodYear() }} · {{ __('messages.status') }}: {{ $currentUsage->usagePeriodStatus() }}</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">AI Search Ranking</div>
                <div class="vb-metric">{{ $currentUsage->ai_search_ranking_used }} / {{ $currentUsage->ai_search_ranking_limit }}</div>
                <div class="vb-progress">
                    <div class="vb-progress-bar {{ ($currentUsage->ai_search_ranking_limit > 0 && $currentUsage->ai_search_ranking_used / $currentUsage->ai_search_ranking_limit >= 0.9) ? 'vb-danger' : '' }}" style="width:{{ $currentUsage->ai_search_ranking_limit > 0 ? ($currentUsage->ai_search_ranking_used / $currentUsage->ai_search_ranking_limit * 100) : 0 }}%"></div>
                </div>
                <div class="vb-period">{{ __('messages.usage_month') }}: {{ $currentUsage->usagePeriodMonth() }} {{ $currentUsage->usagePeriodYear() }} · {{ __('messages.status') }}: {{ $currentUsage->usagePeriodStatus() }}</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">{{ __('messages.competitor_scans') }}</div>
                <div class="vb-metric">{{ $currentUsage->competitor_scans_used }} / {{ $currentUsage->competitor_scans_limit }}</div>
                <div class="vb-progress">
                    <div class="vb-progress-bar {{ ($currentUsage->competitor_scans_limit > 0 && $currentUsage->competitor_scans_used / $currentUsage->competitor_scans_limit >= 0.9) ? 'vb-danger' : '' }}" style="width:{{ $currentUsage->competitor_scans_limit > 0 ? ($currentUsage->competitor_scans_used / $currentUsage->competitor_scans_limit * 100) : 0 }}%"></div>
                </div>
                <div class="vb-period">{{ __('messages.usage_month') }}: {{ $currentUsage->usagePeriodMonth() }} {{ $currentUsage->usagePeriodYear() }} · {{ __('messages.status') }}: {{ $currentUsage->usagePeriodStatus() }}</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">{{ __('messages.authority_reviews') }}</div>
                <div class="vb-metric">{{ $currentUsage->authority_review_updates_used }} / {{ $currentUsage->authority_review_updates_limit }}</div>
                <div class="vb-progress">
                    <div class="vb-progress-bar {{ ($currentUsage->authority_review_updates_limit > 0 && $currentUsage->authority_review_updates_used / $currentUsage->authority_review_updates_limit >= 0.9) ? 'vb-danger' : '' }}" style="width:{{ $currentUsage->authority_review_updates_limit > 0 ? ($currentUsage->authority_review_updates_used / $currentUsage->authority_review_updates_limit * 100) : 0 }}%"></div>
                </div>
                <div class="vb-period">{{ __('messages.usage_month') }}: {{ $currentUsage->usagePeriodMonth() }} {{ $currentUsage->usagePeriodYear() }} · {{ __('messages.status') }}: {{ $currentUsage->usagePeriodStatus() }}</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">{{ __('messages.ai_freshness_updates') }}</div>
                <div class="vb-metric">{{ $currentUsage->ai_search_freshness_updates_used }}</div>
                <div class="vb-period">{{ __('messages.usage_month') }}: {{ $currentUsage->usagePeriodMonth() }} {{ $currentUsage->usagePeriodYear() }} · {{ __('messages.status') }}: {{ $currentUsage->usagePeriodStatus() }}</div>
            </div>
        </div>
        @endif

        <!-- Language Settings Section -->
        <div class="row" style="margin-bottom: 28px;">
            <div class="col-lg-6">
                <div class="vb-card" style="height: 100%;">
                    <h2 class="vb-section-title">{{ __('messages.language_settings') }}</h2>
                    <form action="{{ route('agency.settings.language.update') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #374151;">{{ __('messages.control_panel_language') }}</label>
                                <select name="panel_language" class="form-select" style="background-color: #f9fafb;">
                                    @foreach(\App\Http\Controllers\Agency\AgencySettingsController::supportedPanelLanguages() as $code => $name)
                                        <option value="{{ $code }}" {{ ($user->preferred_language ?? 'en') === $code ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #374151;">{{ __('messages.ai_webpage_posting_language') }}</label>
                                <select name="ai_content_language" class="form-select" style="background-color: #f9fafb;">
                                    @foreach(\App\Http\Controllers\Agency\AgencySettingsController::supportedAiContentLanguages() as $lang)
                                        <option value="{{ $lang }}" {{ ($user->agencyProfile->ai_content_language ?? 'English') === $lang ? 'selected' : '' }}>{{ $lang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 p-3" style="background-color: #f3f4f6; border-radius: 8px;">
                            <p class="mb-0" style="font-size: 14px; color: #6b7280;">
                                Panel language controls dashboard interface. AI webpage posting language controls public SEO, AI search, authority, FAQ, market, and lead-magnet content created by AI.
                            </p>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="vb-btn vb-btn-primary">{{ __('messages.save_language_settings') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- AI Features Status Table -->
        @if($aiFeatures->count())
        @php
            $featureOrder = [
                'local_seo_presence_boost' => 1,
                'ai_search_ranking' => 2,
                'daily_competitor_scan' => 3,
                'ai_authority_builder' => 4,
                'invisible_lead_magnet' => 5,
            ];
            $sortedFeatures = $aiFeatures
                ->reject(fn($f) => $f->feature_key === 'small_ai_actions')
                ->sortBy(fn($f) => $featureOrder[$f->feature_key] ?? 99);
        @endphp
        <div class="vb-card" style="margin-bottom: 28px;">
            <h2 class="vb-section-title">{{ __('messages.ai_features') }}</h2>
            <table class="vb-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.feature') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.frequency') }}</th>
                        <th>{{ __('messages.last_run') }}</th>
                        <th>{{ __('messages.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sortedFeatures as $feature)
                    <tr>
                        <td><strong>{{ ucfirst(str_replace('_', ' ', $feature->feature_key)) }}</strong></td>
                        <td>
                            <form action="{{ route('agency.settings.features.toggle') }}" method="POST" class="d-inline" style="display:flex;align-items:center;gap:10px;">
                                @csrf
                                <input type="hidden" name="feature_id" value="{{ $feature->id }}">
                                <input type="hidden" name="is_enabled" value="{{ $feature->is_enabled ? '0' : '1' }}">
                                <label class="vb-switch">
                                    <input type="checkbox" {{ $feature->is_enabled ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span class="vb-slider"></span>
                                </label>
                                <span class="vb-badge {{ $feature->is_enabled ? 'vb-badge-success' : 'vb-badge-muted' }}">{{ $feature->is_enabled ? 'ON' : 'OFF' }}</span>
                            </form>
                        </td>
                        <td>{{ ucfirst($feature->frequency) }}</td>
                        <td>{{ $feature->last_run_at ? $feature->last_run_at->diffForHumans() : '—' }}</td>
                        <td><a href="{{ route('agency.features.show', $feature->feature_key) }}" class="vb-btn vb-btn-sm">{{ __('messages.open') }}</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Uniqueness Rules 
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

    -->
    </div>
@endsection
