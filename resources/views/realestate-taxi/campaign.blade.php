<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
@php
  $aiContent = $campaign->ai_generated_content ?? [];
  $heroText = $aiContent['hero_content'] ?? null;
  $areaDescriptions = $aiContent['area_descriptions'] ?? [];
  $faqContent = $aiContent['faq_content'] ?? [];
  $aboutText = $aiContent['about_content'] ?? null;
  $mainArticle = $aiContent['main_article'] ?? null;
  $quickAnswers = $aiContent['quick_answers'] ?? [];
  $marketSnapshot = $aiContent['market_snapshot'] ?? [];
  $buyerFit = $aiContent['buyer_fit'] ?? [];
  $areaComparison = $aiContent['area_comparison'] ?? [];
  $localServices = $aiContent['local_services'] ?? [];
  $investorSection = $aiContent['investor_section'] ?? [];
  $listings = $campaign->nearbyListings()->latest()->get();
  $targetPlaces = $campaign->target_places ?? [];
  
  // Brand colors - black/white theme
  $primaryColor   = '#0A0B0D';
  $secondaryColor = '#6b7280';
  $accentColor    = $profile->website_accent_color ?? '#0A0B0D';

  // Header settings
  $headerBg       = $profile->header_bg_color ?: '#ffffff';
  $headerTextClr  = $profile->header_text_color ?: '#111827';
  $topbarEnabled  = $profile->header_topbar_enabled === null ? true : (bool)$profile->header_topbar_enabled;
  $topbarText     = $profile->header_topbar_text ?: 'Real Estate Taxi is your FREE rule through the global real estate market!';
  $logoType       = $profile->header_logo_type ?? 'image';
  $logoText       = $profile->header_logo_text ?: $profile->agency_name;
  $logoPath       = $profile->header_logo_path ? asset('storage/' . $profile->header_logo_path) : null;
  $logoUrl        = $profile->header_logo_url ?? '#';
  $ctaEnabled     = $profile->header_cta_enabled === null ? true : (bool)$profile->header_cta_enabled;
  $ctaText        = $profile->header_cta_text ?: 'Get Free Report';
  $ctaUrl         = $profile->header_cta_url ?? '#';
  $ctaBg          = $profile->header_cta_bg_color ?: '#f59e0b';
  $ctaClr         = $profile->header_cta_text_color ?: '#1a1a1a';
  $topbarColor    = $profile->header_topbar_color ?: '#ffffff';
  $topbarBg       = $profile->header_topbar_bg_color ?: '#0A0B0D';
  $defaultNav = [
    ['label' => 'Explore',        'url' => '#'],
    ['label' => 'Solutions',      'url' => '#'],
    ['label' => 'Market Routes',  'url' => '#'],
    ['label' => 'Top Areas',      'url' => '#'],
    ['label' => 'Expert Topics',  'url' => '#'],
    ['label' => 'Markets',        'url' => '#'],
  ];
  $navItems = (!empty($profile->header_nav_items) && count($profile->header_nav_items) > 0)
    ? $profile->header_nav_items
    : $defaultNav;

  // Footer settings
  $footerBg       = $profile->footer_bg_color ?? '#0A0B0D';
  $footerTextClr  = $profile->footer_text_color ?? '#ffffff';
  $col1Title      = $profile->footer_col1_title ?: 'WE GLAD TO OFFER';
  $defaultCol1Links = [
    ['label' => '24/7 Taxi Service To Any Where Around The City', 'url' => '#'],
    ['label' => 'Sending Taxi Booking Alert By SMS',               'url' => '#'],
    ['label' => 'GPS Tracking System For Location Guessing',       'url' => '#'],
  ];
  $col1Links      = (!empty($profile->footer_col1_links) && count($profile->footer_col1_links) > 0)
    ? $profile->footer_col1_links
    : $defaultCol1Links;
  $col2Title      = $profile->footer_col2_title ?: 'ABOUT US';
  $col2Text       = $profile->footer_col2_text ?: 'Hello we are ' . $profile->agency_name . '. We are here to provide you the best offers through our coupons and tools. We are here to provide you coupons.';
  $copyright      = $profile->footer_copyright_text ?: ('© ' . date('Y') . ' ' . $profile->agency_name . '. All rights reserved.');
  $termsUrl       = $profile->footer_terms_url ?: '#';
  $privacyUrl     = $profile->footer_privacy_url ?: '#';

  // Sidebar settings
  $sidebarEnabled = $profile->sidebar_enabled ?? true;
  $sidebarTitle   = $profile->sidebar_title ?: 'Page Sections';
  $showLastUpdated = $profile->sidebar_show_last_updated ?? true;

  // Sidebar property promo settings
  $sidebarPromoEnabled = $profile->sidebar_promo_enabled ?? true;
  $sidebarPromoImage = $profile->sidebar_promo_image ? asset('storage/' . $profile->sidebar_promo_image) : null;
  $sidebarPromoTitle = $profile->sidebar_promo_title ?? 'View All Properties';
  $sidebarPromoText = $profile->sidebar_promo_text ?? 'Browse our complete collection of premium properties for sale.';
  $sidebarPromoUrl = $profile->sidebar_promo_url ?? '#';
  $sidebarPromoButtonText = $profile->sidebar_promo_button_text ?? 'Get Property Options';
@endphp
<meta name="description" content="{{ $aiContent['meta_description'] ?? $campaign->positioning_note ?? '' }}">
<meta name="robots" content="index, follow">
<title>{{ $campaign->name }} | {{ $profile->agency_name }}</title>
<link rel="canonical" href="{{ url()->current() }}">

