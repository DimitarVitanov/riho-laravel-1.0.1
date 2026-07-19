<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
@php
  $primaryColor   = '#0A0B0D';
  $secondaryColor = '#6b7280';
  $accentColor    = $profile->website_accent_color ?? '#0A0B0D';

  $headerBg       = $profile->header_bg_color ?: '#ffffff';
  $headerTextClr  = $profile->header_text_color ?: '#111827';
  $topbarEnabled  = $profile->header_topbar_enabled === null ? true : (bool)$profile->header_topbar_enabled;
  $topbarText     = $profile->header_topbar_text ?: 'Property information presented by the real estate agency on behalf of Villa Ready Croatia.';
  $logoType       = $profile->header_logo_type ?? 'text';
  $logoText       = $profile->header_logo_text ?: $profile->agency_name;
  $logoPath       = $profile->header_logo_path ? asset('storage/' . $profile->header_logo_path) : null;
  $ctaEnabled     = $profile->header_cta_enabled === null ? true : (bool)$profile->header_cta_enabled;
  $ctaText        = $profile->header_cta_text ?: 'Contact Agency';
  $ctaUrl         = $profile->header_cta_url ?? '#contact';
  $ctaBg          = $profile->header_cta_bg_color ?: '#f59e0b';
  $ctaClr         = $profile->header_cta_text_color ?: '#1a1a1a';
  $topbarColor    = $profile->header_topbar_color ?: '#ffffff';
  $topbarBg       = $profile->header_topbar_bg_color ?: '#0A0B0D';

  $footerBg       = $profile->footer_bg_color ?? '#0A0B0D';
  $footerTextClr  = $profile->footer_text_color ?? '#ffffff';
  $copyright      = $profile->footer_copyright_text ?: ('© ' . date('Y') . ' ' . $profile->agency_name . '. All rights reserved.');
  
  $galleryImages = $property->images->whereNotIn('image_type', ['floor_plan'])->take(5);
  $floorPlans = $property->images->where('image_type', 'floor_plan');
  $featuredImage = $property->featured_image ? asset('storage/' . $property->featured_image) : null;
@endphp
<meta name="description" content="{{ $property->meta_description ?? $property->intro }}">
<meta name="robots" content="index, follow">
<title>{{ $property->meta_title ?? $property->title }} | {{ $profile->agency_name }}</title>
<link rel="canonical" href="{{ url()->current() }}">

