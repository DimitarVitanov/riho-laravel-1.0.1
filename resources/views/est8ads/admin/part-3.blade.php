<th>Agency</th>
<th>Location</th>
<th>Contact</th>
<th>Listings</th>
<th>Chains</th>
<th>Commission</th>
<th>VIEW</th>
<th>PAID</th>
<th>Status</th>
<th>Actions</th></tr></thead>
<tbody id="agenciesTable"></tbody></table></div></section>
<section class="panel-section" data-section="billing"
    data-invoice-mark-paid-url="{{ \App\Support\Est8adsRoute::to('admin.invoices.mark-paid', ['__INVOICE__']) }}">
<div class="section-head">
<div>
<h2>Payments and subscriptions</h2>
<p>Track $12/month individual subscriptions and PayPal payment reconciliation. Payments are verified manually and marked as paid here.</p></div></div>
<div class="grid kpis" style="margin-bottom:18px">
<div class="kpi-card">
<h3 id="billingMonthlyRevenue">$0</h3>
<p>Monthly revenue (paid this month)</p></div>
<div class="kpi-card">
<h3 id="billingPaidInvoices">0</h3>
<p>Paid invoices this month</p></div>
<div class="kpi-card">
<h3 id="billingActiveSubscriptions">0</h3>
<p>Active subscriptions</p></div>
<div class="kpi-card">
<h3 id="billingPendingAmount">$0</h3>
<p>Pending / overdue amount</p></div></div>
<div class="table-wrap">
<table class="data-table">
<thead>
<tr>
<th>Invoice</th>
<th>Date</th>
<th>Customer</th>
<th>Item</th>
<th>Amount</th>
<th>Status</th>
<th>Actions</th></tr></thead>
<tbody id="paymentsTable"></tbody></table></div></section>
<section class="panel-section" data-section="reports">
<div class="section-head">
<div>
<h2>Reports and analytics</h2>
<p>Measure chain growth, match quality, conversions and unlocked value.</p></div>
<div class="section-actions">
<select class="filter-select">
<option>Last 30 days</option>
<option>Quarter</option>
<option>Year</option></select>
<button class="btn">Download PDF</button></div></div>
<div class="two-col">
<div class="card">
<div class="card-head">
<div>
<h3>New property moves</h3>
<p>Daily requests added to the system.</p></div></div>
<div class="card-body">
<div class="bar-chart">
<div class="bar-col">
<div class="bar" style="--h:42%"></div>
<span>Mon</span></div>
<div class="bar-col">
<div class="bar" style="--h:58%"></div>
<span>Tue</span></div>
<div class="bar-col">
<div class="bar" style="--h:67%"></div>
<span>Wed</span></div>
<div class="bar-col">
<div class="bar" style="--h:54%"></div>
<span>Thu</span></div>
<div class="bar-col">
<div class="bar" style="--h:86%"></div>
<span>Fri</span></div>
<div class="bar-col">
<div class="bar" style="--h:72%"></div>
<span>Sat</span></div>
<div class="bar-col">
<div class="bar" style="--h:63%"></div>
<span>Sun</span></div></div></div></div>
<div class="card">
<div class="card-head">
<div>
<h3>Chain status distribution</h3>
<p>Potential, action required and completed.</p></div></div>
<div class="card-body">
<div class="donut"></div></div></div></div>
<div class="grid kpis" style="margin-top:18px">
<div class="kpi-card">
<h3>82%</h3>
<p>Average AI match confidence</p></div>
<div class="kpi-card">
<h3>4.7</h3>
<p>Average nodes per chain</p></div>
<div class="kpi-card">
<h3>16.4%</h3>
<p>Request-to-match conversion</p></div>
<div class="kpi-card">
<h3>€8.6M</h3>
<p>Potential value unlocked</p></div></div></section>
<section class="panel-section" data-section="settings">
<div class="section-head">
<div>
<h2>System settings and integration</h2>
<p>Configure AI scoring, prompts, APIs, billing and platform behavior.</p></div>
<button class="btn primary" id="saveSettings">Save settings</button></div>
<div class="settings-grid">
<div class="settings-menu">
<button class="active">General</button>
<button>AI analysis</button>
<button>Property fields</button>
<button>Payments</button>
<button>API & Laravel</button>
<button>Notifications</button>
<button>Security</button></div>
<div class="settings-pane">
<div class="settings-group">
<h3>Platform configuration</h3>
<p>General account and request settings.</p>
<div class="form-grid">
<div class="form-field">
<label>Request duration</label>
<input value="30 days"></div>
<div class="form-field">
<label>Request price</label>
<input value="12 USD"></div>
<div class="form-field">
<label>Default currency</label>
<select>
<option>EUR</option>
<option>USD</option></select></div>
<div class="form-field">
<label>Minimum connected transactions</label>
<input type="number" value="2"></div></div></div>
<div class="settings-group">
<h3>AI compatibility weights</h3>
<p>Weights are normalized during the final compatibility calculation.</p>
<div class="form-grid">
<div class="form-field">
<label>Location compatibility (%)</label>
<input type="number" value="35"></div>
<div class="form-field">
<label>Price compatibility (%)</label>
<input type="number" value="35"></div>
<div class="form-field">
<label>Property type (%)</label>
<input type="number" value="20"></div>
<div class="form-field">
<label>Timing and flexibility (%)</label>
<input type="number" value="10"></div></div></div>
<div class="settings-group">
<h3>AI chain-analysis prompt</h3>
<p>Production prompt template used after structured database filters produce a candidate graph.</p>
<div class="prompt-box">SYSTEM: You are EST8ADS Property Chain Intelligence. Analyze the supplied sell properties, buy requests, participant constraints, prices, locations, property types and flexibility. Build only realistic connected transaction chains. Rank chains by compatibility, transaction continuity and total unlocked value. Identify exactly one missing property, buyer, seller or condition when the chain cannot close. Return structured JSON with chain nodes, score, risk flags, missing_link and recommended_action.</div></div>
<div class="settings-group">
<h3>Laravel/API integration map</h3>
<p>Suggested production endpoints for the developer implementation.</p>
<div class="integration-list">
<div class="integration-row">
<strong>Create property</strong>
<code>POST /api/properties</code>
<span class="status active">Ready</span></div>
<div class="integration-row">
<strong>Create move</strong>
<code>POST /api/property-moves</code>
<span class="status active">Ready</span></div>
<div class="integration-row">
<strong>Run analysis</strong>
<code>POST /api/chains/analyze</code>
<span class="status potential">Queue</span></div>
<div class="integration-row">
<strong>Start internet discovery</strong>
<code>POST /api/internet-discovery/jobs</code>
<span class="status potential">Queue</span></div>
<div class="integration-row">
<strong>Discovery results</strong>
<code>GET /api/internet-discovery/jobs/{id}/results</code>
<span class="status active">Ready</span></div>
<div class="integration-row">
<strong>Import external listing</strong>
<code>POST /api/external-listings/{id}/import</code>
<span class="status active">Ready</span></div>
<div class="integration-row">
<strong>Auto-connect listing</strong>
<code>POST /api/external-listings/{id}/connect</code>
<span class="status active">Ready</span></div>
<div class="integration-row">
<strong>Get chain graph</strong>
<code>GET /api/chains/{id}/graph</code>
<span class="status active">Ready</span></div>
<div class="integration-row">
<strong>Save missing link</strong>
<code>POST /api/missing-links</code>
<span class="status active">Ready</span></div>
<div class="integration-row">
<strong>Activate 30-day request</strong>
<code>POST /api/payments/activate-request</code>
<span class="status pending">Stripe</span></div></div></div></div></div></section>
<section class="panel-section" data-section="audit">
<div class="section-head">
<div>
<h2>Audit log</h2>
<p>Permanent record of administrator, agency and automated system actions.</p></div>
<button class="btn">Export audit log</button></div>
<div class="table-wrap">
<table class="data-table">
<thead>
<tr>
<th>Time</th>
<th>Actor</th>
<th>Action</th>
<th>Entity</th>
<th>IP / source</th>
<th>Result</th></tr></thead>
<tbody id="auditLogTable"><tr><td colspan="6">No audit records yet.</td></tr></tbody></table></div></section></div></main></div>
<div class="modal-backdrop" id="propertyModal">
<div class="modal">
<div class="modal-head">
<h2>Add or edit property</h2>
<button class="modal-close">×</button></div>
<form id="propertyForm">
<div class="modal-body">
<div class="form-grid">
<div class="form-field">
<label>Property side *</label>
<select name="side" required>
<option value="sell">Property to sell</option>
<option value="buy">Property wanted / buy request</option></select></div>
<div class="form-field">
<label>Status</label>
<select name="status">
<option>Active</option>
<option>Pending review</option>
<option>Draft</option>
<option>Archived</option></select></div>
<div class="form-field full">
<label>Title *</label>
<input name="title" required placeholder="Property title or buying requirement"></div>
<div class="form-field">
<label>Property type *</label>
<select name="type" required>@include('partials.property-type-options', ['selected' => ''])</select></div>
<div class="form-field">
<label>Owner / client *</label>
<input name="owner" required></div>
<div class="form-field">
<label>Country *</label>
<input name="country" value="Croatia" required></div>
<div class="form-field">
<label>City *</label>
<input name="city" required></div>
<div class="form-field full">
<label>Area / micro-location</label>
<input name="area"></div>
<div class="form-field">
<label>Price or maximum budget *</label>
<input name="price" type="number" min="0" required></div>
<div class="form-field">
<label>Currency</label>
<select name="currency">
<option>EUR</option>
<option>USD</option>
<option>GBP</option>
<option>CHF</option></select></div>
<div class="form-field">
<label>Interior size (m²)</label>
<input name="size" type="number" min="0"></div>
<div class="form-field">
<label>Land size (m²)</label>
<input name="landSize" type="number" min="0"></div>
<div class="form-field">
<label>Bedrooms</label>
<input name="beds" type="number" min="0"></div>
<div class="form-field">
<label>Bathrooms</label>
<input name="baths" type="number" min="0"></div>
<div class="form-field">
<label>Price flexibility (%)</label>
<input name="flexibility" type="number" min="0" max="100" value="5"></div>
<div class="form-field">
<label>Agency</label>
<select name="agency"><option value="">Direct user</option>@foreach($est8adsData['agencies'] as $agency)<option value="{{ $agency['id'] }}">{{ $agency['name'] }}</option>@endforeach</select></div>
<div class="form-field full">
<label>Listing URL</label>
<input name="url" type="url" placeholder="https://..."></div>
<div class="form-field full">
<label>Description and chain conditions</label>
<textarea name="description"></textarea></div>
<div class="form-field full">
<label>Photos / documents</label>
<input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple>
<div class="form-help">Upload listing media for Laravel Storage processing.</div></div></div></div>
<div class="modal-foot">
<button class="btn" type="button" data-close-modal>Cancel</button>
<button class="btn primary" type="submit">Save property</button></div></form></div></div>
<div class="modal-backdrop" id="userModal">
<div class="modal">
<div class="modal-head">
<h2>Edit user</h2>
<button class="modal-close">×</button></div>
<form id="userForm">
<div class="modal-body">
<div class="form-grid">
<div class="form-field">
<label>First name *</label>
<input name="first_name" required></div>
<div class="form-field">
<label>Last name *</label>
<input name="last_name" required></div>
<div class="form-field full">
<label>Email *</label>
<input name="email" type="email" required></div>
<div class="form-field">
<label>Phone</label>
<input name="phone"></div>
<div class="form-field">
<label>Status *</label>
<select name="status" required>
<option value="active">Active</option>
<option value="suspended">Suspended</option>
<option value="waitlist">Waitlist</option></select></div></div></div>
<div class="modal-foot">
<button class="btn" type="button" data-close-modal>Cancel</button>
<button class="btn primary" type="submit">Save user</button></div></form></div></div>
<div class="toast" id="panelToast"></div>
<script>window.EST8DATA = {{ Illuminate\Support\Js::from($est8adsData) }};</script>
<script src="{{ asset('est8ads-assets/panel/panel.js') }}"></script></body></html>
