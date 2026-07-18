<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $page->title }} | Real Estate Analysis</title>
<meta name="description" content="Comprehensive real estate analysis for {{ $page->location }}, {{ $page->country }}. Property prices, investment potential, rental intelligence and local market insights.">
<meta name="robots" content="index,follow">
@php
  // Header settings from profile
  $headerBg       = $profile->header_bg_color ?? '#ffffff';
  $headerTextClr  = $profile->header_text_color ?? '#111827';
  $topbarEnabled  = $profile->header_topbar_enabled ?? true;
  $topbarText     = $profile->header_topbar_text ?? 'Real Estate Taxi — free global real estate market intelligence and analysis.';
  $topbarColor    = $profile->header_topbar_color ?? '#ffffff';
  $topbarBg       = $profile->header_topbar_bg_color ?? '#0a0a0a';
  $logoType       = $profile->header_logo_type ?? 'text';
  $logoText       = $profile->header_logo_text ?? $profile->agency_name ?? 'Real Estate Taxi';
  $logoPath       = $profile->header_logo_path ? asset('storage/' . $profile->header_logo_path) : null;
  $logoUrl        = $profile->header_logo_url ?? '#';
  $ctaEnabled     = $profile->header_cta_enabled ?? true;
  $ctaText        = $profile->header_cta_text ?? 'Get Free Report';
  $ctaUrl         = $profile->header_cta_url ?? '#';
  $ctaBg          = $profile->header_cta_bg_color ?? '#ffb31a';
  $ctaClr         = $profile->header_cta_text_color ?? '#0a0a0a';

  // Footer settings
  $footerBg       = $profile->footer_bg_color ?? '#12141c';
  $footerTextClr  = $profile->footer_text_color ?? '#ffffff';
  $col1Title      = $profile->footer_col1_title ?? 'WE GLAD TO OFFER';
  $col1Links      = $profile->footer_col1_links ?? [];
  $col2Title      = $profile->footer_col2_title ?? 'ABOUT US';
  $col2Text       = $profile->footer_col2_text ?? 'Independent real estate intelligence and analysis.';
  $copyright      = $profile->footer_copyright_text ?? ('© ' . date('Y') . ' Real Estate Taxi. All rights reserved.');
  $termsUrl       = $profile->footer_terms_url ?? '#';
  $privacyUrl     = $profile->footer_privacy_url ?? '#';

  // Content sections
  $sections = $page->content_sections ?? [];
  $hasContent = in_array($page->status, ['generated', 'published']) && !empty($sections);
@endphp
<style>
@font-face {
  font-family: 'Satoshi';
  src: url('//cdn.fontshare.com/wf/TTX2Z3BF3P6Y5BQT3IV2VNOK6FL22KUT/7QYRJOI3JIMYHGY6CH7SOIFRQLZOLNJ6/KFIAZD4RUMEZIYV6FQ3T3GP5PDBDB6JY.woff2') format('woff2');
  font-weight: 400;
  font-display: swap;
}
@font-face {
  font-family: 'Satoshi';
  src: url('//cdn.fontshare.com/wf/P2LQKHE6KA6ZP4AAGN72KDWMHH6ZH3TA/ZC32TK2P7FPS5GFTL46EU6KQJA24ZYDB/7AHDUZ4A7LFLVFUIFSARGIWCRQJHISQP.woff2') format('woff2');
  font-weight: 500;
  font-display: swap;
}
@font-face {
  font-family: 'Satoshi';
  src: url('//cdn.fontshare.com/wf/LAFFD4SDUCDVQEXFPDC7C53EQ4ZELWQI/PXCT3G6LO6ICM5I3NTYENYPWJAECAWDD/GHM6WVH6MILNYOOCXHXB5GTSGNTMGXZR.woff2') format('woff2');
  font-weight: 700;
  font-display: swap;
}
@font-face {
  font-family: 'Satoshi';
  src: url('//cdn.fontshare.com/wf/NHPGVFYUXYXE33DZ75OIT4JFGHITX5PE/PSUTMASCDJTVPERDYJZPN23BVUFUCQIF/J64QX5IPOHK56I2KYUNBQ5M2XWZEYKYX.woff2') format('woff2');
  font-weight: 900;
  font-display: swap;
}