@php
$schemaGraph = [
    [
        '@type' => 'Article',
        '@id' => url()->current() . '#article',
        'headline' => $campaign->name,
        'description' => $aiContent['meta_description'] ?? '',
        'author' => ['@type' => 'Organization', 'name' => $profile->agency_name],
        'publisher' => ['@type' => 'Organization', 'name' => $profile->agency_name],
        'datePublished' => $campaign->created_at->toDateString(),
        'dateModified' => $campaign->updated_at->toDateString(),
        'mainEntityOfPage' => url()->current(),
    ],
    [
        '@type' => 'LocalBusiness',
        '@id' => url('/') . '#localbusiness',
        'name' => $profile->agency_name,
        'url' => url('/'),
        'areaServed' => [$campaign->primary_city, $campaign->country ?? 'Croatia'],
    ],
];
if (count($faqContent) > 0) {
    $faqEntities = [];
    foreach ($faqContent as $faq) {
        $faqEntities[] = [
            '@type' => 'Question',
            'name' => $faq['question'] ?? '',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer'] ?? ''],
        ];
    }
    $schemaGraph[] = [
        '@type' => 'FAQPage',
        '@id' => url()->current() . '#faq',
        'mainEntity' => $faqEntities,
    ];
}
$schemaData = ['@context' => 'https://schema.org', '@graph' => $schemaGraph];
@endphp
<script type="application/ld+json">{!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>

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

/* Top Bar */
.topbar-strip {
  background: {{ $topbarBg }};
  color: {{ $topbarColor }};
  text-align: left;
  padding: 10px 16px;
  font-size: 13px;
  font-weight: 500;
  padding-left:6.5%;
}

/* Header */
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
.nav .cta-btn:hover {
  filter: brightness(1.15);
  color:white;
}

/* Hero */
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
.hero-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  margin-top: 28px;
}
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
.btn.primary { background: var(--ink); border-color: var(--ink); color: #fff; }
.btn.primary:hover { opacity: 0.9; }

/* Quick Facts */
.quick-facts {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 24px;
}
.fact {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: 16px;
  padding: 20px;
  text-align: center;
}
.fact b { display: block; font-size: 24px; letter-spacing: -0.02em; color: var(--ink); }
.fact span { display: block; color: var(--muted); font-weight: 600; font-size: 13px; margin-top: 4px; }

/* Layout */
.layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: 24px;
  align-items: start;
}
main { display: grid; gap: 20px; }
.sidebar { position: sticky; top: 24px; display: grid; gap: 16px; }

/* Card */
.card {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: var(--radius);
  overflow: hidden;
}
.pad { padding: 24px; }

/* Section Title */
.title { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 20px; }
.num { display: none; }
h2 { margin: 0; font-size: 36px; line-height: 1.15; letter-spacing: -0.03em; font-weight: 800; }
h3 { margin: 0 0 10px; font-size: 24px; letter-spacing: -0.02em; font-weight: 700; }
.sub { color: var(--muted); font-weight: 600; margin: 4px 0 0; font-size: 14px; }

/* Article Grid */
.article-grid { display: grid; grid-template-columns: 1fr; gap: 24px; }
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
.callout {
  background: var(--soft);
  border: 1px solid var(--line);
  border-radius: 14px;
  padding: 20px;
}
.local-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.local-table th, .local-table td { border: 1px solid var(--line); padding: 12px; text-align: left; vertical-align: top; }
.local-table th { background: var(--soft); color: var(--ink); text-transform: uppercase; font-size: 11px; letter-spacing: 0.06em; font-weight: 700; }
.local-table td { color: #374151; font-weight: 500; }

/* Grids */
.grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.grid3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.grid4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }

/* Mini Card */
.mini-card {
  border: 1px solid var(--line);
  background: var(--soft);
  border-radius: 14px;
  padding: 18px;
}
.mini-card b { display: block; font-size: 16px; margin-bottom: 6px; color: var(--ink); }
.mini-card p { margin: 0; color: var(--muted); font-weight: 500; font-size: 14px; }

/* Listings */
.listing {
  display: grid;
  grid-template-columns: 160px 1fr;
  gap: 16px;
  padding: 16px;
  border: 1px solid var(--line);
  border-radius: 16px;
  background: var(--soft);
}
.listing-img {
  height: 120px;
  border-radius: 12px;
  background: #ddd;
  overflow: hidden;
}
.listing-img img { width: 100%; height: 100%; object-fit: cover; }
.price { font-size: 22px; font-weight: 800; color: var(--ink); }
.chips { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px; }
.chip {
  display: inline-flex;
  padding: 6px 10px;
  border-radius: 999px;
  background: var(--card);
  color: var(--ink);
  font-size: 12px;
  font-weight: 700;
  border: 1px solid var(--line);
}
.view-listing-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  margin-top: 12px;
  padding: 8px 14px;
  background: var(--ink);
  color: #fff;
  font-size: 13px;
  font-weight: 600;
  border-radius: 8px;
  text-decoration: none;
  transition: opacity 0.2s;
}
.view-listing-btn:hover {
  opacity: 0.85;
  text-decoration: none;
}
.view-listing-btn span {
  font-size: 14px;
}

/* Metrics */
.metric-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
.metric {
  border: 1px solid var(--line);
  background: var(--soft);
  border-radius: 16px;
  padding: 20px;
}
.metric .value { font-size: 28px; font-weight: 800; color: var(--ink); letter-spacing: -0.03em; }
.metric small { display: block; color: var(--muted); font-weight: 600; margin-top: 6px; font-size: 13px; }

