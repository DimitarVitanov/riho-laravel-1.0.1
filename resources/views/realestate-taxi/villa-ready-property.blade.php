<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<title>Milna, Brač — Complete Villa Ready Croatia Property | Your Real Estate Agency</title>
<meta content="noindex,nofollow" name="robots"/>
<style>
:root {
  --ink: #0A0B0D;
  --muted: #6b7280;
  --bg: #f4f5f6;
  --card: #ffffff;
  --soft: #f8f9fa;
  --line: #e4e6e9;
  --accent: #0A0B0D;
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
  background: #0A0B0D;
  color: #ffffff;
  text-align: left;
  padding: 10px 16px;
  font-size: 13px;
  font-weight: 500;
  padding-left:6.5%;
}

/* Header */
.site-header {
  background: #ffffff;
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
.brand strong { display: block; font-size: 13px; letter-spacing: -0.01em; color: #111827; }
.brand small { display: block; color: var(--muted); font-weight: 500; font-size: 12px; }
.nav { display: flex; gap: 24px; align-items: center; }
.nav a { font-size: 14px; font-weight: 600; color: #111827; }
.nav a:hover { color: var(--accent); text-decoration: none; }
.nav .cta-btn {
  background: #f59e0b;
  color: #1a1a1a;
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
  margin-bottom: 24px;
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
.grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: center; }
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
  background: #0A0B0D;
  color: #ffffff;
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


/* Villa Ready property system additions */
.property-hero-image{width:100%;height:520px;object-fit:cover;border-radius:16px;display:block}
.property-gallery{display:grid;grid-template-columns:2fr 1fr 1fr;grid-template-rows:220px 220px;gap:10px}
.property-gallery img{width:100%;height:100%;object-fit:cover;border-radius:12px;cursor:pointer}
.property-gallery img:first-child{grid-row:1/3}
.property-meta{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}
.property-meta .chip{background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.22);color:#fff}
.spec-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
.spec-box{padding:18px;border:1px solid var(--line);border-radius:14px;background:var(--soft)}
.spec-box strong{display:block;font-size:18px}.spec-box span{color:var(--muted);font-size:12px;font-weight:700}
.unit-table{width:100%;border-collapse:collapse}.unit-table th,.unit-table td{padding:14px;border-bottom:1px solid var(--line);text-align:left}
.unit-table th{font-size:11px;text-transform:uppercase;letter-spacing:.05em;color:var(--muted)}
.status-pill{display:inline-flex;padding:7px 11px;border-radius:999px;font-size:11px;font-weight:800}
.status-available{background:#dcfce7;color:#166534}.status-reserved{background:#fef3c7;color:#92400e}
.agency-contact{background:#0A0B0D;color:#fff}.agency-contact .sub{color:rgba(255,255,255,.72)}
.agency-contact input,.agency-contact textarea,.agency-contact select{border-color:rgba(255,255,255,.28)}
.offer-card{display:grid;gap:14px}.offer-card img{width:100%;height:220px;object-fit:cover;border-radius:14px}
.offer-card h3{margin:0}.offer-button{width:100%}
.notice-bar{border:1px solid #f59e0b;background:#fffbeb;color:#78350f;padding:13px 16px;border-radius:12px;font-size:13px;font-weight:700}
.image-note{font-size:12px;color:var(--muted);margin-top:8px}
.modal-viewer{display:none;position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:9999;padding:35px}
.modal-viewer.open{display:grid;place-items:center}.modal-viewer img{max-height:90vh;max-width:95vw;border-radius:12px}
.modal-close{position:fixed;top:18px;right:25px;color:white;font-size:34px;cursor:pointer}
.price-panel{padding:24px;background:#0A0B0D;color:#fff;border-radius:16px}.price-panel .big{font-size:34px;font-weight:900}
@media(max-width:900px){.property-gallery{grid-template-columns:1fr 1fr;grid-template-rows:auto}.property-gallery img:first-child{grid-row:auto;grid-column:1/3;height:300px}.property-gallery img{height:180px}.spec-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.property-gallery{display:block}.property-gallery img{height:220px;margin-bottom:10px}.property-gallery img:first-child{height:260px}.spec-grid{grid-template-columns:1fr}}

/* Complete original Villa Ready Croatia content additions */
.original-hero{background:#0A0B0D url('/villa-ready-assets/lines.webp') center/cover no-repeat;}
.original-hero-grid{display:grid;grid-template-columns:minmax(0,1.1fr) minmax(320px,.9fr);gap:28px;align-items:center;position:relative;z-index:1}
.original-hero-media{position:relative}.original-hero-media img{width:100%;height:430px;object-fit:cover;border-radius:18px;border:1px solid rgba(255,255,255,.18)}
.original-hero-media .media-label{position:absolute;left:18px;bottom:18px;background:rgba(0,0,0,.75);padding:10px 14px;border-radius:10px;font-weight:800}
.source-logo-row{display:flex;align-items:center;gap:15px}.source-logo-row img{width:58px;height:58px;object-fit:cover;border-radius:12px}
.full-image{max-width:100%;height:auto;display:block;margin:24px auto 0;border-radius:14px}
.wide-image{width:100%;max-height:620px;object-fit:cover;display:block;border-radius:14px;border:1px solid var(--line)}
.original-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}.original-gallery img{width:100%;height:290px;object-fit:cover;border-radius:14px;cursor:pointer;border:1px solid var(--line)}
.original-gallery img:first-child{grid-column:1/3;height:460px}
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}.stat-box{background:#0A0B0D;color:#fff;border-radius:16px;padding:26px;text-align:center}.stat-box strong{font-size:34px;display:block}.stat-box span{font-size:12px;font-weight:800;letter-spacing:.08em}
.plot-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}.plot-box{background:var(--soft);border:1px solid var(--line);border-radius:14px;padding:20px;text-align:center}.plot-box strong{display:block;font-size:30px}.plot-box span{display:block;font-weight:700;color:var(--muted);font-size:12px}
.original-copy p{font-size:16px;line-height:1.8;color:#374151;margin:0 0 16px}.original-copy p:last-child{margin-bottom:0}
.exact-highlight{background:#0A0B0D;color:#fff;padding:22px;border-radius:14px;font-size:20px;font-weight:800;margin:20px 0}
.dark-property-card{background:#0A0B0D;color:#fff}.dark-property-card h2,.dark-property-card h3,.dark-property-card h4{color:#fff}.dark-property-card .sub,.dark-property-card p{color:rgba(255,255,255,.76)}
.discount-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}.discount-box{padding:20px;border:1px solid rgba(255,255,255,.18);border-radius:14px;background:rgba(255,255,255,.06)}.discount-box strong{font-size:30px;display:block}
.building-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}.building-card{border:1px solid rgba(255,255,255,.18);border-radius:16px;padding:20px;background:rgba(255,255,255,.05)}.building-card h3{margin-bottom:14px}.building-row{display:flex;justify-content:space-between;gap:18px;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.14);font-size:14px}.building-row:last-child{border-bottom:0}.building-row.total{font-weight:900;font-size:16px;color:#fff}
.tax-list{list-style:none;padding:0;margin:10px 0 0}.tax-list li{position:relative;padding:9px 0 9px 24px;border-bottom:1px solid var(--line)}.tax-list li:last-child{border:0}.tax-list li:before{content:'';position:absolute;left:0;top:15px;width:8px;height:8px;background:url('/villa-ready-assets/dot.webp') center/cover no-repeat;border-radius:50%}
.call-action-card{background:#f59e0b;color:#111827}.call-action-card h2,.call-action-card p{color:#111827}
.core-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}.core-item{border:1px solid var(--line);border-radius:14px;padding:20px;background:var(--soft)}.core-item h3{font-size:18px}.core-item p{margin:0;color:#4b5563}
.history-quote{font-size:25px;font-weight:900;border-left:5px solid #f59e0b;padding:15px 20px;background:#fffbeb;border-radius:0 12px 12px 0}
.original-source-note{display:flex;justify-content:space-between;gap:20px;align-items:center;background:#fff;border:1px solid var(--line);border-radius:16px;padding:18px;margin-bottom:24px}
.original-source-note p{margin:0;color:var(--muted);font-size:13px}
.sticky-property-nav{position:sticky;top:0;z-index:80;background:rgba(255,255,255,.96);backdrop-filter:blur(12px);border-bottom:1px solid var(--line)}.sticky-property-nav .inner{max-width:var(--max);margin:auto;padding:11px 24px;display:flex;gap:14px;overflow:auto}.sticky-property-nav a{white-space:nowrap;font-size:12px;font-weight:800;padding:8px 11px;border-radius:999px;background:var(--soft)}
@media(max-width:900px){.original-hero-grid,.building-grid,.core-grid{grid-template-columns:1fr}.original-gallery{grid-template-columns:1fr}.original-gallery img:first-child{grid-column:auto;height:300px}.original-gallery img{height:260px}.stat-grid,.plot-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:560px){.stat-grid,.plot-grid,.discount-grid{grid-template-columns:1fr}.original-hero-media img{height:280px}}
</style>
</head>
<body>
<div class="topbar-strip">Property information presented by the real estate agency on behalf of Villa Ready Croatia.</div>
<header class="site-header">
<div class="header-inner">
<a class="brand" href="{{ $profile->official_website_url ?? '/' }}">
@if($profile->logo_url)
<img src="{{ $profile->logo_url }}" alt="{{ $profile->agency_name }}" style="height: 44px; width: auto; border-radius: 12px;">
@else
<span class="brand-mark">⌂</span>
@endif
<span><strong>{{ strtoupper($profile->agency_name ?? 'YOUR REAL ESTATE AGENCY') }}</strong><small>Authorized property presentation</small></span>
</a>
<nav class="nav">
<a href="#">Properties</a>
<a href="#">Areas</a>
<a href="#">Services</a>
<a href="#">About</a>
<a class="cta-btn" href="#contact">Contact Agency</a>
</nav>
</div>
</header><div class="sticky-property-nav"><div class="inner"><a href="#location-value">Location Value</a><a href="#chain-location">4-Villa Location</a><a href="#sea-view">Sea View</a><a href="#access">Access</a><a href="#plot-sizes">Plots</a><a href="#concept">Concept</a><a href="#pricing">Pricing</a><a href="#tax">Tax</a><a href="#core-values">Core Values</a><a href="#contact">Contact Agency</a></div></div>
<div class="wrap">
<div class="original-source-note">
<div class="source-logo-row"><img alt="Villa Ready Croatia logo" src="https://app.villabit.ai/villa-ready-assets/logo.webp" style="width:58px;height:58px;object-fit:contain;border-radius:12px;background:#f8f9fa;"/><div><strong>Original Villa Ready Croatia property content</strong><p>All supplied property texts and meaningful images from the saved source page are inserted below inside the agency design.</p></div></div>
<span class="badge">Agency presentation</span>
</div>
<section class="hero original-hero">
<div class="original-hero-grid">
<div>
<span class="eyebrow">360° · Drone View · Milna · Island of Brač</span>
<h1>RARE, LIMITED, AND<br/>RISING IN VALUE</h1>
<p class="hero-desc">Limited Supply Means Really Limited — It's Real, Not a Sales Pitch.</p>
<div class="property-meta"><span class="chip">WATCH A 360° DRONE VIEW FROM THE SKY</span><span class="chip">MILNA</span><span class="chip">ISLAND OF BRAČ</span></div>
</div>
<div class="original-hero-media"><img alt="360 degree drone view preview" onclick="openViewer(this.src)" src="https://app.villabit.ai/storage/villa-ready/gallery/eJdXoVJHDlZeD3NThGzwFxCnX5Nd37Y2pW5hetzA.jpg"/><span class="media-label" onclick="openVideoViewer()" style="cursor:pointer;">360° Drone View ▶</span></div>
</div>
</section>
<section class="card pad" id="location-value"><div class="title"><div><h2>UNDERSTAND LOCATION VALUE</h2><p class="sub">Why the supply is structurally limited on Brač and other Split-area islands.</p></div></div><div class="original-copy"><p>This is not a sales tactic. It is a structural reality of the Croatian islands — especially on islands where, in strategically located main places, new development is not allowed. On Brač, Milna and Supetar are the two main strategically located places.</p><p>New construction in coastal and natural zones is heavily restricted by law. Large parts of the island are protected, and available building-permitted land is both scarce and tightly regulated.</p><p>At the same time, demand continues to grow — driven by tourism, EU and worldwide buyers, and global interest in Croatian properties, backed by constantly increasing touristic demand.</p><p>So even if we ignore that building-permitted areas are rare, another angle is that these are small islands, literally with no space to grow anyway!</p><p>You understand why local people, when they see that someone on the Split-area islands like Brač and Hvar has property, consider you a "rich man."</p><div class="exact-highlight">The result of any fact-based analysis is simple:<br/>Real limited supply + constant tourism demand = long-term value.</div><p>This is why opportunities like the one we offer here in Milna are not widely available — in fact, this is the ONLY EXISTING ONE for the new building of 25 modern apartments in a 4-villa chain area.</p><p>You can learn more about Milna on the island of Brač if you click here .</p></div></section><section class="card pad" id="original-gallery"><div class="title"><div><h2>ORIGINAL LOCATION AND PROJECT IMAGES</h2><p class="sub">All principal property images supplied in the downloaded source page.</p></div></div>
<div class="original-gallery">
@foreach($property->images->whereIn('image_type', ['gallery', 'aerial', 'concept'])->take(4) as $img)
<img alt="{{ $img->caption ?? $property->title }}" onclick="openViewer(this.src)" src="{{ $img->image_url }}"/>
@endforeach
</div></section><section class="card pad" id="chain-location"><div class="title"><div><h2>THE 4-VILLA CHAIN LOCATION</h2><p class="sub">A permitted development opportunity near the sea and Milna amenities.</p></div></div><div class="original-copy"><p>Discover an exceptional opportunity to own land in one of Milna's most attractive locations, with building permit availability near the sea and still within Milna's amenities. Just above Marina Vlaška, this site offers both tranquillity and convenience. We all like nature, but you still want to be in nature and not too far from the beach, shops, restaurants, ferry, and the centre of the place.</p><p>This is an elite location that still exists in Milna and attracts all these values at the same time.</p><p>You can look around and see green zones that will stay green. You can see with your own eyes that there is no space for more building development, even if the government decides to give permits to anyone.</p></div><img alt="Proximity details" class="full-image" onclick="openViewer(this.src)" src="/villa-ready-assets/details.webp"/></section><section class="card pad" id="sea-view"><div class="grid2"><div><div class="title"><div><h2>SEA VIEW FROM THE LOCATION</h2><p class="sub">The view and access value of the site.</p></div></div><div class="original-copy"><p>Boasting a beautiful sea view from the villa location, with easy access from both the upper road and the charming Riva (promenade); the only available location ideal for a stylish, highest-value holiday home in Milna.</p></div></div><img alt="Sea view from location" class="wide-image" onclick="openViewer(this.src)" src="/villa-ready-assets/villareadycroatia2.webp"/></div></section><section class="card pad" id="map-view"><div class="title"><div><h2>MAP VIEW OF THE LOCATION</h2><p class="sub">Walking access to essential amenities and the sea.</p></div></div><div class="original-copy"><p>Simplified map view shows easy access to all essential amenities — restaurants, shops, and the ferry port are all in close proximity, with a beautiful beach only 10 minutes away on foot. The first sea access is just 5 minutes walking away.</p></div><img alt="Map view of the location" class="wide-image" onclick="openViewer(this.src)" src="/villa-ready-assets/villareadycroatia3.webp"/></section><section class="stat-grid" id="project-numbers">
<div class="stat-box"><strong>4,283</strong><span>M² TOTAL AREA</span></div>
<div class="stat-box"><strong>07</strong><span>PLOTS</span></div>
<div class="stat-box"><strong>04</strong><span>VILLAS</span></div>
<div class="stat-box"><strong>24</strong><span>APARTMENTS</span></div>
</section><section class="card pad" id="access"><div class="title"><div><h2>LAND ACCESS &amp; INFRASTRUCTURE OVERVIEW</h2><p class="sub">Connectivity, privacy and the access routes serving the project.</p></div></div><div class="original-copy"><p>The property benefits from a well-planned network of access roads, ensuring excellent connectivity while maintaining a sense of privacy and exclusivity. Below is a quick overview of the different access points that enhance the appeal and functionality of the site:</p></div><img alt="Land access overview" class="wide-image" onclick="openViewer(this.src)" src="/villa-ready-assets/villareadycroatia4.webp"/><div class="grid2" style="margin-top:18px"><div class="mini-card"><b>MAIN ACCESS ROAD</b><p>While the main road circling Milna is not typically busy, the site is set just inside this route, offering both easy access and a sense of privacy from traffic.</p></div><div class="mini-card"><b>SUPPORTING ACCESS ROAD</b><p>A newly constructed road provides near-exclusive access to the plots, enhancing security and reducing traffic flow—ideal for a peaceful residential environment.</p></div><div class="mini-card"><b>DIRECT ACCESS LANE</b><p>A charming, narrow lane leads directly to the sea, offering the perfect route for walking or driving to the beach, ferry terminal, and nearby shops.</p></div><div class="mini-card"><b>ADDITIONAL ACCESS ROAD</b><p>According to the current Urban Plan, a roundabout is proposed in this area. While not yet constructed, it is likely to be replaced by a more modest, single-lane road.</p></div></div></section><section class="card pad dark-property-card"><h2 style="text-align:center">FULLY READY VILLAS FOR LIVING, HOLIDAYS,<br/>AND RENTAL INCOME</h2></section><section class="card pad" id="plot-sizes"><div class="title"><div><h2>LAND PLOT SIZES (M²)</h2><p class="sub">All seven original plot sizes.</p></div></div><div class="plot-grid"><div class="plot-box"><strong>724</strong><span>SITE 1 — M²</span></div><div class="plot-box"><strong>614</strong><span>SITE 2 — M²</span></div><div class="plot-box"><strong>494</strong><span>SITE 3 — M²</span></div><div class="plot-box"><strong>470</strong><span>SITE 4 — M²</span></div><div class="plot-box"><strong>595</strong><span>SITE 5 — M²</span></div><div class="plot-box"><strong>612</strong><span>SITE 6 — M²</span></div><div class="plot-box"><strong>774</strong><span>SITE 7 — M²</span></div></div><img alt="Land plot sizes" class="wide-image" onclick="openViewer(this.src)" src="/villa-ready-assets/villareadycroatia5.jpg" style="margin-top:18px"/></section><section class="card pad" id="concept"><div class="title"><div><h2>CONCEPTUAL SITE DEVELOPMENT</h2><p class="sub">Original conceptual presentation supplied with the project.</p></div></div><div class="original-copy"><p>The images shown are conceptual visuals provided for illustrative purposes only. Final building designs and layouts will be developed in collaboration with an architect, tailored to your individual vision and preferences.</p></div><img alt="Conceptual site development" class="wide-image" onclick="openViewer(this.src)" src="/villa-ready-assets/villareadycroatia6.webp"/></section><section class="card pad" id="aerial"><div class="title"><div><h2>AERIAL SITE PERSPECTIVE</h2><p class="sub">Original aerial project perspective.</p></div></div><img alt="Aerial site perspective" class="wide-image" onclick="openViewer(this.src)" src="/villa-ready-assets/villareadycroatia7.jpg"/></section><section class="card pad dark-property-card" id="pricing"><div class="title"><div><h2>PRICING OPTIONS</h2><p class="sub">Exact payment, discount, building structure and price information from the source page.</p></div></div>
<div class="original-copy"><p>These are the actual prices, payable in a 2-step process: 30% at the start, and the remaining 70% when building starts.</p><p>Villa Ready Croatia offers two major discount possibilities for a limited number of properties (maximum 50% of properties can have a discount, based on a first-come, first-served allocation):</p></div>
<div class="discount-grid"><div class="discount-box"><span>Whole apartment paid in advance:</span><strong>10% discount</strong><p style="margin:8px 0 0;color:rgba(255,255,255,.72)">Whole apartment paid in advance: 10% discount</p></div><div class="discount-box"><span>Whole villa paid in advance:</span><strong>15% discount</strong><p style="margin:8px 0 0;color:rgba(255,255,255,.72)">Whole villa paid in advance: 15% discount</p></div></div>
<div class="highlight-box" style="margin:18px 0">CUSTOM VILLA SETUP OPTION: People who want an ultra-custom villa that is not divided into apartments, but instead is a single standalone villa setup, can have it if the purchase is made early enough—before the final plan is fully executed.</div>
<img alt="Villa visualization" class="wide-image" onclick="openViewer(this.src)" src="/villa-ready-assets/mainVilla.png"/>
<div class="building-card" style="margin-top:18px"><h3>PERMITTED BUILDING STRUCTURE</h3><div class="building-row"><span>Structure</span><strong>Basement + Ground Floor + 1st Floor + Attic</strong></div><div class="building-row"><span>Villa Layout</span><strong>Basement (garage/storage) · Ground floor · 1st floor · Attic</strong></div><div class="building-row"><span>Cost</span><strong>€5,900 / m²</strong></div></div>
<div class="building-grid" style="margin-top:16px"><div class="building-card"><h3>BUILDING 1</h3><div class="building-row"><span>Maximum gross area</span><strong>885 m²</strong></div><div class="building-row"><span>Net sellable area (~75%)</span><strong>664 m²</strong></div><div class="building-row"><span><strong>GROUND FLOOR</strong><br/>100 m² × 2 Units = 200 m²</span><strong>€1,180,000</strong></div><div class="building-row"><span><strong>1ST FLOOR</strong><br/>110 m² × 2 Units = 220 m²</span><strong>€1,298,000</strong></div><div class="building-row"><span><strong>ATTIC</strong><br/>122 m² × 2 Units = 244 m²</span><strong>€1,439,600</strong></div><div class="building-row total"><span>TOTAL BUILDING 1</span><span>€3,917,600</span></div></div><div class="building-card"><h3>BUILDING 2</h3><div class="building-row"><span>Maximum gross area</span><strong>885 m²</strong></div><div class="building-row"><span>Net sellable area (~75%)</span><strong>664 m²</strong></div><div class="building-row"><span><strong>GROUND FLOOR</strong><br/>100 m² × 2 Units = 200 m²</span><strong>€1,180,000</strong></div><div class="building-row"><span><strong>1ST FLOOR</strong><br/>110 m² × 2 Units = 220 m²</span><strong>€1,298,000</strong></div><div class="building-row"><span><strong>ATTIC</strong><br/>122 m² × 2 Units = 244 m²</span><strong>€1,439,600</strong></div><div class="building-row total"><span>TOTAL BUILDING 2</span><span>€3,917,600</span></div></div><div class="building-card"><h3>BUILDING 3</h3><div class="building-row"><span>Maximum gross area</span><strong>710 m²</strong></div><div class="building-row"><span>Net sellable area (~75%)</span><strong>532.5 m²</strong></div><div class="building-row"><span><strong>GROUND FLOOR</strong><br/>85 m² × 2 Units = 170 m²</span><strong>€1,003,000</strong></div><div class="building-row"><span><strong>1ST FLOOR</strong><br/>90 m² × 2 Units = 180 m²</span><strong>€1,062,000</strong></div><div class="building-row"><span><strong>ATTIC</strong><br/>91.25 m² × 2 Units = 182.5 m²</span><strong>€1,076,750</strong></div><div class="building-row total"><span>TOTAL BUILDING 3</span><span>€3,141,750</span></div></div><div class="building-card"><h3>BUILDING 4</h3><div class="building-row"><span>Maximum gross area</span><strong>710 m²</strong></div><div class="building-row"><span>Net sellable area (~75%)</span><strong>532.5 m²</strong></div><div class="building-row"><span><strong>GROUND FLOOR</strong><br/>85 m² × 2 Units = 170 m²</span><strong>€1,003,000</strong></div><div class="building-row"><span><strong>1ST FLOOR</strong><br/>90 m² × 2 Units = 180 m²</span><strong>€1,062,000</strong></div><div class="building-row"><span><strong>ATTIC</strong><br/>91.25 m² × 2 Units = 182.5 m²</span><strong>€1,076,750</strong></div><div class="building-row total"><span>TOTAL BUILDING 4</span><span>€3,141,750</span></div></div></div>
<div class="building-card" style="margin-top:16px"><div class="building-row total"><span>TOTAL — ALL BUILDINGS</span><span>€14,118,700</span></div></div>
</section><section class="card pad" id="tax"><div class="title"><div><h2>PRICING &amp; TAX INFORMATION</h2><p class="sub">Exact net price, VAT and ownership information from the source page.</p></div></div><div class="original-copy"><p>All prices listed for the properties are net prices, excluding applicable taxes.</p><p>As this is a newly developed real estate project sold by a Croatian company (d.o.o.), the transaction is subject to Value Added Tax (VAT) in accordance with Croatian law.</p><div class="highlight-box"><strong>NOTE FOR NON-EU CITIZENS</strong><p>Any non-EU citizens need to register a Croatian local company in order to be owners, and that is the main option for them.</p><p>Our All-Included Villa Management Service—a turnkey solution covering maintenance, rentals, and guest services—can be used, and within that service we can register and manage a local Croatian company for you.</p></div></div><div class="grid2"><div class="mini-card"><b>VAT TREATMENT</b><ul class="tax-list"><li>VAT (25%) is not included in the listed prices</li><li>VAT will be added to the purchase price at the time of sale</li><li>The sale is conducted under the VAT system for new developments</li></ul></div><div class="mini-card"><b>FOR PRIVATE BUYERS</b><ul class="tax-list"><li>Private individuals purchasing the property:</li><li>Pay the full purchase price + 25% VAT</li><li>VAT is a final cost and cannot be reclaimed</li></ul></div><div class="mini-card"><b>FOR COMPANY BUYERS (EU / VAT REGISTERED)</b><ul class="tax-list"><li>Companies registered within the VAT system:</li><li>Pay the purchase price + VAT</li><li>May be eligible to reclaim VAT, subject to their local tax regulations</li><li>This makes the acquisition significantly more efficient for investment purposes.</li></ul></div><div class="mini-card"><b>EXAMPLE</b><ul class="tax-list"><li>For a property priced at 500,000 € (net):</li><li>VAT (25%): 125,000 €</li><li>Total purchase price: 625,000 €</li></ul></div><div class="mini-card"><b>SUMMARY</b><ul class="tax-list"><li>All prices on this website are exclusive of VAT</li><li>VAT (25%) applies to all units</li><li>No additional real estate transfer tax applies to these properties</li><li>VAT may be recoverable for eligible company buyers</li></ul></div></div></section><section class="card pad call-action-card" id="private-call"><div class="title"><div><h2>SCHEDULE A PRIVATE INVESTOR CALL</h2><p class="sub">Connect directly for premium villa opportunities in Croatia.</p></div></div><div class="original-copy"><p>Connect with our CEO directly to explore premium villa opportunities in Croatia.Villa Ready Croatia's CEO personally handles all calls with potential investors.</p><p>Book a personalized Zoom or WhatsApp video call at your convenience.</p></div><a class="btn primary" href="#contact">BOOK A PRIVATE CALL</a></section><section class="card pad" id="core-values"><div class="title"><div><h2>CORE VALUES</h2><p class="sub">The complete project value proposition from the original page.</p></div></div><div class="core-grid"><div class="core-item"><h3>Building in a Rare, Permitted Natural Area</h3><p>A unique opportunity to develop within a protected, low-density natural setting where new construction is highly limited—significantly increasing long-term value and exclusivity.</p></div><div class="core-item"><h3>Privacy &amp; Tranquility, Yet Close to Milna</h3><p>The property offers exceptional peace and seclusion while remaining just minutes from the charming coastal village of Milna, with its restaurants, marina, and daily amenities.</p></div><div class="core-item"><h3>Sea View</h3><p>Just enough elevated positioning ensures attractive sea views—one of the strongest value drivers in coastal real estate.</p></div><div class="core-item"><h3>Swimming Pool &amp; Modern Architecture</h3><p>Ideal for high-end development, combining contemporary design with luxury features that meet international buyer expectations.</p></div><div class="core-item"><h3>Strategic Location Facing Split</h3><p>Positioned directly across from Split—the largest city on the Adriatic coast and a major international gateway—providing excellent connectivity to global markets via ferry, airport, and yacht marina infrastructure.</p></div><div class="core-item"><h3>Safe &amp; Stable Country</h3><p>Located in one of Europe's most politically and socially stable countries, offering a secure environment for both lifestyle and investment.</p></div><div class="core-item"><h3>EU Real Estate Framework</h3><p>Full compliance with European Union property laws ensures transparency, legal security, and ease of ownership for international buyers.</p></div><div class="core-item"><h3>Optional: All-Included Villa Management Service</h3><p>A turnkey solution for owners, covering maintenance, rentals, and guest services—maximizing convenience and investment returns.</p></div></div></section><section class="card pad" id="project-summary"><div class="title"><div><h2>PROJECT SUMMARY</h2><p class="sub">The source page’s concluding project assessment.</p></div></div><div class="exact-highlight">This is a rare opportunity to develop in a naturally preserved yet fully permitted area, combining privacy, sea views, and immediate proximity to the vibrant marina town of Milna. With direct orientation toward Split—Dalmatia's key international hub—the location offers both exclusivity and connectivity. Supported by EU legal security and optional full-service villa management, the project represents a compelling blend of lifestyle excellence and high-value investment potential.</div></section><section class="card pad" id="history"><div class="title"><div><h2>HISTORY LESSON THAT CAN TELL YOU ALL</h2><p class="sub">The complete Diocletian and Milna story from the source page.</p></div></div><div class="original-copy"><p>Diocletian (ruled 284–305 AD) was born in nearby Salona, which today corresponds to the area of Solin, on the edge of Split. He built his famous palace in Split, just across the channel from Milna, Brač. After his abdication, he lived there in retirement—so he was definitely active in this region.</p><p>According to local tradition, when Emperor Diocletian came with the intention to build his palace, imagine that there was nothing around—no city of Split yet. He could choose ANY place in front of Split to anchor his main fleet.</p><div class="history-quote">And he chose Milna, on Brač.</div><p>He anchored his fleet in the sheltered bay of Milna—drawn by its natural protection and strategic beauty along the coast.</p><p>This tells you everything.</p><div class="history-quote">So, at Villa Ready Croatia, we have an inside saying: "If it was good for Emperor Diocletian, then it is surely good for you too."</div><p>Message from Villa Ready Croatia CEO for You</p></div></section><section class="card pad dark-property-card"><h2>INCOME-READY VILLAS WITH NO MANAGEMENT HASSLE</h2><h3>STABLE TOURISM DEMAND WITH RENTAL INCOME FLOW</h3></section><section class="card pad agency-contact" id="contact"><div class="title"><div><h2>CONTACT OUR AGENCY ABOUT THIS PROPERTY</h2><p class="sub">The agency handles your questions, availability request, viewing and purchase communication.</p></div></div><form class="form" id="contactForm" onsubmit="return validateForm(event)"><input name="property_id" type="hidden" value="VRC-MILNA-001"/><input name="affiliate_code" type="hidden" value="AGENCY-DEMO-001"/><div class="grid2"><div class="form-field"><input name="full_name" id="full_name" placeholder="Full name"/><span class="error-msg" id="error_full_name"></span></div><div class="form-field"><input name="email" id="email" placeholder="Email" type="email"/><span class="error-msg" id="error_email"></span></div></div><div class="grid2"><input name="phone" placeholder="Phone"/><select name="interest"><option>Request current availability</option><option>Request all plans and documents</option><option>Schedule a private investor call</option><option>Ask about company ownership and VAT</option></select></div><textarea name="message" placeholder="Your message"></textarea><button class="btn" type="submit">SEND ENQUIRY TO AGENCY</button></form>
<style>.form-field{display:flex;flex-direction:column;gap:4px;}.error-msg{color:#ef4444;font-size:12px;font-weight:600;display:none;}.error-msg.show{display:block;}.form-field input.error{border-color:#ef4444;}</style></section></div>
<footer class="site-footer">
<div class="footer-inner">
<div class="footer-grid">
<div class="footer-col"><h4>Your Real Estate Agency</h4><p>This property is marketed and sold through our agency. Contact our team for viewings, documentation and purchase assistance.</p></div>
<div class="footer-col"><h4>Property</h4><p><a href="02_AGENCY_FULL_VILLA_PROPERTY_PAGE.html">Milna sea-view development</a><br/><a href="#">Available units</a><br/><a href="#">Buyer service</a></p></div>
<div class="footer-col"><h4>Contact</h4><p>agency@example.com<br/>+385 00 000 0000<br/>Split, Croatia</p></div>
</div>
<div class="footer-bottom"><span>© 2026 Your Real Estate Agency. All rights reserved.</span><div class="footer-links"><a href="#">Privacy</a><a href="#">Terms</a></div></div>
</div>
</footer>
<div class="modal-viewer" id="imageViewer"><span class="modal-close" onclick="closeViewer()">×</span><span class="modal-nav modal-prev" onclick="prevImage()">‹</span><img alt="" id="viewerImage"/><span class="modal-nav modal-next" onclick="nextImage()">›</span></div>
<style>.modal-nav{position:fixed;top:50%;transform:translateY(-50%);font-size:60px;color:#fff;cursor:pointer;padding:20px;z-index:10001;user-select:none;}.modal-nav:hover{color:#ccc;}.modal-prev{left:20px;}.modal-next{right:20px;}</style>
<div class="modal-viewer" id="videoViewer"><span class="modal-close" onclick="closeVideoViewer()">×</span><video id="droneVideo" controls autoplay loop style="max-width:95vw;max-height:90vh;border-radius:12px;"><source src="{{ asset('villa-ready-assets/MilnaDroneAerial.mp4') }}" type="video/mp4"></video></div>
<script>
function setAffiliateCookie() {
  const agencyCode = 'AGENCY-DEMO-001';
  const expires = new Date(Date.now() + 180*24*60*60*1000).toUTCString();
  document.cookie = 'vrc_affiliate=' + encodeURIComponent(agencyCode) + '; expires=' + expires + '; path=/; SameSite=Lax';
  localStorage.setItem('vrc_last_referral', JSON.stringify({
    agency_code: agencyCode,
    property_id: 'VRC-MILNA-001',
    status: 'VIEWED',
    viewed_at: new Date().toISOString(),
    expires_at: new Date(Date.now() + 180*24*60*60*1000).toISOString()
  }));
}
var galleryImages = [];
var currentImageIndex = 0;
document.addEventListener('DOMContentLoaded', function() {
  galleryImages = Array.from(document.querySelectorAll('.original-gallery img, .original-hero-media img')).map(img => img.src);
});
function openViewer(src) {
  currentImageIndex = galleryImages.indexOf(src);
  if (currentImageIndex === -1) { galleryImages.push(src); currentImageIndex = galleryImages.length - 1; }
  document.getElementById('viewerImage').src = src;
  document.getElementById('imageViewer').classList.add('open');
}
function closeViewer() { document.getElementById('imageViewer').classList.remove('open'); }
function prevImage() { currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length; document.getElementById('viewerImage').src = galleryImages[currentImageIndex]; }
function nextImage() { currentImageIndex = (currentImageIndex + 1) % galleryImages.length; document.getElementById('viewerImage').src = galleryImages[currentImageIndex]; }
function openVideoViewer() { document.getElementById('videoViewer').classList.add('open'); document.getElementById('droneVideo').play(); }
function closeVideoViewer() { document.getElementById('videoViewer').classList.remove('open'); document.getElementById('droneVideo').pause(); }
document.addEventListener('keydown',e=>{
  if(e.key==='Escape'){closeViewer();closeVideoViewer();}
  if(e.key==='ArrowLeft'){prevImage();}
  if(e.key==='ArrowRight'){nextImage();}
});
setAffiliateCookie();
function validateForm(e) {
  e.preventDefault();
  var valid = true;
  var name = document.getElementById('full_name');
  var email = document.getElementById('email');
  var errorName = document.getElementById('error_full_name');
  var errorEmail = document.getElementById('error_email');
  errorName.classList.remove('show'); errorEmail.classList.remove('show');
  name.classList.remove('error'); email.classList.remove('error');
  if (!name.value.trim()) { errorName.textContent = 'Full name is required'; errorName.classList.add('show'); name.classList.add('error'); valid = false; }
  if (!email.value.trim()) { errorEmail.textContent = 'Email is required'; errorEmail.classList.add('show'); email.classList.add('error'); valid = false; }
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) { errorEmail.textContent = 'Please enter a valid email'; errorEmail.classList.add('show'); email.classList.add('error'); valid = false; }
  if (valid) { alert('Demo enquiry saved. Connect this form to the agency CRM or Laravel controller.'); }
  return false;
}
document.getElementById('full_name').addEventListener('input', function() { this.classList.remove('error'); document.getElementById('error_full_name').classList.remove('show'); });
document.getElementById('email').addEventListener('input', function() { this.classList.remove('error'); document.getElementById('error_email').classList.remove('show'); });
</script>
</body>
</html>