@extends('layouts.simple.master')

@section('title', 'Add Competitor')

@push('css')
<style>
label{font-weight:700;font-size:11px;margin-bottom:6px;display:block}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
.form-group{margin-bottom:12px}
.form-control{width:100%;border:1px solid #d7dbe1;padding:10px 11px;border-radius:7px;font-family:inherit;font-size:12px}
.form-control:focus{outline:none;border-color:#697484;box-shadow:0 0 0 3px rgba(32,39,51,.08)}
.check-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px 14px}
.check{display:flex;align-items:center;gap:7px;font-size:12px}
.notice{padding:12px 14px;background:#f8fafc;border:1px solid #e1e5ea;border-radius:8px;color:#4f5865;line-height:1.5}
.vb-page-head{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:20px}
.vb-page-head h1{font-size:24px;margin:0 0 6px;font-weight:800}
.vb-page-head p{margin:0;color:#69707d;font-size:13px;max-width:780px;line-height:1.5}
.vb-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:10px 14px;border:1px solid #202733;background:#202733;color:white;border-radius:7px;font-weight:700;cursor:pointer;font-size:13px;text-decoration:none}
.vb-btn:hover{background:#0f1319;color:white}
.vb-btn-light{background:white;color:#202733;border:1px solid #cfd4db}
.vb-btn-light:hover{background:#f3f4f6;color:#111}
.vb-card{background:white;border:1px solid #e2e5ea;border-radius:14px;box-shadow:0 2px 8px rgba(18,24,40,.03);overflow:hidden;margin-bottom:14px}
.vb-card-head{padding:16px 20px;border-bottom:1px solid #e2e5ea;display:flex;align-items:center;justify-content:space-between;gap:10px}
.vb-card-head h2{font-size:15px;margin:0;font-weight:800}
.vb-card-body{padding:18px 20px}
.vb-grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.vb-grid-2{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}
.vb-toolbar{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
.badge-soft{display:inline-flex;padding:5px 9px;border-radius:999px;font-weight:700;font-size:10px}
.b-blue{background:#eef5ff;color:#1765cd}
.small{font-size:11px}
.muted{color:#747b87}
@media(max-width:900px){.vb-grid-3,.vb-grid-2{grid-template-columns:1fr}.form-row{grid-template-columns:1fr}.check-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('main_content')
<div class="container-fluid">
<div class="vb-page-head">
  <diV>
    <h1>Add Competitor</h1>
    <p>Add a real estate competitor once. Villa Bit AI will build its Competitor Digital Twin and continuously monitor property inventory, pricing, website changes, SEO pages, reputation signals and external internet activity.</p>
  </div>
  <div class="vb-toolbar">
    <a href="{{ route('agency.competitor-intelligence.competitors.index') }}" class="vb-btn vb-btn-light"><span class="text-dark">Cancel</span></a>
    <button type="submit" form="competitor-form" class="vb-btn">Save & Start Discovery</button>
  </div>
</div>

<form id="competitor-form" action="{{ route('agency.competitor-intelligence.competitors.store') }}" method="POST">
@csrf

<div class="vb-grid-3">
  <div class="vb-card" style="grid-column:span 3">
    <div class="vb-card-head"><h2>1. Competitor Identity</h2><span class="badge-soft b-blue">REQUIRED</span></div>
    <div class="vb-card-body">
      <div class="form-group"><label>Competitor / Agency Name</label><input class="form-control" name="name" value="{{ old('name') }}" placeholder="e.g. Viga Nekretnine" required></div>
      <div class="form-group"><label>Main Website URL</label><input class="form-control" name="website_url" value="{{ old('website_url') }}" placeholder="https://www.competitor.com" required></div>
      <div class="form-row">
        <div><label>Legal Company Name (optional)</label><input class="form-control" name="legal_name" value="{{ old('legal_name') }}" placeholder="e.g. Example Real Estate d.o.o."></div>
        <div><label>Country</label><select class="form-control" name="country"><option value="" disabled {{ old('country') ? '' : 'selected' }}>Select country</option>@foreach($countries as $country)<option value="{{ $country->name }}" {{ old('country') === $country->name ? 'selected' : '' }}>{{ $country->iso_3166_2 }} — {{ $country->name }}</option>@endforeach</select></div>
      </div>
      <div class="form-group"><label>Brand Variations / Aliases</label><input class="form-control" name="aliases" value="{{ old('aliases') }}" placeholder="Separate aliases with commas"></div>
      <div class="notice">Villa Bit AI uses identity signals such as brand name, legal name, domain, phones, emails and aliases to match the same competitor across different websites and external sources.</div>
    </div>
  </div>

  <div class="vb-card d-none">
    <div class="vb-card-head"><h2>Monitoring Status</h2></div>
    <div class="vb-card-body">
      <label class="check"><input type="checkbox" name="is_active" value="1" checked> Active monitoring</label>
      <label class="check"><input type="checkbox" name="include_in_daily_report" value="1" checked> Include in daily report</label>
      <label class="check"><input type="checkbox" name="include_in_comparison" value="1" checked> Include in all-competitor comparison</label>
      <label style="margin-top:14px">Priority</label>
      <select class="form-control" name="priority"><option value="high">High</option><option value="normal" selected>Normal</option><option value="low">Low</option></select>
      <label style="margin-top:14px">Scan profile</label>
      <select class="form-control" name="scan_profile"><option value="full" selected>Full Real Estate Intelligence</option><option value="website_properties">Website + Properties</option><option value="seo_only">SEO Only</option><option value="custom">Custom</option></select>
    </div>
  </div>
</div>

<div class="vb-grid-2" style="margin-top:18px">
  <div class="vb-card">
    <div class="vb-card-head"><h2>2. Google & Reputation Identity</h2></div>
    <div class="vb-card-body">
      <div class="form-group"><label>Google Maps / Business Profile URL</label><input class="form-control" name="google_maps_url" value="{{ old('google_maps_url') }}" placeholder="https://maps.google.com/..."></div>
      <div class="form-group"><label>Google Place ID (optional)</label><input class="form-control" name="google_place_id" value="{{ old('google_place_id') }}" placeholder="ChIJ..."></div>
      <label class="check"><input type="checkbox" name="track_rating" value="1" checked> Track rating movement</label>
      <label class="check"><input type="checkbox" name="track_reviews" value="1" checked> Track observed review-count changes</label>
      <label class="check"><input type="checkbox" name="analyze_reviews" value="1" checked> Analyze available recent public review signals</label>
    </div>
  </div>

  <div class="vb-card">
    <div class="vb-card-head"><h2>3. Contact & Entity Fingerprints</h2></div>
    <div class="vb-card-body">
      <div class="form-group"><label>Known Phone Numbers</label><textarea class="form-control" name="phones" rows="3" placeholder="+385 ...&#10;+385 ...">{{ old('phones') }}</textarea></div>
      <div class="form-group"><label>Known Email Domains / Emails</label><textarea class="form-control" name="emails" rows="3" placeholder="@competitor.com&#10;info@competitor.com">{{ old('emails') }}</textarea></div>
      <div class="form-group"><label>Important Agent / Founder Names</label><input class="form-control" name="agent_names" value="{{ old('agent_names') }}" placeholder="Names used for internet discovery"></div>
    </div>
  </div>
</div>

<div class="vb-card" style="margin-top:18px">
  <div class="vb-card-head"><h2>4. Sources to Monitor</h2><span class="small muted">Choose everything Villa Bit AI should discover and track</span></div>
  <div class="vb-card-body">
    <div class="check-grid">
      <label class="check"><input type="checkbox" name="sources[]" value="website" checked> Website & homepage</label>
      <label class="check"><input type="checkbox" name="sources[]" value="sitemap" checked> Sitemap / sitemap indexes</label>
      <label class="check"><input type="checkbox" name="sources[]" value="properties" checked> Property listing pages</label>
      <label class="check"><input type="checkbox" name="sources[]" value="developments" checked> New developments / projects</label>
      <label class="check"><input type="checkbox" name="sources[]" value="seo_pages" checked> Location / SEO pages</label>
      <label class="check"><input type="checkbox" name="sources[]" value="blog" checked> Blog / news / guides</label>
      <label class="check"><input type="checkbox" name="sources[]" value="price_changes" checked> Price changes</label>
      <label class="check"><input type="checkbox" name="sources[]" value="removed" checked> Removed / disappeared properties</label>
      <label class="check"><input type="checkbox" name="sources[]" value="content_changes" checked> Description / image changes</label>
      <label class="check"><input type="checkbox" name="sources[]" value="meta_changes" checked> Title / H1 / meta / schema</label>
      <label class="check"><input type="checkbox" name="sources[]" value="google_reputation" checked> Google reputation signals</label>
      <label class="check"><input type="checkbox" name="sources[]" value="portals" checked> Property portals</label>
      <label class="check"><input type="checkbox" name="sources[]" value="external_mentions" checked> External mentions / news</label>
      <label class="check"><input type="checkbox" name="sources[]" value="backlinks" checked> New backlinks / directories</label>
      <label class="check"><input type="checkbox" name="sources[]" value="social"> Public social-source adapters</label>
    </div>
  </div>
</div>

<div class="vb-card" style="margin-top:18px">
  <div class="vb-card-head"><h2>5. Known External Profiles & Sources</h2></div>
  <div class="vb-card-body">
    <div class="form-group"><label>Property Portal Profile URLs</label><textarea class="form-control" name="portal_urls" rows="4" placeholder="One URL per line">{{ old('portal_urls') }}</textarea></div>
    <div class="form-group"><label>Social / Directory / Other Known URLs</label><textarea class="form-control" name="social_urls" rows="4" placeholder="One URL per line">{{ old('social_urls') }}</textarea></div>
    <div class="notice"><b>Automatic discovery:</b> You do not need to know every source. After saving, Villa Bit AI should run an initial discovery scan to find sitemaps, feeds, property templates, public profiles and matching external mentions automatically.</div>
  </div>
</div>

<div style="display:flex;justify-content:flex-end;gap:10px;margin-top:18px">
  <a href="{{ route('agency.competitor-intelligence.competitors.index') }}" class="vb-btn vb-btn-light"><span class="text-dark">Cancel</span></a>
  <button type="submit" class="vb-btn">Save & Build Competitor Digital Twin</button>
</div>
</form>
</div>
@endsection
