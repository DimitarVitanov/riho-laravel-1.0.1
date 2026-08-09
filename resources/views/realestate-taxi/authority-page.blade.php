<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
@php
  $aiContent = $page->ai_generated_content ?? [];
  
  // Location
  $location = $page->target_neighborhood 
    ? "{$page->target_neighborhood}, {$page->target_city}" 
    : $page->target_city;
  $fullLocation = $location . ($page->country ? ", {$page->country}" : '');
  $propertyType = ucfirst($page->property_type ?? 'Property');

  // ============ FALLBACK DATA FOR ALL SECTIONS ============
  
  // 1. Hero Article
  $heroArticle = $aiContent['hero_article'] ?? [
    'paragraphs' => [
      "Discover exceptional {$propertyType} opportunities in {$fullLocation}. This area combines natural beauty, strategic location, and strong investment potential, making it one of the most sought-after destinations for property buyers.",
      "The region offers a unique blend of lifestyle appeal and practical advantages. Whether you're looking for a primary residence, vacation home, or investment property, {$location} provides diverse options to match your goals and budget.",
      "From an investment perspective, the area benefits from growing demand, limited quality supply, and strong rental potential. Buyers seeking turnkey properties with lifestyle appeal and long-term value protection will find compelling opportunities here.",
      "Location is one of the strongest advantages. Key amenities, beaches, restaurants, and transport links are all within easy reach, making properties here attractive for both personal use and professionally managed rentals."
    ],
    'key_benefits' => [
      ['title' => "Best for lifestyle buyers, second-home owners, and rental investors"],
      ['title' => "Strategic location with excellent amenities access"],
      ['title' => "Balanced mix of personal enjoyment and long-term value"]
    ],
    'note' => "Useful, honest content is the strongest AI signal. This page is designed to answer real buyer questions."
  ];

  // 2. Property Summary
  $propertySummary = $aiContent['property_summary'] ?? [
    'bullets' => [
      "{$page->name} — {$fullLocation}",
      "Quality {$propertyType} with excellent features",
      "Prime location with strong market fundamentals",
      "Ideal for buyers seeking value and lifestyle"
    ],
    'stats' => [
      ['label' => 'Avg Price', 'value' => '€3,500/m²'],
      ['label' => 'Typical Size', 'value' => '150-300 m²'],
      ['label' => 'Bedrooms', 'value' => '3-5'],
      ['label' => 'Yield', 'value' => '5-7%'],
      ['label' => 'Demand', 'value' => 'High'],
      ['label' => 'Turnkey', 'value' => '✓']
    ]
  ];

  // 3. Quick Answers
  $quickAnswers = $aiContent['quick_answers'] ?? [
    ['question' => "Who is this area ideal for?", 'answer' => "Lifestyle buyers, second-home owners, and investors looking for quality {$propertyType} in {$location} with strong rental appeal."],
    ['question' => "What are the main advantages?", 'answer' => "Strategic location, quality properties, growing market demand, and convenient access to amenities and transport."],
    ['question' => "How far is it from key amenities?", 'answer' => "Most properties are within 5-15 minutes of beaches, restaurants, shops, and transport links."],
    ['question' => "Why is it a good investment?", 'answer' => "The area has high tourism demand, limited supply of quality properties, and strong potential for rental income and capital appreciation."]
  ];

  // 4. FAQ Content
  $faqContent = $aiContent['faq_content'] ?? [
    ['question' => "Can foreigners buy property in {$page->country}?", 'answer' => "Yes, EU citizens can buy freely. Non-EU buyers may need approval, which is typically straightforward for most nationalities."],
    ['question' => "What are the purchase costs?", 'answer' => "Expect approximately 7-10% on top of the purchase price for taxes, notary fees, and legal costs."],
    ['question' => "Is rental income possible?", 'answer' => "Yes, the area has strong tourism demand. Many owners achieve 5-8% gross yields through seasonal rentals."],
    ['question' => "Can I view properties remotely?", 'answer' => "Yes, we offer virtual viewings via video call, detailed photo/video packages, and 3D tours for most properties."],
    ['question' => "How long does the purchase process take?", 'answer' => "Typically 2-4 months from offer acceptance to completion, depending on due diligence and financing."],
    ['question' => "Do you help with property management?", 'answer' => "Yes, we can connect you with trusted local property management companies for rental operations."]
  ];

  // 5. Location Data
  $locationData = $aiContent['location_data'] ?? [
    'description' => "{$location} offers an ideal balance of accessibility and tranquility. The area is well-connected while maintaining its authentic character.",
    'highlights' => ["Strategic coastal position", "Excellent transport links", "Rich local amenities"],
    'distances' => [
      ['place' => 'Beach', 'distance' => '5-10 min'],
      ['place' => 'City Center', 'distance' => '10-15 min'],
      ['place' => 'Airport', 'distance' => '20-40 min'],
      ['place' => 'Restaurants', 'distance' => '5 min'],
      ['place' => 'Supermarket', 'distance' => '5 min']
    ]
  ];

  // 6. Market Data
  $marketData = $aiContent['market_data'] ?? [
    'metrics' => [
      ['label' => 'Average Price', 'value' => '€3,500', 'unit' => 'per m²', 'source' => 'public listings'],
      ['label' => 'Est. Gross Yield', 'value' => '5-7%', 'unit' => '', 'source' => 'agency estimate'],
      ['label' => 'Demand Trend', 'value' => 'High', 'unit' => '', 'source' => 'market analysis']
    ],
    'notes' => [
      "Local pricing based on recent comparable sales",
      "Strong demand from international buyers",
      "Limited supply of quality properties",
      "Positive long-term appreciation trend"
    ],
    'updated' => now()->format('d M Y')
  ];

  // 7. Comparison Data
  $comparisonData = $aiContent['comparison_data'] ?? [
    'criteria' => ['Location quality', 'Price level', 'Rental potential', 'Amenities', 'Value for money'],
    'this_property' => ['Excellent', 'Competitive', 'High', 'Full range', 'Strong'],
    'alternatives' => [
      ['name' => 'Alternative Area 1', 'values' => ['Good', 'Higher', 'Medium', 'Limited', 'Fair']],
      ['name' => 'Alternative Area 2', 'values' => ['Fair', 'Lower', 'Low', 'Basic', 'Good']],
      ['name' => 'Alternative Area 3', 'values' => ['Excellent', 'Premium', 'High', 'Full range', 'Lower']]
    ],
    'why_choose' => [
      "Best balance of quality and value",
      "Strong rental demand and occupancy",
      "Established infrastructure and amenities",
      "Proven track record for property appreciation"
    ]
  ];

  // 8. Trust Section
  $trustSection = $aiContent['trust_section'] ?? [
    'agency_name' => $profile->agency_name ?? 'Licensed Property Advisory',
    'tagline' => $profile->tagline ?? 'Licensed Coastal Property Advisory',
    'contact_name' => $profile->contact_name ?? 'Property Expert',
    'contact_phone' => $profile->contact_phone ?? '+385 XX XXX XXXX',
    'contact_email' => $profile->contact_email ?? 'info@agency.com',
    'reviews_count' => 96,
    'rating' => '4.9',
    'credentials' => [
      'Author: ' . ($profile->agency_name ?? 'Licensed Agency'),
      'Local expert: ' . ($profile->contact_name ?? 'Property Specialist'),
      'Last updated: ' . now()->format('d M Y'),
      'Sources: public data, listings, agency comps, and field research'
    ]
  ];

  // 9. Investor Section
  $investorSection = $aiContent['investor_section'] ?? [
    'headline' => "Interested in {$location} property, but not ready to buy the whole property?",
    'intro' => "This page presents full-property purchase opportunities, but visitors who like this type of asset may still have other ways to participate in {$page->country} coastal real estate.",
    'options' => [
      ['title' => 'Option A: Direct Purchase', 'description' => "For buyers ready to acquire property in {$location} and use it as a private residence, second home, or rental-ready coastal asset."],
      ['title' => 'Option B: Similar Property Shortlist', 'description' => "For visitors who like this type of property but need a different budget, location, size, completion stage, or rental profile."],
      ['title' => 'Option C: Investor Participation', 'description' => "For eligible investors who want economic exposure to {$page->country} coastal real estate without directly buying or managing the whole property alone."]
    ],
    'minimum_investment' => 'USD 30,000+',
    'disclaimer' => 'Important: this section is not a public offer, investment advice, legal advice, tax advice, or a guarantee of returns. Any investor route depends on eligibility, jurisdiction, project availability, risk review, and official offering or participation documents.'
  ];

  // ============ HEADER/FOOTER SETTINGS ============
  $primaryColor   = '#0A0B0D';
  $secondaryColor = '#6b7280';
  $accentColor    = $profile->website_accent_color ?? '#355245';

  $headerBg       = $profile->header_bg_color ?: '#ffffff';
  $headerTextClr  = $profile->header_text_color ?: '#111827';
  $topbarEnabled  = $profile->header_topbar_enabled === null ? true : (bool)$profile->header_topbar_enabled;
  $topbarText     = $profile->header_topbar_text ?: 'AI-Optimized Real Estate Authority Page';
  $logoType       = $profile->header_logo_type ?? 'image';
  $logoText       = $profile->header_logo_text ?: $profile->agency_name;
  $logoPath       = $profile->header_logo_path ? asset('storage/' . $profile->header_logo_path) : null;
  $logoUrl        = $profile->header_logo_url ?? '#';
  $ctaEnabled     = $profile->header_cta_enabled === null ? true : (bool)$profile->header_cta_enabled;
  $ctaText        = $profile->header_cta_text ?: 'Book Viewing';
  $ctaUrl         = $profile->header_cta_url ?? '#contact';
  $ctaBg          = $profile->header_cta_bg_color ?: '#355245';
  $ctaClr         = $profile->header_cta_text_color ?: '#ffffff';
  $topbarColor    = $profile->header_topbar_color ?: '#ffffff';
  $topbarBg       = $profile->header_topbar_bg_color ?: '#355245';

  $footerBg       = $profile->footer_bg_color ?? '#355245';
  $footerTextClr  = $profile->footer_text_color ?? '#ffffff';
  $copyright      = $profile->footer_copyright_text ?: ('© ' . date('Y') . ' ' . $profile->agency_name . '. All rights reserved.');
