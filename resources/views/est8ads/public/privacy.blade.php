<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Global EST8ADS privacy notice covering property profiles, AI matching, public listings, EU/UK rights, U.S. state rights and international data transfers.">
  <title>EST8ADS Privacy Policy — EU, U.S. and Global Privacy Notice</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('est8ads-assets/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('est8ads-assets/legal.css') }}">
    @include('est8ads.partials.favicon')
</head>
<body>

@include('est8ads.public.partials.nav')

@if (($locale ?? 'en') !== 'en')
<div class="section-shell" style="padding-top:18px"><div class="legal-callout"><p>{{ __('This legal document is only available in English to ensure accuracy. Use the language selector above for the rest of the site.') }}</p></div></div>
@endif

<main>
<section class="legal-hero"><div class="section-shell legal-hero-inner"><span class="section-kicker">EU, U.S. AND GLOBAL NOTICE</span><h1>Privacy Policy</h1><p>How EST8ADS handles account, property, verification, payment, public-source and AI-generated information across property-chain analysis and Missing Link campaigns.</p><div class="legal-meta"><span>Effective: July 26, 2026</span><span>Version 1.0</span><span>Applies globally, subject to local law</span></div><div class="prelaunch-note"><strong>Complete before publication:</strong> add the exact controller identity, address, registration details, processor list, hosting countries, retention schedule, cookie inventory, EU/UK representative and DPO details where required. The final policy must match the system’s real data flows.</div></div></section>
<div class="section-shell legal-layout"><aside class="legal-toc"><strong>ON THIS PAGE</strong><a href="#summary">1. Summary</a><a href="#controller">2. Controller and scope</a><a href="#data">3. Data we collect</a><a href="#sources">4. Data sources</a><a href="#purposes">5. Purposes and legal bases</a><a href="#ai">6. AI analysis and profiling</a><a href="#public">7. Public ads and controlled sharing</a><a href="#recipients">8. Recipients and service providers</a><a href="#transfers">9. International transfers</a><a href="#retention">10. Retention</a><a href="#security">11. Security</a><a href="#cookies">12. Cookies and tracking</a><a href="#eu">13. EU/EEA rights</a><a href="#uk">14. UK rights</a><a href="#us">15. U.S. state privacy rights</a><a href="#california">16. California notice</a><a href="#global">17. Other countries</a><a href="#children">18. Children</a><a href="#complaints">19. Complaints</a><a href="#updates">20. Updates</a><a href="#contact">21. Contact</a></aside><article class="legal-document">
<section class="legal-section" id="summary">
<h2>1. Summary</h2>
<p>This Privacy Policy explains how EST8ADS collects and uses personal information when people submit property moves, create accounts, use AI analysis, publish Missing Link Ads, respond to opportunities, communicate with agencies, use Chain Rooms, or contact us.</p>
<p>EST8ADS uses data to provide property-chain matching and analytics. We do not guarantee a sale, and we do not intend to sell personal information for money. Public ads are designed to use privacy-safe summaries rather than automatically publishing participant identities or contact details.</p>
<div class="legal-actions"><a class="legal-button primary" href="{{ \App\Support\Est8adsRoute::to('contact') }}?subject=privacy-request">Submit a privacy request</a><a class="legal-button" href="{{ \App\Support\Est8adsRoute::to('contact') }}?subject=privacy-opt-out">Opt out / privacy choices</a></div>
</section>

<section class="legal-section" id="controller">
<h2>2. Controller, representative and scope</h2>
<p>The data controller is <strong>[INSERT LEGAL ENTITY NAME]</strong>, a company registered in the Republic of Croatia under company number <strong>[INSERT NUMBER]</strong>, with registered office at <strong>[INSERT ADDRESS]</strong>.</p>
<p>This Policy applies to EST8ADS websites, accounts, property-move forms, agency and admin panels, AI matching, Internet Discovery, Missing Link Ads, Chain Rooms, communications, and support. Separate providers may act as independent controllers for brokerage, legal, payment, identity, financing, valuation, inspection, or other professional services.</p>
<p>If required, add the following before launch: EU representative <strong>[INSERT OR NOT APPLICABLE]</strong>; UK representative <strong>[INSERT OR NOT APPLICABLE]</strong>; Data Protection Officer <strong>[INSERT OR NOT REQUIRED]</strong>.</p>
</section>

<section class="legal-section" id="data">
<h2>3. Personal information we collect</h2>
<div class="legal-table-wrap"><table class="legal-table"><thead><tr><th>Category</th><th>Examples</th></tr></thead><tbody>
<tr><td>Account and contact data</td><td>Name, email, telephone, country, language, login information, role, agency and team details.</td></tr>
<tr><td>Property and transaction data</td><td>Property type, general or exact location, address, size, rooms, price, budget, photos, descriptions, URLs, listing status, desired purchase, conditions, dependencies and timing.</td></tr>
<tr><td>Representation and verification data</td><td>Identity, ownership, agency authority, proof of funds or financing status, verification records and results. Do not submit unnecessary government identifiers.</td></tr>
<tr><td>Communications</td><td>Messages, support requests, contact-form submissions, complaints, consent records and participant confirmations.</td></tr>
<tr><td>Payment and billing data</td><td>Payment status, amount, currency, invoice, tax information, processor reference and limited card metadata. Full card details are normally handled by the payment provider.</td></tr>
<tr><td>Technical and usage data</td><td>IP address, browser, device, timestamps, security logs, pages viewed, actions, referrer, cookie identifiers and approximate location derived from IP.</td></tr>
<tr><td>AI and inferred data</td><td>Similarity scores, Chain Confidence, readiness indicators, candidate matches, possible dependencies, Missing Links, summaries and risk or quality flags.</td></tr>
<tr><td>Public and partner-source data</td><td>Property listings, agency feeds, public webpages, public records where lawful, connected APIs and information supplied by authorized partners.</td></tr>
</tbody></table></div>
<p>We ask users not to submit special-category or highly sensitive data unless specifically requested and legally justified. Never upload unnecessary health, biometric, political, religious, sexual-orientation, criminal-record, government-ID or financial-account information into ordinary property descriptions.</p>
</section>

<section class="legal-section" id="sources">
<h2>4. Where data comes from</h2>
<ul>
<li>you, your authorized representative, agent, agency, team member or client;</li>
<li>other participants when they identify a possible match or chain relationship;</li>
<li>public property listings, agency sites, approved feeds, APIs and public sources;</li>
<li>identity, payment, fraud-prevention, mapping, analytics, communication and property-data providers;</li>
<li>Villa Bit AI or another connected system where you or your agency activate an integration.</li>
</ul>
<p>Where we obtain personal data indirectly, we provide notice where required unless an exception applies. Data from public sources is still handled under applicable privacy law.</p>
</section>

<section class="legal-section" id="purposes">
<h2>5. Purposes and legal bases</h2>
<div class="legal-table-wrap"><table class="legal-table"><thead><tr><th>Purpose</th><th>Typical legal basis in the EU/EEA/UK</th></tr></thead><tbody>
<tr><td>Create and manage accounts; publish and maintain a request; provide AI analysis and matching.</td><td>Performance of a contract or steps requested before entering a contract.</td></tr>
<tr><td>Find compatible properties, buyers, sellers and chain structures; improve scoring and prevent duplicate or low-quality results.</td><td>Contract and legitimate interests in operating and improving property intelligence, balanced against user rights.</td></tr>
<tr><td>Verify identity, ownership, authority, funding status or platform integrity.</td><td>Contract, legitimate interests, legal obligation where applicable, and consent where required.</td></tr>
<tr><td>Process payment, tax, accounting and refunds.</td><td>Contract and legal obligation.</td></tr>
<tr><td>Security, fraud prevention, audit, abuse detection and legal claims.</td><td>Legitimate interests and legal obligation.</td></tr>
<tr><td>Publish a public Missing Link Ad or share a profile with selected participants or agencies.</td><td>Contract, explicit user choices and, where required, consent.</td></tr>
<tr><td>Optional analytics, marketing communications or non-essential cookies.</td><td>Consent where required; otherwise legitimate interests subject to opt-out rights.</td></tr>
<tr><td>Respond to rights requests, regulators, courts or law enforcement.</td><td>Legal obligation and legitimate interests.</td></tr>
</tbody></table></div>
<p>Where we rely on legitimate interests, we consider necessity, reasonable expectations, sensitivity, safeguards and possible impact. You may object in applicable circumstances.</p>
</section>

<section class="legal-section" id="ai">
<h2>6. AI analysis, matching and profiling</h2>
<p>EST8ADS may use AI and automated processing to:</p>
<ul><li>structure free-text property information;</li><li>compare property types, locations, price ranges and requirements;</li><li>identify similar public or connected listings;</li><li>rank candidate matches and possible chain paths;</li><li>identify missing properties, buyers, sellers or conditions;</li><li>generate summaries, readiness indicators, Chain Confidence and quality flags;</li><li>detect duplicate, inconsistent, suspicious or incomplete data.</li></ul>
<p>These outputs are informational. EST8ADS does not intend to use solely automated processing to make a legally binding decision to buy, sell, approve financing, reject a participant, transfer title, or enter a contract. Authorized people decide whether to contact, verify, negotiate or proceed.</p>
<p>Where applicable, you may ask for meaningful information about the factors used, correct input data, object to certain profiling, or request human review.</p>
</section>

<section class="legal-section" id="public">
<h2>7. Public ads, Chain Rooms and controlled disclosure</h2>
<p>A public Missing Link Ad may show a general location, property type, budget or price range, verification label, chain count, estimated chain value, readiness indicator, timeline, and privacy-safe description. It should not automatically show participant names, private contact details, exact addresses, identity documents, proof of funds, or confidential transaction terms.</p>
<p>Private details may be disclosed to verified participants, agencies, service providers or professionals only when relevant, authorized, and subject to platform permissions or separate agreements. Users must not republish Chain Room data.</p>
</section>

<section class="legal-section" id="recipients">
<h2>8. Recipients and service providers</h2>
<p>We may disclose data to:</p>
<ul>
<li>authorized participants, agents and agencies involved in a potential match;</li>
<li>hosting, database, security, email, support, analytics, mapping, AI and software providers;</li>
<li>payment, identity, fraud, verification and accounting providers;</li>
<li>authorized property-data, listing, CRM and Villa Bit AI integration partners;</li>
<li>licensed professionals selected by you or necessary to provide an expressly requested service;</li>
<li>courts, regulators, authorities, advisers, insurers, acquirers or successors where legally required or necessary to protect rights.</li>
</ul>
<p>Service providers receive only data reasonably necessary for their role and are subject to contracts and safeguards where required.</p>
</section>

<section class="legal-section" id="transfers">
<h2>9. International data transfers</h2>
<p>EST8ADS may use providers or communicate with participants outside your country. For transfers from the EEA, EU or UK, we use an applicable adequacy decision, Standard Contractual Clauses, the UK International Data Transfer Agreement or Addendum, or another lawful safeguard. The EU–U.S. Data Privacy Framework is used only where the specific U.S. recipient is currently certified and the transfer is within that certification.</p>
<p>Transfer safeguards do not eliminate all foreign-law risk. You may request information about the relevant safeguard through the Contact page.</p>
</section>

<section class="legal-section" id="retention">
<h2>10. Retention</h2>
<p>We keep personal information only as long as reasonably necessary for the stated purpose, contract, security, disputes, tax, accounting and legal obligations. The following are default targets that must be validated before production launch:</p>
<div class="legal-table-wrap"><table class="legal-table"><thead><tr><th>Data</th><th>Default target period</th></tr></thead><tbody>
<tr><td>Public 30-day request</td><td>Public for the purchased period, then unpublished or archived. Core account and transaction records may remain while the account is active and for up to 24 months after inactivity.</td></tr>
<tr><td>Account and profile</td><td>While active, then up to 24 months unless deletion is requested or a longer legal need applies.</td></tr>
<tr><td>Billing, invoice and tax records</td><td>For the period required by applicable accounting and tax law, commonly up to 7–11 years depending on the record and jurisdiction.</td></tr>
<tr><td>Verification and fraud records</td><td>For the verification relationship and a reasonable claims, fraud and regulatory period; longer where AML/KYC law applies.</td></tr>
<tr><td>Support and complaints</td><td>Up to 3 years after closure, or longer for an active dispute.</td></tr>
<tr><td>Security and access logs</td><td>Normally up to 12 months, with longer retention for incidents or legal claims.</td></tr>
<tr><td>Marketing consent</td><td>Until withdrawal, unsubscribe, or a defined inactivity period.</td></tr>
</tbody></table></div>
<p>Data may be retained longer where required by law, legal hold, dispute, fraud prevention or a professional-service obligation. It may also be de-identified so it no longer identifies an individual.</p>
</section>

<section class="legal-section" id="security">
<h2>11. Security and incidents</h2>
<p>We use reasonable administrative, technical and organizational measures appropriate to the data and risk, such as access controls, encryption in transit, role permissions, logging, backups, vendor controls, secure development and incident response. No system is completely secure.</p>
<p>If a breach creates a legally reportable risk, we will notify authorities and affected people as required. Users must protect credentials and promptly report suspicious access.</p>
</section>

<section class="legal-section" id="cookies">
<h2>12. Cookies, local storage and tracking</h2>
<p>Essential cookies or local storage may be used for login, security, language, preferences, forms and saved drafts. Optional analytics or marketing technology should be activated only with the consent or opt-out controls required in the relevant jurisdiction.</p>
<ul><li><strong>Essential:</strong> required to operate the Service.</li><li><strong>Preferences:</strong> remember language or interface settings.</li><li><strong>Analytics:</strong> understand traffic and improve the Service.</li><li><strong>Marketing:</strong> measure campaigns or personalize advertising, if activated.</li></ul>
<p>A production cookie banner must allow users to accept all, reject optional cookies, and manage preferences. Browser settings may also block cookies, but some functionality may stop working.</p>
</section>

<section class="legal-section" id="eu">
<h2>13. EU/EEA rights under the GDPR</h2>
<p>Subject to conditions and exceptions, people in the EU/EEA may have rights to:</p>
<ul><li>receive transparent information;</li><li>access personal data;</li><li>correct inaccurate or incomplete data;</li><li>erase data;</li><li>restrict processing;</li><li>receive portable data;</li><li>object to legitimate-interest processing and direct marketing;</li><li>withdraw consent without affecting earlier lawful processing;</li><li>request safeguards concerning significant solely automated decisions;</li><li>complain to the data-protection authority in their country.</li></ul>
<p>We normally respond without undue delay and within one month, subject to lawful extensions. We may verify identity and may refuse or charge for manifestly unfounded or excessive requests where permitted.</p>
</section>

<section class="legal-section" id="uk">
<h2>14. United Kingdom rights</h2>
<p>UK residents have rights broadly including information, access, correction, erasure, restriction, portability, objection and protections concerning automated decisions, subject to the UK GDPR, Data Protection Act and current amendments. You may complain to the UK Information Commissioner’s Office.</p>
</section>

<section class="legal-section" id="us">
<h2>15. U.S. state privacy rights</h2>
<p>If a U.S. state privacy law applies to EST8ADS and to you, you may have rights to confirm processing, access, correct, delete, obtain a portable copy, opt out of sale, targeted advertising or certain profiling, limit certain sensitive-data uses, appeal a refusal, and receive non-discriminatory service.</p>
<p>Rights, definitions, business thresholds and exceptions vary by state. EST8ADS will apply the rights required for residents of applicable states, including California and other states with comprehensive consumer privacy laws.</p>
<p>We do not sell personal information for money. We do not knowingly use personal information for cross-context behavioral advertising unless this Policy and a legally required opt-out or consent mechanism clearly state otherwise. We recognize valid browser-based opt-out preference signals where required and technically supported.</p>
</section>

<section class="legal-section" id="california">
<h2>16. California notice at collection</h2>
<p>California residents may have rights to know, access, delete, correct, opt out of sale or sharing, limit certain sensitive-personal-information use, and avoid discrimination.</p>
<div class="legal-table-wrap"><table class="legal-table"><thead><tr><th>CCPA category</th><th>Collected / purpose</th></tr></thead><tbody>
<tr><td>Identifiers and customer records</td><td>Account, contact, support, verification, billing and communication.</td></tr>
<tr><td>Commercial information</td><td>Purchased publication, plan, payment status and service history.</td></tr>
<tr><td>Internet or electronic activity</td><td>Security, login, device, usage, diagnostics and analytics.</td></tr>
<tr><td>Geolocation</td><td>General property location and approximate IP-derived location; precise location only if expressly supplied or enabled.</td></tr>
<tr><td>Professional information</td><td>Agency, role, representation and team information.</td></tr>
<tr><td>Audio/visual content</td><td>Property photos, uploaded media and support attachments.</td></tr>
<tr><td>Inferences</td><td>Compatibility, readiness, chain, similarity and Missing Link outputs.</td></tr>
<tr><td>Sensitive personal information</td><td>Account credentials and limited verification or financial-readiness information where necessary. Used only for permitted service, security, verification or legal purposes.</td></tr>
</tbody></table></div>
<p>We disclose these categories to service providers, contractors and authorized transaction participants for the business purposes described above. We do not offer financial incentives for personal information. To exercise rights, use the Contact page. An authorized agent may submit a request with proof of authority.</p>
</section>

<section class="legal-section" id="global">
<h2>17. Rights in Canada, Brazil, Australia, Switzerland and other countries</h2>
<p>Depending on applicable law, you may have rights to know or access data, correct it, withdraw consent, object, delete data, receive portability, complain, or challenge certain automated processing.</p>
<ul>
<li><strong>Canada:</strong> applicable federal or provincial privacy laws may provide access, correction, consent and complaint rights.</li>
<li><strong>Brazil:</strong> the LGPD may provide confirmation, access, correction, anonymization, blocking, deletion, portability, consent withdrawal and review rights.</li>
<li><strong>Australia:</strong> the Privacy Act and Australian Privacy Principles may provide access, correction and complaint rights where EST8ADS is an APP entity.</li>
<li><strong>Switzerland:</strong> the Federal Act on Data Protection may provide transparency, access, correction and related rights.</li>
</ul>
<p>Where local law provides stronger mandatory protection, that protection applies. We may ask for location and identity information to determine the applicable right.</p>
</section>

<section class="legal-section" id="children">
<h2>18. Children</h2>
<p>EST8ADS is intended for adults involved in property transactions and is not directed to children under 18. We do not knowingly collect personal information from children for independent use of the Service. Contact us if you believe a child submitted data without proper authorization.</p>
</section>

<section class="legal-section" id="complaints">
<h2>19. Questions and complaints</h2>
<p>Contact us first so we can investigate. EU/EEA residents may complain to their local supervisory authority; UK residents may complain to the ICO; California residents may consult the California Privacy Protection Agency or Attorney General; and residents elsewhere may contact their local privacy regulator.</p>
</section>

<section class="legal-section" id="updates">
<h2>20. Changes to this Policy</h2>
<p>We may update this Policy when data practices, providers, laws or features change. The effective date will be revised. Material changes may be notified through the Service or by email where appropriate.</p>
</section>

<section class="legal-section" id="contact">
<h2>21. Contact and rights requests</h2>
<p>Use the <a href="{{ \App\Support\Est8adsRoute::to('contact') }}?subject=privacy-request">privacy request form</a> to exercise rights, withdraw consent, object, appeal, or ask about transfers and automated analysis.</p>
<div class="operator-box"><strong>Privacy contact details to complete before launch</strong><br>[INSERT LEGAL ENTITY NAME]<br>[INSERT REGISTERED ADDRESS]<br>Privacy: privacy@est8ads.com<br>General: contact@est8ads.com<br>EU representative: [INSERT IF REQUIRED]<br>UK representative: [INSERT IF REQUIRED]<br>DPO: [INSERT IF REQUIRED]</div>
</section>
</article></div>
</main>
@include('est8ads.public.partials.footer')
<script src="{{ asset('est8ads-assets/page.js') }}"></script></body></html>
