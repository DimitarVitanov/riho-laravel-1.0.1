<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>EST8ADS Admin Panel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('est8ads/panel/panel.css') }}"></head>
<body data-panel-role="admin">
<div class="panel-app">
<aside class="panel-sidebar">
<a class="panel-brand" href="{{ route('est8ads.home') }}">
<img src="{{ asset('est8ads/panel/est8ads-logo.svg') }}" alt="EST8ADS"></a>
<div class="workspace-label">ADMIN WORKSPACE</div>
<nav class="side-nav">
<button data-section-target="overview" class="active">
<span class="nav-dot">◆</span>Overview</button>
<button data-section-target="requests" class="">
<span class="nav-dot">R</span>Property requests</button>
<button data-section-target="discovery" class="">
<span class="nav-dot">WEB</span>Internet discovery</button>
<button data-section-target="properties" class="">
<span class="nav-dot">P</span>All properties</button>
<button data-section-target="analyzer" class="">
<span class="nav-dot">AI</span>Chain analyzer</button>
<button data-section-target="map" class="">
<span class="nav-dot">◎</span>Chain map</button>
<button data-section-target="missing" class="">
<span class="nav-dot">?</span>Missing links</button>
<button data-section-target="matches" class="">
<span class="nav-dot">M</span>Matches</button>
<button data-section-target="users" class="">
<span class="nav-dot">U</span>Users</button>
<button data-section-target="agencies" class="">
<span class="nav-dot">A</span>Agencies</button>
<button data-section-target="billing" class="">
<span class="nav-dot">$</span>Payments</button>
<button data-section-target="reports" class="">
<span class="nav-dot">▥</span>Reports</button>
<button data-section-target="settings" class="">
<span class="nav-dot">⚙</span>System settings</button>
<button data-section-target="audit" class="">
<span class="nav-dot">↺</span>Audit log</button></nav>
<div class="sidebar-footer">
<div class="account-mini">
<div class="avatar">{{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}</div>
<div>
<strong>{{ auth()->user()->full_name }}</strong>
<small>{{ auth()->user()->email }}</small></div></div>
<button class="logout" data-logout>Sign out</button></div></aside>
<main class="panel-main">
<header class="panel-topbar">
<div style="display:flex;align-items:center;gap:12px">
<button class="icon-btn mobile-nav-toggle" aria-label="Open navigation">☰</button>
<div class="topbar-title">
<h1 data-page-title>Admin overview</h1>
<p>Complete control of the EST8ADS property-chain system</p></div></div>
<div class="topbar-actions">
<button class="icon-btn" title="Notifications">●</button>
<button class="top-primary" data-open-property>Add property</button></div></header>
<div class="panel-content">
<section class="panel-section active" data-section="overview">
<div class="section-head">
<div>
<h2>System overview</h2>
<p>Monitor active property moves, AI chain opportunities and revenue.</p></div>
<div class="section-actions">
<button class="btn">Export report</button>
<button class="btn primary" data-nav-to="analyzer">Run chain analysis</button></div></div>
<div class="grid kpis">
<div class="kpi-card">
<div class="kpi-top">
<span class="kpi-icon">P</span>
</div>
<h3>{{ count($est8adsData['properties']) + count($est8adsData['requests']) }}</h3>
<p>Active properties and requests</p></div>
<div class="kpi-card"><div class="kpi-top"><span class="kpi-icon">AI</span></div>
<h3>{{ count($est8adsData['chains']) }}</h3><p>Potential property chains</p></div>
<div class="kpi-card"><div class="kpi-top"><span class="kpi-icon">?</span></div>
<h3>{{ count($est8adsData['missingLinks']) }}</h3><p>High-value missing links</p></div>
<div class="kpi-card"><div class="kpi-top"><span class="kpi-icon">$</span></div>
<h3>{{ count($est8adsData['payments']) }}</h3><p>Recorded payments</p></div></div>
<div class="two-col" style="margin-top:18px">
<div class="card">
<div class="card-head">
<div>
<h3>Highest-value chain opportunities</h3>
<p>AI-ranked by compatibility and unlocked transaction value.</p></div>
<button class="btn small" data-nav-to="analyzer">Open analyzer</button></div>
<div class="card-body">
<div class="alert-list" id="overviewChains"></div></div></div>
<div class="card">
<div class="card-head">
<div>
<h3>Recent system activity</h3>
<p>Latest updates from users, agencies and AI.</p></div></div>
<div class="card-body">
<div class="activity-list" id="systemActivity"><p>No system activity recorded yet.</p></div></div></div></div></section>
<section class="panel-section" data-section="requests">
<div class="section-head">
<div>
<h2>Property move requests</h2>
<p>Review sell-only, buy-only and connected sell/buy moves.</p></div>
<div class="section-actions">
<button class="btn" data-nav-to="discovery">Internet search queue</button>
<button class="btn">Bulk update</button>
<button class="btn primary" data-open-property>New request</button></div></div>
<div class="toolbar">
<div class="search">
<input placeholder="Search by user, property or request ID"></div>
<select class="filter-select">
<option>All move types</option>
<option>Sell and buy</option>
<option>Sell only</option>
<option>Buy only</option></select>
<select class="filter-select">
<option>All statuses</option>
<option>Active</option>
<option>Pending review</option>
<option>Draft</option></select></div>
<div class="table-wrap">
<table class="data-table">
<thead>
<tr>
<th>Request</th>
<th>User</th>
<th>Move type</th>
<th>Sell value</th>
<th>Buy budget</th>
<th>Status</th>
<th>Created</th>
<th>Actions</th></tr></thead>
<tbody id="requestsTable"></tbody></table></div></section>


@php($discoveryRoute = (\Illuminate\Support\Facades\Route::is('est8ads.local.*') ? 'est8ads.local.' : 'est8ads.') . 'admin.discovery.')
<section class="panel-section" data-section="discovery"
    data-jobs-store-url="{{ route($discoveryRoute . 'jobs.store') }}"
    data-job-retry-url="{{ route($discoveryRoute . 'jobs.retry', ['__JOB__']) }}"
    data-settings-url="{{ route($discoveryRoute . 'settings.update') }}"
    data-match-import-url="{{ route($discoveryRoute . 'matches.import', ['__MATCH__']) }}"
    data-match-connect-url="{{ route($discoveryRoute . 'matches.connect', ['__MATCH__']) }}"
    data-match-reject-url="{{ route($discoveryRoute . 'matches.reject', ['__MATCH__']) }}"
    data-bulk-import-url="{{ route($discoveryRoute . 'matches.bulk-import') }}"
    data-bulk-connect-url="{{ route($discoveryRoute . 'matches.bulk-connect') }}">
  
<div class="section-head">
    
<div>
      
<h2>AI internet property discovery</h2>
      
<p>Every new property request automatically starts an internet search, identifies similar active listings and connects the strongest candidates to the EST8ADS property graph.</p>
    </div>
    
<div class="section-actions">
      
<button class="btn" id="pauseDiscovery">Pause automation</button>
      
<button class="btn primary" id="runDiscoveryAll">Search all active requests now</button>
    </div>
  </div>

  
<div class="discovery-banner">
    
<div class="discovery-banner-icon">WEB</div>
    
<div>
      
<strong id="discoveryAutomationTitle">Loading automation status</strong>
      
<p>Trigger: new sell request, new buy request or edited search criteria. The queued worker searches configured internet sources, normalizes listing data, removes duplicates, calculates similarity and creates proposed property connections.</p>
    </div>
    
<span class="status pending" id="discoveryAutomationStatus">LOADING</span>
  </div>

  
<div class="grid kpis discovery-kpis">
    
<div class="kpi-card">
<div class="kpi-top">
<span class="kpi-icon">Q</span>
<span class="kpi-change">live queue</span></div>
<h3 id="discoveryQueueKpi">0</h3>
<p>Requests waiting or searching</p></div>
    
<div class="kpi-card">
<div class="kpi-top">
<span class="kpi-icon">WEB</span>
<span class="kpi-change" id="discoverySourcesActive">0 active</span></div>
<h3 id="discoverySourcesKpi">0</h3>
<p>Configured websites and search feeds</p></div>
    
<div class="kpi-card">
<div class="kpi-top">
<span class="kpi-icon">F</span>
<span class="kpi-change">today</span></div>
<h3 id="discoveryFoundKpi">0</h3>
<p>Similar internet listings found</p></div>
    
<div class="kpi-card">
<div class="kpi-top">
<span class="kpi-icon">↔</span>
<span class="kpi-change">AI approved</span></div>
<h3 id="discoveryConnectedKpi">0</h3>
<p>Listings automatically connected</p></div>
  </div>

  
<div class="discovery-layout">
    
<div class="card discovery-config-card">
      
<div class="card-head">
<div>
<h3>Automatic discovery configuration</h3>
<p>Rules applied whenever a user or agency submits a property request.</p></div>
<span class="status pending" id="discoveryConfigStatus">LOADING</span></div>
      
<div class="card-body">
        
<div class="form-grid">
          
<div class="form-field">
<label>Automation trigger</label>
<select id="discoveryTrigger" name="trigger">
<option value="immediate">Immediately after every new request</option>
<option value="after_approval">After administrator approval</option>
<option value="manual">Manual only</option></select></div>
          
<div class="form-field">
<label>Refresh existing requests</label>
<select id="discoveryRefresh" name="refresh_interval">
<option value="12_hours">Every 12 hours</option>
<option value="24_hours">Every 24 hours</option>
<option value="3_days">Every 3 days</option>
<option value="never">Never</option></select></div>
          
<div class="form-field">
<label>Minimum similarity to save (%)</label>
<input id="discoverySaveThreshold" name="save_threshold" type="number" min="0" max="100"></div>
          
<div class="form-field">
<label>Minimum score for auto-connect (%)</label>
<input id="discoveryConnectThreshold" name="connect_threshold" type="number" min="0" max="100"></div>
          
<div class="form-field">
<label>Maximum results per request</label>
<input id="discoveryResultLimit" name="result_limit" type="number" min="10" max="500"></div>
          
<div class="form-field">
<label>Search radius</label>
<select id="discoveryRadius" name="radius">
<option value="city_nearby">Exact city + nearby areas</option>
<option value="exact_area">Exact area only</option>
<option value="country">Whole country</option>
<option value="international">International</option></select></div>
        </div>
        
<div class="source-selector">
          
<label class="source-check">
<input type="checkbox" name="sources[]" value="portals">
<span>
<strong>Real estate portals</strong>
<small>Configured marketplace APIs and permitted search feeds</small></span></label>
          
<label class="source-check">
<input type="checkbox" name="sources[]" value="agencies">
<span>
<strong>Agency websites</strong>
<small>Agency XML feeds, sitemaps and permitted listing pages</small></span></label>
          
<label class="source-check">
<input type="checkbox" name="sources[]" value="private_owners">
<span>
<strong>Private-owner listings</strong>
<small>Public listings where collection and reuse are permitted</small></span></label>
          
<label class="source-check">
<input type="checkbox" name="sources[]" value="developments">
<span>
<strong>New developments</strong>
<small>Developer inventories and project pages</small></span></label>
        </div>
        
<div class="discovery-save-row">
<button class="btn" id="testDiscoveryRules" type="button">Test rules</button>
<button class="btn primary" id="saveDiscoveryRules" type="button">Save automation rules</button></div>
      </div>
    </div>

    
<div class="card discovery-request-card">
      
<div class="card-head">
<div>
<h3>Search one request now</h3>
<p>Run the internet discovery workflow for a selected request.</p></div></div>
      
<div class="card-body">
        
<div class="form-field">
<label>Property request</label>
<select id="discoveryRequestSelect"><option value="">No active requests</option></select></div>
        
<div class="request-search-profile" id="discoveryRequestProfile"><span>SEARCH PROFILE</span><strong>Select a live request</strong><p>No request selected.</p></div>
        