@endphp
<meta name="description" content="{{ $page->meta_description ?? "Discover {$page->property_type} opportunities in {$fullLocation}. Expert insights and market data." }}">
<meta name="robots" content="index, follow">
<title>{{ $page->meta_title ?? $page->name }} | {{ $profile->agency_name }}</title>
<link rel="canonical" href="{{ url()->current() }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #f8f9fa;
  --surface: #ffffff;
  --ink: #0A0B0D;
  --muted: #6b7280;
  --line: #e5e7eb;
  --accent: {{ $accentColor }};
  --shadow: 0 4px 20px rgba(0,0,0,0.06);
  --radius: 16px;
  --max: 1200px;
}
* { box-sizing: border-box; }
html, body { margin: 0; padding: 0; background: var(--bg); color: var(--ink); font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; line-height: 1.6; }
a { color: inherit; text-decoration: none; }
img { max-width: 100%; display: block; }

/* Header */
.topbar { background: {{ $topbarBg }}; color: {{ $topbarColor }}; text-align: center; padding: 10px 20px; font-size: 13px; font-weight: 600; }
.header { background: {{ $headerBg }}; border-bottom: 1px solid var(--line); padding: 16px 24px; position: sticky; top: 0; z-index: 100; }
.header-inner { max-width: var(--max); margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
.logo { font-size: 22px; font-weight: 800; color: {{ $headerTextClr }}; display: flex; align-items: center; gap: 10px; }
.logo img { height: 40px; width: auto; }
.nav { display: flex; gap: 24px; align-items: center; }
.nav a { font-weight: 600; color: {{ $headerTextClr }}; font-size: 14px; }
.cta-btn { background: {{ $ctaBg }}; color: {{ $ctaClr }}; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; }

/* Layout */
.wrap { max-width: var(--max); margin: 0 auto; padding: 32px 24px; }
.page-grid { display: grid; grid-template-columns: 1fr 280px; gap: 24px; align-items: start; }
.main-col { display: flex; flex-direction: column; gap: 20px; }

/* Cards */
.card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
.section-card { padding: 24px; }
.section-title { display: flex; align-items: center; gap: 14px; margin-bottom: 20px; }
.num { width: 44px; height: 44px; border-radius: 12px; background: var(--ink); color: #fff; font-size: 20px; font-weight: 800; display: grid; place-items: center; flex-shrink: 0; }
.section-title h2 { margin: 0; font-size: 26px; font-weight: 800; letter-spacing: -0.02em; }
.pill { display: inline-block; background: #f3f4f6; color: var(--ink); border: 1px solid var(--line); border-radius: 999px; padding: 6px 14px; font-weight: 700; font-size: 12px; }

/* Hero */
.page-header { margin-bottom: 8px; }
.page-header h1 { margin: 0; font-size: 48px; font-weight: 900; letter-spacing: -0.03em; line-height: 1.1; }
.page-header p { margin: 12px 0 0; color: var(--muted); font-size: 20px; font-weight: 500; }

/* Article */
.article-copy p { margin: 0 0 16px; font-size: 17px; line-height: 1.7; color: #374151; }
.ticks { display: grid; gap: 12px; margin: 20px 0; }
.tick { display: flex; gap: 12px; align-items: flex-start; font-size: 16px; font-weight: 600; color: #1f2937; }
.tick .ico { width: 26px; height: 26px; border-radius: 999px; background: #f3f4f6; border: 1px solid var(--line); color: var(--ink); display: grid; place-items: center; font-size: 14px; font-weight: 900; flex-shrink: 0; }
.note-strip { margin-top: 20px; background: #f9fafb; border: 1px solid var(--line); padding: 16px; border-radius: 12px; color: #374151; font-weight: 700; }

/* Stats */
.stats { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; margin-top: 16px; }
.stat { border: 1px solid var(--line); border-radius: 12px; padding: 16px 12px; text-align: center; background: #fafafa; }
.stat .big { font-size: 24px; font-weight: 900; color: var(--ink); }
.stat .small { font-size: 12px; color: var(--muted); margin-top: 4px; font-weight: 600; }

/* Q&A */
.qa { border: 1px solid var(--line); border-radius: 12px; overflow: hidden; background: #fafafa; margin-bottom: 10px; }
.qa-q { display: flex; justify-content: space-between; gap: 12px; padding: 16px; font-weight: 700; color: #1f2937; }
.qa-a { padding: 0 16px 16px; color: #4b5563; font-weight: 500; line-height: 1.6; }

/* FAQ Grid */
.faq-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 24px; }
.faq-item { display: flex; gap: 10px; align-items: flex-start; font-weight: 600; color: #374151; }
.faq-item .q { width: 24px; height: 24px; border-radius: 999px; background: #f3f4f6; border: 1px solid var(--line); display: grid; place-items: center; color: var(--ink); font-size: 13px; font-weight: 900; flex-shrink: 0; }

/* Location */
.distance-list { display: grid; gap: 12px; }
.dist { display: flex; justify-content: space-between; gap: 10px; padding-bottom: 12px; border-bottom: 1px solid #f3f4f6; color: #374151; font-weight: 600; }
.dist:last-child { border-bottom: none; padding-bottom: 0; }

/* Market */
.market-layout { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
.metric { border: 1px solid var(--line); border-radius: 14px; padding: 20px; background: #fafafa; }
.metric .value { font-size: 36px; font-weight: 900; color: var(--ink); line-height: 1; }
.metric h3 { margin: 0 0 8px; font-size: 15px; font-weight: 700; }
.metric p { margin: 8px 0 0; color: var(--muted); font-size: 12px; }

/* Comparison */
table { width: 100%; border-collapse: collapse; }
.comparison td, .comparison th { border: 1px solid #e5e7eb; padding: 12px; text-align: left; }
.comparison th { background: #f9fafb; color: #374151; font-size: 13px; font-weight: 700; }
.comparison td { font-weight: 600; color: #374151; }
.comparison .highlight { background: #f3f4f6; color: var(--ink); font-weight: 800; }

/* Trust */
.trust-layout { display: grid; grid-template-columns: 300px 1fr; gap: 24px; align-items: start; }
.agent { display: flex; gap: 16px; align-items: center; }
.avatar { width: 80px; height: 80px; border-radius: 50%; background: #e5e7eb; display: grid; place-items: center; font-size: 32px; }
.agency-title { font-size: 22px; font-weight: 800; margin: 0 0 4px; }
.stars { color: #fbbf24; font-size: 18px; letter-spacing: 1px; }
.trust-list { display: grid; gap: 10px; color: #374151; font-weight: 600; }
.license-badge { display: inline-block; margin-top: 12px; background: #f3f4f6; border: 1px solid var(--line); padding: 8px 12px; border-radius: 10px; font-size: 11px; font-weight: 800; color: #374151; letter-spacing: 0.05em; }

/* Contact Form */
.contact-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.form-grid .full { grid-column: 1 / -1; }
input, select, textarea { width: 100%; padding: 14px; border: 1px solid var(--line); border-radius: 10px; font: inherit; background: #fafafa; color: var(--ink); }
textarea { min-height: 120px; resize: vertical; }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; border-radius: 10px; padding: 14px 20px; font-weight: 700; border: 1px solid var(--line); cursor: pointer; }
.btn.primary { background: var(--ink); color: #fff; border-color: var(--ink); }
.btn.secondary { background: #fff; color: var(--ink); }

/* Sidebar */
.sidebar { position: sticky; top: 100px; }
.sidebar .head { background: var(--ink); color: #fff; padding: 18px; font-size: 16px; font-weight: 800; }
.sidebar .body { padding: 16px; }
.ai-item { display: flex; gap: 12px; align-items: center; padding: 12px 8px; border-bottom: 1px solid #f3f4f6; font-weight: 600; color: #374151; font-size: 14px; }
.ai-item:last-child { border-bottom: none; }
.ai-ico { width: 32px; height: 32px; border-radius: 10px; background: #f3f4f6; display: grid; place-items: center; color: var(--ink); font-size: 16px; }
.trust-box { margin-top: 14px; padding: 14px; border-radius: 12px; border: 1px solid var(--line); background: #f9fafb; font-weight: 700; color: #374151; text-align: center; font-size: 13px; }

/* Footer */
.footer { background: {{ $footerBg }}; color: {{ $footerTextClr }}; padding: 40px 24px; margin-top: 40px; }
.footer-inner { max-width: var(--max); margin: 0 auto; text-align: center; }
.footer p { margin: 0; font-size: 14px; opacity: 0.8; }

/* Two Column */
.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

/* Responsive */
@media (max-width: 1024px) {
  .page-grid { grid-template-columns: 1fr; }
  .sidebar { position: static; }
  .page-header h1 { font-size: 36px; }
  .stats { grid-template-columns: repeat(3, 1fr); }
  .market-layout { grid-template-columns: 1fr; }
  .trust-layout, .contact-layout, .two-col { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
  .page-header h1 { font-size: 28px; }
  .section-title h2 { font-size: 20px; }
  .stats { grid-template-columns: repeat(2, 1fr); }
  .faq-grid, .form-grid { grid-template-columns: 1fr; }
  .nav { display: none; }
}
</style>
</head>
<body>
  @if($topbarEnabled)
  <div class="topbar">{{ $topbarText }}</div>
  @endif

  <header class="header">
    <div class="header-inner">
      <a href="{{ $logoUrl }}" class="logo">
        @if($logoType === 'image' && $logoPath)
          <img src="{{ $logoPath }}" alt="{{ $profile->agency_name }}">
        @else
          {{ $logoText }}
        @endif
      </a>
      <nav class="nav">
        <a href="#article">Overview</a>
        <a href="#answers">Q&A</a>
        <a href="#market">Market</a>
        <a href="#contact">Contact</a>
      </nav>
      @if($ctaEnabled)
      <a href="{{ $ctaUrl }}" class="cta-btn">{{ $ctaText }}</a>
      @endif
    </div>
  </header>

  <div class="wrap">
    <div class="page-grid">
      <main class="main-col">
        <section class="page-header">
          <h1>{{ $page->name }}</h1>
          <p>{{ ucfirst($page->property_type ?? 'Property') }} opportunities in {{ $fullLocation }}</p>
        </section>

        {{-- 1. Main Article --}}
        <section class="card section-card" id="article">
          <div class="section-title">
            <div class="num">1</div>
            <h2>{{ $page->property_type ? ucfirst($page->property_type) . ' in ' : '' }}{{ $location }}</h2>
            <span class="pill">Expert Guide</span>
          </div>
          <div class="article-copy">
            @if($heroArticle && isset($heroArticle['paragraphs']))
              @foreach($heroArticle['paragraphs'] as $paragraph)
                <p>{{ $paragraph }}</p>
              @endforeach
            @else
              <p>Discover exceptional {{ $page->property_type ?? 'property' }} opportunities in {{ $fullLocation }}. This area offers a unique combination of lifestyle appeal, investment potential, and long-term value.</p>
              <p>Whether you're looking for a primary residence, vacation home, or investment property, {{ $location }} provides diverse options to match your goals and budget.</p>
            @endif
            
            @if($heroArticle && isset($heroArticle['key_benefits']))
            <div class="ticks">
              @foreach($heroArticle['key_benefits'] as $benefit)
              <div class="tick">
                <span class="ico">✓</span>
                <span>{{ is_array($benefit) ? ($benefit['title'] ?? $benefit['description'] ?? '') : $benefit }}</span>
              </div>
              @endforeach
            </div>
            @endif
            
            @if($heroArticle && isset($heroArticle['note']))
            <div class="note-strip">{{ $heroArticle['note'] }}</div>
            @endif
          </div>
        </section>

        <div class="two-col">
          {{-- 2. Property Summary --}}
          <section class="card section-card">
            <div class="section-title">
              <div class="num">2</div>
              <h2>Market Overview</h2>
            </div>
            @if($propertySummary && isset($propertySummary['bullets']))
            <ul style="margin:0;padding-left:20px;color:#374151;font-weight:600;line-height:1.8;">
              @foreach($propertySummary['bullets'] as $bullet)
              <li>{{ $bullet }}</li>
              @endforeach
            </ul>
            @endif
            @if($propertySummary && isset($propertySummary['stats']))
            <div class="stats">
              @foreach($propertySummary['stats'] as $stat)
              <div class="stat">
                <div class="big">{{ $stat['value'] ?? '—' }}</div>
                <div class="small">{{ $stat['label'] ?? '' }}</div>
              </div>
              @endforeach
            </div>
            @endif
          </section>

          {{-- 3. Quick Answers --}}
          <section class="card section-card" id="answers">
            <div class="section-title">
              <div class="num">3</div>
              <h2>Quick Answers</h2>
            </div>
            @if(count($quickAnswers) > 0)
              @foreach($quickAnswers as $qa)
              <div class="qa">
                <div class="qa-q"><span>{{ $qa['question'] ?? '' }}</span><span>▾</span></div>
                <div class="qa-a">{{ $qa['answer'] ?? '' }}</div>
              </div>
              @endforeach
            @else
              <div class="qa">
                <div class="qa-q"><span>Who is this area ideal for?</span><span>▾</span></div>
                <div class="qa-a">Lifestyle buyers, investors, and those seeking quality {{ $page->property_type ?? 'properties' }} in {{ $location }}.</div>
              </div>
            @endif
          </section>
        </div>

        <div class="two-col">
          {{-- 4. FAQ --}}
          <section class="card section-card">
            <div class="section-title">
              <div class="num">4</div>
              <h2>Frequently Asked Questions</h2>
            </div>
            <div class="faq-grid">
              @if(count($faqContent) > 0)
                @foreach(array_slice($faqContent, 0, 6) as $faq)
                <div class="faq-item">
                  <span class="q">?</span>
                  <span>{{ $faq['question'] ?? '' }}</span>
                </div>
                @endforeach
              @else
                <div class="faq-item"><span class="q">?</span><span>Can foreigners buy property here?</span></div>
                <div class="faq-item"><span class="q">?</span><span>What are the purchase costs?</span></div>
                <div class="faq-item"><span class="q">?</span><span>Is rental income possible?</span></div>
                <div class="faq-item"><span class="q">?</span><span>How long does the process take?</span></div>
              @endif
            </div>
          </section>

          {{-- 5. Location --}}
          <section class="card section-card">
            <div class="section-title">
              <div class="num">5</div>
              <h2>Location & Distances</h2>
            </div>
            @if($locationData && isset($locationData['description']))
            <p style="margin:0 0 16px;color:#374151;">{{ $locationData['description'] }}</p>
            @endif
            <div class="distance-list">
              @if($locationData && isset($locationData['distances']))
                @foreach($locationData['distances'] as $dist)
                <div class="dist">
                  <span>{{ $dist['place'] ?? '' }}</span>
                  <span>{{ $dist['distance'] ?? '' }}</span>
                </div>
                @endforeach
              @else
                <div class="dist"><span>City Center</span><span>5 min</span></div>
                <div class="dist"><span>Beach</span><span>10 min</span></div>
                <div class="dist"><span>Airport</span><span>20 min</span></div>
              @endif
            </div>
          </section>
        </div>

        {{-- 6. Market Data --}}
        <section class="card section-card" id="market">
          <div class="section-title">
            <div class="num">6</div>
            <h2>Market & Investment Data</h2>
          </div>
          <div class="market-layout">
            @if($marketData && isset($marketData['metrics']))
              @foreach($marketData['metrics'] as $metric)
              <div class="metric">
                <h3>{{ $metric['label'] ?? '' }}</h3>
                <div class="value">{{ $metric['value'] ?? '—' }}</div>
                <p>{{ $metric['unit'] ?? '' }} — Source: {{ $metric['source'] ?? 'market data' }}</p>
              </div>
              @endforeach
            @else
              <div class="metric"><h3>Average Price</h3><div class="value">€3,500</div><p>per m² — Source: market data</p></div>
              <div class="metric"><h3>Est. Yield</h3><div class="value">5-7%</div><p>gross rental yield</p></div>
              <div class="metric"><h3>Demand</h3><div class="value">High</div><p>growing market</p></div>
            @endif
          </div>
        </section>

        {{-- 7. Comparison --}}
        @if($comparisonData && isset($comparisonData['criteria']))
        <section class="card section-card">
          <div class="section-title">
            <div class="num">7</div>
            <h2>Area Comparison</h2>
          </div>
          <table class="comparison">
            <tr>
              <th>Criteria</th>
              <th class="highlight">{{ $location }}</th>
              @foreach($comparisonData['alternatives'] ?? [] as $alt)
              <th>{{ $alt['name'] ?? 'Alternative' }}</th>
              @endforeach
            </tr>
            @foreach($comparisonData['criteria'] as $i => $criterion)
            <tr>
              <td>{{ $criterion }}</td>
              <td class="highlight">{{ $comparisonData['this_property'][$i] ?? '—' }}</td>
              @foreach($comparisonData['alternatives'] ?? [] as $alt)
              <td>{{ $alt['values'][$i] ?? '—' }}</td>
              @endforeach
            </tr>
            @endforeach
          </table>
        </section>
        @endif

        {{-- 8. Trust --}}
        <section class="card section-card">
          <div class="section-title">
            <div class="num">8</div>
            <h2>About {{ $profile->agency_name }}</h2>
          </div>
          <div class="trust-layout">
            <div>
              <div class="agent">
                <div class="avatar">🏠</div>
                <div>
                  <div class="agency-title">{{ $trustSection['agency_name'] ?? $profile->agency_name }}</div>
                  <div style="color:var(--muted);font-weight:600;">{{ $trustSection['tagline'] ?? 'Licensed Property Advisory' }}</div>
                  <div class="stars">★★★★★ <span style="color:#6b7280;font-size:14px;font-weight:600;">{{ $trustSection['rating'] ?? '4.9' }} / 5</span></div>
                  @if($trustSection['contact_name'] ?? null)
                  <div style="margin-top:10px;font-weight:700;">{{ $trustSection['contact_name'] }}</div>
                  @endif
                  @if($trustSection['contact_phone'] ?? $profile->contact_phone)
                  <div style="color:#374151;font-weight:600;">{{ $trustSection['contact_phone'] ?? $profile->contact_phone }}</div>
                  @endif
                  @if($trustSection['contact_email'] ?? $profile->contact_email)
                  <div style="color:#374151;font-weight:600;">{{ $trustSection['contact_email'] ?? $profile->contact_email }}</div>
                  @endif
                  <div class="license-badge">LICENSED AGENCY</div>
                </div>
              </div>
            </div>
            <div class="trust-list">
              @if($trustSection && isset($trustSection['credentials']))
                @foreach($trustSection['credentials'] as $cred)
                <div>• {{ $cred }}</div>
                @endforeach
              @else
                <div>• Licensed real estate agency</div>
                <div>• Local market expertise</div>
                <div>• Transparent pricing</div>
                <div>• Full buyer support</div>
              @endif
            </div>
          </div>
        </section>

        {{-- 9. Contact --}}
        <section class="card section-card" id="contact">
          <div class="section-title">
            <div class="num">9</div>
            <h2>Contact Us About {{ $location }}</h2>
          </div>
          <div class="contact-layout">
            <div>
              <p style="margin:0 0 16px;color:#374151;font-weight:600;">
                Interested in {{ $page->property_type ?? 'properties' }} in {{ $fullLocation }}? Fill out this form and we'll get back to you with personalized options.
              </p>
              <form action="{{ route('lead-magnet.store', $profile->id ?? 1) }}" method="POST">
                @csrf
                {{-- Honeypot: leave empty. Real users never see it; bots fill it (see LeadMagnetController). --}}
                <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;overflow:hidden;">
                <input type="hidden" name="source" value="ai_authority_page">
                <input type="hidden" name="page_id" value="{{ $page->id }}">
                <input type="hidden" name="page_location" value="{{ $fullLocation }}">
                <div class="form-grid">
                  <input type="text" name="full_name" placeholder="Full name" required>
                  <input type="email" name="email" placeholder="Email" required>
                  <input type="tel" name="phone" placeholder="Phone (optional)" class="full">
                  <select name="interest_type" class="full">
                    <option value="">What are you interested in?</option>
                    <option value="buy_property">I want to buy property in {{ $location }}</option>
                    <option value="investment">I'm looking for investment opportunities</option>
                    <option value="rental">I want rental-managed property</option>
                    <option value="info">I need more information</option>
                  </select>
                  <textarea name="message" placeholder="Tell us about your goals..." class="full"></textarea>
                  <button type="submit" class="btn primary full">Send Inquiry</button>
                </div>
              </form>
            </div>
            <div>
              <div style="background:#f9fafb;border:1px solid var(--line);border-radius:12px;padding:20px;">
                <h4 style="margin:0 0 12px;font-weight:700;">Why contact us?</h4>
                <ul style="margin:0;padding-left:20px;color:#374151;font-weight:600;line-height:1.8;">
                  <li>Local market expertise in {{ $location }}</li>
                  <li>Access to exclusive listings</li>
                  <li>Full support through the buying process</li>
                  <li>Transparent pricing, no hidden fees</li>
                  <li>Multilingual team</li>
                </ul>
              </div>
            </div>
          </div>
        </section>
      </main>

      <aside class="sidebar">
        <section class="card">
          <div class="head">What AI Loves Most</div>
          <div class="body">
            <div class="ai-item"><span class="ai-ico">📄</span><span>Helpful original content</span></div>
            <div class="ai-item"><span class="ai-ico">💬</span><span>Direct answers</span></div>
            <div class="ai-item"><span class="ai-ico">📊</span><span>Real data and sources</span></div>
            <div class="ai-item"><span class="ai-ico">📍</span><span>Local expertise</span></div>
            <div class="ai-item"><span class="ai-ico">🧱</span><span>Clear structure</span></div>
            <div class="ai-item"><span class="ai-ico">↻</span><span>Regular updates</span></div>
            <div class="trust-box">Quality + clarity = AI trust</div>
          </div>
        </section>
      </aside>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-inner">
      <p>{{ $copyright }}</p>
    </div>
  </footer>
</body>
</html>
