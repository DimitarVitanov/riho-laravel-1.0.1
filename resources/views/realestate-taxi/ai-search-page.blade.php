<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $aiContent = $page->ai_generated_content ?? [];
        $location = $page->target_neighborhood
            ? "{$page->target_neighborhood}, {$page->target_city}"
            : $page->target_city ?? 'Location';
        $fullLocation = $location . ($page->country ? ", {$page->country}" : '');
        $propertyType = ucfirst($page->property_type ?? 'Property');

        $heroArticle = $aiContent['hero_article'] ?? [];
        $propertySummary = $aiContent['property_summary'] ?? [];
        $quickAnswers = $aiContent['quick_answers'] ?? [];
        $faqContent = $aiContent['faq_content'] ?? [];
        $locationData = $aiContent['location_data'] ?? [];
        $marketData = $aiContent['market_data'] ?? [];
        $comparisonData = $aiContent['comparison_data'] ?? [];
        $trustSection = $aiContent['trust_section'] ?? [];
        $investorSection = $aiContent['investor_section'] ?? [];

        $primaryColor = '#0A0B0D';
        $secondaryColor = '#6b7280';
        $accentColor = $profile->website_accent_color ?? '#0A0B0D';
        $headerBg = $profile->header_bg_color ?? '#ffffff';
        $headerTextClr = $profile->header_text_color ?? '#111827';
        $topbarEnabled = $profile->header_topbar_enabled === null ? true : (bool) $profile->header_topbar_enabled;
        $topbarText =
            $profile->header_topbar_text ?: 'Real Estate Taxi is your FREE ride through the global real estate market!';
        $logoType = $profile->header_logo_type ?? 'text';
        $logoText = $profile->header_logo_text ?? ($profile->agency_name ?? 'Agency');
        $logoPath = $profile->header_logo_path ? asset('storage/' . $profile->header_logo_path) : null;
        $logoUrl = $profile->header_logo_url ?? '#';
        $ctaEnabled = $profile->header_cta_enabled ?? true;
        $ctaText = $profile->header_cta_text ?? 'Book Viewing';
        $ctaUrl = $profile->header_cta_url ?? '#contact';
        $ctaBg = $profile->header_cta_bg_color ?? '#f59e0b';
        $ctaClr = $profile->header_cta_text_color ?? '#1a1a1a';
        $topbarColor = $profile->header_topbar_color ?? '#ffffff';
        $topbarBg = $profile->header_topbar_bg_color ?? '#0A0B0D';
        $defaultNav = [
            ['label' => 'Explore', 'url' => '#'],
            ['label' => 'Solutions', 'url' => '#'],
            ['label' => 'Market Routes', 'url' => '#'],
            ['label' => 'Top Areas', 'url' => '#'],
            ['label' => 'Expert Topics', 'url' => '#'],
            ['label' => 'Markets', 'url' => '#'],
        ];
        $navItems =
            !empty($profile->header_nav_items) && count($profile->header_nav_items) > 0
                ? $profile->header_nav_items
                : $defaultNav;
        $footerBg = $profile->footer_bg_color ?? '#0A0B0D';
        $footerTextClr = $profile->footer_text_color ?? '#ffffff';
        $col1Title = $profile->footer_col1_title ?? 'WE GLAD TO OFFER';
        $defaultCol1Links = [
            ['label' => 'Property Search Services', 'url' => '#'],
            ['label' => 'Investment Advisory', 'url' => '#'],
            ['label' => 'Market Analysis Reports', 'url' => '#'],
        ];
        $col1Links =
            !empty($profile->footer_col1_links) && count($profile->footer_col1_links) > 0
                ? $profile->footer_col1_links
                : $defaultCol1Links;
        $col2Title = $profile->footer_col2_title ?? 'ABOUT US';
        $col2Text =
            $profile->footer_col2_text ??
            'Hello we are ' .
                ($profile->agency_name ?? 'Agency') .
                '. We provide expert real estate services and property investment guidance.';
        $copyright = $profile->footer_copyright_text ?? '© ' . date('Y') . ' ' . ($profile->agency_name ?? 'Agency');
        $privacyUrl = $profile->footer_privacy_url ?? '#';
        $termsUrl = $profile->footer_terms_url ?? '#';

        // Sidebar property promo settings
        $sidebarPromoEnabled = $profile->sidebar_promo_enabled ?? true;
        $sidebarPromoImage = $profile->sidebar_promo_image ? asset('storage/' . $profile->sidebar_promo_image) : null;
        $sidebarPromoTitle = $profile->sidebar_promo_title ?? 'View All Properties';
        $sidebarPromoText = $profile->sidebar_promo_text ?? 'Browse our complete collection of premium properties for sale.';
        $sidebarPromoUrl = $profile->sidebar_promo_url ?? '#';
        $sidebarPromoButtonText = $profile->sidebar_promo_button_text ?? 'Get Property Options';
    @endphp
    <title>{{ $page->meta_title ?? ($page->name ?? 'Property') }} | {{ $profile->agency_name ?? 'Agency' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --ink: {{ $primaryColor }};
            --muted: {{ $secondaryColor }};
            --bg: #f4f5f6;
            --card: #fff;
            --soft: #f8f9fa;
            --line: #e4e6e9;
            --accent: {{ $accentColor }};
            --radius: 18px;
            --max: 90%
        }

        * {
            box-sizing: border-box
        }

        html {
            scroll-behavior: smooth
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: Inter, sans-serif;
            line-height: 1.6
        }

        a {
            text-decoration: none;
            color: inherit
        }

        section[id] {
            scroll-margin-top: 32px
        }

        .wrap {
            max-width: var(--max);
            margin: 0 auto;
            padding: 32px 24px;
            width: 100%;
            box-sizing: border-box
        }

        .topbar-strip {
            background: {{ $topbarBg }};
            color: {{ $topbarColor }};
            text-align: left;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 500
              padding-left:6.5%;

        }

        .site-header {
            background: {{ $headerBg }};
            border-bottom: 1px solid var(--line);
            padding: 16px 24px
        }

        .header-inner {
            max-width: var(--max);
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px
        }

        .brand {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 6px
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--ink);
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 20px;
            font-weight: 800
        }

        .brand strong {
            display: block;
            font-size: 13px;
            letter-spacing: -0.01em;
            color: {{ $headerTextClr }}
        }

        .brand small {
            display: block;
            font-size: 12px;
            color: var(--muted);
            font-weight: 500
        }

        .nav {
            display: flex;
            gap: 24px;
            align-items: center
        }

        .nav a {
            font-size: 14px;
            font-weight: 600;
            color: {{ $headerTextClr }}
        }

        .nav .cta-btn {
            background: {{ $ctaBg }};
            color: {{ $ctaClr }};
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 700;
            transition: filter 0.2s ease
        }

        .nav .cta-btn:hover {
              filter: brightness(1.15);
            color:white;
        }

        .page-header {
            padding: 6px 4px 24px
        }

        .page-header h1 {
            margin: 0;
            font-size: 62px;
            line-height: .98;
            letter-spacing: -.045em;
            font-weight: 800
        }

        .page-header p {
            margin: 12px 0 0;
            color: var(--muted);
            font-size: 24px
        }

        h1 {
            margin: 20px 0 16px;
            font-size: 48px;
            line-height: 1;
            font-weight: 800
        }

        .layout {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 24px
        }

        main {
            display: grid;
            gap: 20px
        }

        .sidebar {
            position: sticky;
            top: 24px;
            display: grid;
            gap: 16px
        }

        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius)
        }

        .pad {
            padding: 24px
        }

        .title {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 20px
        }

        .num {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--ink);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 800;
            font-size: 18px
        }

        h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 800
        }

        h3 {
            margin: 0 0 10px;
            font-size: 20px;
            font-weight: 700
        }

        .sub {
            color: var(--muted);
            font-size: 14px
        }

        .grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px
        }

        .article p {
            font-size: 16px;
            color: #374151;
            margin: 0 0 16px;
            line-height: 1.75
        }

        .ticks {
            display: grid;
            gap: 10px;
            margin: 20px 0
        }

        .tick {
            display: flex;
            gap: 12px;
            font-size: 15px;
            font-weight: 600;
            align-items: flex-start
        }

        .tick-icon {
            color: #0d6a43;
            font-weight: 900
        }

        .note-strip {
            margin-top: 20px;
            background: var(--soft);
            border: 1px solid var(--line);
            padding: 16px;
            border-radius: 12px;
            font-weight: 700
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 170px 1fr;
            gap: 18px;
            align-items: start;
            margin-bottom: 20px
        }

        .summary-photo img {
            width: 100%;
            border-radius: 14px;
            border: 1px solid var(--line)
        }

        .placeholder-img {
            width: 100%;
            height: 120px;
            background: var(--soft);
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 40px;
            border: 1px solid var(--line)
        }

        .summary-bullets ul {
            margin: 0;
            padding-left: 20px;
            color: #274235;
            display: grid;
            gap: 10px;
            font-size: 15px;
            line-height: 1.5;
            font-weight: 600
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 16px
        }

        .stats-6 {
            grid-template-columns: repeat(6, 1fr)
        }

        .stat {
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            background: var(--soft)
        }

        .stat .big {
            font-size: 22px;
            font-weight: 900
        }

        .stat .small {
            font-size: 11px;
            color: var(--muted)
        }

        .answers-layout {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 18px
        }

        .answer-tags {
            display: grid;
            gap: 12px
        }

        .tag {
            padding: 16px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fbfdfc;
            font-weight: 700;
            color: #315042;
            display: flex;
            align-items: center
        }

        .answers-list {
            display: grid;
            gap: 12px
        }

        .qa-box {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 16px;
            background: #fcfefd
        }

        .qa-box strong {
            display: block;
            font-size: 14px;
            font-weight: 800;
            color: #223a2f;
            margin-bottom: 10px
        }

        .qa-box p {
            margin: 0;
            color: #3b5a4a;
            font-weight: 600;
            line-height: 1.5;
            font-size: 14px
        }

        .answer-tags .tag:nth-child(1),
        .answers-list .qa-box:nth-child(1) {
            min-height: 140px
        }

        .answer-tags .tag:nth-child(2),
        .answers-list .qa-box:nth-child(2) {
            min-height: 140px
        }

        .answer-tags .tag:nth-child(3),
        .answers-list .qa-box:nth-child(3) {
            min-height: 140px
        }

        .faq-accordion {
            display: grid;
            gap: 10px
        }

        .faq-item {
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
            background: var(--soft)
        }

        .faq-question {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: none;
            border: none;
            cursor: pointer;
            font: inherit;
            font-weight: 600;
            font-size: 14px;
            text-align: left
        }

        .faq-icon {
            width: 24px;
            height: 24px;
            background: var(--ink);
            color: #fff;
            border-radius: 6px;
            display: grid;
            place-items: center;
            font-size: 12px;
            flex-shrink: 0
        }

        .faq-arrow {
            margin-left: auto;
            font-size: 10px;
            transition: transform 0.3s;
            color: var(--muted)
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease
        }

        .faq-answer p {
            margin: 0;
            padding: 0 16px 16px 52px;
            font-size: 14px;
            color: #374151;
            line-height: 1.7
        }

        .faq-item.open .faq-arrow {
            transform: rotate(180deg)
        }

        .faq-item.open .faq-answer {
            max-height: 500px;
            padding-bottom: 16px
        }

        .location-layout {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 24px
        }

        .location-map {
            position: relative
        }

        .location-features {
            margin: 0 0 16px;
            padding-left: 18px;
            font-size: 13px;
            color: #4b5563;
            line-height: 1.8
        }

        .distance-list {
            display: grid;
            gap: 8px
        }

        .dist {
            display: flex;
            justify-content: space-between;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--line);
            font-weight: 600;
            font-size: 14px
        }

        .market-layout {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 20px
        }

        .metric {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 20px;
            background: var(--soft)
        }

        .metric h4 {
            font-size: 13px;
            font-weight: 700;
            margin: 0 0 8px;
            color: #374151
        }

        .metric .value {
            font-size: 32px;
            font-weight: 900;
            color: #166534
        }

        .metric-sub {
            font-size: 11px;
            color: #6b7280;
            margin-top: 6px
        }

        .market-bullets {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 24px
        }

        .market-bullet {
            font-size: 13px;
            color: #4b5563;
            font-weight: 600
        }

        .compare-layout {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 24px
        }

        .compare-features {
            margin: 0;
            padding-left: 18px;
            font-size: 13px;
            color: #4b5563;
            line-height: 2
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px
        }

        .comparison td,
        .comparison th {
            border: 1px solid var(--line);
            padding: 12px
        }

        .comparison th {
            background: var(--soft);
            font-size: 12px;
            font-weight: 700
        }

        .comparison .highlight {
            background: #f0fdf4;
            color: #166534;
            font-weight: 800
        }

        .left-col {
            display: grid;
            gap: 18px
        }

        .trust-content {
            display: grid;
            gap: 16px
        }

        .agent-header strong {
            font-size: 16px;
            display: block;
            margin-bottom: 4px
        }

        .agent-row {
            display: flex;
            gap: 16px;
            align-items: flex-start
        }

        .agent-tagline {
            font-size: 13px;
            color: #6b7280
        }

        .agent-photo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb
        }

        .agent-photo-placeholder {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #e5e7eb;
            display: grid;
            place-items: center;
            font-size: 28px
        }

        .agent-rating {
            font-size: 13px
        }

        .stars {
            color: #f59e0b;
            margin-right: 4px
        }

        .agent-contact {
            font-size: 13px;
            color: #374151
        }

        .agent-contact strong {
            display: block;
            margin-bottom: 4px
        }

        .agent-badge {
            display: inline-block;
            padding: 6px 12px;
            border: 1px solid var(--line);
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            color: #374151
        }

        .trust-list {
            display: grid;
            gap: 8px;
            font-size: 14px;
            color: #4b5563
        }

        .investor-section {
            background: linear-gradient(180deg, #fff, #f8fcfa)
        }

        .investor-hero-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px
        }

        .investor-copy {
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 18px
        }

        .investor-copy p {
            margin: 0 0 12px;
            font-size: 14px;
            line-height: 1.6
        }

        .clear-answer-box {
            background: #f0fdf4;
            border-left: 4px solid #166534;
            padding: 14px 16px;
            margin-top: 16px;
            font-size: 14px;
            line-height: 1.5
        }

        .clear-answer-box strong {
            color: #166534
        }

        .investor-highlight {
            background: var(--ink);
            color: #fff;
            border-radius: 16px;
            padding: 24px
        }

        .investor-highlight strong {
            font-size: 24px;
            display: block;
            margin-bottom: 12px
        }

        .investor-highlight p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 16px
        }

        .alt-access-label {
            font-size: 11px;
            letter-spacing: 1px;
            opacity: 0.7;
            margin-bottom: 12px
        }

        .investor-routes {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 13px
        }

        .investor-routes li {
            margin-bottom: 10px;
            padding-left: 16px;
            position: relative
        }

        .investor-routes li:before {
            content: "•";
            position: absolute;
            left: 0
        }

        .form-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 16px
        }

        .form-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 12px
        }

        .form-buttons .btn {
            flex: 1
        }

        .btn.secondary {
            background: #fff;
            border: 1px solid var(--line);
            color: var(--ink)
        }

        .btn.outline {
            display: block;
            text-align: center;
            background: transparent;
            border: 1px solid var(--line);
            color: var(--ink);
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px
        }

        .btn.outline:hover {
            background: var(--soft)
        }

        .full-listing-link {
            margin-top: 12px;
            background: #f0fdf4;
            border-color: #166534;
            color: #166534
        }

        .route-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 16px
        }

        .route-card {
            border: 1px solid var(--line);
            background: var(--soft);
            border-radius: 16px;
            padding: 16px
        }

        .route-card b {
            display: block;
            font-size: 16px;
            margin-bottom: 6px
        }

        .route-card p {
            margin: 0;
            color: var(--muted);
            font-size: 14px
        }

        .investor-form-wrap {
            margin-top: 18px;
            background: var(--soft);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 18px
        }

        .investor-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px
        }

        .investor-form {
            display: grid;
            gap: 10px
        }

        .investor-form input,
        .investor-form select,
        .investor-form textarea {
            width: 100%;
            padding: 14px;
            border: 2px solid var(--accent);
            border-radius: 12px;
            font: inherit
        }

        .investor-form input:focus,
        .investor-form select:focus,
        .investor-form textarea:focus {
            outline: none;
            border-color: var(--ink);
            box-shadow: 0 0 0 3px rgba(0,0,0,0.05)
        }

        .investor-reasons {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: #fff;
            padding: 16px
        }

        .investor-reasons ul {
            margin: 0;
            padding-left: 20px;
            display: grid;
            gap: 10px;
            font-weight: 700
        }

        .investor-note {
            margin-top: 14px;
            background: var(--soft);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 14px;
            font-weight: 800
        }

        .btn {
            display: inline-flex;
            padding: 14px 20px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            border: none
        }

        .btn.primary {
            background: var(--ink);
            color: #fff
        }

        .sidebar {
            position: sticky;
            top: 24px;
            align-self: start
        }

        .sidebar-box {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--line)
        }

        .sidebar-box .head {
            background: var(--ink);
            color: #fff;
            padding: 16px;
            font-weight: 700;
            border-radius: 16px 16px 0 0
        }

        .sidebar-list {
            padding: 8px
        }

        .sidebar-row {
            display: flex;
            gap: 12px;
            padding: 12px 8px;
            border-bottom: 1px solid var(--line);
            font-weight: 700;
            font-size: 13px;
            transition: background 0.2s
        }

        .sidebar-row:hover {
            background: var(--soft)
        }

        .sidebar-row:last-child {
            border-bottom: none
        }

        .ico {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--ink);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0
        }

        .site-footer {
            background: {{ $footerBg }};
            color: {{ $footerTextClr }};
            padding: 48px 24px 24px;
            margin-top: 48px
        }

        .footer-inner {
            max-width: var(--max);
            margin: 0 auto
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 32px;
            margin-bottom: 32px
        }

        .footer-col h4 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 16px;
            opacity: 0.7
        }

        .footer-col p,
        .footer-col a {
            font-size: 15px;
            line-height: 1.8;
            opacity: 0.8;
            font-weight: 600
        }

        .footer-col a:hover {
            opacity: 1;
            text-decoration: none
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px 0 0;
            margin: 0 auto;
            max-width: var(--max);
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            opacity: 1
        }

        .footer-bottom a {
            font-weight: 600
        }

        .footer-bottom a:hover {
            text-decoration: none
        }

        @media(max-width:1100px) {
            .layout {
                grid-template-columns: 1fr
            }

            .investor-hero-box,
            .investor-form-grid {
                grid-template-columns: 1fr
            }

            .route-grid {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:900px) {

            .grid2,
            .market-layout,
            .trust-content,
            .summary-grid,
            .answers-layout,
            .location-layout,
            .compare-layout {
                grid-template-columns: 1fr
            }

            .stats,
            .stats-6 {
                grid-template-columns: repeat(3, 1fr)
            }

            .nav {
                display: none
            }

            h1 {
                font-size: 36px
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr
            }

            @media (max-width: 600px) {
                .footer-grid {
                    grid-template-columns: 1fr
                }
            }

            .answer-tags {
                display: flex;
                flex-wrap: wrap;
                gap: 8px
            }

            .location-map {
                order: 2
            }

            .compare-features {
                margin-bottom: 16px
            }

            .trust-agent {
                margin-bottom: 16px
            }
        }

        @media(max-width:600px) {

            .stats,
            .stats-6 {
                grid-template-columns: repeat(2, 1fr)
            }
        }

    </style>
</head>

<body>

    @if ($topbarEnabled)
        <div class="topbar-strip">{{ $topbarText }}</div>
    @endif

    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ $logoUrl }}">
                @if ($logoType === 'image' && $logoPath)
                    <img src="{{ $logoPath }}" alt="{{ $profile->agency_name ?? 'Agency' }}" style="height:44px;">
                @else
                    <span class="brand-mark">⌂</span>
                @endif
            </a>
            <nav class="nav">
                @foreach ($navItems as $item)
                    <a href="{{ $item['url'] ?? '#' }}">{{ $item['label'] ?? '' }}</a>
                @endforeach
                @if ($ctaEnabled)
                    <a class="cta-btn" href="{{ $ctaUrl }}">{{ $ctaText }}</a>
                @endif
            </nav>
        </div>
    </header>

    <div class="wrap">
        <section class="page-header">
            <h1>{{ $page->name ?? 'Property Page' }}</h1>
            <p>{{ $propertyType }} opportunities in {{ $fullLocation }}</p>
        </section>

        <div class="layout">
            <main>

                <section class="card pad" id="article">
                    <div class="title">
                        <h2>{{ $propertyType }} in {{ $location }}</h2>
                    </div>
                    <div class="article">
                        @if (!empty($heroArticle['paragraphs']) && is_array($heroArticle['paragraphs']))
                            @foreach ($heroArticle['paragraphs'] as $p)
                                <p>{{ $p }}</p>
                            @endforeach
                        @elseif(!empty($heroArticle['paragraphs']) && is_string($heroArticle['paragraphs']))
                            <p>{{ $heroArticle['paragraphs'] }}</p>
                        @else
                            <p>Discover exceptional {{ $propertyType }} opportunities in {{ $fullLocation }}.</p>
                        @endif
                    </div>
                    @php
                        $defaultHighlights = [
                            'Best for lifestyle buyers, second-home owners, and rental investors',
                            'Sea view, private pool, and quick access to the marina',
                            'Balanced mix of personal enjoyment and long-term value',
                        ];
                        $highlights =
                            isset($heroArticle['highlights']) && is_array($heroArticle['highlights'])
                                ? $heroArticle['highlights']
                                : $defaultHighlights;
                    @endphp
                    <div class="ticks">
                        @foreach ($highlights as $h)
                            <div class="tick"><span class="tick-icon">✓</span><span>{{ $h }}</span></div>
                        @endforeach
                    </div>
                    <div class="note-strip">Useful, honest content is the strongest AI signal.</div>
                </section>

                <div class="grid2">
                    <section class="card pad">
                        <div class="title">
                            <h2>Property Summary</h2>
                        </div>
                        <div class="summary-grid">
                            <div class="summary-photo">
                                @php
                                    $listingImgs = [];
                                    if (!empty($listing)) {
                                        $listingImgs = is_array($listing->images) ? $listing->images : (is_string($listing->images_json) ? json_decode($listing->images_json, true) : []);
                                    }
                                @endphp
                                @if (!empty($listingImgs) && is_array($listingImgs) && count($listingImgs) > 0)
                                    @php $listingImgSrc = Str::startsWith($listingImgs[0], 'http') ? $listingImgs[0] : asset('storage/' . $listingImgs[0]); @endphp
                                    <img src="{{ $listingImgSrc }}" alt="{{ $page->name ?? 'Property' }}" onerror="this.parentElement.innerHTML='<div class=\'placeholder-img\'>🏠</div>'">
                                @else
                                    <div class="placeholder-img">🏠</div>
                                @endif
                            </div>
                            <div class="summary-bullets">
                                <ul>
                                    @if (!empty($propertySummary['bullets']))
                                        @foreach ($propertySummary['bullets'] as $b)
                                            <li>{{ $b }}</li>
                                        @endforeach
                                    @else
                                        <li>{{ $page->name ?? 'Property' }} — {{ $location }},
                                            {{ $page->country ?? 'Croatia' }}</li>
                                        <li>Modern {{ strtolower($propertyType) }} with premium finishes</li>
                                        <li>{{ $listing->living_area ?? '—' }} m² interior living space on a
                                            {{ $listing->plot_size ?? '—' }} m² plot</li>
                                        <li>{{ $listing->is_turnkey ?? false ? 'Turnkey property with' : 'Property with' }}
                                            private pool and terraces</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="stats">
                            <div class="stat">
                                <div class="big">€{{ number_format($listing->price ?? 0, 0, ',', '.') }}</div>
                                <div class="small">Price</div>
                            </div>
                            <div class="stat">
                                <div class="big">{{ $listing->living_area ?? '—' }}</div>
                                <div class="small">Living Area m²</div>
                            </div>
                            <div class="stat">
                                <div class="big">{{ $listing->bedrooms ?? '—' }}</div>
                                <div class="small">Bedrooms</div>
                            </div>
                            <div class="stat">
                                <div class="big">{{ $listing->bathrooms ?? '—' }}</div>
                                <div class="small">Bathrooms</div>
                            </div>
                            <div class="stat">
                                <div class="big">{{ $listing->plot_size ?? '—' }}</div>
                                <div class="small">Plot m²</div>
                            </div>
                            <div class="stat">
                                <div class="big">{{ $listing->is_turnkey ?? false ? '✓' : '—' }}</div>
                                <div class="small">Turnkey</div>
                            </div>
                        </div>
                    </section>

                    <section class="card pad" id="answers">
                        <div class="title">
                            <h2>Quick Answers</h2>
                        </div>
                        <div class="answers-layout">
                            <div class="answer-tags">
                                <div class="tag">Short, direct answers</div>
                                <div class="tag">Plain language</div>
                                <div class="tag">Buyer-focused</div>
                            </div>
                            <div class="answers-list">
                                @php
                                    $defaultAnswers = [
                                        [
                                            'question' => 'Who is this property ideal for?',
                                            'answer' =>
                                                'Lifestyle buyers, second-home owners, and investors looking for a premium coastal property with strong rental appeal.',
                                        ],
                                        [
                                            'question' => 'What are the main advantages?',
                                            'answer' =>
                                                'Sea views, privacy, turnkey condition, a private pool, and convenient access to the marina, old town, and airport.',
                                        ],
                                        [
                                            'question' => 'How far is it from the beach and marina?',
                                            'answer' =>
                                                'The beach is approximately 450 meters away, and the marina is about 2.7 kilometers away.',
                                        ],
                                    ];
                                    $answers = !empty($quickAnswers)
                                        ? array_slice($quickAnswers, 0, 3)
                                        : $defaultAnswers;
                                @endphp
                                @foreach ($answers as $qa)
                                    <div class="qa-box">
                                        <strong>{{ $qa['question'] ?? '' }}</strong>
                                        <p>{{ $qa['answer'] ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                </div>

                <div class="grid2">
                    <section class="card pad" id="faq">
                        <div class="title">
                            <h2>FAQ</h2>
                        </div>
                        <div class="faq-accordion">
                            @php
                                $faqCountry = $page->country ?? 'Croatia';
                                $defaultFaqs = [
                                    ['question' => "Can foreigners buy property in " . $faqCountry . "?", 'answer' => "Yes, EU citizens can buy freely. Non-EU buyers need approval which takes 3-6 months and has a 95% approval rate. The process is straightforward for most nationalities."],
                                    ['question' => "What are the purchase costs?", 'answer' => "Expect approximately 7-10% on top of the purchase price: 3% transfer tax, 1% notary fees, 2-3% agency fee, and 1-2% legal costs."],
                                    ['question' => "What is the rental income potential?", 'answer' => "Rental yields range from 5-8% gross. Peak season (June-September) generates 60% of annual income. Average occupancy is 65-75% annually."],
                                    ['question' => "How long does the purchase process take?", 'answer' => "Typically 2-4 months from offer acceptance to completion, depending on due diligence and financing arrangements."],
                                    ['question' => "Can I view properties remotely?", 'answer' => "Yes, we offer virtual viewings via video call, detailed photo/video packages, and 3D tours for most properties."],
                                    ['question' => "Do you help with property management?", 'answer' => "Yes, we can connect you with trusted local property management companies. Management fees typically range from 15-25% of rental income."],
                                ];
                                $faqs = !empty($faqContent) ? $faqContent : $defaultFaqs;
                            @endphp
                            @foreach (array_slice($faqs, 0, 6) as $i => $f)
                                <div class="faq-item">
                                    <button class="faq-question" type="button">
                                        <span class="faq-icon">?</span>
                                        <span>{{ $f['question'] ?? '' }}</span>
                                        <span class="faq-arrow">▼</span>
                                    </button>
                                    <div class="faq-answer">
                                        <p>{{ $f['answer'] ?? 'Answer coming soon.' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="card pad" id="location">
                        <div class="title">
                            <h2>Location + Area</h2>
                        </div>
                        <div class="location-layout">
                            <div class="location-map">
                                @php
                                    $mapLat = $page->latitude ?? null;
                                    $mapLng = $page->longitude ?? null;
                                    $mapQuery = urlencode(($page->target_neighborhood ? $page->target_neighborhood . ', ' : '') . $page->target_city . ', ' . ($page->country ?? 'Croatia'));
                                @endphp
                                @if($mapLat && $mapLng)
                                    <iframe 
                                        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d5000!2d{{ $mapLng }}!3d{{ $mapLat }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2shr!4v1" 
                                        width="100%" 
                                        height="220" 
                                        style="border:0; border-radius: 12px;" 
                                        allowfullscreen="" 
                                        loading="lazy" 
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                @else
                                    <iframe 
                                        src="https://www.google.com/maps?q={{ $mapQuery }}&output=embed" 
                                        width="100%" 
                                        height="220" 
                                        style="border:0; border-radius: 12px;" 
                                        allowfullscreen="" 
                                        loading="lazy" 
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                @endif
                            </div>
                            <div class="location-info">
                                <ul class="location-features">
                                    <li>Micro-location map</li>
                                    <li>Key distances</li>
                                    <li>Neighborhood and lifestyle</li>
                                    <li>What matters in this zone</li>
                                </ul>
                                <div class="distance-list">
                                    @php
                                        $defaultDistances = [
                                            ['place' => 'Beach', 'distance' => '450 m'],
                                            ['place' => 'Old Town', 'distance' => '2.1 km'],
                                            ['place' => 'Marina', 'distance' => '2.7 km'],
                                            ['place' => 'School', 'distance' => '1.8 km'],
                                            ['place' => 'Airport', 'distance' => '9 km'],
                                        ];
                                        $distances = !empty($locationData['distances'])
                                            ? $locationData['distances']
                                            : $defaultDistances;
                                    @endphp
                                    @foreach ($distances as $d)
                                        <div class="dist">
                                            <span>{{ $d['place'] ?? '' }}</span><span>{{ $d['distance'] ?? '' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="grid2">
                    <div class="left-col">
                        <section class="card pad" id="market">
                            <div class="title">
                                <h2>Market + Investment Data</h2>
                            </div>
                            @php
                                $defaultMetrics = [
                                    [
                                        'label' => 'Average Price',
                                        'value' => '€4,900',
                                        'sub' => 'per m² — Source: public listings',
                                    ],
                                    ['label' => 'Est. Gross Yield', 'value' => '5.4%', 'sub' => 'Source: agency estimate'],
                                    ['label' => 'Demand Trend', 'value' => 'High', 'sub' => 'Updated: ' . date('d M Y')],
                                ];
                                $metrics = !empty($marketData['metrics']) ? $marketData['metrics'] : $defaultMetrics;
                            @endphp
                            <div class="market-layout">
                                @foreach ($metrics as $m)
                                    <div class="metric">
                                        <h4>{{ $m['label'] ?? '' }}</h4>
                                        <div class="value">{{ $m['value'] ?? '—' }}</div>
                                        @if (!empty($m['sub']))
                                            <div class="metric-sub">{{ $m['sub'] }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="market-bullets">
                                <div class="market-bullet">• Local pricing and performance</div>
                                <div class="market-bullet">• Demand trend insights</div>
                                <div class="market-bullet">• Seasonality and occupancy trends</div>
                                <div class="market-bullet">• Sources and update date</div>
                            </div>
                        </section>

                        <section class="card pad" id="trust">
                        <div class="title">
                            <h2>Trust + Expertise</h2>
                        </div>
                        @php
                            $agent = $profile->agents->first() ?? null;
                            $agencyName = $agent->agency_name ?? ($profile->agency_name ?? 'Agency');
                            $agentName = $agent->name ?? null;
                            $agentPhone = $agent->phone ?? null;
                            $agentEmail = $agent->email ?? null;
                            $agentPhoto = $agent->photo ?? null;
                            $agentRating = $agent->rating ?? 4.9;
                            $agentReviews = $agent->reviews_count ?? 96;
                            $agentTagline = $agent->tagline ?? 'Licensed Coastal Property Advisory';
                            $agentLicense = $agent->license ?? 'Licensed Croatia';
                        @endphp
                        <div class="trust-content">
                            <div class="agent-header">
                                <strong>{{ $agencyName }}</strong>
                                <div class="agent-tagline">{{ $agentTagline }}</div>
                                <div class="agent-rating">
                                    <span class="stars">★★★★★</span>
                                    <span>{{ $agentRating }} / 5 from {{ $agentReviews }} reviews</span>
                                </div>
                            </div>
                            <div class="agent-row">
                                @if ($agentPhoto)
                                    <img src="{{ asset('storage/' . $agentPhoto) }}" class="agent-photo" alt="{{ $agentName }}">
                                @else
                                    <div class="agent-photo-placeholder">👤</div>
                                @endif
                                @if ($agentName)
                                    <div class="agent-contact">
                                        <strong>{{ $agentName }}</strong>
                                        @if ($agentEmail)
                                            <div>{{ $agentEmail }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>
                    </div>

                    <section class="card pad" id="compare">
                        <div class="title">
                            <h2>Comparison / Why This Property</h2>
                        </div>
                        <div class="compare-layout">
                            <div class="compare-table-wrap">
                                @php
                                    $defaultComparison = [
                                        ['criteria' => 'Distance to sea', 'this' => '450 m', 'alt' => '800 m'],
                                        ['criteria' => 'Sea view', 'this' => 'Full', 'alt' => 'Partial'],
                                        ['criteria' => 'Build quality', 'this' => 'High', 'alt' => 'Medium'],
                                        ['criteria' => 'Outdoor space', 'this' => 'Large', 'alt' => 'Medium'],
                                        ['criteria' => 'Value for money', 'this' => 'Strong', 'alt' => 'Good'],
                                    ];
                                    $comparison = !empty($comparisonData['rows'])
                                        ? $comparisonData['rows']
                                        : $defaultComparison;
                                @endphp
                                <table class="comparison">
                                    <tr>
                                        <th>Criteria</th>
                                        <th class="highlight">This Property</th>
                                        <th>Alternative 1</th>
                                    </tr>
                                    @foreach ($comparison as $row)
                                        <tr>
                                            <td>{{ $row['criteria'] ?? '' }}</td>
                                            <td class="highlight">{{ $row['this'] ?? '—' }}</td>
                                            <td>{{ $row['alt'] ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </section>
                </div>

                @php
                    $propertyName = $listing->title ?? $page->name ?? 'This Property';
                    $listingUrl = $listing->external_url ?? '#';
                @endphp
                <section class="card pad investor-section" id="investor">
                    <div class="title">
                        <h2>Contact + Property Investor Options</h2>
                    </div>

                    <div class="investor-hero-box">
                        <div class="investor-copy">
                            <h3>Interested in {{ $propertyName }}, but not ready to buy the whole property?</h3>
                            <p>{{ $propertyName }} is presented as a full-property purchase opportunity, but visitors who like this type of asset may still have other ways to participate in Croatian coastal real estate.</p>
                            <p>Some buyers want to purchase the entire villa. Others want a similar rental-managed property. Some investors may prefer lower-entry, structured real-estate participation from approximately <strong>USD 30,000+</strong>, subject to eligibility, project availability, risk review, and documentation.</p>
                            <p>The goal is simple: keep motivated visitors on the page and give each person a clear next step based on budget, timing, and eligibility.</p>
                            <div class="clear-answer-box">
                                <strong>Clear answer:</strong> a visitor does not need to buy the whole {{ $propertyName }} to ask about Croatian coastal real-estate opportunities. They can request direct purchase details, similar villas, rental-managed ownership, or structured investor participation options.
                            </div>
                        </div>
                        <div class="investor-highlight">
                            <div class="alt-access-label">ALTERNATIVE ACCESS TO REAL ESTATE</div>
                            <strong>You can access real estate economics before full ownership.</strong>
                            <p>{{ $propertyName }} may be too large as a full purchase, but the same asset class can still create a serious next step for a motivated buyer or investor.</p>
                            <ul class="investor-routes">
                                <li><strong>Direct route:</strong> buy this villa or request similar {{ $location }} villas.</li>
                                <li><strong>Managed ownership route:</strong> ask about rental-managed coastal property instead of handling guests, maintenance, and operations alone.</li>
                                <li><strong>Structured participation route:</strong> eligible investors may ask about U.S. LLC or UK LLP participation from USD 30,000+, subject to legal eligibility, project availability, risk review, and official documents.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="route-grid">
                        <div class="route-card"><b>Option A: Buy {{ $propertyName }}</b>
                            <p>For buyers ready to acquire this specific villa and use it as a private residence, second home, or rental-ready coastal asset.</p>
                        </div>
                        <div class="route-card"><b>Option B: Similar villa shortlist</b>
                            <p>For visitors who like this property but need a different budget, location, size, completion stage, or rental profile.</p>
                        </div>
                        <div class="route-card"><b>Option C: Investor participation</b>
                            <p>For eligible investors who want economic exposure to Croatian coastal real estate without directly buying or managing the whole property alone.</p>
                        </div>
                    </div>

                    <div class="investor-form-wrap" id="contact">
                        <div class="investor-form-grid">
                            <div>
                                <h3>Ask which route fits this property goal</h3>
                                <p class="form-subtitle">Fill this form if {{ $propertyName }} is interesting, but you want to understand whether direct purchase, similar villas, rental-managed ownership, or a lower-entry investor route makes more sense.</p>
                                <form class="investor-form" action="#" method="POST">
                                    @csrf
                                    <input type="text" name="full_name" placeholder="Full name" required>
                                    <input type="email" name="email" placeholder="Email" required>
                                    <input type="text" name="phone" placeholder="Phone / WhatsApp">
                                    <select name="interest_type">
                                        <option value="">What are you interested in?</option>
                                        <option value="buy_direct">I want to buy {{ $propertyName }} directly</option>
                                        <option value="schedule_viewing">I want to schedule a viewing for {{ $propertyName }}</option>
                                        <option value="similar">I want similar {{ $location }} villas</option>
                                        <option value="rental_managed">I want rental-managed ownership for this type of villa</option>
                                        <option value="investor">I want investor participation from USD 30,000+</option>
                                        <option value="not_sure">I am not sure — show me the best route</option>
                                    </select>
                                    <textarea name="message" placeholder="Tell us your goal..."></textarea>
                                    <button type="submit" class="btn primary" style="width:100%">Request Private Property Options</button>
                                </form>
                            </div>
                            <div class="investor-reasons">
                                <h3>Why this captures more serious leads</h3>
                                <ul>
                                    <li>It captures people who love this villa but cannot buy the whole property today.</li>
                                    <li>It gives buyers a direct path to similar {{ $location }} villas instead of leaving the page.</li>
                                    <li>It connects the property page with rental-managed ownership and investor participation logic.</li>
                                    <li>It lets eligible investors ask about lower-entry real-estate participation from USD 30,000+.</li>
                                    <li>It helps AI systems understand that this page is not only a listing, but a structured property opportunity page.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

            </main>

            <aside class="sidebar">
                <section class="card sidebar-box">
                    <div class="head">What helps local SEO + AI search?</div>
                    <div class="sidebar-list">
                        <a href="#article" class="sidebar-row"><span class="ico">1</span><span>Strong local article first</span></a>
                        <a href="#answers" class="sidebar-row"><span class="ico">2</span><span>Micro-location keywords</span></a>
                        <a href="#faq" class="sidebar-row"><span class="ico">3</span><span>Available property connections</span></a>
                        <a href="#location" class="sidebar-row"><span class="ico">4</span><span>Map and distance data</span></a>
                        <a href="#market" class="sidebar-row"><span class="ico">5</span><span>Market snapshot with date</span></a>
                        <a href="#compare" class="sidebar-row"><span class="ico">6</span><span>Area comparison table</span></a>
                        <a href="#trust" class="sidebar-row"><span class="ico">7</span><span>Visible FAQ + schema</span></a>
                        <a href="#investor" class="sidebar-row"><span class="ico">8</span><span>Internal links to related pages</span></a>
                        <a href="#contact" class="sidebar-row"><span class="ico">9</span><span>Real author, agency and update date</span></a>
                    </div>
                </section>


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

                {{-- Last Updated --}}
                <section class="card pad">
                    <span class="badge">Local update note</span>
                    <h3 style="margin-top:12px;">Last updated: {{ $page->updated_at->format('d M Y') }}</h3>
                    <p class="sub">This guide is regularly updated with current market data and available properties.</p>
                </section>
            </aside>
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>{{ $col1Title }}</h4>
                    @foreach ($col1Links as $l)
                        <a href="{{ $l['url'] ?? '#' }}" style="display:block">{{ $l['label'] ?? '' }}</a>
                    @endforeach
                </div>
                <div class="footer-col">
                    <h4>Related Pages</h4>
                    <a href="#article" style="display:block">{{ $propertyType }} in {{ $location }}</a>
                    <a href="#answers" style="display:block">Quick Answers</a>
                    <a href="#faq" style="display:block">FAQ</a>
                    <a href="#location" style="display:block">Location + Area</a>
                    <a href="#market" style="display:block">Market + Investment Data</a>
                    <a href="#contact" style="display:block">Contact + Investor Options</a>
                </div>
                <div class="footer-col">
                    <h4>{{ $col2Title }}</h4>
                    <p>{{ $col2Text }}</p>
                </div>
            </div>
        </div>
        <div class="footer-bottom text-white">
            <span style="color:white !important">{{ $copyright }}</span>
            <div class="footer-links"><a href="{{ $privacyUrl }}">Privacy</a> | <a href="{{ $termsUrl }}">Terms</a></div>
        </div>
    </footer>

    <script>
        // FAQ accordion - ensure it works
        document.addEventListener('DOMContentLoaded', function() {
            var faqButtons = document.querySelectorAll('.faq-question');
            faqButtons.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var item = this.closest('.faq-item');
                    if (item) {
                        item.classList.toggle('open');
                    }
                });
            });
        });
    </script>

</body>

</html>