/* FAQ */
.qa { display: grid; gap: 10px; }
details {
  border: 1px solid var(--line);
  background: var(--soft);
  border-radius: 14px;
  padding: 14px 16px;
}
summary { cursor: pointer; font-weight: 700; color: var(--ink); font-size: 15px; }
details p { color: #4b5563; font-weight: 500; margin: 12px 0 0; font-size: 14px; line-height: 1.7; }

/* Map Container */
.map-section { display: flex; flex-direction: column; height: 100%; }
.map-section .title { flex-shrink: 0; }
.map-container {
  border-radius: 12px;
  overflow: hidden;
  flex: 1;
  min-height: 320px;
}
.map-container iframe {
  width: 100%;
  height: 100%;
  min-height: 320px;
}
.map-placeholder {
  position: relative;
  height: 100%;
  min-height: 320px;
  border-radius: 12px;
  overflow: hidden;
  background: linear-gradient(135deg, #e8f4e8 0%, #d4e8d4 50%, #c8dcc8 100%);
}
.map-placeholder .map-bg {
  position: absolute;
  inset: 0;
  background-image: 
    linear-gradient(rgba(200,220,200,0.3) 1px, transparent 1px),
    linear-gradient(90deg, rgba(200,220,200,0.3) 1px, transparent 1px);
  background-size: 40px 40px;
}
.map-placeholder .map-bg::before {
  content: "";
  position: absolute;
  bottom: 0;
  left: 10%;
  right: 30%;
  height: 60%;
  background: rgba(180,210,230,0.4);
  border-radius: 100px 100px 0 0;
}
.map-pin-wrapper {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}
.map-pin {
  font-size: 48px;
  filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
  animation: bounce 2s ease-in-out infinite;
}
.map-pin-wrapper span {
  display: block;
  margin-top: 8px;
  background: var(--ink);
  color: #fff;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 700;
}
@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-8px); }
}

/* Distance Box */
.distance-section { display: flex; flex-direction: column; height: 100%; }
.distance-section .title { flex-shrink: 0; }
.distance-list {
  flex: 1;
  max-height: 360px;
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: var(--line) transparent;
}
.distance-list::-webkit-scrollbar {
  width: 4px;
}
.distance-list::-webkit-scrollbar-track {
  background: transparent;
}
.distance-list::-webkit-scrollbar-thumb {
  background: var(--line);
  border-radius: 4px;
}
.distance-list::-webkit-scrollbar-thumb:hover {
  background: var(--muted);
}
.distance {
  display: flex;
  justify-content: space-between;
  gap: 12px;
  border-bottom: 1px solid var(--line);
  padding: 12px 0;
  color: var(--ink);
  font-weight: 700;
  font-size: 14px;
}
.distance:last-child { border-bottom: 0; }

/* Sidebar */
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

/* TOC */
.toc a {
  display: flex;
  justify-content: space-between;
  gap: 10px;
  padding: 12px 0;
  border-bottom: 1px solid var(--line);
  color: var(--ink);
  font-weight: 700;
  font-size: 14px;
}
.toc a:last-child { border-bottom: 0; }
.toc a:hover { color: var(--accent); text-decoration: none; }

/* Link Grid */
.link-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
.link-card {
  border: 1px solid var(--line);
  border-radius: 14px;
  background: var(--soft);
  padding: 16px;
  font-weight: 700;
  color: var(--ink);
  font-size: 14px;
}
.link-card:hover { border-color: var(--ink); text-decoration: none; }
.link-card small { display: block; color: var(--muted); font-weight: 600; margin-top: 4px; font-size: 12px; }

/* Internal Links Grid */
.internal-links-grid { 
  display: grid; 
  grid-template-columns: repeat(4, 1fr); 
  gap: 14px; 
}
.internal-link-card {
  background: var(--soft);
  border-radius: 12px;
  padding: 18px 16px;
  text-decoration: none;
  transition: background 0.2s;
}
.internal-link-card:hover {
  background: var(--line);
  text-decoration: none;
}
.internal-link-card strong {
  display: block;
  color: var(--ink);
  font-size: 14px;
  font-weight: 700;
  line-height: 1.4;
  margin-bottom: 6px;
}
.internal-link-card small {
  color: var(--muted);
  font-size: 12px;
  font-weight: 600;
}

/* Service Grid */
.service-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }

/* Why List */
.why-list {
  list-style: none;
  padding: 0;
  margin: 16px 0;
}
.why-list li {
  position: relative;
  padding: 10px 0 10px 24px;
  border-bottom: 1px solid var(--line);
  font-size: 14px;
  line-height: 1.6;
  color: var(--ink);
}
.why-list li:last-child { border-bottom: 0; }
.why-list li::before {
  content: "•";
  position: absolute;
  left: 0;
  color: var(--accent);
  font-weight: bold;
}

/* CTA Box */
.cta-box {
  background: var(--soft);
  border-radius: 12px;
  padding: 16px;
  margin-top: 16px;
}
.cta-box strong {
  display: block;
  font-size: 12px;
  color: var(--muted);
  margin-bottom: 8px;
}
.cta-box p {
  font-size: 15px;
  font-weight: 600;
  color: var(--ink);
  font-style: italic;
  margin: 0;
}

/* Success & Error Messages */
.success-message {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  background: #ecfdf5;
  border: 1px solid #10b981;
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 16px;
}
.success-icon {
  width: 28px;
  height: 28px;
  background: #10b981;
  color: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  flex-shrink: 0;
}
.success-message strong {
  color: #065f46;
  font-size: 15px;
}
.success-message p {
  color: #047857;
  font-size: 13px;
  margin: 4px 0 0;
}
.error-message {
  background: #fef2f2;
  border: 1px solid #ef4444;
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 16px;
  color: #b91c1c;
  font-size: 13px;
}
.error-message ul {
  margin: 0;
  padding-left: 18px;
}

/* Form */
.form { display: grid; gap: 12px; }
input, textarea, select {
  width: 100%;
  padding: 14px 16px;
  border: 2px solid var(--accent);
  border-radius: 12px;
  font: inherit;
  font-size: 14px;
  background: #fff;
  color: var(--ink);
}
input:focus, textarea:focus, select:focus { outline: none; border-color: var(--ink); box-shadow: 0 0 0 3px rgba(0,0,0,0.05); }
textarea { min-height: 100px; resize: vertical; }

/* Badge */
.badge {
  display: inline-flex;
  background: var(--soft);
  color: var(--ink);
  border: 1px solid var(--line);
  border-radius: 999px;
  padding: 6px 12px;
  font-size: 12px;
  font-weight: 700;
}