@php
$schemaData = [
    '@context' => 'https://schema.org',
    '@type' => 'RealEstateListing',
    'name' => $property->title,
    'description' => $property->description,
    'url' => url()->current(),
    'image' => $property->featured_image ? asset('storage/' . $property->featured_image) : null,
    'offers' => [
        '@type' => 'Offer',
        'price' => $property->price_per_m2,
        'priceCurrency' => 'EUR',
    ],
    'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => $property->location,
        'addressCountry' => 'HR',
    ],
];
@endphp
<script type="application/ld+json">{!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

<style>
:root {
  --ink: {{ $primaryColor }};
  --muted: {{ $secondaryColor }};
  --bg: #f4f5f6;
  --card: #ffffff;
  --soft: #f8f9fa;
  --line: #e4e6e9;
  --accent: {{ $accentColor }};
  --radius: 18px;
  --max: 90%;
}
* { box-sizing: border-box; }
html { scroll-behavior: smooth; }
body {
  margin: 0;
  background: var(--bg);
  color: var(--ink);
  font-family: Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  line-height: 1.6;
}
a { text-decoration: none; color: inherit; }
a:hover { text-decoration: underline; }
img { max-width: 100%; }

.wrap { max-width: var(--max); margin: 0 auto; padding: 32px 24px; width: 100%; box-sizing: border-box; }

.topbar-strip {
  background: {{ $topbarBg }};
  color: {{ $topbarColor }};
  text-align: left;
  padding: 10px 16px;
  font-size: 13px;
  font-weight: 500;
  padding-left: 6.5%;
}

.site-header {
  background: {{ $headerBg }};
  border-bottom: 1px solid var(--line);
  padding: 16px 24px;
}
.header-inner {
  max-width: var(--max);
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
}
.brand { display: flex; flex-direction: column; align-items: flex-start; gap: 6px; }
.brand-mark {
  width: 44px; height: 44px;
  border-radius: 12px;
  background: var(--ink);
  display: grid; place-items: center;
  color: #fff;
  font-size: 20px;
  font-weight: 800;
}
.brand strong { display: block; font-size: 13px; letter-spacing: -0.01em; color: {{ $headerTextClr }}; }
.brand small { display: block; color: var(--muted); font-weight: 500; font-size: 12px; }
.nav { display: flex; gap: 24px; align-items: center; }
.nav a { font-size: 14px; font-weight: 600; color: {{ $headerTextClr }}; }
.nav a:hover { color: var(--accent); text-decoration: none; }
.nav .cta-btn {
  background: {{ $ctaBg }};
  color: {{ $ctaClr }};
  padding: 12px 20px;
  border-radius: 10px;
  font-weight: 700;
  transition: filter 0.5s ease;
}
.nav .cta-btn:hover { filter: brightness(1.15); color: white; }

.hero {
  background: var(--ink);
  color: #fff;
  border-radius: 5px;
  padding: 48px;
  margin-bottom: 24px;
  position: relative;
  overflow: hidden;
}
.hero::before {
  content: "";
  position: absolute;
  right: -80px;
  top: -80px;
  width: 280px;
  height: 280px;
  background: rgba(255,255,255,0.06);
  border-radius: 50%;
  pointer-events: none;
}
.hero::after {
  content: "";
  position: absolute;
  right: 120px;
  bottom: -60px;
  width: 180px;
  height: 180px;
  background: rgba(255,255,255,0.04);
  border-radius: 50%;
  pointer-events: none;
}
.eyebrow {
  display: inline-flex;
  gap: 8px;
  align-items: center;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.16);
  padding: 8px 14px;
  border-radius: 999px;
  color: rgba(255,255,255,0.7);
  font-weight: 700;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}
h1 {
  margin: 20px 0 16px;
  font-size: clamp(36px, 5vw, 64px);
  line-height: 1;
  letter-spacing: -0.04em;
  font-weight: 800;
}
.hero-desc {
  font-size: 19px;
  opacity: 0.85;
  max-width: 100%;
  line-height: 1.7;
}
.property-meta { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px; }
.chip {
  display: inline-flex;
  padding: 6px 10px;
  border-radius: 999px;
  background: rgba(255,255,255,.1);
  border: 1px solid rgba(255,255,255,.22);
  color: #fff;
  font-size: 12px;
  font-weight: 700;
}

.card {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: var(--radius);
  overflow: hidden;
}
.pad { padding: 24px; }

.title { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 20px; }
h2 { margin: 0; font-size: 36px; line-height: 1.15; letter-spacing: -0.03em; font-weight: 800; }
h3 { margin: 0 0 10px; font-size: 24px; letter-spacing: -0.02em; font-weight: 700; }
.sub { color: var(--muted); font-weight: 600; margin: 4px 0 0; font-size: 14px; }

.article p { font-size: 16px; color: #374151; margin: 0 0 16px; line-height: 1.75; }
.highlight-box {
  border: 1px solid var(--line);
  background: var(--soft);
  border-radius: 14px;
  padding: 18px;
  margin-top: 20px;
  color: var(--ink);
  font-weight: 700;
  font-size: 15px;
}

.property-gallery { display: grid; grid-template-columns: 2fr 1fr 1fr; grid-template-rows: 220px 220px; gap: 10px; }
.property-gallery img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; cursor: pointer; }
.property-gallery img:first-child { grid-row: 1/3; }
.image-note { font-size: 12px; color: var(--muted); margin-top: 8px; }

.layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: 24px;
  align-items: start;
}
main { display: grid; gap: 20px; }
.sidebar { position: sticky; top: 24px; display: grid; gap: 16px; }

.spec-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
.spec-box { padding: 18px; border: 1px solid var(--line); border-radius: 14px; background: var(--soft); }
.spec-box strong { display: block; font-size: 18px; }
.spec-box span { color: var(--muted); font-size: 12px; font-weight: 700; }

.grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.grid3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.mini-card {
  border: 1px solid var(--line);
  background: var(--soft);
  border-radius: 14px;
  padding: 18px;
}
.mini-card b { display: block; font-size: 16px; margin-bottom: 6px; color: var(--ink); }
.mini-card p { margin: 0; color: var(--muted); font-weight: 500; font-size: 14px; }

.unit-table { width: 100%; border-collapse: collapse; }
.unit-table th, .unit-table td { padding: 14px; border-bottom: 1px solid var(--line); text-align: left; }
.unit-table th { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); }
.status-pill { display: inline-flex; padding: 7px 11px; border-radius: 999px; font-size: 11px; font-weight: 800; }
.status-available { background: #dcfce7; color: #166534; }
.status-reserved { background: #fef3c7; color: #92400e; }
.status-sold { background: #fee2e2; color: #991b1b; }

.price-panel { padding: 24px; background: #0A0B0D; color: #fff; border-radius: 16px; }
.price-panel .big { font-size: 34px; font-weight: 900; }
.price-panel small { display: block; font-size: 12px; opacity: 0.7; margin-bottom: 8px; }
.price-panel p { font-size: 14px; opacity: 0.8; margin: 12px 0 16px; }

.sidebar-box .head {
  background: var(--ink);
  color: #fff;
  padding: 18px 20px;
  font-size: 18px;
  font-weight: 800;
}
.sidebar-list { padding: 14px 18px 18px; }
.sidebar-row {
  display: flex;
  gap: 12px;
  align-items: center;
  padding: 12px 4px;
  border-bottom: 1px solid var(--line);
  font-weight: 700;
  color: var(--ink);
  font-size: 14px;
}
.sidebar-row:last-child { border-bottom: 0; }
.ico {
  width: 38px; height: 38px;
  border-radius: 10px;
  background: var(--soft);
  display: grid; place-items: center;
  color: var(--ink);
  font-weight: 800;
  flex: 0 0 auto;
  font-size: 15px;
}

.notice-bar {
  border: 1px solid #f59e0b;
  background: #fffbeb;
  color: #78350f;
  padding: 13px 16px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 700;
}

.agency-contact { background: #0A0B0D; color: #fff; }
.agency-contact .sub { color: rgba(255,255,255,.72); }
.agency-contact input, .agency-contact textarea, .agency-contact select {
  width: 100%;
  padding: 14px 16px;
  border: 2px solid rgba(255,255,255,.28);
  border-radius: 12px;
  font: inherit;
  font-size: 14px;
  background: rgba(255,255,255,.05);
  color: #fff;
}
.agency-contact input::placeholder, .agency-contact textarea::placeholder { color: rgba(255,255,255,.5); }
.agency-contact input:focus, .agency-contact textarea:focus, .agency-contact select:focus { outline: none; border-color: #fff; }
.form { display: grid; gap: 12px; }
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 14px 20px;
  border-radius: 12px;
  border: 1px solid var(--line);
  font-weight: 700;
  font-size: 14px;
  background: #fff;
  color: var(--ink);
  cursor: pointer;
  transition: all 0.2s;
}
.btn:hover { text-decoration: none; border-color: var(--ink); }

.site-footer {
  background: {{ $footerBg }};
  color: {{ $footerTextClr }};
  padding: 48px 24px 24px;
  margin-top: 48px;
}
.footer-inner { max-width: var(--max); margin: 0 auto; }
.footer-grid {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 32px;
  margin-bottom: 32px;
}
.footer-col h4 {
  font-size: 15px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 16px;
  opacity: 0.7;
}
.footer-col p, .footer-col a {
  font-size: 15px;
  line-height: 1.8;
  opacity: 0.8;
  font-weight: 600;
}
.footer-col a:hover { opacity: 1; text-decoration: none; }
.footer-bottom {
  border-top: 1px solid rgba(255,255,255,0.1);
  padding: 20px 0 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 14px;
}
.footer-links { display: flex; gap: 24px; }
.footer-links a { font-weight: 600; }

.modal-viewer { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.9); z-index: 9999; padding: 35px; }
.modal-viewer.open { display: grid; place-items: center; }
.modal-viewer img { max-height: 90vh; max-width: 95vw; border-radius: 12px; }
.modal-close { position: fixed; top: 18px; right: 25px; color: white; font-size: 34px; cursor: pointer; }

@media (max-width: 1100px) {
  .layout { grid-template-columns: 1fr; }
  .sidebar { position: static; }
}
@media (max-width: 900px) {
  .grid2, .grid3, .spec-grid { grid-template-columns: 1fr; }
  .property-gallery { grid-template-columns: 1fr 1fr; grid-template-rows: auto; }
  .property-gallery img:first-child { grid-row: auto; grid-column: 1/3; height: 300px; }
  .property-gallery img { height: 180px; }
  .nav { display: none; }
  h1 { font-size: 36px; }
  h2 { font-size: 24px; }
  .footer-grid { grid-template-columns: 1fr 1fr; gap: 24px; }
  .footer-bottom { flex-direction: column; gap: 12px; text-align: center; }
}
@media (max-width: 600px) {
  .footer-grid { grid-template-columns: 1fr; }
}
@media (max-width: 560px) {
  .wrap { padding: 16px; }
  .hero { padding: 28px; }
  .pad { padding: 18px; }
  .property-gallery { display: block; }
  .property-gallery img { height: 220px; margin-bottom: 10px; }
  .property-gallery img:first-child { height: 260px; }
  .spec-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="topbar-strip">{{ $topbarText }}</div>

<header class="site-header">
  <div class="header-inner">
    <a class="brand" href="/">
      @if($logoType === 'image' && $logoPath)
        <img src="{{ $logoPath }}" alt="{{ $profile->agency_name }}" style="height:44px">
      @else
        <span class="brand-mark">⌂</span>
        <span><strong>{{ strtoupper($logoText) }}</strong><small>Local property experts</small></span>
      @endif
    </a>
    <nav class="nav">
      <a href="/">Properties</a>
      <a href="#">Areas</a>
      <a href="#">Services</a>
      <a href="#">About</a>
      @if($ctaEnabled)
      <a class="cta-btn" href="{{ $ctaUrl }}">{{ $ctaText }}</a>
      @endif
    </nav>
  </div>
</header>

<div class="wrap">
  <section class="hero">
    <span class="eyebrow">Exclusive property offered by our agency</span>
    <h1>{{ $property->title }}</h1>
    <p class="hero-desc">{{ $property->intro }}</p>
    <div class="property-meta">
      <span class="chip">{{ $property->location }}</span>
      @if($property->property_type)
      <span class="chip">{{ $property->property_type }}</span>
      @endif
      @if($property->buildings_count)
      <span class="chip">{{ $property->buildings_count }} buildings</span>
      @endif
    </div>
  </section>

  @php
    $placeholders = [
      'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNjAwIiBoZWlnaHQ9IjEwMDAiIHZpZXdCb3g9IjAgMCAxNjAwIDEwMDAiPjxkZWZzPjxsaW5lYXJHcmFkaWVudCBpZD0iZyIgeDE9IjAiIHkxPSIwIiB4Mj0iMSIgeTI9IjEiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iIzExMTgyNyIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iIzI0MzQ0NyIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZykiLz48cGF0aCBkPSJNMCA3MjAuMCBDIDMyMC4wIDU4MC4wLCA1NjAuMCA4MzAuMCwgODQ4LjAgNjcwLjAgUyAxMzI4LjAgNTQwLjAsIDE2MDAgNzAwLjAgViAxMDAwIEgwWiIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwuMjQpIi8+PGNpcmNsZSBjeD0iMTI0OC4wIiBjeT0iMjMwLjAiIHI9IjkwLjAiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsLjM1KSIvPjx0ZXh0IHg9IjUwJSIgeT0iNDclIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iNjUuMCIgZm9udC13ZWlnaHQ9IjcwMCIgZmlsbD0iI2ZmZmZmZiI+TWFpbiBEZXZlbG9wbWVudCBJbWFnZTwvdGV4dD48dGV4dCB4PSI1MCUiIHk9IjU2JSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjI4LjAiIGZpbGw9IiNmZmZmZmYiIG9wYWNpdHk9Ii43OCI+UmVwbGFjZSB3aXRoIG9yaWdpbmFsIGltYWdlIGZyb20gVmlsbGEgUmVhZHkgQ3JvYXRpYTwvdGV4dD48L3N2Zz4=',
      'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNjAwIiBoZWlnaHQ9IjEwMDAiIHZpZXdCb3g9IjAgMCAxNjAwIDEwMDAiPjxkZWZzPjxsaW5lYXJHcmFkaWVudCBpZD0iZyIgeDE9IjAiIHkxPSIwIiB4Mj0iMSIgeTI9IjEiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iI2RmZTdlYyIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2I5Y2JkNiIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZykiLz48cGF0aCBkPSJNMCA3MjAuMCBDIDMyMC4wIDU4MC4wLCA1NjAuMCA4MzAuMCwgODQ4LjAgNjcwLjAgUyAxMzI4LjAgNTQwLjAsIDE2MDAgNzAwLjAgViAxMDAwIEgwWiIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwuMjQpIi8+PGNpcmNsZSBjeD0iMTI0OC4wIiBjeT0iMjMwLjAiIHI9IjkwLjAiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsLjM1KSIvPjx0ZXh0IHg9IjUwJSIgeT0iNDclIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iNjUuMCIgZm9udC13ZWlnaHQ9IjcwMCIgZmlsbD0iIzI0MzQ0NyI+RHJvbmUgSW1hZ2UgMTwvdGV4dD48dGV4dCB4PSI1MCUiIHk9IjU2JSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjI4LjAiIGZpbGw9IiMyNDM0NDciIG9wYWNpdHk9Ii43OCI+U2VhLXZpZXcgc2l0ZSBhYm92ZSBNYXJpbmEgVmxhxaFrYTwvdGV4dD48L3N2Zz4=',
      'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNjAwIiBoZWlnaHQ9IjEwMDAiIHZpZXdCb3g9IjAgMCAxNjAwIDEwMDAiPjxkZWZzPjxsaW5lYXJHcmFkaWVudCBpZD0iZyIgeDE9IjAiIHkxPSIwIiB4Mj0iMSIgeTI9IjEiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iI2RmZTdlYyIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2I5Y2JkNiIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZykiLz48cGF0aCBkPSJNMCA3MjAuMCBDIDMyMC4wIDU4MC4wLCA1NjAuMCA4MzAuMCwgODQ4LjAgNjcwLjAgUyAxMzI4LjAgNTQwLjAsIDE2MDAgNzAwLjAgViAxMDAwIEgwWiIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwuMjQpIi8+PGNpcmNsZSBjeD0iMTI0OC4wIiBjeT0iMjMwLjAiIHI9IjkwLjAiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsLjM1KSIvPjx0ZXh0IHg9IjUwJSIgeT0iNDclIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iNjUuMCIgZm9udC13ZWlnaHQ9IjcwMCIgZmlsbD0iIzI0MzQ0NyI+RHJvbmUgSW1hZ2UgMjwvdGV4dD48dGV4dCB4PSI1MCUiIHk9IjU2JSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjI4LjAiIGZpbGw9IiMyNDM0NDciIG9wYWNpdHk9Ii43OCI+TWlsbmEgYW5kIHN1cnJvdW5kaW5nIGNvYXN0bGluZTwvdGV4dD48L3N2Zz4=',
      'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNjAwIiBoZWlnaHQ9IjEwMDAiIHZpZXdCb3g9IjAgMCAxNjAwIDEwMDAiPjxkZWZzPjxsaW5lYXJHcmFkaWVudCBpZD0iZyIgeDE9IjAiIHkxPSIwIiB4Mj0iMSIgeTI9IjEiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iI2RmZTdlYyIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2I5Y2JkNiIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZykiLz48cGF0aCBkPSJNMCA3MjAuMCBDIDMyMC4wIDU4MC4wLCA1NjAuMCA4MzAuMCwgODQ4LjAgNjcwLjAgUyAxMzI4LjAgNTQwLjAsIDE2MDAgNzAwLjAgViAxMDAwIEgwWiIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwuMjQpIi8+PGNpcmNsZSBjeD0iMTI0OC4wIiBjeT0iMjMwLjAiIHI9IjkwLjAiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsLjM1KSIvPjx0ZXh0IHg9IjUwJSIgeT0iNDclIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iNjUuMCIgZm9udC13ZWlnaHQ9IjcwMCIgZmlsbD0iIzI0MzQ0NyI+QnVpbGRpbmcgQ29uY2VwdCAxPC90ZXh0Pjx0ZXh0IHg9IjUwJSIgeT0iNTYlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjguMCIgZmlsbD0iIzI0MzQ0NyIgb3BhY2l0eT0iLjc4Ij5Db25jZXB0dWFsIGV4dGVyaW9yIHZpc3VhbDwvdGV4dD48L3N2Zz4=',
      'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNjAwIiBoZWlnaHQ9IjEwMDAiIHZpZXdCb3g9IjAgMCAxNjAwIDEwMDAiPjxkZWZzPjxsaW5lYXJHcmFkaWVudCBpZD0iZyIgeDE9IjAiIHkxPSIwIiB4Mj0iMSIgeTI9IjEiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iI2RmZTdlYyIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2I5Y2JkNiIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZykiLz48cGF0aCBkPSJNMCA3MjAuMCBDIDMyMC4wIDU4MC4wLCA1NjAuMCA4MzAuMCwgODQ4LjAgNjcwLjAgUyAxMzI4LjAgNTQwLjAsIDE2MDAgNzAwLjAgViAxMDAwIEgwWiIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwuMjQpIi8+PGNpcmNsZSBjeD0iMTI0OC4wIiBjeT0iMjMwLjAiIHI9IjkwLjAiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsLjM1KSIvPjx0ZXh0IHg9IjUwJSIgeT0iNDclIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iNjUuMCIgZm9udC13ZWlnaHQ9IjcwMCIgZmlsbD0iIzI0MzQ0NyI+QnVpbGRpbmcgQ29uY2VwdCAyPC90ZXh0Pjx0ZXh0IHg9IjUwJSIgeT0iNTYlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjguMCIgZmlsbD0iIzI0MzQ0NyIgb3BhY2l0eT0iLjc4Ij5Qb29sIGFuZCBwcml2YXRlIG91dGRvb3IgYXJlYXM8L3RleHQ+PC9zdmc+',
    ];
    $realImages = collect();
    if($featuredImage) $realImages->push($featuredImage);
    foreach($galleryImages as $img) $realImages->push($img->image_url);
    $totalImages = $realImages->count();
  @endphp
  <section class="card pad">
    <div class="property-gallery">
      @for($i = 0; $i < 5; $i++)
        @if($i < $totalImages)
          <img src="{{ $realImages[$i] }}" alt="{{ $property->title }}" onclick="openViewer(this.src)">
        @else
          <img src="{{ $placeholders[$i] }}" alt="Placeholder image {{ $i + 1 }}" onclick="openViewer(this.src)">
        @endif
      @endfor
    </div>
    <p class="image-note">{{ $galleryImages->count() ? 'Click any image to enlarge.' : 'The gallery fields are connected to the admin property editor. Replace these visual placeholders with the original Villa Ready Croatia images.' }}</p>
  </section>

  <div class="layout">
    <main>
      <section class="card pad">
        <div class="title"><div><h2>Property Overview</h2><p class="sub">{{ $property->location }}</p></div></div>
        <div class="article">
          <p>{{ $property->description }}</p>
          @if($property->location_description)
          <p>{{ $property->location_description }}</p>
          @endif
          <div class="highlight-box">Buy through our real estate agency at the same approved property price supplied by the developer.</div>
        </div>
      </section>

      <section class="card pad">
        <div class="title"><div><h2>Property Details</h2><p class="sub">Current project structure and available unit sizes.</p></div></div>
        <div class="spec-grid">
          @if($property->buildings_count)
          <div class="spec-box"><strong>{{ $property->buildings_count }}</strong><span>Residential buildings</span></div>
          @endif
          @if($property->ground_floor_range || $property->first_floor_range)
          <div class="spec-box"><strong>{{ $property->ground_floor_range ?? $property->first_floor_range }}</strong><span>Approximate apartment sizes</span></div>
          @endif
          @if($property->price_per_m2)
          <div class="spec-box"><strong>€{{ number_format($property->price_per_m2, 0) }}/m²</strong><span>Net price</span></div>
          @endif
          @if($property->payment_structure)
          <div class="spec-box"><strong>{{ Str::limit($property->payment_structure, 20) }}</strong><span>Payment structure</span></div>
          @endif
        </div>
      </section>

      <section class="card pad">
        <div class="title"><div><h2>Development and Location</h2><p class="sub">Premium Croatian sea-view property.</p></div></div>
        <div class="grid2">
          <div class="mini-card"><b>Location</b><p>{{ $property->location_description ?? $property->location }}</p></div>
          <div class="mini-card"><b>Project</b><p>{{ $property->structure ?? 'Modern residential development with swimming pools, private outdoor areas, garages and storage.' }}</p></div>
          @if($property->use_options)
          <div class="mini-card"><b>Use Options</b><p>{{ $property->use_options }}</p></div>
          @endif
          @if($property->management_service)
          <div class="mini-card"><b>Optional Management</b><p>{{ $property->management_service }}</p></div>
          @endif
        </div>
      </section>

      @if($property->units->count())
      <section class="card pad">
        <div class="title"><div><h2>Available Apartment Sizes and Example Prices</h2><p class="sub">All prices shown are net and must be confirmed before reservation.</p></div></div>
        <div style="overflow:auto">
          <table class="unit-table">
            <thead><tr><th>Floor / Unit Type</th><th>Approximate Size</th><th>Example Net Price</th><th>Status</th></tr></thead>
            <tbody>
              @foreach($property->units as $unit)
              <tr>
                <td>{{ $unit->floor }} apartment</td>
                <td>{{ $unit->size_m2 }} m²</td>
                <td>{{ $unit->formatted_price }}</td>
                <td>
                  @if($unit->status === 'available')
                  <span class="status-pill status-available">AVAILABLE</span>
                  @elseif($unit->status === 'reserved')
                  <span class="status-pill status-reserved">CHECK AVAILABILITY</span>
                  @else
                  <span class="status-pill status-sold">SOLD</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </section>
      @endif

      @if($property->payment_structure || $property->vat_info)
      <section class="card pad">
        <div class="title"><div><h2>Payment, VAT and Purchase Structure</h2><p class="sub">Confirm all legal and tax details with professional advisers before purchase.</p></div></div>
        <div class="article">
          @if($property->payment_structure)
          <p><strong>Payment structure:</strong> {{ $property->payment_structure }}</p>
          @endif
          @if($property->vat_info)
          <p><strong>VAT:</strong> {{ $property->vat_info }}</p>
          @endif
          <p><strong>Direct project information:</strong> Our agency presents the same approved property information, plans and pricing provided by Villa Ready Croatia.</p>
        </div>
      </section>
      @endif

      @if($floorPlans->count())
      <section class="card pad">
        <div class="title"><div><h2>Plans and Additional Images</h2><p class="sub">Click an image to enlarge it.</p></div></div>
        <div class="grid3">
          @foreach($floorPlans as $plan)
          <img src="{{ $plan->image_url }}" alt="{{ $plan->caption ?? 'Floor plan' }}" onclick="openViewer(this.src)" style="width:100%;border-radius:12px;cursor:pointer">
          @endforeach
        </div>
      </section>
      @endif

      <section class="card pad agency-contact" id="contact">
        <div class="title"><div><h2>Contact Our Agency About This Property</h2><p class="sub">Your enquiry goes to the real estate agency presenting this property.</p></div></div>
        <form class="form" action="{{ route('lead-magnet.store', $profile->id) }}" method="POST">
          @csrf
          <input type="hidden" name="property_id" value="{{ $property->property_id }}">
          <input type="hidden" name="affiliate_code" value="{{ $publication->affiliate_code ?? 'PREVIEW' }}">
          <input type="hidden" name="source" value="villa_ready_property">
          <div class="grid2">
            <input name="full_name" placeholder="Full name" required>
            <input type="email" name="email" placeholder="Email" required>
          </div>
          <div class="grid2">
            <input name="phone" placeholder="Phone">
            <select name="interest">
              <option>Request current availability</option>
              <option>Request floor plans</option>
              <option>Schedule a call</option>
              <option>Ask about purchase structure</option>
            </select>
          </div>
          <textarea name="message" placeholder="Your message"></textarea>
          <button class="btn" type="submit">Send Enquiry to Agency</button>
        </form>
      </section>
    </main>

    <aside class="sidebar">
      <div class="price-panel">
        <small>NET PRICE</small>
        <div class="big">{{ $property->price_display }}</div>
        <p>Final unit price depends on floor, size and availability.</p>
        <a class="btn" href="#contact" style="width:100%">Ask Our Agency</a>
      </div>
      <div class="card sidebar-box">
        <div class="head">Key Facts</div>
        <div class="sidebar-list">
          <div class="sidebar-row"><span class="ico">⌖</span><span>{{ $property->location }}</span></div>
          @if($property->property_type)
          <div class="sidebar-row"><span class="ico">≈</span><span>{{ $property->property_type }}</span></div>
          @endif
          <div class="sidebar-row"><span class="ico">⌂</span><span>New development</span></div>
          <div class="sidebar-row"><span class="ico">€</span><span>Same approved developer price</span></div>
        </div>
      </div>
      <div class="notice-bar">Affiliate tracking is active for {{ $property->cookie_duration_days }} days after a visitor opens this property through the agency.</div>
    </aside>
  </div>
</div>

<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-col">
        <h4>{{ $profile->agency_name }}</h4>
        <p>This property is marketed and sold through our agency. Contact our team for viewings, documentation and purchase assistance.</p>
      </div>
      <div class="footer-col">
        <h4>Property</h4>
        <p>
          <a href="#">{{ $property->short_title ?? $property->title }}</a><br>
          <a href="#units">Available units</a><br>
          <a href="#contact">Buyer service</a>
        </p>
      </div>
      <div class="footer-col">
        <h4>Contact</h4>
        <p>
          @if($profile->user->email ?? null){{ $profile->user->email }}<br>@endif
          @if($profile->phone){{ $profile->phone }}<br>@endif
          {{ $property->location }}
        </p>
      </div>
    </div>
    <div class="footer-bottom">
      <span>{{ $copyright }}</span>
      <div class="footer-links">
        <a href="{{ route('privacy') }}">Privacy</a>
        <a href="{{ route('terms') }}">Terms</a>
      </div>
    </div>
  </div>
</footer>

<div class="modal-viewer" id="imageViewer">
  <span class="modal-close" onclick="closeViewer()">×</span>
  <img id="viewerImage" alt="">
</div>

<script>
function setAffiliateCookie() {
  const agencyCode = '{{ $publication->affiliate_code ?? 'PREVIEW' }}';
  const propertyId = '{{ $property->property_id }}';
  const expires = new Date(Date.now() + {{ $property->cookie_duration_days }}*24*60*60*1000).toUTCString();
  document.cookie = 'vrc_affiliate_{{ $property->id }}=' + encodeURIComponent(agencyCode) + '; expires=' + expires + '; path=/; SameSite=Lax';
  localStorage.setItem('vrc_last_referral', JSON.stringify({
    agency_code: agencyCode,
    property_id: propertyId,
    status: 'VIEWED',
    viewed_at: new Date().toISOString(),
    expires_at: new Date(Date.now() + {{ $property->cookie_duration_days }}*24*60*60*1000).toISOString()
  }));
}
function openViewer(src) { document.getElementById('viewerImage').src = src; document.getElementById('imageViewer').classList.add('open'); }
function closeViewer() { document.getElementById('imageViewer').classList.remove('open'); }
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeViewer(); });
setAffiliateCookie();
</script>
</body>
</html>
