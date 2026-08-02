<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $detail['title'] ?: 'Property details' }} | EST8ADS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('est8ads/panel/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('est8ads/panel/property-details.css') }}">
</head>
<body data-panel-role="{{ $detail['isAdmin'] ? 'admin' : 'user' }}" class="property-detail-page">
@php
    $display = static function ($value) {
        if ($value instanceof \DateTimeInterface) return $value->format('Y-m-d H:i');
        if (is_bool($value)) return $value ? 'Yes' : 'No';
        if (is_array($value)) return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return ($value === null || $value === '') ? 'Not provided' : (string) $value;
    };
    $statusClass = str_contains(strtolower((string) $detail['status']), 'active') || str_contains(strtolower((string) $detail['status']), 'approved') ? 'active' : (str_contains(strtolower((string) $detail['status']), 'correction') ? 'action' : 'pending');
    $matchScore = $detail['match']['score'] ?? null;
@endphp
<div class="panel-app">
    <aside class="panel-sidebar">
        <a class="panel-brand" href="{{ url('/dashboard') }}"><img src="{{ asset('est8ads/panel/est8ads-logo.svg') }}" alt="EST8ADS"></a>
        <div class="workspace-label">{{ $detail['isAdmin'] ? 'ADMIN' : 'USER' }} WORKSPACE</div>
        <nav class="side-nav detail-side-nav">
            <a href="{{ $detail['isAdmin'] ? url('/admin') : url('/dashboard') }}"><span class="nav-dot">◆</span>Overview</a>
            <a class="active" href="{{ $detail['isAdmin'] ? url('/admin') . '?section=matches' : url('/dashboard') . '?section=matches' }}"><span class="nav-dot">M</span>Matches & properties</a>
            <a href="{{ $detail['isAdmin'] ? url('/admin') . '?section=analyzer' : url('/dashboard') . '?section=chains' }}"><span class="nav-dot">AI</span>Chain analysis</a>
        </nav>
        <div class="sidebar-footer">
            <div class="account-mini"><span class="avatar">{{ strtoupper(substr(auth()->user()->first_name ?: auth()->user()->email, 0, 2)) }}</span><div><strong>{{ auth()->user()->full_name }}</strong><small>{{ $detail['isAdmin'] ? 'Full access' : 'Authorized record' }}</small></div></div>
            <form action="{{ url('/logout') }}" method="POST">@csrf<button class="logout">Sign out</button></form>
        </div>
    </aside>

    <main class="panel-main">
        <header class="panel-topbar">
            <div class="topbar-title"><h1>Property move details</h1><p>Complete submitted data, linked request and match context</p></div>
            <div class="topbar-actions"><a class="btn" href="{{ url()->previous() }}">← Back</a></div>
        </header>
        <div class="panel-content detail-page-content">
            @if(session('est8ads_success'))<div class="detail-alert">{{ session('est8ads_success') }}</div>@endif
            <div class="detail-breadcrumb"><a href="{{ $detail['isAdmin'] ? url('/admin') : url('/dashboard') }}">Workspace</a><span>›</span><strong>{{ implode(' · ', $detail['ids']) }}</strong></div>
            <section class="detail-hero">
                <div class="detail-hero-main">
                    <div class="detail-kicker">COMPLETE DATABASE RECORD</div>
                    <h1>{{ $detail['sell']['Listing title'] ?? $detail['title'] ?? 'Property' }} @if($detail['buy'])<span>→</span> {{ $detail['buy']['Wanted property type'] ?? 'Next property' }}@endif</h1>
                    <p>Review all available public-intake fields together with linked system, match and property-chain context.</p>
                    <div class="detail-hero-tags"><span class="status {{ $statusClass }}">{{ ucfirst((string) $detail['status']) }}</span>@foreach($detail['ids'] as $id)<span>{{ $id }}</span>@endforeach</div>
                </div>
                @if($matchScore !== null)<div class="detail-score-card"><span>MATCH SCORE</span><strong>{{ $matchScore }}%</strong><small>Database-calculated candidate score</small></div>@endif
            </section>

            <div class="detail-layout">
                <div class="detail-main-column">
                    <section class="detail-section">
                        <div class="detail-section-head"><span>01</span><div><h2>Who created this property move</h2><p>Submission, transaction, account and system context.</p></div></div>
                        <div class="detail-field-grid three">
                            @forelse($detail['about'] as $label => $value)<div class="detail-field"><span>{{ $label }}</span><strong>{{ $display($value) }}</strong></div>@empty<div class="detail-empty">No move intake is linked to this record.</div>@endforelse
                            @foreach($detail['ids'] as $label => $id)<div class="detail-field"><span>{{ ucfirst($label) }} ID</span><strong>{{ $id }}</strong></div>@endforeach
                        </div>
                    </section>

                    @if($detail['sell'])
                    <section class="detail-section {{ $detail['focus'] === 'sell' ? 'detail-focus' : '' }}">
                        <div class="detail-section-head"><span>02</span><div><h2>Property the participant wants to sell</h2><p>Every available field from the sell-property record.</p></div></div>
                        <div class="detail-field-grid two">
                            @foreach($detail['sell'] as $label => $value)
                                <div class="detail-field {{ in_array($label, ['Address or micro-location','Main property features','Property description','Existing listing URL']) ? 'wide' : '' }}"><span>{{ $label }}</span><strong>
                                    @if($label === 'Existing listing URL' && filter_var($value, FILTER_VALIDATE_URL))<a class="detail-external-link" href="{{ $value }}" target="_blank" rel="noopener noreferrer">{{ $value }} ↗</a>@else{{ $display($value) }}@endif
                                </strong></div>
                            @endforeach
                        </div>
                        <div class="detail-subhead"><h3>Property media</h3><span>{{ count($detail['media']) }} files</span></div>
                        <div class="detail-photo-grid">
                            @forelse($detail['media'] as $media)<a class="detail-photo" href="{{ $media['url'] }}" target="_blank" rel="noopener noreferrer" style="background-image:linear-gradient(0deg,rgba(7,18,37,.68),rgba(7,18,37,.05)),url('{{ $media['url'] }}')"><span>PROPERTY MEDIA</span><strong>{{ $media['title'] }}</strong></a>@empty<div class="detail-empty">No media uploaded.</div>@endforelse
                        </div>
                    </section>
                    @endif

                    @if($detail['buy'])
                    <section class="detail-section {{ $detail['focus'] === 'buy' ? 'detail-focus' : '' }}">
                        <div class="detail-section-head"><span>03</span><div><h2>Property the participant wants to buy</h2><p>Every available field from the buy-property request.</p></div></div>
                        <div class="detail-field-grid two">@foreach($detail['buy'] as $label => $value)<div class="detail-field {{ in_array($label, ['Preferred locations','Must-have features','Flexible preferences','Ideal next property description','Requirements / notes']) ? 'wide' : '' }}"><span>{{ $label }}</span><strong>{{ $display($value) }}</strong></div>@endforeach</div>
                    </section>
                    @endif

                    @if($detail['chain'])
                    <section class="detail-section">
                        <div class="detail-section-head"><span>04</span><div><h2>Property chain conditions</h2><p>Conditions used to analyze the complete transaction.</p></div></div>
                        <div class="detail-toggle-grid">
                            @foreach(array_slice($detail['chain'], 0, 4, true) as $label => $value)<div><span>{{ $label }}</span><span class="detail-boolean {{ $value ? 'yes' : 'no' }}">{{ $value ? 'Yes' : 'No' }}</span></div>@endforeach
                        </div>
                        <div class="detail-field-grid two detail-field-grid-spaced">@foreach(array_slice($detail['chain'], 4, null, true) as $label => $value)<div class="detail-field {{ str_contains($label, 'Conditions') || str_contains($label, 'Additional') ? 'wide' : '' }}"><span>{{ $label }}</span><strong>{{ $display($value) }}</strong></div>@endforeach</div>
                    </section>
                    @endif

                    <section class="detail-section">
                        <div class="detail-section-head"><span>05</span><div><h2>Contact and account details</h2><p>Sensitive contact and consent data, shown only after record authorization.</p></div></div>
                        <div class="detail-field-grid two">@forelse($detail['contact'] as $label => $value)<div class="detail-field"><span>{{ $label }}</span><strong>{{ $display($value) }}</strong></div>@empty<div class="detail-empty">No linked contact information.</div>@endforelse</div>
                        @if($detail['consents'])<div class="detail-consent-list">@foreach($detail['consents'] as $label => $value)<div><span>{{ $label }}</span><span class="detail-boolean {{ $value ? 'yes' : 'no' }}">{{ $value ? 'Yes' : 'No' }}</span></div>@endforeach</div>@endif
                    </section>

                    @if($detail['match'])
                    <section class="detail-section">
                        <div class="detail-section-head"><span>AI</span><div><h2>Match and chain analysis</h2><p>Persisted score, explanation and system metadata. No inferred or demo values.</p></div></div>
                        <div class="detail-ai-grid">
                            <div><span>Overall score</span><strong>{{ $detail['match']['score'] }}%</strong></div>
                            <div><span>Match type</span><strong>{{ $display($detail['match']['type']) }}</strong></div>
                            <div><span>Algorithm</span><strong>{{ $display($detail['match']['algorithm']) }}</strong></div>
                            <div><span>Expires</span><strong>{{ $display($detail['match']['expiresAt']) }}</strong></div>
                        </div>
                        <div class="detail-field-grid two detail-field-grid-spaced">
                            <div class="detail-field wide"><span>Score breakdown</span><strong>{{ $display($detail['match']['breakdown']) }}</strong></div>
                            <div class="detail-field wide"><span>Match explanation</span><strong>{{ $display($detail['match']['explanation']) }}</strong></div>
                            <div class="detail-field"><span>Match UUID</span><strong>{{ $detail['match']['uuid'] }}</strong></div>
                        </div>
                    </section>
                    @endif

                    @if($detail['chainContext'])
                    <section class="detail-section">
                        <div class="detail-section-head"><span>C</span><div><h2>Linked chain context</h2><p>Current persisted chain status and summary.</p></div></div>
                        <div class="detail-field-grid three">@foreach($detail['chainContext'] as $label => $value)<div class="detail-field {{ $label === 'summary' ? 'wide' : '' }}"><span>{{ ucfirst($label) }}</span><strong>{{ $display($value) }}</strong></div>@endforeach</div>
                    </section>
                    @endif
                </div>

                <aside class="detail-side-column">
                    <div class="detail-sticky-card">
                        <div class="detail-card-head"><h3>Record controls</h3><span class="status {{ $statusClass }}">{{ ucfirst((string) $detail['status']) }}</span></div>
                        <dl class="detail-meta-list">@foreach($detail['ids'] as $label => $id)<div><dt>{{ ucfirst($label) }}</dt><dd>{{ $id }}</dd></div>@endforeach<div><dt>Created</dt><dd>{{ $display($detail['createdAt']) }}</dd></div><div><dt>Updated</dt><dd>{{ $display($detail['updatedAt']) }}</dd></div></dl>
                        @if($detail['isAdmin'])
                            <form method="POST" action="{{ request()->url() }}/approve">@csrf<button class="btn primary detail-action" type="submit">Approve record</button></form>
                            <form method="POST" action="{{ request()->url() }}/correction" class="detail-correction-form">@csrf<textarea name="note" maxlength="2000" required placeholder="Explain the required correction"></textarea><button class="btn detail-action" type="submit">Request correction</button></form>
                        @endif
                        <a class="btn detail-action" href="{{ url()->previous() }}">Back to records</a>
                    </div>
                    @if($detail['agency'])<div class="detail-agency-card"><span>LINKED AGENCY</span><h3>{{ $detail['agency'] }}</h3><p>This record is scoped to its linked account and active agency memberships.</p></div>@endif
                </aside>
            </div>
        </div>
    </main>
</div>
</body>
</html>
