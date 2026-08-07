<button class="btn primary discovery-run-button" id="runDiscoveryRequest">Search internet and auto-connect</button>
        
<div class="discovery-progress" id="discoveryProgress" hidden>
          
<div>
<span id="discoveryProgressText">Preparing search query…</span>
<strong id="discoveryProgressPercent">0%</strong></div>
          
<i>
<b id="discoveryProgressBar"></b></i>
        </div>
      </div>
    </div>
  </div>

  
<div class="card" style="margin-top:18px">
    
<div class="card-head">
<div>
<h3>Automatic search queue</h3>
<p>Every property request creates or refreshes one discovery job.</p></div>
<div class="queue-legend">
<span class="status active">Completed</span>
<span class="status potential">Searching</span>
<span class="status pending">Queued</span></div></div>
    
<div class="toolbar discovery-toolbar">
<div class="search">
<input id="discoveryQueueSearch" placeholder="Search request, user, location or job ID"></div>
<select class="filter-select" id="discoveryQueueStatus">
<option value="all">All statuses</option>
<option value="Completed">Completed</option>
<option value="Searching">Searching</option>
<option value="Queued">Queued</option>
<option value="Needs review">Needs review</option></select></div>
    
<div class="table-wrap">
<table class="data-table">
<thead>
<tr>
<th>Job</th>
<th>Request</th>
<th>Search target</th>
<th>Sources</th>
<th>Found</th>
<th>Connected</th>
<th>Status</th>
<th>Last run</th>
<th>Actions</th></tr></thead>
<tbody id="discoveryQueueTable"></tbody></table></div>
  </div>

  
<div class="card" style="margin-top:18px">
    
<div class="card-head">
<div>
<h3>Discovered internet listings</h3>
<p>Normalized and deduplicated listings ranked against the selected EST8ADS request.</p></div>
<div class="section-actions">
<button class="btn" id="importSelectedDiscovery">Import selected</button>
<button class="btn primary" id="connectSelectedDiscovery">Auto-connect selected</button></div></div>
    
<div class="toolbar discovery-toolbar">
<div class="search">
<input id="discoveryResultSearch" placeholder="Search discovered title, domain or location"></div>
<select class="filter-select" id="discoveryResultStatus">
<option value="all">All connection statuses</option>
<option value="Connected">Connected</option>
<option value="Proposed">Proposed</option>
<option value="Review">Needs review</option>
<option value="Rejected">Rejected</option></select>
<select class="filter-select" id="discoveryResultMinScore">
<option value="0">All similarity scores</option>
<option value="70">70%+</option>
<option value="80">80%+</option>
<option value="90">90%+</option></select></div>
    
<div class="table-wrap">
<table class="data-table">
<thead>
<tr>
<th>
<input type="checkbox" id="selectAllDiscovery"></th>
<th>External listing</th>
<th>Source</th>
<th>Location</th>
<th>Price</th>
<th>Similarity</th>
<th>Data confidence</th>
<th>Connection</th>
<th>Actions</th></tr></thead>
<tbody id="discoveryResultsTable"></tbody></table></div>
  </div>

  
<div class="two-col" style="margin-top:18px">
    
<div class="card">
      
<div class="card-head">
<div>
<h3>AI auto-connection explanation</h3>
<p>Why the strongest external result was attached to the request.</p></div>
<span class="status active">92% MATCH</span></div>
      
<div class="card-body">
        
<div class="connection-score-grid">
          
<div>
<span>Location</span>
<strong>100%</strong>
<i>
<b style="width:100%"></b></i></div>
          
<div>
<span>Price</span>
<strong>91%</strong>
<i>
<b style="width:91%"></b></i></div>
          
<div>
<span>Property type</span>
<strong>100%</strong>
<i>
<b style="width:100%"></b></i></div>
          
<div>
<span>Requirements</span>
<strong>82%</strong>
<i>
<b style="width:82%"></b></i></div>
        </div>
        