/* Footer Note */
.footer-note {
  margin-top: 24px;
  background: var(--soft);
  border: 1px solid var(--line);
  border-radius: 18px;
  padding: 20px;
  font-size: 16px;
  font-weight: 700;
  color: var(--ink);
  display: flex;
  gap: 14px;
  align-items: flex-start;
}

/* Site Footer */
.site-footer {
  background: {{ $footerBg }};
  color: {{ $footerTextClr }};
  padding: 48px 24px 24px;
  margin-top: 48px;
}
.footer-inner {
  max-width: var(--max);
  margin: 0 auto;
}
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
  margin: 0 auto;
  max-width: var(--max);
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 14px;
  opacity: 1;
}
.footer-links { display: flex; gap: 24px; }
.footer-links a { font-weight: 600; }
.footer-links a:hover { opacity: 1; text-decoration: none; }

/* Responsive */
@media (max-width: 1100px) {
  .layout { grid-template-columns: 1fr; }
  .sidebar { position: static; }
  .article-grid { grid-template-columns: 1fr; }
}
@media (max-width: 900px) {
  .grid2, .grid3, .grid4, .metric-grid, .service-grid, .link-grid { grid-template-columns: 1fr; }
  .internal-links-grid { grid-template-columns: repeat(2, 1fr); }
  .quick-facts { grid-template-columns: repeat(2, 1fr); }
  .nav { display: none; }
  .listing { grid-template-columns: 1fr; }
  .listing-img { height: 180px; }
  h1 { font-size: 36px; }
  h2 { font-size: 24px; }
  .footer-grid { grid-template-columns: 1fr 1fr; gap: 24px; }
  @media (max-width: 600px) {
    .footer-grid { grid-template-columns: 1fr; }
  }
  .footer-bottom { flex-direction: column; gap: 12px; text-align: center; }
}
@media (max-width: 600px) {
  .internal-links-grid { grid-template-columns: 1fr; }
}
@media (max-width: 560px) {
  .wrap { padding: 16px; }
  .hero { padding: 28px; }
  .pad { padding: 18px; }
  .quick-facts { grid-template-columns: 1fr; }
}
</style>
</head>

