<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>EST8ADS User Panel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('est8ads-assets/panel/panel.css') }}">    @include('est8ads.partials.favicon')
</head>
<body data-panel-role="user">
<div class="panel-app">
<aside class="panel-sidebar">
<a class="panel-brand" href="{{ route('est8ads.home') }}">
<img src="{{ asset('est8ads-assets/panel/est8ads-logo.svg') }}" alt="EST8ADS"></a>
<div class="workspace-label">USER WORKSPACE</div>
<nav class="side-nav">
<button data-section-target="overview" class="active">
<span class="nav-dot">◆</span>Dashboard</button>
 <!-- <button data-section-target="move" class="">
<span class="nav-dot">↔</span>My complete move</button> -->
<button data-section-target="properties" class="">
<span class="nav-dot">P</span>My properties</button>
<button data-section-target="chains" class="">
<span class="nav-dot">AI</span>Chain analysis</button>
<button data-section-target="matches" class="">
<span class="nav-dot">M</span>Matches</button>
<button data-section-target="messages" class="">
<span class="nav-dot">✉</span>Messages</button>
<button data-section-target="billing" class="">
<span class="nav-dot">$</span>Billing</button>
<button data-section-target="profile" class="">
<span class="nav-dot">U</span>Profile & security</button></nav>
<div class="sidebar-footer">
<div class="account-mini">
<div class="avatar">AK</div>
<div>
<strong data-account-name>{{ auth()->user()->full_name }}</strong>
<small data-account-type>{{ auth()->user()->isAgency() ? 'Agency account' : 'Private user' }}</small></div></div>
<form method="POST" action="{{ \App\Support\Est8adsRoute::to('logout') }}" style="margin:0">@csrf<button class="logout" type="submit">Sign out</button></form></div></aside>
<main class="panel-main">
<header class="panel-topbar">
<div style="display:flex;align-items:center;gap:12px">
<button class="icon-btn mobile-nav-toggle" aria-label="Open navigation">☰</button>
<div class="topbar-title">
<h1 data-page-title>My EST8ADS dashboard</h1>
<p>Your complete sell-and-buy move in one connected workspace</p></div></div>
<div class="topbar-actions">
<button class="icon-btn" title="Notifications">●</button>
<button class="top-primary" data-open-property>Add property</button></div></header>
<div class="panel-content">
<section class="panel-section active" data-section="overview">
<div class="section-head">
<div>
<h2>Your property move</h2>
<p>Track your current property, next purchase, chain candidates and request status.</p></div>
<button class="btn primary" data-nav-to="move">Edit complete move</button></div>
<div class="grid kpis">
<div class="kpi-card">
<div class="kpi-top">
<span class="kpi-icon">P</span>
<span class="status active">Live</span></div>
<h3>{{ count($est8adsData['properties']) }}</h3>
<p>Properties in your move</p></div>
<div class="kpi-card">
<div class="kpi-top"><span class="kpi-icon">AI</span></div>
<h3>{{ count($est8adsData['chains']) }}</h3>
<p>Potential chain candidates</p></div>
<div class="kpi-card">
<div class="kpi-top"><span class="kpi-icon">M</span></div>
<h3>{{ count($est8adsData['missingLinks']) }}</h3>
<p>Missing links</p></div>
<div class="kpi-card">
<div class="kpi-top"><span class="kpi-icon">R</span></div>
<h3>{{ count($est8adsData['requests']) }}</h3>
<p>Active property moves</p></div></div>
<div class="two-col" style="margin-top:18px">
<div class="card">
<div class="card-head">
<div>
<h3>Live move summary</h3>
<p>Your sale and next purchase are analyzed together.</p></div>
<button class="btn small" data-nav-to="move">Edit</button></div>
<div class="card-body">
<div class="property-grid" style="grid-template-columns:repeat(2,1fr)" id="userOverviewProperties"></div></div></div>
<div class="card">
<div class="card-head">
<div>
<h3>Recent activity</h3>
<p>Messages and match activity.</p></div></div>
<div class="card-body">
<div class="message-list" id="overviewMessages"></div></div></div></div>
@if (auth()->user()->isAgency())
<div class="card" style="margin-top:18px">
<div class="card-head">
<div>
<h3>Agency clients and moves</h3>
<p>Submit and manage requests on behalf of clients.</p></div>
<button class="btn primary" data-open-property>Add client property</button></div>
<div class="table-wrap" style="border:0;border-radius:0">
<table class="data-table">
<thead>
<tr>
<th>Client</th>
<th>Move</th>
<th>Properties</th>
<th>Best chain</th>
<th>Status</th>
<th>Actions</th></tr></thead>
<tbody id="agencyMovesTable"><tr><td colspan="6">No agency client moves yet.</td></tr></tbody></table></div></div>
@endif
</section>
<section class="panel-section" data-section="move">
<div class="section-head">
<div>
<h2>My complete property move</h2>
<p>Add one or several properties to sell and one or several properties you may buy.</p></div>
<button class="btn primary" data-open-property>Add another property</button></div>
<div class="card">
<div class="card-head">
<div>
<h3>Move settings</h3>
<p>These conditions help EST8ADS build realistic chains.</p></div>
<span class="status active">Request active</span></div>
<div class="card-body">
<div class="form-grid">
<div class="form-field">
<label>Move type</label>
<select>
<option>Sell one property and buy the next</option>
<option>Sell only</option>
<option>Buy only</option>
<option>Open to direct exchange</option></select></div>
<div class="form-field">
<label>Current stage</label>
<select>
<option>Planning only</option>
<option>Property ready to list</option>
<option>Property already advertised</option>
<option>Offer received</option>
<option>Next property already found</option></select></div>
<div class="form-field">
<label>Must sell before buying</label>
<select>
<option>Yes</option>
<option>No</option>
<option>Flexible</option></select></div>
<div class="form-field">
<label>Temporary housing possible</label>
<select>
<option>No</option>
<option>Yes</option></select></div>
<div class="form-field">
<label>Open to direct exchange</label>
<select>
<option>No</option>
<option>Yes</option></select></div>
<div class="form-field">
<label>Selling price flexibility</label>
<select>
<option>Up to 5%</option>
<option>Up to 10%</option>
<option>Up to 15%</option>
<option>Not flexible</option></select></div>
<div class="form-field full">
<label>Conditions that must be met</label>
<textarea>Sale proceeds must be available for the next purchase. Completion dates should be coordinated.</textarea></div></div>
<div style="display:flex;justify-content:flex-end;margin-top:16px">
<button class="btn primary" data-save-generic>Save move settings</button></div></div></div>
<div class="section-head" style="margin-top:25px">
<div>
<h2 style="font-size:23px">Properties connected to this move</h2>
<p>EST8ADS can analyze several possible sales and purchases in the same request.</p></div></div>
<div class="property-grid" id="userMoveProperties"></div></section>
<section class="panel-section" data-section="properties">
<div class="section-head">
<div>
<h2>My properties and requirements</h2>
<p>Manage listings you want to sell and the properties you want to buy.</p></div>
<button class="btn primary" data-open-property>Add property</button></div>
<div class="toolbar">
<div class="search">
<input id="userPropertySearch" placeholder="Search my properties"></div>
<select class="filter-select" id="userPropertySide">
<option value="all">Sell and buy</option>
<option value="sell">For sale</option>
<option value="buy">Wanted</option></select></div>
<div class="property-grid" id="userPropertiesGrid"></div></section>
<section class="panel-section" data-section="chains">
<div class="section-head">
<div>
<h2>AI property-chain analysis</h2>
<p>Review complete chains that connect your move with other participants and properties.</p></div>
<button class="btn primary" id="userRunAnalysis" data-analyze-url="{{ \App\Support\Est8adsRoute::to('moves.analyze') }}">Refresh analysis</button></div>
@php
$hasMatches = count($est8adsData['chainMatches'] ?? []) > 0;
@endphp
<div class="network-map {{ $hasMatches ? 'network-map--active' : '' }}" id="userNetworkMap">
@if (!$hasMatches)
<div class="network-caption">Chain visualization will appear here once properties are discovered and matched.</div>
@endif
</div>
@php 
$chainMatches = $est8adsData['chainMatches'] ?? [];
$discoveryJob = $est8adsData['latestDiscoveryJob'] ?? null;
@endphp
<div class="card" style="margin-top:18px">
<div class="card-head">
<div><h3>AI discovery activity</h3>
<p>Watch the AI search the internet, scrape property sites, and match listings to your requirements.</p></div>
<span class="status {{ $discoveryJob && $discoveryJob['status'] === 'running' ? 'active' : 'pending' }}" id="discoveryStatus">
{{ $discoveryJob ? ucfirst($discoveryJob['status']) : 'Idle' }}
</span>
</div>
<div class="card-body">
<div class="discovery-activity-feed" id="discoveryActivityFeed">
@if ($discoveryJob && isset($discoveryJob['activity']))
@foreach (array_slice($discoveryJob['activity'], -8) as $event)
<div class="activity-item discovery-event discovery-event--{{ $event['type'] ?? 'info' }}">
<span class="activity-mark">{{ $event['icon'] ?? '●' }}</span>
<div>
<strong>{{ $event['title'] }}</strong>
<p>{{ $event['message'] }}</p>
</div>
<time>{{ $event['time'] ?? 'just now' }}</time>
</div>
@endforeach
@else
<div class="discovery-event-empty">
<span class="discovery-pulse">◆</span>
<p>No active discovery running. Click "Refresh analysis" to start searching the internet.</p>
</div>
@endif
</div>
</div>
</div>
<div class="card" style="margin-top:18px">
<div class="card-head">
<div><h3>Properties found on the internet</h3>
<p>Exact matches first, then near matches inside your {{ (int) ($est8adsData['chainTolerance'] ?? 10) }}% size and price tolerance.</p></div>
@if (count($chainMatches))<span class="status potential">{{ count($chainMatches) }} found</span>@endif</div>
<div class="card-body">
@if (count($chainMatches))
<div class="chain-match-grid">
@foreach ($chainMatches as $match)
<article class="chain-match chain-match--{{ $match['kind'] }}">
<header class="chain-match-top">
<span class="chain-match-score">{{ number_format((float) $match['score']) }}<small>%</small></span>
<span class="status {{ $match['kind'] === 'exact' ? 'active' : 'pending' }}">{{ $match['kind'] === 'exact' ? 'Exact match' : 'Within tolerance' }}</span>
</header>
<div class="chain-match-body">
<h4>{{ $match['title'] }}</h4>
<p class="chain-match-location">{{ $match['city'] ?: 'Location not published' }}</p>
<div class="chain-match-meta">
<span>Price<strong>{{ $match['price'] ? number_format((float) $match['price']) . ' ' . $match['currency'] : '—' }}</strong>
@if ($match['priceNote'])<em>{{ $match['priceNote'] }}</em>@endif</span>
<span>Size<strong>{{ $match['size'] ? number_format((float) $match['size']) . ' m²' : '—' }}</strong>
@if ($match['sizeNote'])<em>{{ $match['sizeNote'] }}</em>@endif</span>
</div>
@if ($match['explanation'])<p class="chain-match-why">{{ $match['explanation'] }}</p>@endif
</div>
@if ($match['url'])
<footer class="chain-match-footer">
<a class="btn small" href="{{ $match['url'] }}" target="_blank" rel="noopener noreferrer">View listing ↗</a>
<span class="chain-match-host">{{ parse_url($match['url'], PHP_URL_HOST) }}</span>
</footer>
@endif
</article>
@endforeach
</div>
@else
<div class="chain-match-empty">
<strong>No properties discovered yet</strong>
<p>Results appear here automatically once a chain search has run for this listing.</p>
</div>
@endif
</div></div>
@php $memberMatches = $est8adsData['memberMatches'] ?? []; @endphp
<div class="card" style="margin-top:18px">
<div class="card-head">
<div><h3>Matches from other EST8ADS members</h3>
<p>Properties other EST8ADS members (and mirrored Villa Bit listings) are selling that fit what you want to buy, within {{ (int) ($est8adsData['chainTolerance'] ?? 15) }}% of your budget and size.</p></div>
@if (count($memberMatches))<span class="status potential">{{ count($memberMatches) }} found</span>@endif</div>
<div class="card-body">
@if (count($memberMatches))
<div class="chain-match-grid">
@foreach ($memberMatches as $match)
<article class="chain-match chain-match--{{ $match['kind'] }}">
<header class="chain-match-top">
<span class="chain-match-score">{{ number_format((float) $match['score']) }}<small>%</small></span>
<span class="status {{ $match['kind'] === 'exact' ? 'active' : 'pending' }}">{{ $match['kind'] === 'exact' ? 'Exact match' : 'Within tolerance' }}</span>
</header>
<div class="chain-match-body">
<h4>{{ $match['title'] }}</h4>
<p class="chain-match-location">{{ $match['city'] ?: 'Location not published' }} · {{ $match['owner'] }}</p>
<div class="chain-match-meta">
<span>Price<strong>{{ $match['price'] ? number_format((float) $match['price']) . ' ' . $match['currency'] : '—' }}</strong>
@if ($match['priceNote'])<em>{{ $match['priceNote'] }}</em>@endif</span>
<span>Size<strong>{{ $match['size'] ? number_format((float) $match['size']) . ' m²' : '—' }}</strong>
@if ($match['sizeNote'])<em>{{ $match['sizeNote'] }}</em>@endif</span>
</div>
@if ($match['explanation'])<p class="chain-match-why">{{ $match['explanation'] }}</p>@endif
</div>
@if ($match['url'])
<footer class="chain-match-footer">
<a class="btn small" href="{{ $match['url'] }}" target="_blank" rel="noopener noreferrer">View listing ↗</a>
</footer>
@endif
</article>
@endforeach
</div>
@else
<div class="chain-match-empty">
<strong>No member matches yet</strong>
<p>When another EST8ADS member lists a property that fits your budget and size, it appears here automatically.</p>
</div>
@endif
</div></div></section>
<section class="panel-section" data-section="matches">
<div class="section-head">
<div>
<h2>My matches</h2>
<p>Compatible properties, buyers and chain participants selected for your request.</p></div>
<select class="filter-select">
<option>All matches</option>
<option>Direct matches</option>
<option>Chain matches</option></select></div>
<div id="userMatchesGrid"></div></section>
<section class="panel-section" data-section="messages">
<div class="section-head">
<div>
<h2>Messages</h2>
<p>Communicate with verified agencies and the EST8ADS match team.</p></div>
<button class="btn primary">New message</button></div>
<div class="two-col">
<div class="card">
<div class="card-head">
<h3>Inbox</h3></div>
<div class="card-body">
<div class="message-list" id="messagesInbox"></div></div></div>
<div class="card"><div class="card-head"><h3>Conversation</h3></div><div class="card-body"><p>Select a live conversation from your inbox.</p></div></div></div></section>
<section class="panel-section" data-section="billing">
<div class="section-head">
<div>
<h2>Billing and payments</h2>
<p>Your complete sell-and-buy move in one connected workspace</p></div>
<button class="btn primary" data-open-property>Add property</button></div>
@php
$activeSubscription = $est8adsData['activeSubscription'] ?? null;
$payments = $est8adsData['payments'] ?? [];
@endphp
@if ($activeSubscription)
@php
$subscriptionBadge = match ($activeSubscription['status']) {
    'active' => '✓ ACTIVE',
    'suspended' => '⛔ SUSPENDED — PAYMENT OVERDUE',
    default => '⏳ AWAITING PAYMENT',
};
@endphp
<div class="card">
<div class="card-head">
<h3>Billing</h3>
<p>Manage request activation, invoices and payment history</p></div>
<div class="card-body">
<div class="subscription-card">
<div class="subscription-badge">{{ $subscriptionBadge }}</div>
<h3>{{ $activeSubscription['name'] }}</h3>
<p>{{ $activeSubscription['description'] }}</p>
<div class="subscription-price">
<strong>${{ number_format($activeSubscription['amount'], 2) }}</strong>
<span>/ {{ $activeSubscription['billing_period'] }} days</span></div>
@if ($activeSubscription['status'] === 'active')
<p style="font-size:11px;color:var(--muted)">Invoice {{ $activeSubscription['invoice_number'] }} paid on {{ $activeSubscription['paid_at'] }}.</p>
@else
<p style="font-size:11px;color:var(--muted)">Invoice {{ $activeSubscription['invoice_number'] }} due {{ $activeSubscription['due_on'] }}. Pay within {{ $activeSubscription['grace_period_days'] }} days of the due date to keep your account active.</p>
@endif
<a href="{{ route('est8ads.payment.paypal') }}" class="btn primary">{{ $activeSubscription['status'] === 'active' ? 'Extend another '.$activeSubscription['billing_period'].' days' : 'Pay now via PayPal' }}</a>
<p style="font-size:10px;color:var(--muted);margin-top:8px">Payments are reconciled manually — your invoice is marked as paid once our team confirms the PayPal transaction.</p>
</div></div></div>
@endif
<div class="card" style="margin-top:18px">
<div class="card-head">
<h3>Payment history</h3></div>
<div class="card-body">
@if (count($payments))
<div class="table-wrap" style="border:0;border-radius:0">
<table class="data-table">
<thead>
<tr>
<th>DATE</th>
<th>ITEM</th>
<th>AMOUNT</th>
<th>STATUS</th>
<th>INVOICE</th></tr></thead>
<tbody id="userPaymentsTable"></tbody></table></div>
@else
<p style="color:var(--muted);font-size:11px">No payment records yet.</p>
@endif
</div></div>
</section>
<section class="panel-section" data-section="profile">
<div class="section-head">
<div>
<h2>Profile and security</h2>
<p>Manage your personal details, privacy permissions and account security.</p></div>
<button class="btn primary" data-save-generic>Save profile</button></div>
<div class="card">
<div class="card-body">
<div class="profile-card">
<div class="profile-avatar">{{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}</div>
<div class="form-grid">
<div class="form-field">
<label>First name</label>
<input value="{{ auth()->user()->first_name }}"></div>
<div class="form-field">
<label>Last name</label>
<input value="{{ auth()->user()->last_name }}"></div>
<div class="form-field">
<label>Email</label>
<input value="{{ auth()->user()->email }}"></div>
<div class="form-field">
<label>Phone number</label>
<input value="{{ auth()->user()->phone }}"></div>
<div class="form-field">
<label>Preferred language</label>
<select>
<option>English</option>
<option>Croatian</option>
<option>German</option></select></div>
<div class="form-field">
<label>Country</label>
<input value="Croatia"></div>
<div class="form-field full">
<label>Account permissions</label>
<label style="display:flex;gap:9px;align-items:center;font-size:11px">
<input type="checkbox" checked> Verified agencies may contact me when they have a relevant buyer, seller or property.</label></div></div></div></div></div>
<div class="card" style="margin-top:18px">
<div class="card-head">
<h3>Security</h3></div>
<div class="card-body">
<div class="form-grid">
<div class="form-field">
<label>Current password</label>
<input type="password"></div>
<div class="form-field">
<label>New password</label>
<input type="password"></div>
<div class="form-field">
<label>Two-factor authentication</label>
<select>
<option>Enabled</option>
<option>Disabled</option></select></div>
<div class="form-field">
<label>Login alerts</label>
<select>
<option>Email and SMS</option>
<option>Email only</option></select></div></div></div></div></section></div></main></div>
<div class="modal-backdrop" id="propertyModal">
<div class="modal">
<div class="modal-head">
<h2>Add property to your move</h2>
<button class="modal-close">×</button></div>
<form id="propertyForm" method="POST" action="{{ route(request()->routeIs('est8ads.dev.*') ? 'est8ads.dev.listings.store' : (request()->routeIs('est8ads.local.*') ? 'est8ads.local.listings.store' : 'est8ads.listings.store')) }}" enctype="multipart/form-data">
@csrf
<div class="modal-body">
<div class="form-grid">
<div class="form-field">
<label>Property side *</label>
<select name="side" required>
<option value="sell">Property I want to sell</option>
<option value="buy">Property I want to buy</option></select></div>
<div class="form-field">
<label>Property type *</label>
<select name="type" required>
@include('partials.property-type-options', ['selected' => ''])
</select></div>
<div class="form-field full">
<label>Title *</label>
<input name="title" required></div>
<div class="form-field">
<label>Country *</label>
<input name="country" value="Croatia" required></div>
<div class="form-field">
<label>City or preferred location *</label>
<input name="city" required></div>
<div class="form-field full">
<label>Area / micro-location</label>
<input name="area"></div>
<div class="form-field">
<label>Asking price or maximum budget *</label>
<input name="price" type="number" required></div>
<div class="form-field">
<label>Currency</label>
<select name="currency">
<option>EUR</option>
<option>USD</option>
<option>GBP</option></select></div>
<div class="form-field">
<label>Interior size (m²)</label>
<input name="size" type="number"></div>
<div class="form-field">
<label>Bedrooms</label>
<input name="beds" type="number"></div>
<div class="form-field">
<label>Bathrooms</label>
<input name="baths" type="number"></div>
<div class="form-field">
<label>Price flexibility (%)</label>
<input name="flexibility" type="number" value="5"></div>
<div class="form-field full">
<label>Description and required conditions</label>
<textarea name="description"></textarea></div>
<div class="form-field full">
<label>Listing URL</label>
<input name="url" type="url" placeholder="https://..."></div>
<div class="form-field full">
<label>Property photos</label>
<input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple></div></div></div>
<div class="modal-foot">
<button class="btn" type="button" data-close-modal>Cancel</button>
<button class="btn primary" type="submit">Save property</button></div></form></div></div>
<div class="toast" id="panelToast"></div>
<script>window.EST8DATA = {{ Illuminate\Support\Js::from($est8adsData) }};</script>
<script src="{{ asset('est8ads-assets/panel/panel.js') }}"></script></body></html>