<div class="ai-explanation">
<strong>Recommended connection</strong>
<p>The listing matches the requested island, property type and budget. Three bedrooms, parking and sea view are confirmed. The listing is recent and the source data confidence is high. Connect it as an external candidate and verify availability before contacting the user.</p></div>
      </div>
    </div>
    
<div class="card">
      
<div class="card-head">
<div>
<h3>Production workflow</h3>
<p>Required queued services behind this admin section.</p></div>
<span class="status potential">LARAVEL</span></div>
      
<div class="card-body">
<div class="discovery-flow">
<div>
<b>1</b>
<span>
<strong>RequestSubmitted event</strong>
<small>Dispatch after user or agency submission.</small></span></div>
<i>↓</i>
<div>
<b>2</b>
<span>
<strong>DiscoverInternetProperties job</strong>
<small>Search approved APIs, feeds and permitted pages.</small></span></div>
<i>↓</i>
<div>
<b>3</b>
<span>
<strong>Normalize + deduplicate</strong>
<small>Canonical URL, address fingerprint and image hash.</small></span></div>
<i>↓</i>
<div>
<b>4</b>
<span>
<strong>AI similarity + auto-connect</strong>
<small>Save candidates and connect above the threshold.</small></span></div></div></div>
    </div>
  </div></section>