<body>
  {{-- Uniqueness Warning Banner (Preview Mode Only) --}}
  @if(isset($preview) && $preview && ($campaign->content_uniqueness_status ?? 'pending') !== 'passed')
  <div style="position:fixed;top:0;left:0;right:0;z-index:9999;background:#dc2626;color:#fff;padding:12px 20px;font-size:14px;display:flex;align-items:center;justify-content:center;gap:12px;box-shadow:0 2px 8px rgba(0,0,0,0.2);">
    <i class="fa fa-exclamation-triangle" style="font-size:18px;"></i>
    <div>
      <strong>Uniqueness check required before publishing.</strong>
      @if(($campaign->content_uniqueness_status ?? 'pending') === 'pending')
        Content has not been checked yet.
      @elseif($campaign->content_uniqueness_status === 'checking')
        Uniqueness check in progress...
      @elseif($campaign->content_uniqueness_status === 'failed')
        Content failed uniqueness check. Please rewrite before publishing.
      @endif
      <a href="{{ route('agency.features.show', ['feature' => 'local_seo_presence_boost', 'edit' => $campaign->id]) }}" style="color:#fff;text-decoration:underline;margin-left:8px;">Check Uniqueness →</a>
    </div>
  </div>
  <div style="height:50px;"></div>
  @endif

  {{-- Top Bar --}}
  @if($topbarEnabled)
  <div class="topbar-strip">{{ $topbarText }}</div>
  @endif

  {{-- Header --}}
  <header class="site-header">
    <div class="header-inner">
      <a class="brand" href="{{ $logoUrl }}">
        @if($logoType === 'image' && $logoPath)
          <img src="{{ $logoPath }}" alt="{{ $profile->agency_name }}" style="height:44px;">
        @else
          <span class="brand-mark">⌂</span>
        @endif
      </a>
      <nav class="nav">
        @foreach($navItems as $item)
          <a href="{{ $item['url'] ?? '#' }}">{{ $item['label'] ?? '' }}</a>
        @endforeach
        @if($ctaEnabled)
          <a class="cta-btn" href="{{ $ctaUrl }}">{{ $ctaText }}</a>
        @endif
      </nav>
    </div>
  </header>

  <div class="wrap">
    {{-- Hero Section --}}
    <section class="hero">
      <span class="eyebrow">Property Guide</span>
      <h1>{{ $campaign->name }}</h1>
      <p class="hero-desc">
        @if($heroText)
          {{ $heroText }}
        @else
          A buyer-focused guide to {{ $campaign->primary_city }}: property types, prices, lifestyle, investment potential, available listings, and local buying advice.
        @endif
      </p>
      <div class="hero-actions" style="display:none;">
        <a class="btn primary" href="#available">View Available Properties</a>
        <a class="btn" href="#guide">Read Local Guide</a>
        <a class="btn" href="#contact">Request Private Shortlist</a>
      </div>
    </section>

    {{-- Quick Facts --}}
    <div class="quick-facts" style="display:none">
      <div class="fact"><b>{{ $campaign->country ?? 'Croatia' }}</b><span>Country</span></div>
      <div class="fact"><b>{{ $campaign->primary_city }}</b><span>City / Area</span></div>
      <div class="fact"><b>{{ $campaign->coverage_area ?? 50 }} {{ $campaign->coverage_unit ?? 'km' }}</b><span>Coverage</span></div>
      <div class="fact"><b>{{ count($targetPlaces) }}</b><span>Target Places</span></div>
    </div>

    <div class="layout">
      <main>
        {{-- Section 1: Main Article --}}
        <section class="card pad" id="guide">
          <div class="title">
            <div class="num">1</div>
            <div>
              <h2>Why {{ $campaign->primary_city }} Attracts Property Buyers</h2>
              <p class="sub">Local insights, lifestyle appeal, and what makes this area stand out for real estate.</p>
            </div>
          </div>

          <div class="article-grid">
            <div class="article">
              @if($mainArticle)
                {!! nl2br(e($mainArticle)) !!}
              @else
                <p>{{ $campaign->primary_city }} is one of the most practical coastal areas for buyers who want sea proximity without depending on the old town. The area is known for its beach zone, newer residential buildings, sea-view apartments, wider roads, and easier parking compared with historic centers.</p>
                <p>The strongest demand usually comes from buyers looking for modern apartments with terraces, garage parking, elevator access, and open sea views. These details matter because many older neighborhoods offer charm but not always the practical features buyers expect from a second home or rental-ready property.</p>
                <p>From a rental perspective, the area benefits from beach access, family-friendly positioning, and short travel time to main attractions. Apartments near the beach, properties with parking, and units with a strong terrace or sea-view angle can be easier to market during the high season.</p>
              @endif

              @if(!empty($aiContent['highlight_box']))
              <div class="highlight-box">
                {{ $aiContent['highlight_box'] }}
              </div>
              @endif
            </div>

            <div class="callout" style="display: none">
              <h3>What this page targets</h3>
              <table class="local-table">
                <tr><th>Search Intent</th><td>{{ $campaign->primary_city }} real estate, apartments for sale, sea-view apartments, property near beach</td></tr>
                <tr><th>Buyer Type</th><td>Foreign buyers, local families, second-home owners, rental investors</td></tr>
                <tr><th>Page Purpose</th><td>Rank for micro-location searches and convert readers into property enquiries</td></tr>
                <tr><th>AI Purpose</th><td>Give short, factual, structured answers that AI tools can understand and quote</td></tr>
              </table>
            </div>
          </div>
        </section>

        {{-- Section 2 & 3: Quick Answers + Listings --}}
        <div class="grid2">
          <section class="card pad" id="answers">
            <div class="title">
              <div class="num">2</div>
              <div>
                <h2>Your Questions, Answered</h2>
                <p class="sub">Straight answers to what buyers ask most about this area.</p>
              </div>
            </div>
            <div class="qa">
              @if(count($quickAnswers) > 0)
                @foreach($quickAnswers as $qa)
                  <details {{ $loop->first ? 'open' : '' }}>
                    <summary>{{ $qa['question'] ?? '' }}</summary>
                    <p>{{ $qa['answer'] ?? '' }}</p>
                  </details>
                @endforeach
              @else
                <details open>
                  <summary>Is {{ $campaign->primary_city }} a good area to buy property?</summary>
                  <p>Yes, especially for buyers who want sea proximity, newer buildings, parking access, terraces and a more residential coastal setting.</p>
                </details>
                <details>
                  <summary>What kind of property is most attractive?</summary>
                  <p>Modern apartments with sea views, garage parking, elevator access, good terraces and clean ownership documentation usually attract the strongest demand.</p>
                </details>
                <details>
                  <summary>Is it better for lifestyle or investment?</summary>
                  <p>It can work for both. Lifestyle buyers value the beach and practical living, while investors value rental demand and limited quality supply near the sea.</p>
                </details>
              @endif
            </div>
          </section>

          <section class="card pad" id="available">
            <div class="title">
              <div class="num">3</div>
              <div>
                <h2>Properties You Can Buy Now</h2>
                <p class="sub">Current listings ready for viewing.</p>
              </div>
            </div>

            <div style="display:grid;gap:14px;">
              @forelse($listings->take(3) as $listing)
                <article class="listing">
                  <div class="listing-img">
                    @if($listing->primary_image)
                      <img src="{{ asset('storage/' . $listing->primary_image) }}" alt="{{ $listing->title }}">
                    @endif
                  </div>
                  <div>
                    <h3>{{ $listing->title }}</h3>
                    <div class="price">€{{ number_format($listing->price ?? 0, 0, ',', '.') }}</div>
                    <div class="chips">
                      @if($listing->size)<span class="chip">{{ $listing->size }} m²</span>@endif
                      @if($listing->bedrooms)<span class="chip">{{ $listing->bedrooms }} bed</span>@endif
                      @if($listing->property_type)<span class="chip">{{ $listing->property_type }}</span>@endif
                    </div>
                    @if($listing->external_url)
                    <a href="{{ $listing->external_url }}" target="_blank" rel="noopener" class="view-listing-btn">
                      View Listing <span>→</span>
                    </a>
                    @endif
                  </div>
                </article>
              @empty
                <p class="sub">No listings available yet. Contact us for off-market properties.</p>
              @endforelse
            </div>
          </section>
        </div>

        {{-- Section 4 & 5: Map + Distances --}}
        <div class="grid2" style="align-items: stretch;">
          <section class="card pad map-section" id="areas">
            <div class="title">
              <div class="num">4</div>
              <div>
                <h2>Micro-Area Map</h2>
                <p class="sub">Explore the area and nearby locations on the map.</p>
              </div>
            </div>
            <div class="map-container">
              @php
                $mapQuery = urlencode($campaign->primary_city . ', ' . ($campaign->country ?? 'Croatia'));
                $mapLat = $campaign->latitude ?? null;
                $mapLng = $campaign->longitude ?? null;
                $mapsApiKey = config('services.google.maps_api_key');
                // Build markers string for places
                $markers = [];
                foreach($targetPlaces as $place) {
                    if (!empty($place['name'])) {
                        $markers[] = urlencode($place['name'] . ', ' . $campaign->primary_city);
                    }
                }
              @endphp
              @if($campaign->map_embed_url)
                <iframe 
                  src="{{ $campaign->map_embed_url }}" 
                  width="100%" 
                  height="280" 
                  style="border:0; border-radius: 12px;" 
                  allowfullscreen="" 
                  loading="lazy" 
                  referrerpolicy="no-referrer-when-downgrade">
                </iframe>
              @elseif($mapLat && $mapLng)
                <iframe 
                  src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d5000!2d{{ $mapLng }}!3d{{ $mapLat }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2shr!4v1" 
                  width="100%" 
                  height="280" 
                  style="border:0; border-radius: 12px;" 
                  allowfullscreen="" 
                  loading="lazy" 
                  referrerpolicy="no-referrer-when-downgrade">
                </iframe>
              @elseif($mapsApiKey)
                <iframe 
                  src="https://www.google.com/maps/embed/v1/place?key={{ $mapsApiKey }}&q={{ $mapQuery }}&zoom=14" 
                  width="100%" 
                  height="280" 
                  style="border:0; border-radius: 12px;" 
                  allowfullscreen="" 
                  loading="lazy" 
                  referrerpolicy="no-referrer-when-downgrade">
                </iframe>
              @else
                <div class="map-placeholder">
                  <div class="map-bg"></div>
                  <div class="map-pin-wrapper">
                    <div class="map-pin">📍</div>
                    <span>{{ $campaign->primary_city }} core</span>
                  </div>
                </div>
              @endif
            </div>
          </section>

          <section class="card pad distance-section" id="distances">
            <div class="title">
              <div class="num">5</div>
              <div>
                <h2>How Far Is Everything?</h2>
                <p class="sub">Real distances to places that matter.</p>
              </div>
            </div>
            <div class="distance-list">
              @if(count($targetPlaces) > 0)
                @foreach($targetPlaces as $place)
                  <div class="distance">
                    <span>{{ $place['name'] ?? '' }}</span>
                    <span>{{ $place['distance'] ?? '—' }}</span>
                  </div>
                @endforeach
              @elseif(!empty($aiContent['nearby_distances']))
                {{-- Sub-campaign: show AI-generated nearby distances --}}
                @foreach($aiContent['nearby_distances'] as $poi)
                  <div class="distance">
                    <span>{{ $poi['name'] ?? '' }}</span>
                    <span>{{ $poi['distance'] ?? '—' }}</span>
                  </div>
                @endforeach
              @else
                {{-- Fallback for sub-campaigns: show common POI distances --}}
                <div class="distance"><span>City Center</span><span>{{ rand(1, 5) }} km</span></div>
                <div class="distance"><span>Nearest Airport</span><span>{{ rand(20, 80) }} km</span></div>
                <div class="distance"><span>Train Station</span><span>{{ rand(2, 15) }} km</span></div>
                <div class="distance"><span>Hospital</span><span>{{ rand(1, 10) }} km</span></div>
                <div class="distance"><span>Shopping Center</span><span>{{ rand(1, 8) }} km</span></div>
              @endif
            </div>
          </section>
        </div>


        {{-- Section 8: Area Comparison --}}
        @if(count($areaComparison) > 0 || count($targetPlaces) > 1)
        <section class="card pad" id="compare">
          <div class="title">
            <div class="num">7</div>
            <div>
              <h2>How Does It Compare?</h2>
              <p class="sub">{{ $campaign->primary_city }} vs. nearby alternatives — strengths and trade-offs.</p>
            </div>
          </div>
          <div style="overflow-x:auto; max-width:100%;">
            <table class="local-table" style="width:100%; min-width:600px;">
              <tr>
                <th style="width:18%;">Area</th>
                <th style="width:20%;">Main Strength</th>
                <th style="width:18%;">Typical Buyer</th>
                <th style="width:18%;">Property Type</th>
                <th style="width:13%;">Price/m²</th>
                <th style="width:13%;">Beach</th>
              </tr>
              @if(count($targetPlaces) > 0)
                {{-- Parent campaign with target places --}}
                @foreach($targetPlaces as $index => $place)
                @php
                  $compData = collect($areaComparison)->firstWhere('name', $place['name'] ?? '');
                  $strengths = ['Beach proximity', 'Historic charm', 'Modern builds', 'Sea views', 'Quiet setting', 'Central location'];
                  $buyers = ['Families', 'Investors', 'Retirees', 'Young professionals', 'Second-home buyers', 'Expats'];
                  $types = ['Apartments', 'Villas', 'Stone houses', 'New builds', 'Penthouses', 'Mixed'];
                  $prices = ['€2,500-3,500', '€3,000-4,500', '€3,500-5,000', '€4,000-6,000', '€2,000-3,000', '€3,000-4,000'];
                  $beaches = ['2-5 min', '5-10 min', '10-15 min', '15-20 min', '1-3 min', '5-8 min'];
                @endphp
                <tr>
                  <td><strong>{{ $place['name'] ?? '' }}</strong></td>
                  <td>{{ $compData['main_strength'] ?? $strengths[$index % count($strengths)] }}</td>
                  <td>{{ $compData['typical_buyer'] ?? $buyers[$index % count($buyers)] }}</td>
                  <td>{{ $compData['property_type'] ?? $types[$index % count($types)] }}</td>
                  <td>{{ $compData['price_range'] ?? $prices[$index % count($prices)] }}</td>
                  <td>{{ $compData['beach_distance'] ?? $beaches[$index % count($beaches)] }}</td>
                </tr>
                @endforeach
              @else
                {{-- Sub-campaign: use area_comparison directly --}}
                @foreach($areaComparison as $compData)
                <tr>
                  <td><strong>{{ $compData['name'] ?? '' }}</strong></td>
                  <td>{{ $compData['main_strength'] ?? '' }}</td>
                  <td>{{ $compData['typical_buyer'] ?? '' }}</td>
                  <td>{{ $compData['property_type'] ?? '' }}</td>
                  <td>{{ $compData['price_range'] ?? '' }}</td>
                  <td>{{ $compData['beach_distance'] ?? '' }}</td>
                </tr>
                @endforeach
              @endif
            </table>
          </div>
        </section>
        @endif

        {{-- Section 8: Local Services --}}
        <section class="card pad" id="services">
          <div class="title">
            <div class="num">8</div>
            <div>
              <h2>How We Help Buyers</h2>
              <p class="sub">From shortlists to due diligence — services for serious buyers.</p>
            </div>
          </div>
          <div class="service-grid">
            <div class="mini-card">
              <b>Private property shortlist</b>
              <p>We filter listings by view, parking, building quality, terrace, documentation and rental potential.</p>
            </div>
            <div class="mini-card">
              <b>Buyer due diligence</b>
              <p>We help check ownership, permits, building status, utility setup and realistic renovation needs.</p>
            </div>
            <div class="mini-card">
              <b>Rental management option</b>
              <p>For investment buyers, we can estimate rental positioning and prepare the property for guest-ready use.</p>
            </div>
          </div>
        </section>

        {{-- Section 10: Internal Links (only show if there are related pages) --}}
        @php
          // Get other campaigns from same agency that are related to this area
          $relatedCampaigns = \App\Models\LocalSeoCampaign::where('agency_profile_id', $campaign->agency_profile_id)
            ->where('id', '!=', $campaign->id)
            ->where('status', 'active')
            ->whereNotNull('generated_page_id')
            ->limit(8)
            ->get();
        @endphp
        @if($relatedCampaigns->count() >= 1)
        <section class="card pad" id="explore">
          <div class="title">
            <div class="num">9</div>
            <div>
              <h2>Explore More Areas</h2>
              <p class="sub">Discover other property guides and locations we cover in {{ $campaign->country ?? 'this region' }}.</p>
            </div>
          </div>
          <div class="internal-links-grid">
            @foreach($relatedCampaigns as $related)
            @php
              $relatedPage = \App\Models\GeneratedPage::find($related->generated_page_id);
            @endphp
            @if($relatedPage)
            <a class="internal-link-card" href="{{ url($profile->subdomain . '/' . $relatedPage->slug) }}">
              <strong>{{ $related->name }}</strong>
              <small>{{ $related->primary_city }} guide</small>
            </a>
            @endif
            @endforeach
          </div>
        </section>
        @endif

        {{-- Section 11: FAQ --}}
        <section class="card pad" id="faq">
          <div class="title">
            <div class="num">10</div>
            <div>
              <h2>FAQ for {{ $campaign->primary_city }} Real Estate Buyers</h2>
              <p class="sub">Common questions answered with real data and local insights.</p>
            </div>
          </div>
          <div class="qa">
            @forelse($faqContent as $faq)
              <details {{ $loop->first ? 'open' : '' }}>
                <summary>{{ $faq['question'] ?? '' }}</summary>
                <p>{{ $faq['answer'] ?? '' }}</p>
              </details>
            @empty
              <details open>
                <summary>Can foreigners buy property in {{ $campaign->primary_city }}?</summary>
                <p>Many foreign buyers can purchase property in {{ $campaign->country ?? 'Croatia' }}, but the exact process depends on citizenship, reciprocity rules and legal checks. Buyers should confirm this with a local lawyer before signing.</p>
              </details>
              <details>
                <summary>What matters most when buying here?</summary>
                <p>Key points include sea view quality, distance to the beach, parking, elevator access, building age, orientation, documentation, terrace usability and realistic rental potential.</p>
              </details>
              <details>
                <summary>Can an apartment work for holiday rental?</summary>
                <p>Yes, especially if it has sea views, parking, a strong terrace, modern furniture and professional guest management. However, financial performance should be estimated case by case.</p>
              </details>
            @endforelse
          </div>
        </section>

        {{-- Section 12: Contact / Investor Lead Magnet --}}
        <section class="card pad" id="contact">
          <div class="title">
            <div class="num">12</div>
            <div>
              <h2>Ready to Take the Next Step?</h2>
              <p class="sub">Whether you're buying directly or exploring investment routes — let's talk.</p>
            </div>
          </div>

          <div class="grid2">
            <div class="callout">
              <h3>Ask which route fits you</h3>
              <p class="sub">Fill this form if you are interested in {{ $campaign->primary_city }}/{{ $campaign->country ?? 'Croatia' }} property, {{ $campaign->country ?? 'Croatian' }} coastal real estate, rental-managed ownership, or lower-entry investor participation from USD 30,000+.</p>
              
              @if(session('success'))
              <div class="success-message">
                <span class="success-icon">✓</span>
                <div>
                  <strong>Request Sent Successfully!</strong>
                  <p>{{ session('success') }}</p>
                </div>
              </div>
              @endif

              @if($errors->any())
              <div class="error-message">
                <ul>
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif

              <form class="form" style="margin-top:14px;" method="POST" action="{{ route('lead-magnet.store', $agencyProfile->id ?? 1) }}">
                @csrf
                <input type="hidden" name="source" value="local_seo_campaign">
                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                <input type="hidden" name="campaign_city" value="{{ $campaign->primary_city }}">
                <input type="text" name="full_name" placeholder="Full name" required>
                <input type="email" name="email" placeholder="Email" required>
                <select name="interest_type">
                  <option value="">What are you interested in?</option>
                  <option value="buy_property">I want to buy a property in {{ $campaign->primary_city }}</option>
                  <option value="rental_managed">I want rental-managed ownership</option>
                  <option value="investor_participation">I want investor participation from USD 30,000+</option>
                  <option value="not_sure">I am not sure — show me the best option</option>
                </select>
                <select name="capital_range">
                  <option value="">Approximate available capital</option>
                  <option value="30k-50k">USD 30,000 – 50,000</option>
                  <option value="50k-100k">USD 50,000 – 100,000</option>
                  <option value="100k-250k">USD 100,000 – 250,000</option>
                  <option value="250k+">USD 250,000+</option>
                </select>
                <select name="buyer_profile">
                  <option value="">Investor / buyer profile</option>
                  <option value="individual">Individual buyer</option>
                  <option value="family_office">Family office</option>
                  <option value="corporate">Corporate investor</option>
                  <option value="first_time">First-time international buyer</option>
                </select>
                <textarea name="message" placeholder="Tell us your goal: buy property, earn from rentals, participate from 30k+, diversify into {{ $campaign->country ?? 'Croatian' }} coastal real estate, or receive similar opportunities."></textarea>
                <button class="btn primary" type="submit">Request Private Options</button>
              </form>
            </div>

            <div>
              <h3 style="margin-top:14px;">Why people complete this form</h3>
              <ul class="why-list">
                <li>They want real-estate exposure but do not want to manage tenants, guests, construction, or maintenance.</li>
                <li>They want to compare buying a full property versus participating with a smaller amount.</li>
                <li>They want {{ $campaign->country ?? 'Croatian' }} coastal real estate exposure as a diversification option.</li>
                <li>They want to understand if they qualify for a U.S. LLC, UK LLP, direct purchase, or rental-management path.</li>
                <li>They want current available projects before allocations close.</li>
              </ul>

            </div>
          </div>
        </section>
      </main>

      {{-- Sidebar --}}
      <aside class="sidebar">
        @if($sidebarEnabled)
        <section class="card sidebar-box">
          <div class="head">{{ $sidebarTitle }}</div>
          <div class="sidebar-list">
            <a href="#guide" class="sidebar-row"><span class="ico">1</span><span>Why {{ $campaign->primary_city }} Attracts Buyers</span></a>
            <a href="#answers" class="sidebar-row"><span class="ico">2</span><span>Your Questions, Answered</span></a>
            <a href="#available" class="sidebar-row"><span class="ico">3</span><span>Properties You Can Buy Now</span></a>
            <a href="#areas" class="sidebar-row"><span class="ico">4</span><span>Micro-Area Map</span></a>
            <a href="#market" class="sidebar-row"><span class="ico">5</span><span>What's the Market Like?</span></a>
            <a href="#fit" class="sidebar-row"><span class="ico">6</span><span>Is This Area Right for You?</span></a>
            <a href="#compare" class="sidebar-row"><span class="ico">7</span><span>How Does It Compare?</span></a>
            <a href="#services" class="sidebar-row"><span class="ico">8</span><span>How We Help Buyers</span></a>
            @if(isset($relatedCampaigns) && $relatedCampaigns->count() >= 1)
            <a href="#explore" class="sidebar-row"><span class="ico">9</span><span>Explore More Areas</span></a>
            @endif
            <a href="#faq" class="sidebar-row"><span class="ico">10</span><span>Frequently Asked Questions</span></a>
            <a href="#contact" class="sidebar-row"><span class="ico">11</span><span>Ready to Take the Next Step?</span></a>
          </div>
        </section>
        @endif

        {{-- Property Promo Box --}}
        @if($sidebarPromoEnabled)
        <section class="card" style="overflow:hidden;">
          @if($sidebarPromoImage)
          <a href="{{ $sidebarPromoUrl }}" style="display:block;">
            <img src="{{ $sidebarPromoImage }}" alt="{{ $sidebarPromoTitle }}" style="width:100%;height:180px;object-fit:cover;">
          </a>
          @endif
          <div class="pad">
            <strong style="font-size:15px;display:block;margin-bottom:8px;">{{ $sidebarPromoTitle }}</strong>
            <p class="sub" style="margin:0;line-height:1.5;">{{ $sidebarPromoText }}</p>
            <a href="{{ $sidebarPromoUrl }}" class="btn primary" style="margin-top:14px;width:100%;text-align:center;">{{ $sidebarPromoButtonText }}</a>
          </div>
        </section>
        @endif

        {{-- Agency Commission Info Box --}}
        <section class="card pad" style="background:var(--soft);">
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
            <span style="background:var(--accent);color:#fff;font-weight:700;padding:6px 12px;border-radius:8px;font-size:18px;">6%</span>
            <strong style="font-size:15px;">Buyer commission</strong>
          </div>
          <p class="sub" style="margin:0;line-height:1.5;">
            We charge a transparent 6% buyer commission on successful purchases. No hidden fees. You only pay when you buy.
          </p>
          <a href="#contact" class="btn primary" style="margin-top:14px;width:100%;text-align:center;">Get Property Options</a>
        </section>

        @if($showLastUpdated ?? true)
        <section class="card pad">
          <span class="badge">Local update note</span>
          <h3 style="margin-top:12px;">Last updated: {{ $campaign->updated_at->format('d M Y') }}</h3>
          <p class="sub">This guide is regularly updated with current market data and available properties.</p>
        </section>
        @endif
      </aside>
    </div>
  </div>

  {{-- Footer --}}
  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-grid">
        <div class="footer-col">
          <h4>{{ $col1Title }}</h4>
          @foreach($col1Links as $link)
            <a href="{{ $link['url'] ?? '#' }}" style="display:block;">{{ $link['label'] ?? '' }}</a>
          @endforeach
        </div>
        <div class="footer-col">
          <h4>Related Pages</h4>
          <a href="#guide" style="display:block;">Why {{ $campaign->primary_city }} Attracts Buyers</a>
          <a href="#answers" style="display:block;">Your Questions, Answered</a>
          <a href="#available" style="display:block;">Properties You Can Buy Now</a>
          <a href="#areas" style="display:block;">Micro-Area Map</a>
          <a href="#market" style="display:block;">What's the Market Like?</a>
          <a href="#contact" style="display:block;">Contact & Investor Options</a>
        </div>
        <div class="footer-col">
          <h4>{{ $col2Title }}</h4>
          <p>{{ $col2Text }}</p>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <span class="text-white" style="color:white !important;">{{ $copyright }}</span>
      <div class="footer-links">
        <a href="{{ $privacyUrl }}">Privacy Policy</a>
        <a href="{{ $termsUrl }}">Terms of Use</a>
      </div>
    </div>
  </footer>
</body>
</html>