:root {
  --bg: #f5f7fb;
  --card: #ffffff;
  --ink: #0f172a;
  --body: #3e4348;
  --muted: #74839a;
  --line: #e5eaf1;
  --teal: #0d8d8c;
  --teal-dark: #086f70;
  --teal-soft: #eef9f9;
  --gold: #ffb31a;
  --black: #0a0a0a;
  --shadow: 0 10px 30px rgba(15,23,42,.06);
  --radius: 14px;
  --header-h: 84px;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  font-family: 'Satoshi', Nunito, Inter, ui-sans-serif, system-ui, -apple-system, sans-serif;
  color: var(--body);
  background: var(--bg);
  line-height: 1.62;
  font-size: 17px;
}
a { color: inherit; text-decoration: none; }
button, input { font: inherit; }

/* ── TOP STRIP ── */
.top-strip {
  background: {{ $topbarBg }};
  color: {{ $topbarColor }};
  font-size: 12.5px;
  font-weight: 600;
  border-bottom: 1px solid rgba(255,255,255,.06);
}
.top-strip-inner {
  width: min(1420px, calc(100% - 36px));
  margin: 0 auto;
  min-height: 36px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}
.top-strip-left { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.top-strip-left strong { color: #fff; }

/* ── HEADER ── */
.header {
  position: sticky;
  top: 52px;
  z-index: 50;
  background: {{ $headerBg }};
  backdrop-filter: blur(18px);
  border-bottom: 1px solid var(--line);
  box-shadow: 0 2px 12px rgba(15,23,42,.04);
}
.container { width: min(1800px, calc(100% - 36px)); margin: 0 auto; }
.header-inner {
  min-height: var(--header-h);
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}
.brand { display: inline-flex; align-items: center; gap: 14px; min-width: max-content; }
.brand-icon {
  width: 46px; height: 46px;
  background: linear-gradient(135deg, #0f2027, #1a3a3a);
  border-radius: 12px;
  display: grid; place-items: center;
  font-size: 22px;
  flex-shrink: 0;
}
.brand-text { display: flex; flex-direction: column; line-height: 1.1; }
.brand-text b { font-size: 17px; letter-spacing: -.02em; color: {{ $headerTextClr }}; font-weight: 900; }
.brand-text span { font-size: 11.5px; color: var(--muted); font-weight: 700; }
.nav { display: flex; align-items: center; gap: 6px; flex: 1; justify-content: center; }
.nav a {
  color: {{ $headerTextClr }};
  font-size: 13.5px;
  font-weight: 800;
  white-space: nowrap;
  padding: 6px 12px;
  border-radius: 8px;
  display: inline-flex; align-items: center; gap: 4px;
  transition: background .15s;
}
.nav a:hover { background: #f1f5f9; color: var(--teal-dark); }
.header-actions { display: flex; align-items: center; gap: 10px; }
.primary-btn {
  min-height: 42px;
  display: inline-flex; align-items: center; justify-content: center;
  padding: 0 18px;
  border-radius: 11px;
  border: 1.5px solid var(--teal);
  background: {{ $ctaBg }};
  color: {{ $ctaClr }};
  font-weight: 800;
  font-size: 13.5px;
  cursor: pointer;
  white-space: nowrap;
}
.primary-btn:hover { filter: brightness(1.1); }

/* ── MAIN GRID ── */
.page { padding: 28px 0 48px; }
.grid { display: grid; grid-template-columns: repeat(12, minmax(0,1fr)); gap: 20px; }
.card {
  position: relative;
  border: 1px solid var(--line);
  border-radius: var(--radius);
  background: var(--card);
  box-shadow: var(--shadow);
  overflow: hidden;
}
.card-pad { padding: 30px; }
.span-12 { grid-column: span 12; }
.span-6 { grid-column: span 6; }

.number-label {
  color: var(--teal-dark);
  font-size: 11px;
  font-weight: 900;
  letter-spacing: .13em;
  display: inline-flex; align-items: center; gap: 7px;
}
.number-label::before {
  content: "";
  display: inline-block;
  width: 7px; height: 7px;
  border-radius: 50%;
  background: var(--gold);
}

/* ── HERO ── */
.hero-title {
  margin: 12px 0 14px;
  font-size: clamp(34px, 4vw, 58px);
  line-height: 1.06;
  letter-spacing: -1.8px;
  font-weight: 900;
  color: #0f172a;
}
.hero-title-2 {
  margin: 12px 0 14px;
  font-size: clamp(30px, 3.4vw, 48px);
  line-height: 1.08;
  letter-spacing: -1.6px;
  font-weight: 900;
  color: #0f172a;
}
.hero-copy {
  font-size: 18px;
  color: #3e4348;
  max-width: 820px;
  line-height: 1.7;
}

/* ── ANALYSIS STYLES ── */
.analysis-hero .hero-copy { max-width: 1120px; }
.analysis-direct {
  background: #0f172a;
  border-color: #0f172a;
}
.analysis-direct .number-label { color: #a7f3d0; }
.analysis-direct .hero-title-2 { color: #fff; margin-bottom: 12px; }
.analysis-direct .analysis-copy { color: #e2e8f0 !important; text-align: left !important; }
.analysis-direct .analysis-copy p { color: #e2e8f0 !important; font-size: 18px; text-align: left !important; }
.analysis-direct .analysis-copy ul { 
  margin: 20px 0 !important; 
  padding: 0 !important; 
  text-align: left !important; 
  list-style: none !important;
  display: block !important;
}
.analysis-direct .analysis-copy ul li { 
  color: #e2e8f0 !important; 
  padding-left: 28px !important; 
  text-align: left !important; 
  position: relative !important;
  margin-bottom: 10px !important;
  display: block !important;
}
.analysis-direct .analysis-copy ul li::before { 
  content: "✓" !important;
  position: absolute !important;
  left: 0 !important;
  top: 0 !important;
  color: #a7f3d0 !important; 
  font-weight: 900 !important;
}
.analysis-direct .analysis-copy ol { 
  margin: 20px 0 !important; 
  padding: 0 !important;
  text-align: left !important; 
  list-style: none !important; 
}
.analysis-direct .analysis-copy ol li { 
  color: #e2e8f0 !important; 
  padding-left: 28px !important; 
  text-align: left !important; 
  position: relative !important; 
  margin-bottom: 10px !important; 
}
.analysis-direct .analysis-copy ol li::before { 
  content: "✓" !important; 
  position: absolute !important; 
  left: 0 !important; 
  color: #a7f3d0 !important; 
  font-weight: 900 !important; 
}
.analysis-direct strong { color: #fff !important; }
.analysis-direct h3, .analysis-direct h4 { color: #fff !important; margin-top: 24px; margin-bottom: 12px; text-align: left !important; }
.analysis-direct .analysis-copy table { border-color: rgba(255,255,255,0.15); }
.analysis-direct .analysis-copy table th { background: rgba(255,255,255,0.08); color: #fff; border-color: rgba(255,255,255,0.15); }
.analysis-direct .analysis-copy table td { background: transparent; color: #e2e8f0; border-color: rgba(255,255,255,0.15); }

.analysis-metrics {
  margin-top: 22px;
  display: grid;
  grid-template-columns: repeat(3, minmax(0,1fr));
  gap: 14px;
}
.metric-taxi {
  position: relative;
  min-height: 158px;
  padding: 21px;
  display: flex;
  align-items: flex-start;
  gap: 15px;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  background: #fff;
  overflow: hidden;
}
.metric-taxi::after {
  content: "";
  position: absolute;
  right: -28px;
  bottom: -30px;
  width: 110px;
  height: 110px;
  border: 18px solid rgba(10,10,10,.035);
  border-radius: 50%;
}
.metric-icon {
  width: 48px; height: 48px; flex: 0 0 48px;
  display: grid; place-items: center;
  border-radius: 13px;
  background: #0a0a0a;
  color: #ffb31a;
  box-shadow: 0 7px 16px rgba(10,10,10,.15);
  font-size: 16px;
  font-weight: 900;
}
.metric-content { position: relative; z-index: 1; }
.metric-content small {
  display: block;
  margin-bottom: 5px;
  color: #0a0a0a;
  font-size: 10px;
  font-weight: 900;
  letter-spacing: .1em;
  text-transform: uppercase;
}
.metric-content b {
  display: block;
  color: #0f172a;
  font-size: 22px;
  line-height: 1.12;
  font-weight: 900;
}
.metric-content span {
  display: block;
  margin-top: 7px;
  color: #64748b;
  font-size: 12.5px;
  line-height: 1.4;
}

/* ── TOPIC CARDS ── */
.topic-card::after {
  content: attr(data-no);
  position: absolute; right: 16px; bottom: -36px;
  color: #f0f4f8;
  font-size: 142px; line-height: 1; font-weight: 900; letter-spacing: -8px;
  pointer-events: none;
}
.topic-head {
  display: flex; align-items: center; gap: 14px;
  padding-bottom: 18px;
  border-bottom: 1px solid var(--line);
}
.topic-number {
  width: 48px; height: 48px; flex: 0 0 48px;
  display: grid; place-items: center;
  border-radius: 13px;
  background: #0a0a0a;
  color: #ffb31a;
  box-shadow: 0 7px 16px rgba(10,10,10,.15);
  font-size: 14px;
  font-weight: 900;
}
.topic-head h2 { margin: 2px 0 0; font-size: 23px; line-height: 1.2; letter-spacing: -.5px; color: #0f172a; }

/* ── ANALYSIS COPY ── */
.analysis-copy {
  position: relative;
  z-index: 1;
  padding-top: 20px;
}
.analysis-copy p {
  color: #3e4348;
  font-size: 16px;
  line-height: 1.72;
  margin: 0;
}
.analysis-copy p + p { margin-top: 14px; }
.analysis-copy strong { color: #0f172a; }
.analysis-copy a {
  color: #086f70;
  font-weight: 800;
  text-decoration: underline;
  text-decoration-color: #b7dede;
  text-underline-offset: 3px;
}
.analysis-copy ul {
  margin: 16px 0 0;
  padding: 0;
  list-style: none;
  display: grid;
  gap: 9px;
}
.analysis-copy ul li {
  position: relative;
  padding-left: 23px;
  color: #3e4348;
  font-size: 15px;
  line-height: 1.58;
}
.analysis-copy ul li::before {
  content: "✓";
  position: absolute;
  left: 0;
  top: 0;
  color: #0d8d8c;
  font-weight: 900;
}
.analysis-copy dl {
  display: grid;
  grid-template-columns: minmax(145px,34%) 1fr;
  margin: 4px 0 0;
}
.analysis-copy dt,
.analysis-copy dd {
  padding: 10px 0;
  border-bottom: 1px solid #e5eaf1;
  margin: 0;
  font-size: 14.5px;
  line-height: 1.48;
}
.analysis-copy dt {
  color: #74839a;
  font-weight: 700;
  padding-right: 15px;
}
.analysis-copy dd {
  color: #0f172a;
  font-weight: 800;
}
.analysis-copy table,
.analysis-copy .data-table {
  width: 100%;
  border-collapse: collapse;
  margin: 14px 0;
  font-size: 13.5px;
  border: 1px solid #e5eaf1;
  border-radius: 8px;
  overflow: hidden;
}
.analysis-copy table th,
.analysis-copy table td,
.analysis-copy .data-table th,
.analysis-copy .data-table td {
  text-align: left;
  padding: 12px 14px;
  border: 1px solid #e5eaf1;
  vertical-align: top;
}
.analysis-copy table th,
.analysis-copy .data-table th {
  color: #0f172a;
  background: #f8fafc;
  font-size: 11px;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: .05em;
}
.analysis-copy table td,
.analysis-copy .data-table td {
  color: #3e4348;
  font-weight: 500;
  background: #fff;
}
.analysis-copy table tbody tr:hover td {
  background: #f8fafc;
}
.analysis-copy .insight {
  margin: 17px 0 0;
  padding: 14px 16px;
  border-left: 3px solid #ffb31a;
  border-radius: 0 9px 9px 0;
  color: #1e3a5f;
  background: #fff8e7;
  font-size: 14.5px;
  font-weight: 700;
  line-height: 1.55;
}

.analysis-section-title {
  background: transparent;
  border: 0;
  box-shadow: none;
}
.analysis-section-title .card-pad { padding: 26px 4px 4px; }
.focus-copy { color: #3e4348; font-size: 17px; margin-top: 10px; line-height: 1.72; }

.analysis-card.wide-analysis { grid-column: span 12; }
.analysis-card.alert-card { border-color: #f1c15b; }
.analysis-card.alert-card::before {
  content: "DATA / RISK ALERT";
  position: absolute;
  right: 18px;
  top: 17px;
  color: #7c5700;
  background: #fff3cd;
  border: 1px solid #f4d98f;
  border-radius: 7px;
  padding: 4px 8px;
  font-size: 9px;
  font-weight: 900;
  letter-spacing: .08em;
  z-index: 2;
}

.placeholder-content {
  background: linear-gradient(135deg, #f8fafc, #e2e8f0);
  border: 2px dashed #cbd5e1;
  border-radius: 12px;
  padding: 40px;
  text-align: center;
  color: #64748b;
}
.placeholder-content h4 {
  font-size: 16px;
  font-weight: 700;
  margin-bottom: 8px;
  color: #475569;
}

/* ── PROPERTY IMAGES SECTION ── */
.property-images-section {
  background: #fff;
}
.property-images-section .topic-number {
  background: #0f172a;
  color: #fff;
  font-size: 18px;
}
.property-gallery {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-top: 20px;
}
.gallery-item {
  aspect-ratio: 4/3;
  border-radius: 10px;
  overflow: hidden;
  background: #f1f5f9;
}
.gallery-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}
.gallery-item:hover img {
  transform: scale(1.05);
}
.images-placeholder {
  text-align: center;
  padding: 40px 20px;
}
.images-placeholder .placeholder-icon {
  font-size: 48px;
  margin-bottom: 16px;
}
.images-placeholder h4 {
  color: #0f172a;
  font-size: 20px;
  font-weight: 800;
  margin-bottom: 8px;
}
.images-placeholder p {
  color: #64748b;
  font-size: 15px;
  max-width: 500px;
  margin: 0 auto 24px;
}
.placeholder-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  max-width: 800px;
  margin: 0 auto;
}
.placeholder-img {
  aspect-ratio: 4/3;
  background: linear-gradient(135deg, #e2e8f0, #f1f5f9);
  border: 2px dashed #cbd5e1;
  border-radius: 10px;
  position: relative;
}
.placeholder-img::after {
  content: "📷";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 24px;
  opacity: 0.4;
}
.source-attribution {
  margin-top: 20px;
  padding-top: 16px;
  border-top: 1px solid var(--line);
  text-align: center;
}
.source-attribution p {
  color: #64748b;
  font-size: 13px;
}
.source-attribution a {
  color: var(--teal-dark);
  font-weight: 700;
}
@media (max-width: 768px) {
  .property-gallery,
  .placeholder-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* ── REFERENCES SECTION ── */
.references-section {
  background: linear-gradient(135deg, #f8fafc, #eef2f7);
  border: 2px solid var(--line);
}
.references-section .topic-number {
  background: var(--teal);
  color: #fff;
}
.references-section .reference-item h4 {
  color: #0f172a;
  font-size: 16px;
  font-weight: 800;
  margin: 0 0 12px;
}
.references-section .reference-item p {
  color: #475569;
  font-size: 15px;
  line-height: 1.7;
}
.references-section .reference-item a {
  color: var(--teal-dark);
  font-weight: 700;
  text-decoration: underline;
  text-underline-offset: 3px;
}
.references-section .reference-item ul {
  margin-top: 10px;
}
.references-section .reference-item ul li {
  color: #475569;
  font-size: 14px;
}
.placeholder-content p {
  font-size: 14px;
  line-height: 1.5;
}

/* ── FOOTER ── */
footer {
  background: {{ $footerBg }};
  color: {{ $footerTextClr }};
  font-size: 16px;
  position: relative;
  overflow: hidden;
}
footer::before {
  content: "";
  position: absolute;
  inset: 0;
  opacity: .25;
  pointer-events: none;
  background: radial-gradient(circle at 20% 20%, rgba(255,255,255,.03) 0%, transparent 25%),
              radial-gradient(circle at 80% 80%, rgba(255,255,255,.03) 0%, transparent 25%);
  background-size: 60px 60px;
}
.footer-top {
  padding: 56px 0 40px;
  border-bottom: 1px solid rgba(255,255,255,.08);
  position: relative;
  z-index: 1;
}
.footer-grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr 1.2fr;
  gap: 50px;
}
.footer-col h4 { color: #fff; font-size: 13px; font-weight: 900; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 20px; }
.footer-col ul { list-style: none; padding: 0; display: grid; gap: 12px; }
.footer-col ul li { display: flex; align-items: center; gap: 10px; }
.footer-col ul li::before { content: ">"; color: rgba(255,255,255,.35); font-size: 11px; font-weight: 900; }
.footer-col ul li a { color: rgba(255,255,255,.55); font-size: 13.5px; font-weight: 600; }
.footer-col ul li a:hover { color: var(--gold); }
.footer-col p { color: rgba(255,255,255,.55); font-size: 13.5px; line-height: 1.65; }
.footer-bottom {
  padding: 18px 0;
  display: flex; align-items: center; justify-content: space-between;
  gap: 16px; flex-wrap: wrap;
  position: relative;
  z-index: 1;
}
.footer-bottom p { color: rgba(255,255,255,.35); font-size: 12.5px; }
.footer-bottom-links { display: flex; gap: 20px; }
.footer-bottom-links a { color: rgba(255,255,255,.35); font-size: 12.5px; font-weight: 600; }
.footer-bottom-links a:hover { color: rgba(255,255,255,.7); }

.taxi-checker-line {
  width: 100%;
  height: 12px;
  background: conic-gradient(#ffffff 25%, #0a0b0c 0 50%, #ffffff 0 75%, #0a0b0c 0) 0 0 / 12px 12px;
}

/* ── RESPONSIVE ── */
@media (max-width: 1100px) {
  .analysis-metrics { grid-template-columns: repeat(2, minmax(0,1fr)); }
}
@media (max-width: 1024px) {
  .span-6 { grid-column: span 12; }
  .nav { display: none; }
  .footer-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 768px) {
  .footer-grid { grid-template-columns: 1fr; gap: 28px; }
}
@media (max-width: 680px) {
  .analysis-metrics { grid-template-columns: 1fr; }
  .hero-title { font-size: 33px; }
  .card-pad { padding: 22px; }
  .analysis-copy dl { grid-template-columns: 1fr; }
  .analysis-copy dt { padding-bottom: 3px; border-bottom: 0; }
  .analysis-copy dd { padding-top: 0; }
}
</style>
</head>
<body>


{{-- Top Strip --}}
@if($topbarEnabled)
<div class="top-strip">
  <div class="top-strip-inner">
    <div class="top-strip-left"><strong>Real Estate Taxi</strong> — {{ $topbarText }}</div>
  </div>
</div>
@endif

{{-- Header --}}
<header class="header">
  <div class="container">
    <div class="header-inner">
      <a class="brand" href="{{ $logoUrl }}">
        <div class="brand-icon">🚕</div>
        <div class="brand-text">
          @if($logoType === 'image' && $logoPath)
            <img src="{{ $logoPath }}" alt="{{ $logoText }}" style="height:40px;">
          @else
            <b>{{ $logoText }}</b>
            <span>Real Estate Intelligence</span>
          @endif
        </div>
      </a>
      <nav class="nav">
        <a href="#analysis">Property</a>
        <a href="#market">Valuation</a>
        <a href="#legal">Due Diligence</a>
        <a href="#faq">FAQ</a>
      </nav>
      @if($ctaEnabled)
      <div class="header-actions">
        <a href="{{ $ctaUrl }}" class="primary-btn">{{ $ctaText }}</a>
      </div>
      @endif
    </div>
  </div>
</header>

<main class="page" id="top">
  <div class="container">
    <div class="grid">

      {{-- Hero Section --}}
      <section class="card span-12 analysis-hero" id="analysis">
        <div class="card-pad">
          <span class="number-label">01 · REAL ESTATE ANALYSIS</span>
          <h1 class="hero-title">{{ $page->title }}</h1>
          <p class="hero-copy">Comprehensive real estate analysis for {{ $page->location }}, {{ $page->country }}. Property prices, investment potential, rental intelligence, micro-location factors and local market insights.</p>
          
          <div class="analysis-metrics">
            <div class="metric-taxi">
              <div class="metric-icon">01</div>
              <div class="metric-content">
                <small>Location</small>
                <b>{{ $page->location }}</b>
                <span>{{ $page->country }}</span>
              </div>
            </div>
            <div class="metric-taxi">
              <div class="metric-icon">02</div>
              <div class="metric-content">
                <small>Source</small>
                <b>{{ Str::limit($page->source_title, 30) }}</b>
                <span>{{ $page->source_type === 'local_seo' ? 'Local SEO Campaign' : 'AI Search Page' }}</span>
              </div>
            </div>
            <div class="metric-taxi">
              <div class="metric-icon">03</div>
              <div class="metric-content">
                <small>Analysis Date</small>
                <b>{{ $page->scheduled_for->format('M j, Y') }}</b>
                <span>Authority page generation</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {{-- Direct Answer Section (Box 2) --}}
      <section class="card span-12 analysis-direct">
        <div class="card-pad">
          <span class="number-label">02 · DIRECT ANALYSIS ANSWER</span>
          <h2 class="hero-title-2">Direct analysis answer</h2>
          <div class="analysis-copy">
            @if($hasContent && isset($sections[1]) && !empty($sections[1]['content']))
              {!! $sections[1]['content'] !!}
            @else
              <div class="placeholder-content" style="background:rgba(255,255,255,0.1);border-color:rgba(255,255,255,0.2);">
                <h4 style="color:#fff;">AI Content Pending</h4>
                <p style="color:rgba(255,255,255,0.7);">This section will be generated by AI on {{ $page->scheduled_for->format('F j, Y') }}.<br>
                The analysis will provide a direct executive summary of the real estate market in {{ $page->location }}.</p>
              </div>
            @endif
          </div>
        </div>
      </section>

      {{-- Main Analysis Section Title --}}
      <section class="card span-12 analysis-section-title" id="overview">
        <div class="card-pad">
          <span class="number-label">PROPERTY ANALYSIS</span>
          <h2 class="hero-title-2">{{ $page->location }} Real Estate Intelligence</h2>
          <p class="focus-copy">Entity, location and investment context.</p>
        </div>
      </section>

      {{-- Analysis Boxes --}}
      @if($hasContent)
        @php
          // Separate regular boxes from special sections
          $regularSections = collect($sections)->filter(fn($s) => !isset($s['is_special']) && $s['box_number'] !== 'agency_ref');
          $agencyRefSection = collect($sections)->firstWhere('box_number', 'agency_ref');
        @endphp
        
        @foreach($regularSections as $index => $section)
          @if($section['box_number'] == 2) @continue @endif {{-- Skip box 2 as it's shown above --}}
          @php
            $boxNum = str_pad($section['box_number'] ?? ($index + 1), 2, '0', STR_PAD_LEFT);
            $isWide = $section['box_number'] <= 3;
            $isAlert = str_contains(strtolower($section['title'] ?? ''), 'risk') || str_contains(strtolower($section['title'] ?? ''), 'alert') || str_contains(strtolower($section['title'] ?? ''), 'consistency');
          @endphp
          <section class="card topic-card analysis-card {{ $isWide ? 'span-12 wide-analysis' : 'span-6' }} {{ $isAlert ? 'alert-card' : '' }}" data-no="{{ $boxNum }}">
            <div class="card-pad">
              <div class="topic-head">
                <span class="topic-number" aria-hidden="true">{{ $boxNum }}</span>
                <h2>{{ $section['title'] ?? 'Analysis Section ' . $section['box_number'] }}</h2>
              </div>
              <div class="analysis-copy">
                @if(!empty($section['content']))
                  {!! $section['content'] !!}
                @else
                  <div class="placeholder-content">
                    <h4>Content Generation Failed</h4>
                    <p>This section could not be generated. Please try regenerating the page.</p>
                  </div>
                @endif
              </div>
            </div>
          </section>
        @endforeach

        {{-- Property Images Section --}}
        @php
          // Get property images from source listing if available
          $propertyImages = $page->property_images ?? [];
          
          // If no images stored, try to get from source listing
          if (empty($propertyImages) && $page->source_type === 'ai_search') {
              $aiPage = \App\Models\AiAuthorityPage::find($page->source_id);
              if ($aiPage && $aiPage->agency_listing_id) {
                  $listing = \App\Models\AgencyListing::find($aiPage->agency_listing_id);
                  if ($listing && $listing->images) {
                      $propertyImages = collect($listing->images)->map(function($img) {
                          return asset('storage/' . $img);
                      })->toArray();
                  }
              }
          } elseif (empty($propertyImages) && $page->source_type === 'local_seo') {
              $campaign = \App\Models\LocalSeoCampaign::find($page->source_id);
              if ($campaign && $campaign->agency_listing_id) {
                  $listing = \App\Models\AgencyListing::find($campaign->agency_listing_id);
                  if ($listing && $listing->images) {
                      $propertyImages = collect($listing->images)->map(function($img) {
                          return asset('storage/' . $img);
                      })->toArray();
                  }
              }
          }
          
          $hasImages = !empty($propertyImages);
        @endphp
        <section class="card span-12 property-images-section" id="property-gallery">
          <div class="card-pad">
            <div class="topic-head">
              <span class="topic-number" aria-hidden="true">📷</span>
              <h2>Property Gallery</h2>
            </div>
            <div class="images-content">
              @if($hasImages)
                <div class="property-gallery">
                  @foreach($propertyImages as $index => $image)
                    <div class="gallery-item">
                      <img src="{{ $image['url'] ?? $image }}" alt="Property image {{ $index + 1 }}" loading="lazy">
                    </div>
                  @endforeach
                </div>
              @else
                <div class="images-placeholder">
                  <div class="placeholder-icon">🏠</div>
                  <h4>Property Images</h4>
                  <p>High-quality images of this property will be displayed here once available from the listing source.</p>
                  <div class="placeholder-grid">
                    <div class="placeholder-img"></div>
                    <div class="placeholder-img"></div>
                    <div class="placeholder-img"></div>
                    <div class="placeholder-img"></div>
                  </div>
                </div>
              @endif
              <div class="source-attribution">
                <p>Images sourced from <a href="#references">{{ $profile->agency_name ?? 'listing agency' }}</a>. <a href="#references">View source reference →</a></p>
              </div>
            </div>
          </div>
        </section>

        {{-- References Section --}}
        <section class="card span-12 references-section" id="references">
          <div class="card-pad">
            <div class="topic-head">
              <span class="topic-number" aria-hidden="true">REF</span>
              <h2>References & Sources</h2>
            </div>
            <div class="analysis-copy">
              <div class="reference-item">
                <h4>Source Agency</h4>
                @if($agencyRefSection && !empty($agencyRefSection['content']))
                  {!! $agencyRefSection['content'] !!}
                @else
                  <p>{{ $profile->agency_name ?? 'Listing Agency' }} is a trusted real estate professional operating in {{ $page->location }}, providing expert guidance on local property opportunities.</p>
                @endif
                @if($profile->official_website_url)
                  <p><a href="{{ $profile->official_website_url }}" target="_blank" rel="noopener">Visit {{ $profile->agency_name ?? 'Agency' }} Website →</a></p>
                @endif
              </div>
              <div class="reference-item" style="margin-top: 24px;">
                <h4>Analysis Methodology</h4>
                <p>This analysis was generated using AI-assisted real estate intelligence tools. All data points are derived from publicly available listing information and should be independently verified before making any investment decisions. This content does not constitute financial, legal, or investment advice.</p>
              </div>
              <div class="reference-item" style="margin-top: 24px;">
                <h4>Data Sources</h4>
                <ul>
                  <li>Original property listing from {{ $profile->agency_name ?? 'source agency' }}</li>
                  <li>Local market data and regional statistics</li>
                  <li>{{ $page->location }}, {{ $page->country }} real estate market analysis</li>
                </ul>
              </div>
            </div>
          </div>
        </section>
      @else
        {{-- Show placeholder boxes when content not yet generated --}}
        @php
          $placeholderBoxes = [
            ['num' => '01', 'title' => '1. Property identity and source-normalized facts'],
            ['num' => '03', 'title' => '3. Source Data Consistency / Due Diligence Alert', 'alert' => true],
            ['num' => '04', 'title' => '4. Architecture and spatial program'],
            ['num' => '05', 'title' => '5. Bedroom and privacy analysis'],
            ['num' => '06', 'title' => '6. Sea-view and location scarcity'],
            ['num' => '07', 'title' => '7. Outdoor living and amenities'],
          ];
        @endphp

        @foreach($placeholderBoxes as $index => $box)
          <section class="card topic-card analysis-card {{ $index < 2 ? 'span-12 wide-analysis' : 'span-6' }} {{ isset($box['alert']) ? 'alert-card' : '' }}" data-no="{{ $box['num'] }}">
            <div class="card-pad">
              <div class="topic-head">
                <span class="topic-number" aria-hidden="true">{{ $box['num'] }}</span>
                <h2>{{ $box['title'] }}</h2>
              </div>
              <div class="analysis-copy">
                <div class="placeholder-content">
                  <h4>AI Content Pending</h4>
                  <p>This analysis box will be populated with AI-generated content on {{ $page->scheduled_for->format('F j, Y') }}.</p>
                </div>
              </div>
            </div>
          </section>
        @endforeach

        {{-- More sections indicator --}}
        <section class="card span-12" style="background: #f8fafc; border-style: dashed;">
          <div class="card-pad" style="text-align: center; padding: 60px;">
            <h3 style="color: #475569; font-size: 20px; margin-bottom: 12px;">+ 24 More Analysis Sections</h3>
            <p style="color: #64748b; font-size: 15px; max-width: 600px; margin: 0 auto;">
              The full Authority Builder page will include 30 comprehensive analysis boxes covering market pricing, rental analysis, 
              living conditions, legal considerations, investment risks, and professional recommendations, plus a References section.
            </p>
          </div>
        </section>
      @endif

    </div>
  </div>
</main>

{{-- Footer --}}
<div class="taxi-checker-line"></div>
<footer>
  <div class="container">
    <div class="footer-top">
      <div class="footer-grid">
        <div class="footer-col">
          <h4>{{ $col1Title }}</h4>
          <ul>
            @if(!empty($col1Links))
              @foreach($col1Links as $link)
                <li><a href="{{ $link['url'] ?? '#' }}">{{ $link['label'] ?? '' }}</a></li>
              @endforeach
            @else
              <li><a href="#">Real Estate Market Analysis</a></li>
              <li><a href="#">Property Investment Intelligence</a></li>
              <li><a href="#">Location-Based Insights</a></li>
            @endif
          </ul>
        </div>
        <div class="footer-col">
          <h4>Analysis Sections</h4>
          <ul>
            <li><a href="#analysis">Property Overview</a></li>
            <li><a href="#market">Market Valuation</a></li>
            <li><a href="#legal">Due Diligence</a></li>
            <li><a href="#faq">FAQ</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>{{ $col2Title }}</h4>
          <p>{{ $col2Text }}</p>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>{{ $copyright }}</p>
      <div class="footer-bottom-links">
        <a href="{{ $privacyUrl }}">Privacy Policy</a>
        <a href="{{ $termsUrl }}">Terms of Use</a>
      </div>
    </div>
  </div>
</footer>

</body>
</html>
