@extends('layouts.simple.master')

@section('title', 'Manager Dashboard')

@section('main_content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="vb-page-header">
            <div>
                <h1>Manager Panel</h1>
                <p>Manager panel oversees assigned agencies, investor capital calls, AI content review, payout preparation, and daily AI reports.</p>
            </div>
        </div>

        <!-- Usage Period Status Banner -->
        @include('components.villabit.usage-banner')

        <!-- Stats Cards -->
        <div class="vb-grid">
            <div class="vb-card">
                <div class="vb-label">Assigned Agencies</div>
                <div class="vb-metric">{{ $assigned_agencies }}</div>
                <div class="vb-period">Agencies under your management</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">Assigned Investors</div>
                <div class="vb-metric">{{ $assigned_investors }}</div>
                <div class="vb-period">Investors under your management</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">AI Reports Today</div>
                <div class="vb-metric">{{ $recent_reports->count() }}</div>
                <div class="vb-period">Reports generated in the last 24h</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">Content To Review</div>
                <div class="vb-metric">—</div>
                <div class="vb-period">Pending content drafts awaiting review</div>
            </div>
        </div>

        <!-- Permissions -->
        @if($profile)
        <div class="vb-card" style="margin-bottom: 28px;">
            <h2 class="vb-section-title">Your Permissions</h2>
            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                <span class="vb-badge {{ $profile->can_manage_agencies ? 'vb-badge-success' : 'vb-badge-muted' }}">Manage Agencies</span>
                <span class="vb-badge {{ $profile->can_manage_investors ? 'vb-badge-success' : 'vb-badge-muted' }}">Manage Investors</span>
                <span class="vb-badge {{ $profile->can_review_ai_outputs ? 'vb-badge-success' : 'vb-badge-muted' }}">Review AI Outputs</span>
                <span class="vb-badge {{ $profile->can_prepare_payouts ? 'vb-badge-success' : 'vb-badge-muted' }}">Prepare Payouts</span>
                <span class="vb-badge {{ $profile->can_view_financials ? 'vb-badge-success' : 'vb-badge-muted' }}">View Financials</span>
                <span class="vb-badge {{ $profile->can_login_as_user ? 'vb-badge-success' : 'vb-badge-muted' }}">Login As User</span>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="vb-grid-3">
            <div class="vb-card">
                <h3 class="vb-section-title" style="font-size:16px;">Content Review</h3>
                <p style="color:#6b7280;font-size:13px;margin-bottom:14px;">Review AI-generated content from your assigned agencies.</p>
                <a href="{{ route('manager.content-review.index') }}" class="vb-btn vb-btn-sm">Open</a>
            </div>
            <div class="vb-card">
                <h3 class="vb-section-title" style="font-size:16px;">Capital Calls</h3>
                <p style="color:#6b7280;font-size:13px;margin-bottom:14px;">Follow up on pending capital calls from investors.</p>
                <a href="{{ route('manager.capital-calls.index') }}" class="vb-btn vb-btn-sm">Open</a>
            </div>
            <div class="vb-card">
                <h3 class="vb-section-title" style="font-size:16px;">Payout Preparation</h3>
                <p style="color:#6b7280;font-size:13px;margin-bottom:14px;">Process pending investor and affiliate payouts.</p>
                <a href="{{ route('manager.payout-preparation.index') }}" class="vb-btn vb-btn-sm">Open</a>
            </div>
        </div>
    </div>
@endsection
