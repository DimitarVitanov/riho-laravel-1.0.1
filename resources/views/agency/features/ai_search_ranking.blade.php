@extends('layouts.simple.master')
@section('title', 'AI Search Ranking')
@section('breadcrumb-title')
    <h3>AI Search Ranking</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency Panel</a></li>
    <li class="breadcrumb-item active">AI Search Ranking</li>
@endsection

@section('css')
<style>
:root {
  --ink: #0a0b0c;
  --soft: #f5f6f7;
  --line: #dde1e5;
  --muted: #69717a;
  --accent: #1d8d64;
  --accent-soft: #e7f6ef;
}

.ai-search-feature { font-size: 14px; line-height: 1.45; }
.ai-search-feature .form-label { display: block; font-size: 12px; font-weight: 800; color: #3e454c; margin: 0 0 5px; }
.ai-search-feature .form-control, .ai-search-feature .form-select { 
    display: block; width: 100%; font-size: 14px; 
    border: 1px solid #cfd4d9; border-radius: 8px; 
    padding: 10px 11px; min-height: 40px;
    color: #262c31; background: #fff;
}
.ai-search-feature .btn { font-size: 13px; font-weight: 800; border-radius: 8px; padding: 10px 14px; border: 0; cursor: pointer; }
.ai-search-feature .btn-accent { background: var(--accent); color: #fff !important; }
.ai-search-feature .btn-accent:hover { background: #176347; color: #fff !important; }
.ai-search-feature .btn.btn-outline-secondary { background: #fff !important; color: #26303a !important; border: 1px solid #cfd4d9 !important; }
.ai-search-feature .btn.btn-outline-secondary:hover { background: #f5f6f7 !important; }
.ai-search-feature .btn-dark { background: var(--ink); color: #fff !important; }
.ai-search-feature h5 { margin: 0; font-size: 21px; line-height: 1.2; display: flex; align-items: center; gap: 6px; }
.ai-search-feature small, .ai-search-feature .text-muted { font-size: 12px; color: var(--muted); }
.ai-search-feature table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.ai-search-feature th { text-align: left; font-size: 10px; letter-spacing: .05em; text-transform: uppercase; color: #58616a; background: #f7f8f9; }
.ai-search-feature th, .ai-search-feature td { padding: 10px; border-bottom: 1px solid #edf0f2; vertical-align: top; }
.ai-search-feature tr:last-child td { border-bottom: 0; }
.ai-search-feature .card { background: #fff; border: 1px solid var(--line); border-radius: 16px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04); margin-bottom: 24px; }
.ai-search-feature .card-header { display: flex; justify-content: space-between; gap: 16px; align-items: flex-start; border-bottom: 1px solid var(--line); padding: 21px; border-radius: 16px 16px 0 0; background: #fff; }
.ai-search-feature .card-body { padding: 21px; border-radius: 0 0 16px 16px; }
.ai-search-feature .step-circle { display: inline-grid; place-items: center; width: 31px; height: 31px; border-radius: 50%; background: var(--ink); color: #fff; font-size: 14px; font-weight: 800; margin-right: 9px; }
.ai-search-feature .output-badge { padding: 10px 13px; background: var(--accent-soft); color: #176347; border-radius: 8px; font-size: 13px; font-weight: 700; white-space: nowrap; }
.ai-search-feature .actions-bar { display: flex; justify-content: space-between; gap: 10px; align-items: center; margin-top: 19px; padding-top: 16px; border-top: 1px solid var(--line); flex-wrap: wrap; }
.ai-search-feature .table-wrap { overflow: auto; border: 1px solid var(--line); border-radius: 10px; }
.ai-search-feature .table-wrap table { margin-bottom: 0; min-width: 700px; }
.ai-search-feature .badge-high { display: inline-block; border-radius: 999px; padding: 3px 7px; font-size: 10px; font-weight: 800; background: #e7f6ef; color: #176347; }
.ai-search-feature .badge-medium { display: inline-block; border-radius: 999px; padding: 3px 7px; font-size: 10px; font-weight: 800; background: #fff5d8; color: #765303; }
.ai-search-feature .badge-low { display: inline-block; border-radius: 999px; padding: 3px 7px; font-size: 10px; font-weight: 800; background: #eef1f4; color: #5c656d; }

/* Loader overlay for long-running AI actions */
.vb-loader-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(3px);
    z-index: 9999;
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 18px;
}
.vb-loader-overlay.active { display: flex; }
.vb-loader-overlay .spinner {
    width: 56px; height: 56px;
    border: 4px solid #e5e7eb;
    border-top-color: #1d8d64;
    border-radius: 50%;
    animation: vb-spin 1s linear infinite;
}
@keyframes vb-spin { to { transform: rotate(360deg); } }
.vb-loader-overlay .message {
    font-size: 16px; font-weight: 700; color: #0a0b0c;
    text-align: center; max-width: 320px; line-height: 1.5;
}
.vb-loader-overlay .sub-message {
    font-size: 14px; color: #374151; font-weight: 500;
}
</style>
@endsection

@section('content')
<div class="container-fluid ai-search-feature">

    {{-- LOADER OVERLAY --}}
    <div id="vbLoader" class="vb-loader-overlay">
        <div class="spinner"></div>
        <div class="message"><strong>Villa Bit AI</strong> is building your page…</div>
        <div class="sub-message">This requires up to one minute of processing time. Please do not close the page.</div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    @if(request('create_page') || request('edit_page_id'))
    <a href="{{ route('agency.features.show', 'ai_search_ranking') }}" style="font-size:13px;color:var(--muted);text-decoration:none;display:inline-block;margin-bottom:16px;">← Back to Pages</a>
    @endif

    {{-- MAIN SETTINGS CARD --}}
    <div class="card">
        <div class="card-header">
            <div>
                <h5 style="font-size:18px;">AI Search Ranking</h5>
                <p style="margin:4px 0 0;color:var(--muted);font-size:13px;">Generate AI-optimized pages for your property listings that rank in ChatGPT, Perplexity, and Google AI Overviews</p>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="featureToggle"
                    {{ $featureSetting && $featureSetting->is_enabled ? 'checked' : '' }}
                    style="width: 3em; height: 1.5em;"
                    onchange="toggleFeature(this)">
            </div>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:13px;">
                <div>
                    <label class="form-label">Feature Status</label>
                    <div class="form-control" style="background:var(--soft);">
                        <span style="font-weight:750;color:{{ $featureSetting && $featureSetting->is_enabled ? '#0a0b0c' : 'var(--muted)' }}">
                            <i class="fa {{ $featureSetting && $featureSetting->is_enabled ? 'fa-check-circle' : 'fa-circle-o' }}" style="margin-right:8px;"></i>
                            {{ $featureSetting && $featureSetting->is_enabled ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div>
                    <label class="form-label">AI Content Language</label>
                    <div class="form-control" style="background:var(--soft);">
                        <span style="font-weight:750;">{{ $profile->ai_content_language ?? 'English' }}</span>
                    </div>
                </div>
                <div>
                    <label class="form-label">Total Pages</label>
                    <div class="form-control" style="background:var(--soft);">
                        <span style="font-weight:750;">{{ $pages->count() ?? 0 }} pages created</span>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            @php $canUseAi = ($usageLimitStatus['can_use_today'] ?? true); @endphp
            <div style="display:flex;gap:10px;margin-top:20px;flex-wrap:wrap;">
                @if($canUseAi)
                <a href="{{ route('agency.features.show', 'ai_search_ranking') }}?create_page=1" class="btn btn-accent">
                    <i class="fa fa-magic me-1"></i> Create AI Page for Listing
                </a>
                @else
                <span class="btn" style="background:#9ca3af;color:#fff;cursor:not-allowed;opacity:0.7;">
                    <i class="fa fa-magic me-1"></i> Create AI Page for Listing
                </span>
                @endif
                <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?add_listing=1" class="btn btn-outline-secondary">
                    <i class="fa fa-plus me-1"></i> Add Listing
                </a>
                <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?show_listings=1" class="btn btn-outline-secondary">
                    <i class="fa fa-home me-1"></i> Show Listings
                </a>
                <a href="{{ route('agency.features.show', 'ai_search_ranking') }}?add_agent=1" class="btn btn-outline-secondary">
                    <i class="fa fa-user-plus me-1"></i> Add Agent
                </a>
                <a href="{{ route('agency.features.show', 'ai_search_ranking') }}?show_agents=1" class="btn btn-outline-secondary">
                    <i class="fa fa-users me-1"></i> Show Agents
                </a>
                <a href="{{ route('agency.features.show', 'ai_search_ranking') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list me-1"></i> View All Pages
                </a>
            </div>
        </div>
    </div>

    {{-- USAGE LIMIT WARNING --}}
    @if(isset($usageLimitStatus) && !($usageLimitStatus['can_use_today'] ?? true))
    <div style="background:#fef3cd;border:1px solid #ffc107;border-radius:8px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <i class="fa fa-exclamation-triangle" style="color:#856404;"></i>
        <div>
            <strong style="color:#856404;">AI Usage Limit Reached</strong>
            <p style="margin:4px 0 0;font-size:13px;color:#856404;">
                Daily limit reached. Try again tomorrow or upgrade your plan.
            </p>
        </div>
    </div>
    @endif

    {{-- PAGES TABLE --}}
    @if(!request('create_page') && !request('edit_page_id'))
    <div class="card">
        <div class="card-header">
            <div>
                <h5><span class="step-circle">●</span>Your AI Search Pages</h5>
                <p style="margin:7px 0 0;color:var(--muted);font-size:14px;">AI-optimized pages for your property listings</p>
            </div>
        </div>
        <div class="card-body" style="padding:0;">
            @if($pages->count() > 0)
            <div class="table-wrap">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px">Use</th>
                            <th>Page Name</th>
                            <th>Property/Listing</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                        <tr>
                            <td>
                                <form action="{{ route('agency.ai-search-ranking.toggle', $page) }}" method="POST">
                                    @csrf
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" onchange="this.form.submit()"
                                               {{ $page->status === 'published' ? 'checked' : '' }}>
                                    </div>
                                </form>
                            </td>
                            <td><strong>{{ $page->name }}</strong></td>
                            <td>{{ $page->listing ? $page->listing->title : ($page->property_type ? ucfirst($page->property_type) : '—') }}</td>
                            <td>{{ $page->target_city ?? '—' }}{{ $page->country ? ', ' . $page->country : '' }}</td>
                            <td>
                                @if($page->status === 'published')
                                    <span class="badge badge-high">Published</span>
                                @else
                                    <span class="badge badge-low">Draft</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('agency.ai-search-ranking.generate', $page) }}" class="btn btn-sm btn-accent" title="Regenerate AI Content" onclick="return confirm('Regenerate AI content for this page?')">
                                    <i class="fa fa-magic"></i>
                                </a>
                                <a href="{{ route('agency.ai-search-ranking.preview', $page) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Preview</a>
                                <a href="{{ route('agency.ai-search-ranking.edit', $page) }}" class="btn btn-sm btn-dark">Edit</a>
                                <form action="{{ route('agency.ai-search-ranking.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this page?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <p class="text-muted mb-3">No AI Search pages yet. Create your first page for a property listing.</p>
                @if($canUseAi)
                <a href="{{ route('agency.features.show', 'ai_search_ranking') }}?create_page=1" class="btn btn-accent">
                    <i class="fa fa-magic me-1"></i> Create Your First AI Page
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- CREATE/EDIT PAGE FORM --}}
    @if(request('create_page') || $editPage)
    <div class="card">
        <div class="card-header">
            <div>
                <h5><span class="step-circle">1</span>{{ $editPage ? 'Edit AI Search Page' : 'Create AI Search Page for Listing' }}</h5>
                <p style="margin:7px 0 0;color:var(--muted);font-size:14px;">Select a property listing and AI will generate an optimized page for AI search engines.</p>
            </div>
            <span class="output-badge">OUTPUT → AI-optimized page</span>
        </div>
        <div class="card-body">
            <form action="{{ route('agency.ai-search-ranking.store') }}" method="POST" id="pageForm">
                @csrf
                <input type="hidden" name="page_id" value="{{ $editPage->id ?? '' }}">
                <input type="hidden" name="target_city" id="targetCity" value="{{ $editPage->target_city ?? '' }}">
                <input type="hidden" name="target_neighborhood" id="targetNeighborhood" value="{{ $editPage->target_neighborhood ?? '' }}">
                <input type="hidden" name="country" id="targetCountry" value="{{ $editPage->country ?? '' }}">
                <input type="hidden" name="latitude" id="targetLat" value="{{ $editPage->latitude ?? '' }}">
                <input type="hidden" name="longitude" id="targetLng" value="{{ $editPage->longitude ?? '' }}">

                <div class="row g-3">
                    {{-- Select Listing --}}
                    <div class="col-md-6">
                        <label class="form-label">Select Property Listing *</label>
                        <select name="listing_id" class="form-select" id="listingSelect">
                            <option value="">-- Select a listing --</option>
                            @if(isset($listings) && $listings->count() > 0)
                                @foreach($listings as $listing)
                                <option value="{{ $listing->id }}" 
                                    data-title="{{ $listing->title }}"
                                    data-city="{{ $listing->location ?? '' }}"
                                    data-country=""
                                    data-type="{{ $listing->property_type }}"
                                    {{ ($editPage && $editPage->listing_id == $listing->id) ? 'selected' : '' }}>
                                    {{ $listing->title }} — {{ $listing->location ?? 'No location' }}
                                </option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-muted">Choose from your existing listings. AI will write about this property.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Page Name *</label>
                        <input type="text" name="name" class="form-control" required
                               value="{{ $editPage->name ?? '' }}"
                               placeholder="E.g. Sunset Cliff Villa - Luxury Sea View Property">
                        <small class="text-muted">This will be the page title.</small>
                    </div>

                    <div class="col-md-6 position-relative">
                        <label class="form-label">Property Location * <span style="color:#6b7280;font-weight:400;">/ City / Area</span></label>
                        <input type="text" id="citySearch" class="form-control" autocomplete="off"
                               value="{{ $editPage ? trim(($editPage->target_neighborhood ? $editPage->target_neighborhood . ', ' : '') . ($editPage->target_city ?? '') . ($editPage->country ? ', ' . $editPage->country : '')) : '' }}"
                               placeholder="Start typing a city or area…">
                        <div id="citySuggestions" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000; display:none; max-height: 240px; overflow-y:auto;"></div>
                        <small class="text-muted">Location where the property is located.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Property Type</label>
                        <select name="property_type" class="form-select">
                            <option value="">Select type</option>
                            <option value="villa" {{ ($editPage->property_type ?? '') === 'villa' ? 'selected' : '' }}>Villa</option>
                            <option value="apartment" {{ ($editPage->property_type ?? '') === 'apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="house" {{ ($editPage->property_type ?? '') === 'house' ? 'selected' : '' }}>House</option>
                            <option value="land" {{ ($editPage->property_type ?? '') === 'land' ? 'selected' : '' }}>Land</option>
                            <option value="commercial" {{ ($editPage->property_type ?? '') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="luxury" {{ ($editPage->property_type ?? '') === 'luxury' ? 'selected' : '' }}>Luxury Property</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Property Description / Notes for AI</label>
                        <textarea name="positioning_note" class="form-control" rows="5"
                                  placeholder="Add details about the property that AI should emphasize: sea views, pool, proximity to beach, rental potential, unique features, etc.">{{ $editPage->positioning_note ?? '' }}</textarea>
                        <small class="text-muted">This helps AI write more specific and accurate content about your property.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Page URL Slug</label>
                        <input type="text" name="slug" class="form-control" 
                               value="{{ $editPage->slug ?? '' }}"
                               placeholder="auto-generated-from-name">
                        <small class="text-muted">Leave empty to auto-generate.</small>
                    </div>
                </div>

                <div class="actions-bar">
                    <a href="{{ route('agency.features.show', 'ai_search_ranking') }}" class="btn btn-outline-secondary">← Back to Pages</a>
                    <button type="submit" class="btn btn-accent">
                        <i class="fa fa-magic me-1"></i> {{ $editPage ? 'Save Changes' : 'Generate AI Page' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>

<script>
function toggleFeature(el) {
    fetch('{{ route("agency.local-seo.save-settings") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ feature: 'ai_search_ranking', is_enabled: el.checked ? 1 : 0 })
    }).then(() => location.reload());
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill from listing selection
    var listingSelect = document.getElementById('listingSelect');
    if (listingSelect) {
        listingSelect.addEventListener('change', function() {
            var opt = this.options[this.selectedIndex];
            if (!opt.value) return;
            
            // Get form elements
            var nameInput = document.querySelector('input[name="name"]');
            var cityInput = document.getElementById('citySearch');
            var typeSelect = document.querySelector('select[name="property_type"]');
            var targetCity = document.getElementById('targetCity');
            var targetCountry = document.getElementById('targetCountry');
            
            // Populate page name from listing title
            if (nameInput) {
                nameInput.value = opt.dataset.title || '';
            }
            
            // Populate location from listing
            if (cityInput) {
                var city = opt.dataset.city || '';
                var country = opt.dataset.country || '';
                cityInput.value = city + (country ? ', ' + country : '');
                if (targetCity) targetCity.value = city;
                if (targetCountry) targetCountry.value = country;
            }
            
            // Populate property type
            if (typeSelect && opt.dataset.type) {
                for (var i = 0; i < typeSelect.options.length; i++) {
                    if (typeSelect.options[i].value.toLowerCase() === opt.dataset.type.toLowerCase()) {
                        typeSelect.selectedIndex = i;
                        break;
                    }
                }
            }
        });
    }
    
    // Location autocomplete (OpenStreetMap Nominatim)
    var input = document.getElementById('citySearch');
    var box = document.getElementById('citySuggestions');
    if (input && box) {
        var timer = null;
        function hideBox() { box.style.display = 'none'; box.innerHTML = ''; }

        input.addEventListener('input', function() {
            var q = input.value.trim();
            clearTimeout(timer);
            if (q.length < 3) { hideBox(); return; }
            
            timer = setTimeout(function() {
                fetch('https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=8&q=' + encodeURIComponent(q), {
                    headers: { 'Accept': 'application/json' }
                })
                .then(function(r) { return r.json(); })
                .then(function(results) {
                    box.innerHTML = '';
                    if (!results || !results.length) { hideBox(); return; }
                    
                    results.slice(0, 6).forEach(function(item) {
                        var addr = item.address || {};
                        var country = addr.country || '';
                        var placeName = addr.road || addr.street || addr.pedestrian ||
                                        addr.neighbourhood || addr.suburb || addr.quarter || addr.city_district || 
                                        addr.city || addr.town || addr.village || addr.municipality || 
                                        item.display_name.split(',')[0];
                        var parentCity = addr.city || addr.town || addr.municipality || '';
                        var neighborhood = addr.neighbourhood || addr.suburb || addr.quarter || '';
                        
                        var fullName = placeName;
                        if ((addr.road || addr.street) && neighborhood && neighborhood !== placeName) {
                            fullName = placeName + ', ' + neighborhood;
                        }
                        if (parentCity && parentCity !== placeName && parentCity !== neighborhood) {
                            fullName = fullName + ', ' + parentCity;
                        }
                        
                        var a = document.createElement('button');
                        a.type = 'button';
                        a.className = 'list-group-item list-group-item-action';
                        a.textContent = item.display_name;
                        a.addEventListener('click', function() {
                            input.value = fullName + (country ? ', ' + country : '');
                            if (document.getElementById('targetCity')) document.getElementById('targetCity').value = parentCity || placeName;
                            if (document.getElementById('targetNeighborhood')) document.getElementById('targetNeighborhood').value = neighborhood;
                            if (document.getElementById('targetCountry')) document.getElementById('targetCountry').value = country;
                            if (document.getElementById('targetLat')) document.getElementById('targetLat').value = item.lat || '';
                            if (document.getElementById('targetLng')) document.getElementById('targetLng').value = item.lon || '';
                            hideBox();
                        });
                        box.appendChild(a);
                    });
                    box.style.display = 'block';
                })
                .catch(function(err) {
                    console.error('Location search error:', err);
                    hideBox();
                });
            }, 350);
        });

        document.addEventListener('click', function(e) {
            if (!box.contains(e.target) && e.target !== input) hideBox();
        });
    }

    // ---- Loader overlay for AI/long actions ----
    function showLoader(message) {
        var loader = document.getElementById('vbLoader');
        if (!loader) return;
        var msgEl = loader.querySelector('.message');
        if (message && msgEl) msgEl.innerHTML = message;
        loader.classList.add('active');
    }

    // Create / edit AI page form (triggers AI generation)
    var pageForm = document.getElementById('pageForm');
    if (pageForm) {
        pageForm.addEventListener('submit', function () {
            showLoader('<strong>Villa Bit AI</strong> is building your page…');
        });
    }

    // Regenerate AI content links in pages table
    document.querySelectorAll('a[href*="ai-search-ranking/generate"]').forEach(function (link) {
        link.addEventListener('click', function () {
            showLoader('<strong>Villa Bit AI</strong> is regenerating content…');
        });
    });
});
</script>

{{-- ============ ADD/EDIT AGENT FORM ============ --}}
@if(request('add_agent') || request('edit_agent'))
@php $editAgent = request('edit_agent') ? \App\Models\AgencyAgent::find(request('edit_agent')) : null; @endphp
<div class="card">
    <div class="card-header">
        <div>
            <a href="{{ route('agency.features.show', 'ai_search_ranking') }}" style="font-size:13px;color:var(--muted);text-decoration:none;display:inline-block;margin-bottom:8px;">← Back</a>
            <h5><span class="step-circle">●</span>{{ $editAgent ? 'Edit' : 'Add' }} Agent</h5>
            <p style="margin:7px 0 0;color:var(--muted);font-size:14px;">Agent details will appear in Trust + Expertise section</p>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ $editAgent ? route('agency.agents.update', $editAgent) : route('agency.agents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($editAgent) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Agent Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ $editAgent->name ?? '' }}" required placeholder="e.g. Marko Kovač">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Agency Name</label>
                    <input type="text" name="agency_name" class="form-control" value="{{ $editAgent->agency_name ?? $profile->agency_name ?? '' }}" placeholder="e.g. Adria Prime Estates">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Assigned to Listing</label>
                    <select name="agency_listing_id" class="form-select">
                        <option value="">— No specific listing (Global agent) —</option>
                        @foreach($profile->agencyListings as $listing)
                        <option value="{{ $listing->id }}" {{ ($editAgent->agency_listing_id ?? '') == $listing->id ? 'selected' : '' }}>
                            {{ $listing->title }} — {{ $listing->location }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $editAgent->email ?? '' }}" placeholder="e.g. marko@adriaprime.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ $editAgent->phone ?? '' }}" placeholder="e.g. +385 91 222 3344">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Tagline</label>
                    <input type="text" name="tagline" class="form-control" value="{{ $editAgent->tagline ?? '' }}" placeholder="e.g. Licensed Coastal Property Advisory">
                </div>
                <div class="col-md-4">
                    <label class="form-label">License</label>
                    <input type="text" name="license" class="form-control" value="{{ $editAgent->license ?? '' }}" placeholder="e.g. Licensed Croatia">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Rating (1-5)</label>
                    <input type="number" name="rating" class="form-control" value="{{ $editAgent->rating ?? '5.0' }}" min="1" max="5" step="0.1">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reviews Count</label>
                    <input type="number" name="reviews_count" class="form-control" value="{{ $editAgent->reviews_count ?? '0' }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Photo</label>
                    @if($editAgent && $editAgent->photo)
                    <div style="margin-bottom:8px;">
                        <img src="{{ asset('storage/' . $editAgent->photo) }}" style="width:60px;height:60px;object-fit:cover;border-radius:50%;border:1px solid #ddd;">
                    </div>
                    @endif
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label d-block">Primary Agent</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_primary" value="1" {{ ($editAgent->is_primary ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label">Set as primary agent for Trust section</label>
                    </div>
                </div>
            </div>
            <div class="actions-bar" style="margin-top:20px;">
                <a href="{{ route('agency.features.show', 'ai_search_ranking') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-accent">{{ $editAgent ? 'Update' : 'Add' }} Agent</button>
            </div>
        </form>
    </div>
</div>

{{-- Existing Agents List --}}
@if(request('show_agents') || request('add_agent') || request('edit_agent'))
<div class="card">
    <div class="card-header">
        <div>
            <a href="{{ route('agency.features.show', 'ai_search_ranking') }}" style="font-size:13px;color:var(--muted);text-decoration:none;display:inline-block;margin-bottom:8px;">← Back to Pages</a>
            <h5><span class="step-circle">●</span>Your Agents</h5>
            <p style="margin:7px 0 0;color:var(--muted);font-size:14px;">Agents appear in the Trust + Expertise section of AI pages</p>
        </div>
        <a href="{{ route('agency.features.show', 'ai_search_ranking') }}?add_agent=1" class="btn btn-accent">
            <i class="fa fa-user-plus me-1"></i> Add Agent
        </a>
    </div>
    <div class="card-body" style="padding:0;">
        @if($profile->agents->count() > 0)
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="width:60px">Photo</th>
                    <th>Name</th>
                    <th>Agency</th>
                    <th>Contact</th>
                    <th>Rating</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($profile->agents as $agent)
                <tr>
                    <td>
                        @if($agent->photo)
                        <img src="{{ asset('storage/' . $agent->photo) }}" style="width:40px;height:40px;object-fit:cover;border-radius:50%;">
                        @else
                        <div style="width:40px;height:40px;border-radius:50%;background:#e5e7eb;display:grid;place-items:center;">👤</div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $agent->name }}</strong>
                        @if($agent->is_primary)<span class="badge bg-success ms-1">Primary</span>@endif
                    </td>
                    <td>{{ $agent->agency_name ?? '—' }}</td>
                    <td>
                        @if($agent->email)<div style="font-size:12px;">{{ $agent->email }}</div>@endif
                        @if($agent->phone)<div style="font-size:12px;">{{ $agent->phone }}</div>@endif
                    </td>
                    <td>★ {{ $agent->rating }} ({{ $agent->reviews_count }})</td>
                    <td class="text-end">
                        <a href="{{ route('agency.features.show', 'ai_search_ranking') }}?edit_agent={{ $agent->id }}" class="btn btn-sm btn-dark">Edit</a>
                        <form action="{{ route('agency.agents.destroy', $agent) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this agent?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center py-5">
            <p class="text-muted mb-3">No agents yet. Add your first agent to display in AI pages.</p>
            <a href="{{ route('agency.features.show', 'ai_search_ranking') }}?add_agent=1" class="btn btn-accent">
                <i class="fa fa-user-plus me-1"></i> Add Your First Agent
            </a>
        </div>
        @endif
    </div>
</div>
@endif
@endif
@endsection