<section class="panel-section" data-section="properties">
<div class="section-head">
<div>
<h2>All properties</h2>
<p>Manage every property listing and buying requirement in the system.</p></div>
<div class="section-actions">
<button class="btn" id="addSelectedToAnalysis">Add selected to analysis</button>
<button class="btn primary" data-open-property>Add property</button></div></div>
<div class="toolbar">
<div class="search">
<input id="adminPropertySearch" placeholder="Search title, city, owner or ID"></div>
<select class="filter-select" id="adminPropertySide">
<option value="all">Sell and buy</option>
<option value="sell">Properties for sale</option>
<option value="buy">Buying requests</option></select>
<select class="filter-select">
<option>All countries</option>
<option>Croatia</option></select>
<select class="filter-select">
<option>All statuses</option>
<option>Active</option>
<option>Pending review</option>
<option>Draft</option></select></div>
<div class="table-wrap">
<table class="data-table">
<thead>
<tr>
<th>
<input id="selectAllProperties" type="checkbox"></th>
<th>Property / request</th>
<th>Side</th>
<th>Location</th>
<th>Price / budget</th>
<th>Owner</th>
<th>Agency</th>
<th>Status</th>
<th>Actions</th></tr></thead>
<tbody id="adminPropertiesTable"></tbody></table></div></section>
<section class="panel-section" data-section="analyzer">
<div class="section-head">
<div>
<h2>Multi-property chain analyzer</h2>
<p>Select several sell properties and buying requests, configure the AI weights and analyze complete transaction chains.</p></div>
<div class="section-actions">
<button class="btn" id="clearAnalyzer">Clear selection</button></div></div>
<div class="analyzer-layout">
<div class="analyzer-pool">
<div class="card-head">
<div>
<h3>Property pool</h3>
<p>Select any number of properties and requests.</p></div>
<span class="status active" id="poolCount">0 selected</span></div>
<div class="toolbar" style="margin:10px;border:0;padding:0">
<div class="search">
<input id="poolSearch" placeholder="Search property pool"></div>
<select class="filter-select" id="poolSide">
<option value="all">All</option>
<option value="sell">Sell</option>
<option value="buy">Buy</option></select></div>
<div class="pool-list" id="propertyPool"></div></div>
<div class="analyzer-workbench">
<div class="workbench-top">
<div>
<strong>Chain workbench</strong>
<div style="font-size:10px;color:#777;margin-top:4px">Build and score several connected transactions in one analysis.</div></div>
<span class="status potential">AI READY</span></div>
<div class="workbench-controls">
<div class="compact-field">
<label>Location weight</label>
<input id="weightLocation" type="number" min="0" max="100" value="35"></div>
<div class="compact-field">
<label>Price weight</label>
<input id="weightPrice" type="number" min="0" max="100" value="35"></div>
<div class="compact-field">
<label>Property type</label>
<input id="weightType" type="number" min="0" max="100" value="20"></div>
<div class="compact-field">
<label>Flexibility</label>
<input id="weightFlex" type="number" min="0" max="100" value="10"></div></div>
<div class="selected-columns">
<div class="selection-box">
<h4>SELL PROPERTIES</h4>
<div id="selectedSell"></div></div>
<div class="selection-box">
<h4>BUYING REQUESTS</h4>
<div id="selectedBuy"></div></div></div>
<div class="run-row">
<button class="btn primary" id="runAnalysis">Analyze selected properties</button></div>
<div class="analysis-results" id="analysisResults">
<div class="card-head" style="padding:0 0 13px">
<div>
<h3>AI chain candidates</h3>
<p>Ranked by compatibility, chain continuity and unlocked value.</p></div></div>
<div id="chainResults"></div></div></div></div></section>
<section class="panel-section" data-section="map">
<div class="section-head">
<div>
<h2>Property chain map</h2>
<p>Visualize connected properties, users, agencies and missing links.</p></div>
<div class="section-actions">
<select class="filter-select"><option>All active chains</option></select>
<button class="btn">Full screen</button></div></div>
<div class="network-map" id="adminNetworkMap">
<div class="network-caption">Illustrative preview — blue: sell · red: buy request · yellow: missing link. Not a live calculated result.</div></div></section>
<section class="panel-section" data-section="missing">
<div class="section-head">
<div>
<h2>Missing links</h2>
<p>Specific buyers, sellers, properties or conditions preventing high-value chains from moving forward.</p></div>
<button class="btn primary">Create targeted ad</button></div>
<div class="property-grid" id="missingGrid"></div></section>
<section class="panel-section" data-section="matches">
<div class="section-head">
<div>
<h2>Candidate matches</h2>
<p>Direct and chain-level matches awaiting review or participant confirmation.</p></div>
<button class="btn">Export matches</button></div>
<div class="table-wrap">
<table class="data-table">
<thead>
<tr>
<th>Match</th>
<th>Sell property</th>
<th>Buy request</th>
<th>Score</th>
<th>Potential value</th>
<th>Status</th>
<th>Actions</th></tr></thead>
<tbody id="matchesTable"></tbody></table></div></section>
<section class="panel-section" data-section="users"
    data-user-update-url="{{ \App\Support\Est8adsRoute::to('admin.users.update', ['__USER__']) }}">
<div class="section-head">
<div>
<h2>Users</h2>
<p>Manage private buyers, sellers, agents and account verification.</p></div>
<button class="btn primary" data-open-user>Add user</button></div>
<div class="toolbar">
<div class="search">
<input placeholder="Search users"></div>
<select class="filter-select">
<option>All roles</option>
<option>Private user</option>
<option>Agency agent</option></select>
<select class="filter-select">
<option>All statuses</option>
<option>Verified</option>
<option>Pending</option></select></div>
<div class="table-wrap">
<table class="data-table">
<thead>
<tr>
<th>User</th>
<th>Contact</th>
<th>Role</th>
<th>Moves</th>
<th>Status</th>
<th>Payment</th>
<th>Joined</th>
<th>Actions</th></tr></thead>
<tbody id="usersTable"></tbody></table></div></section>
<section class="panel-section" data-section="agencies">
<div class="section-head">
<div>
<h2>Real estate agencies</h2>
<p>Control agency access, VIEW status, PAID status and commissions.</p></div>
<button class="btn primary" data-open-agency>Add agency</button></div>
<div class="table-wrap">
<table class="data-table">
<thead>
<tr>
