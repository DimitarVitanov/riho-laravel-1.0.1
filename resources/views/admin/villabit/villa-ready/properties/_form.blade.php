@php
    $isEdit = isset($property) && $property;
    $action = $isEdit 
        ? route('admin.villabit.villa-ready.properties.update', $property) 
        : route('admin.villabit.villa-ready.properties.store');
    $content = $isEdit ? $property->content : null;
@endphp

<style>
.vrc-section-title{font-size:18px;font-weight:800;margin:0 0 4px}
.vrc-help{color:#6c757d;font-size:12px;margin:0}
.vrc-card-head{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap}
.vrc-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.vrc-grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.vrc-grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.vrc-field{min-width:0}
.vrc-field label{display:block;font-weight:700;font-size:12px;margin-bottom:7px}
.vrc-field input,.vrc-field textarea,.vrc-field select{width:100%;border:1px solid #dee2e6;border-radius:8px;padding:11px 12px;background:#fff}
.vrc-field textarea{min-height:105px;resize:vertical}
.vrc-field small{display:block;color:#6c757d;font-size:11px;margin-top:5px}
.vrc-editor-card{scroll-margin-top:90px}
.vrc-section-nav{position:sticky;top:74px;z-index:25;background:rgba(255,255,255,.97);border:1px solid #e5e7eb;border-radius:12px;padding:10px;margin-bottom:18px;display:flex;gap:8px;overflow:auto;box-shadow:0 8px 20px rgba(0,0,0,.05)}
.vrc-section-nav a{white-space:nowrap;padding:8px 11px;border-radius:999px;background:#f3f4f6;color:#111827;font-size:11px;font-weight:800;text-decoration:none}
.vrc-section-nav a:hover{background:#e5e7eb}
.vrc-media-editor{display:grid;grid-template-columns:180px 1fr;gap:16px;padding:15px;border:1px solid #e5e7eb;border-radius:12px;margin-bottom:14px;background:#fafafa}
.vrc-media-preview{width:180px;height:130px;object-fit:cover;border-radius:9px;border:1px solid #ddd}
.vrc-media-fields{min-width:0}
.vrc-repeat-card{border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:14px;background:#fafafa}
.vrc-repeat-card h6{font-weight:800;margin-bottom:12px}
.vrc-sticky-actions{position:sticky;bottom:0;background:#fff;border-top:1px solid #e5e7eb;padding:14px;z-index:30;display:flex;justify-content:flex-end;gap:10px;box-shadow:0 -8px 22px rgba(0,0,0,.06)}
.vrc-status{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:999px;font-size:11px;font-weight:800;background:#e0f2fe;color:#0369a1}
.vrc-note{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px;font-size:12px;color:#166534}
.vrc-danger-note{background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px;font-size:12px;color:#991b1b}
.vrc-collapse{font-size:11px}
.vrc-card-body.collapsed{display:none}
@media(max-width:900px){.vrc-grid-2,.vrc-grid-3,.vrc-grid-4{grid-template-columns:1fr}.vrc-media-editor{grid-template-columns:1fr}.vrc-media-preview{width:100%;height:180px}}
</style>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Section Navigation --}}
<nav class="vrc-section-nav">
    <a href="#publication">Publication</a>
    <a href="#hero">Hero</a>
    <a href="#media">Media Library</a>
    <a href="#location-value">Location Value</a>
    <a href="#chain-location">Chain Location</a>
    <a href="#sea-map">Sea & Map</a>
    <a href="#access">Access</a>
    <a href="#pricing">Pricing</a>
    <a href="#tax">Tax & VAT</a>
    <a href="#contact">Contact</a>
    <a href="#seo">SEO</a>
</nav>

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="propertyForm">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- Property Publication Settings --}}
    <section class="card vrc-editor-card" id="publication">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Property Publication Settings</h5>
                <p class="vrc-help">Create one fixed property record that can be published inside selected agency website designs.</p>
            </div>
            @if($isEdit)
            <span class="vrc-status">Property ID: {{ $property->property_id }}</span>
            @endif
        </div>
        <div class="card-body vrc-card-body">
            <div class="vrc-grid-3">
                <div class="vrc-field">
                    <label for="property_id">Internal Property ID *</label>
                    <input type="text" id="property_id" name="property_id" value="{{ old('property_id', $property->property_id ?? 'VRC-'.strtoupper(Str::random(6))) }}" required>
                </div>
                <div class="vrc-field">
                    <label for="status">Property Status *</label>
                    <select id="status" name="status" required>
                        <option value="draft" {{ old('status', $property->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $property->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="reserved" {{ old('status', $property->status ?? '') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="sold" {{ old('status', $property->status ?? '') == 'sold' ? 'selected' : '' }}>Sold</option>
                    </select>
                </div>
                <div class="vrc-field">
                    <label for="commission_percent">Affiliate Commission (%)</label>
                    <input type="number" id="commission_percent" name="commission_percent" step="0.01" value="{{ old('commission_percent', $property->commission_percent ?? 6) }}">
                </div>
            </div>
            <div class="vrc-grid-3 mt-3">
                <div class="vrc-field">
                    <label for="cookie_duration_days">Affiliate Cookie Duration (days)</label>
                    <input type="number" id="cookie_duration_days" name="cookie_duration_days" value="{{ old('cookie_duration_days', $property->cookie_duration_days ?? 180) }}">
                </div>
                <div class="vrc-field">
                    <label for="source_url">Original Source Page</label>
                    <input type="url" id="source_url" name="source_url" value="{{ old('source_url', $property->source_url ?? '') }}" placeholder="https://villareadycroatia.com/villas-for-sale.php">
                </div>
                <div class="vrc-field">
                    <label for="agency_can_edit">Agency Content Editing</label>
                    <select id="agency_can_edit" name="agency_can_edit">
                        <option value="0" {{ old('agency_can_edit', $property->agency_can_edit ?? 0) == 0 ? 'selected' : '' }}>Locked — agency cannot edit</option>
                        <option value="1" {{ old('agency_can_edit', $property->agency_can_edit ?? 0) == 1 ? 'selected' : '' }}>Unlocked — agency can edit</option>
                    </select>
                </div>
            </div>
            <div class="vrc-note mt-3">Agencies should sell this property as part of their own website, while all supplied property text, images, prices and legal information remain controlled by Villa Ready Croatia admin.</div>
        </div>
    </section>

    {{-- Main Text and SEO Fields --}}
    <section class="card vrc-editor-card mt-3">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Main Text And SEO Fields</h5>
                <p class="vrc-help">Admin manually inserts and controls every text shown on the agency property page.</p>
            </div>
        </div>
        <div class="card-body vrc-card-body">
            <div class="vrc-grid-2">
                <div class="vrc-field">
                    <label for="title">Property Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $property->title ?? '') }}" required>
                </div>
                <div class="vrc-field">
                    <label for="short_title">Short Box Title</label>
                    <input type="text" id="short_title" name="short_title" value="{{ old('short_title', $property->short_title ?? '') }}">
                </div>
            </div>
            <div class="vrc-grid-2 mt-3">
                <div class="vrc-field">
                    <label for="location">Location *</label>
                    <input type="text" id="location" name="location" value="{{ old('location', $property->location ?? '') }}" required>
                </div>
                <div class="vrc-field">
                    <label for="address">Address / Micro-location</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $property->address ?? '') }}">
                </div>
            </div>
            <div class="vrc-grid-2 mt-3">
                <div class="vrc-field">
                    <label for="property_type">Property Type</label>
                    <input type="text" id="property_type" name="property_type" value="{{ old('property_type', $property->property_type ?? '') }}" placeholder="Villa Development">
                </div>
                <div class="vrc-field">
                    <label for="price_display">Price Display</label>
                    <input type="text" id="price_display" name="price_display" value="{{ old('price_display', $property->price_display ?? '') }}" placeholder="From €590,000">
                </div>
            </div>
            <div class="vrc-field mt-3">
                <label for="intro">Short Introduction</label>
                <textarea id="intro" name="intro" rows="3">{{ old('intro', $property->intro ?? '') }}</textarea>
            </div>
            <div class="vrc-field mt-3">
                <label for="description">Full Description</label>
                <textarea id="description" name="description" rows="5">{{ old('description', $property->description ?? '') }}</textarea>
            </div>
        </div>
    </section>

    {{-- Hero and 360° Section --}}
    <section class="card vrc-editor-card mt-3" id="hero">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Hero and 360° Section</h5>
                <p class="vrc-help">All hero text, labels, media and 360° link fields.</p>
            </div>
            <button type="button" class="btn btn-light btn-sm vrc-collapse" onclick="toggleCard(this)">Collapse</button>
        </div>
        <div class="card-body vrc-card-body">
            <div class="vrc-grid-2">
                <div class="vrc-field">
                    <label for="hero_eyebrow">Eyebrow</label>
                    <input type="text" id="hero_eyebrow" name="hero_eyebrow" value="{{ old('hero_eyebrow', $content->hero_eyebrow ?? '360° · Drone View · Milna · Island of Brač') }}">
                </div>
                <div class="vrc-field">
                    <label for="hero_title">Main Heading</label>
                    <input type="text" id="hero_title" name="hero_title" value="{{ old('hero_title', $content->hero_title ?? '') }}">
                </div>
            </div>
            <div class="vrc-grid-2 mt-3">
                <div class="vrc-field">
                    <label for="hero_subtitle">Main Subtitle</label>
                    <textarea id="hero_subtitle" name="hero_subtitle" rows="2">{{ old('hero_subtitle', $content->hero_subtitle ?? '') }}</textarea>
                </div>
                <div class="vrc-field">
                    <label for="hero_360_label">360° Button Label</label>
                    <input type="text" id="hero_360_label" name="hero_360_label" value="{{ old('hero_360_label', $content->hero_360_label ?? 'WATCH A 360° DRONE VIEW FROM THE SKY') }}">
                </div>
            </div>
            <div class="vrc-grid-2 mt-3">
                <div class="vrc-field">
                    <label for="hero_360_url">360° Media URL</label>
                    <input type="url" id="hero_360_url" name="hero_360_url" value="{{ old('hero_360_url', $content->hero_360_url ?? '') }}">
                    <small>Paste the hosted 360° tour or drone panorama URL.</small>
                </div>
                <div class="vrc-field">
                    <label for="hero_location_label">Hero Location Label</label>
                    <input type="text" id="hero_location_label" name="hero_location_label" value="{{ old('hero_location_label', $content->hero_location_label ?? '') }}">
                </div>
            </div>
        </div>
    </section>

    {{-- Complete Image and Media Library --}}
    <section class="card vrc-editor-card mt-3" id="media">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Complete Image and Media Library</h5>
                <p class="vrc-help">All source images, drone images, map, plan, 360° and decorative assets.</p>
            </div>
            <button type="button" class="btn btn-light btn-sm vrc-collapse" onclick="toggleCard(this)">Collapse</button>
        </div>
        <div class="card-body vrc-card-body">
            {{-- Featured Image --}}
            <div class="vrc-repeat-card">
                <h6>Featured Image</h6>
                @if($isEdit && $property->featured_image)
                <div class="vrc-media-editor">
                    <img src="{{ asset('storage/' . $property->featured_image) }}" alt="Featured" class="vrc-media-preview">
                    <div class="vrc-media-fields">
                        <div class="vrc-field">
                            <label>Current featured image</label>
                            <input type="text" value="{{ $property->featured_image }}" readonly>
                        </div>
                        <div class="vrc-field mt-2">
                            <label for="featured_image">Upload replacement</label>
                            <input type="file" id="featured_image" name="featured_image" accept="image/*">
                        </div>
                    </div>
                </div>
                @else
                <div class="vrc-field">
                    <label for="featured_image">Upload featured image</label>
                    <input type="file" id="featured_image" name="featured_image" accept="image/*">
                </div>
                @endif
            </div>

            {{-- Existing Images --}}
            @if($isEdit && $property->images->count())
            <div class="vrc-repeat-card">
                <h6>Existing Images ({{ $property->images->count() }})</h6>
                @foreach($property->images as $image)
                <div class="vrc-media-editor">
                    <img src="{{ $image->image_url }}" alt="{{ $image->caption ?? 'Property image' }}" class="vrc-media-preview">
                    <div class="vrc-media-fields">
                        <div class="vrc-grid-3">
                            <div class="vrc-field">
                                <label>Type</label>
                                <input type="text" value="{{ $image->image_type }}" readonly>
                            </div>
                            <div class="vrc-field">
                                <label>Caption</label>
                                <input type="text" value="{{ $image->caption ?? '' }}" readonly>
                            </div>
                            <div class="vrc-field">
                                <label>Delete</label>
                                <label class="d-flex align-items-center gap-2 mt-2">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"> Mark for deletion
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Add New Images --}}
            <div class="vrc-repeat-card">
                <h6>Add New Images</h6>
                <div id="newImageUploads">
                    <div class="vrc-media-editor" style="grid-template-columns:1fr">
                        <div class="vrc-media-fields">
                            <div class="vrc-grid-3">
                                <div class="vrc-field">
                                    <label>Upload File</label>
                                    <input type="file" name="gallery_images[]" accept="image/*">
                                </div>
                                <div class="vrc-field">
                                    <label>Image Type</label>
                                    <select name="gallery_types[]">
                                        <option value="main">Main / Hero image</option>
                                        <option value="gallery" selected>Gallery image</option>
                                        <option value="drone">Drone view</option>
                                        <option value="360">360° view</option>
                                        <option value="map">Map / Location</option>
                                        <option value="floor_plan">Floor plan</option>
                                        <option value="aerial">Aerial perspective</option>
                                        <option value="concept">Concept / Render</option>
                                        <option value="sea_view">Sea view</option>
                                    </select>
                                </div>
                                <div class="vrc-field">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.vrc-media-editor').remove()">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addImageUpload()">+ Add Another Image</button>
            </div>

            {{-- Add Images by URL --}}
            <div class="vrc-repeat-card">
                <h6>Add Images by URL</h6>
                <div id="newImageUrls">
                    <div class="vrc-media-editor" style="grid-template-columns:1fr">
                        <div class="vrc-media-fields">
                            <div class="vrc-grid-3">
                                <div class="vrc-field">
                                    <label>Image URL</label>
                                    <input type="url" name="image_urls[]" placeholder="https://...">
                                </div>
                                <div class="vrc-field">
                                    <label>Image Type</label>
                                    <select name="image_url_types[]">
                                        <option value="main">Main / Hero image</option>
                                        <option value="gallery" selected>Gallery image</option>
                                        <option value="drone">Drone view</option>
                                        <option value="360">360° view</option>
                                        <option value="map">Map / Location</option>
                                        <option value="floor_plan">Floor plan</option>
                                        <option value="aerial">Aerial perspective</option>
                                        <option value="concept">Concept / Render</option>
                                        <option value="sea_view">Sea view</option>
                                    </select>
                                </div>
                                <div class="vrc-field">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.vrc-media-editor').remove()">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addImageUrl()">+ Add Another URL</button>
            </div>
        </div>
    </section>

    {{-- Location Value Section --}}
    <section class="card vrc-editor-card mt-3" id="location-value">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Understand Location Value</h5>
                <p class="vrc-help">All original location-value paragraphs and related link fields.</p>
            </div>
            <button type="button" class="btn btn-light btn-sm vrc-collapse" onclick="toggleCard(this)">Collapse</button>
        </div>
        <div class="card-body vrc-card-body">
            <div class="vrc-grid-2">
                <div class="vrc-field">
                    <label for="location_value_title">Section Heading</label>
                    <input type="text" id="location_value_title" name="location_value_title" value="{{ old('location_value_title', $content->location_value_title ?? 'UNDERSTAND LOCATION VALUE') }}">
                </div>
                <div class="vrc-field">
                    <label for="location_value_subtitle">Section Subtitle</label>
                    <input type="text" id="location_value_subtitle" name="location_value_subtitle" value="{{ old('location_value_subtitle', $content->location_value_subtitle ?? '') }}">
                </div>
            </div>
            <div class="vrc-field mt-3">
                <label for="location_description">Location Description</label>
                <textarea id="location_description" name="location_description" rows="6">{{ old('location_description', $property->location_description ?? '') }}</textarea>
            </div>
        </div>
    </section>

    {{-- Chain Location Section --}}
    <section class="card vrc-editor-card mt-3" id="chain-location">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">The 4-Villa Chain Location</h5>
                <p class="vrc-help">All original location paragraphs and primary location image.</p>
            </div>
            <button type="button" class="btn btn-light btn-sm vrc-collapse" onclick="toggleCard(this)">Collapse</button>
        </div>
        <div class="card-body vrc-card-body">
            <div class="vrc-grid-2">
                <div class="vrc-field">
                    <label for="chain_title">Section Heading</label>
                    <input type="text" id="chain_title" name="chain_title" value="{{ old('chain_title', $content->chain_title ?? 'THE 4-VILLA CHAIN LOCATION') }}">
                </div>
                <div class="vrc-field">
                    <label for="chain_subtitle">Section Subtitle</label>
                    <input type="text" id="chain_subtitle" name="chain_subtitle" value="{{ old('chain_subtitle', $content->chain_subtitle ?? '') }}">
                </div>
            </div>
            <div class="vrc-field mt-3">
                <label for="chain_description">Chain Location Description</label>
                <textarea id="chain_description" name="chain_description" rows="5">{{ old('chain_description', $content->chain_description ?? '') }}</textarea>
            </div>
        </div>
    </section>

    {{-- Sea View, Map and Project Counters --}}
    <section class="card vrc-editor-card mt-3" id="sea-map">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Sea View, Map and Project Counters</h5>
                <p class="vrc-help">Complete sea-view text, map information and project totals.</p>
            </div>
            <button type="button" class="btn btn-light btn-sm vrc-collapse" onclick="toggleCard(this)">Collapse</button>
        </div>
        <div class="card-body vrc-card-body">
            <div class="vrc-repeat-card">
                <h6>Sea View Section</h6>
                <div class="vrc-grid-2">
                    <div class="vrc-field">
                        <label for="sea_title">Heading</label>
                        <input type="text" id="sea_title" name="sea_title" value="{{ old('sea_title', $content->sea_title ?? 'SEA VIEW FROM THE LOCATION') }}">
                    </div>
                    <div class="vrc-field">
                        <label for="sea_subtitle">Subtitle</label>
                        <input type="text" id="sea_subtitle" name="sea_subtitle" value="{{ old('sea_subtitle', $content->sea_subtitle ?? '') }}">
                    </div>
                </div>
                <div class="vrc-field mt-3">
                    <label for="sea_description">Description</label>
                    <textarea id="sea_description" name="sea_description" rows="3">{{ old('sea_description', $content->sea_description ?? '') }}</textarea>
                </div>
            </div>
            <div class="vrc-repeat-card">
                <h6>Map View Section</h6>
                <div class="vrc-grid-2">
                    <div class="vrc-field">
                        <label for="map_title">Heading</label>
                        <input type="text" id="map_title" name="map_title" value="{{ old('map_title', $content->map_title ?? 'MAP VIEW OF THE LOCATION') }}">
                    </div>
                    <div class="vrc-field">
                        <label for="map_subtitle">Subtitle</label>
                        <input type="text" id="map_subtitle" name="map_subtitle" value="{{ old('map_subtitle', $content->map_subtitle ?? '') }}">
                    </div>
                </div>
                <div class="vrc-field mt-3">
                    <label for="map_description">Description</label>
                    <textarea id="map_description" name="map_description" rows="3">{{ old('map_description', $content->map_description ?? '') }}</textarea>
                </div>
            </div>
            <div class="vrc-repeat-card">
                <h6>Project Counters</h6>
                <div class="vrc-grid-4">
                    <div class="vrc-field">
                        <label for="total_area">Total Area (m²)</label>
                        <input type="text" id="total_area" name="total_area" value="{{ old('total_area', $content->total_area ?? '4,283') }}">
                    </div>
                    <div class="vrc-field">
                        <label for="plots_count">Plots</label>
                        <input type="text" id="plots_count" name="plots_count" value="{{ old('plots_count', $content->plots_count ?? '07') }}">
                    </div>
                    <div class="vrc-field">
                        <label for="villas_count">Villas</label>
                        <input type="text" id="villas_count" name="villas_count" value="{{ old('villas_count', $content->villas_count ?? '04') }}">
                    </div>
                    <div class="vrc-field">
                        <label for="apartments_count">Apartments</label>
                        <input type="text" id="apartments_count" name="apartments_count" value="{{ old('apartments_count', $content->apartments_count ?? '24') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Land Access Section --}}
    <section class="card vrc-editor-card mt-3" id="access">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Land Access & Infrastructure</h5>
                <p class="vrc-help">Connectivity, privacy and the access routes serving the project.</p>
            </div>
            <button type="button" class="btn btn-light btn-sm vrc-collapse" onclick="toggleCard(this)">Collapse</button>
        </div>
        <div class="card-body vrc-card-body">
            <div class="vrc-grid-2">
                <div class="vrc-field">
                    <label for="access_title">Section Heading</label>
                    <input type="text" id="access_title" name="access_title" value="{{ old('access_title', $content->access_title ?? 'LAND ACCESS & INFRASTRUCTURE OVERVIEW') }}">
                </div>
                <div class="vrc-field">
                    <label for="access_subtitle">Section Subtitle</label>
                    <input type="text" id="access_subtitle" name="access_subtitle" value="{{ old('access_subtitle', $content->access_subtitle ?? '') }}">
                </div>
            </div>
            <div class="vrc-field mt-3">
                <label for="access_intro">Introduction Text</label>
                <textarea id="access_intro" name="access_intro" rows="3">{{ old('access_intro', $content->access_intro ?? '') }}</textarea>
            </div>
            <div class="vrc-field mt-3">
                <label for="access_cards_json">Access Cards (JSON)</label>
                <textarea id="access_cards_json" name="access_cards_json" rows="6" placeholder='[{"title":"MAIN ACCESS ROAD","description":"..."},{"title":"SUPPORTING ACCESS ROAD","description":"..."}]'>{{ old('access_cards_json', $content->access_cards ? json_encode($content->access_cards, JSON_PRETTY_PRINT) : '') }}</textarea>
                <small>Enter as JSON array with title and description for each access card.</small>
            </div>
        </div>
    </section>

    {{-- Pricing Section --}}
    <section class="card vrc-editor-card mt-3" id="pricing">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Complete Pricing and Building Structure</h5>
                <p class="vrc-help">Payment terms, discounts, and building details.</p>
            </div>
            <button type="button" class="btn btn-light btn-sm vrc-collapse" onclick="toggleCard(this)">Collapse</button>
        </div>
        <div class="card-body vrc-card-body">
            <div class="vrc-grid-3">
                <div class="vrc-field">
                    <label for="buildings_count">Number of Buildings</label>
                    <input type="number" id="buildings_count" name="buildings_count" value="{{ old('buildings_count', $property->buildings_count ?? 4) }}">
                </div>
                <div class="vrc-field">
                    <label for="structure">Building Structure</label>
                    <input type="text" id="structure" name="structure" value="{{ old('structure', $property->structure ?? 'Basement + Ground Floor + 1st Floor + Attic') }}">
                </div>
                <div class="vrc-field">
                    <label for="price_per_m2">Price per m² (€)</label>
                    <input type="number" id="price_per_m2" name="price_per_m2" step="0.01" value="{{ old('price_per_m2', $property->price_per_m2 ?? 5900) }}">
                </div>
            </div>
            <div class="vrc-field mt-3">
                <label for="pricing_payment_text">Payment Terms Text</label>
                <textarea id="pricing_payment_text" name="pricing_payment_text" rows="3">{{ old('pricing_payment_text', $content->pricing_payment_text ?? '') }}</textarea>
            </div>
            <div class="vrc-field mt-3">
                <label for="buildings_data_json">Buildings Data (JSON)</label>
                <textarea id="buildings_data_json" name="buildings_data_json" rows="8" placeholder='[{"name":"BUILDING 1","gross_area":"885 m²","net_area":"664 m²","floors":[...]}]'>{{ old('buildings_data_json', $content->buildings_data ? json_encode($content->buildings_data, JSON_PRETTY_PRINT) : '') }}</textarea>
                <small>Enter detailed building data as JSON array.</small>
            </div>
        </div>
    </section>

    {{-- Tax and VAT Section --}}
    <section class="card vrc-editor-card mt-3" id="tax">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Pricing, VAT and Ownership Information</h5>
                <p class="vrc-help">Complete VAT and ownership information for buyers.</p>
            </div>
            <button type="button" class="btn btn-light btn-sm vrc-collapse" onclick="toggleCard(this)">Collapse</button>
        </div>
        <div class="card-body vrc-card-body">
            <div class="vrc-field">
                <label for="tax_intro">Introduction Text</label>
                <textarea id="tax_intro" name="tax_intro" rows="3">{{ old('tax_intro', $content->tax_intro ?? '') }}</textarea>
            </div>
            <div class="vrc-field mt-3">
                <label for="non_eu_note">Non-EU Citizen Note</label>
                <textarea id="non_eu_note" name="non_eu_note" rows="2">{{ old('non_eu_note', $content->non_eu_note ?? '') }}</textarea>
            </div>
            <div class="vrc-field mt-3">
                <label for="tax_groups_json">Tax Information Groups (JSON)</label>
                <textarea id="tax_groups_json" name="tax_groups_json" rows="6" placeholder='[{"title":"VAT on New Construction","items":["25% VAT applies...","..."]}]'>{{ old('tax_groups_json', $content->tax_groups ? json_encode($content->tax_groups, JSON_PRETTY_PRINT) : '') }}</textarea>
                <small>Enter as JSON array with title and items array for each group.</small>
            </div>
        </div>
    </section>

    {{-- Contact Form Section --}}
    <section class="card vrc-editor-card mt-3" id="contact">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Agency Contact Form Settings</h5>
                <p class="vrc-help">Contact form configuration for the property page.</p>
            </div>
            <button type="button" class="btn btn-light btn-sm vrc-collapse" onclick="toggleCard(this)">Collapse</button>
        </div>
        <div class="card-body vrc-card-body">
            <div class="vrc-grid-2">
                <div class="vrc-field">
                    <label for="contact_form_title">Form Heading</label>
                    <input type="text" id="contact_form_title" name="contact_form_title" value="{{ old('contact_form_title', $content->contact_form_title ?? 'CONTACT AGENCY') }}">
                </div>
                <div class="vrc-field">
                    <label for="contact_form_subtitle">Form Subtitle</label>
                    <input type="text" id="contact_form_subtitle" name="contact_form_subtitle" value="{{ old('contact_form_subtitle', $content->contact_form_subtitle ?? '') }}">
                </div>
            </div>
            <div class="vrc-repeat-card mt-3">
                <h6>Sidebar Price Panel</h6>
                <div class="vrc-grid-3">
                    <div class="vrc-field">
                        <label for="sidebar_price_label">Price Label</label>
                        <input type="text" id="sidebar_price_label" name="sidebar_price_label" value="{{ old('sidebar_price_label', $content->sidebar_price_label ?? 'From') }}">
                    </div>
                    <div class="vrc-field">
                        <label for="sidebar_price_value">Price Value</label>
                        <input type="text" id="sidebar_price_value" name="sidebar_price_value" value="{{ old('sidebar_price_value', $content->sidebar_price_value ?? '€590,000') }}">
                    </div>
                    <div class="vrc-field">
                        <label for="sidebar_price_note">Price Note</label>
                        <input type="text" id="sidebar_price_note" name="sidebar_price_note" value="{{ old('sidebar_price_note', $content->sidebar_price_note ?? '') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SEO Section --}}
    <section class="card vrc-editor-card mt-3" id="seo">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">SEO, Open Graph and Structured Data</h5>
                <p class="vrc-help">Search engine optimization and social sharing settings.</p>
            </div>
            <button type="button" class="btn btn-light btn-sm vrc-collapse" onclick="toggleCard(this)">Collapse</button>
        </div>
        <div class="card-body vrc-card-body">
            <div class="vrc-grid-2">
                <div class="vrc-field">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $property->meta_title ?? '') }}">
                </div>
                <div class="vrc-field">
                    <label for="meta_description">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $property->meta_description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </section>

    {{-- Sticky Actions --}}
    <div class="vrc-sticky-actions">
        <a href="{{ route('admin.villabit.villa-ready.properties.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Property' : 'Create Property' }}</button>
    </div>
</form>

<script>
function toggleCard(btn) {
    const body = btn.closest('.card').querySelector('.vrc-card-body');
    body.classList.toggle('collapsed');
    btn.textContent = body.classList.contains('collapsed') ? 'Expand' : 'Collapse';
}

function addImageUpload() {
    const container = document.getElementById('newImageUploads');
    const html = `
    <div class="vrc-media-editor" style="grid-template-columns:1fr">
        <div class="vrc-media-fields">
            <div class="vrc-grid-3">
                <div class="vrc-field">
                    <label>Upload File</label>
                    <input type="file" name="gallery_images[]" accept="image/*">
                </div>
                <div class="vrc-field">
                    <label>Image Type</label>
                    <select name="gallery_types[]">
                        <option value="main">Main / Hero image</option>
                        <option value="gallery" selected>Gallery image</option>
                        <option value="drone">Drone view</option>
                        <option value="360">360° view</option>
                        <option value="map">Map / Location</option>
                        <option value="floor_plan">Floor plan</option>
                        <option value="aerial">Aerial perspective</option>
                        <option value="concept">Concept / Render</option>
                        <option value="sea_view">Sea view</option>
                    </select>
                </div>
                <div class="vrc-field">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.vrc-media-editor').remove()">Remove</button>
                </div>
            </div>
        </div>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
}

function addImageUrl() {
    const container = document.getElementById('newImageUrls');
    const html = `
    <div class="vrc-media-editor" style="grid-template-columns:1fr">
        <div class="vrc-media-fields">
            <div class="vrc-grid-3">
                <div class="vrc-field">
                    <label>Image URL</label>
                    <input type="url" name="image_urls[]" placeholder="https://...">
                </div>
                <div class="vrc-field">
                    <label>Image Type</label>
                    <select name="image_url_types[]">
                        <option value="main">Main / Hero image</option>
                        <option value="gallery" selected>Gallery image</option>
                        <option value="drone">Drone view</option>
                        <option value="360">360° view</option>
                        <option value="map">Map / Location</option>
                        <option value="floor_plan">Floor plan</option>
                        <option value="aerial">Aerial perspective</option>
                        <option value="concept">Concept / Render</option>
                        <option value="sea_view">Sea view</option>
                    </select>
                </div>
                <div class="vrc-field">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.vrc-media-editor').remove()">Remove</button>
                </div>
            </div>
        </div>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
}
</script>
