@php
    $isEdit = isset($property) && $property;
    $action = $isEdit 
        ? route('admin.villabit.villa-ready.properties.update', $property) 
        : route('admin.villabit.villa-ready.properties.store');
@endphp

<style>
.vrc-section-title{font-size:18px;font-weight:800;margin:0 0 4px}
.vrc-help{color:#6c757d;font-size:12px;margin:0}
.vrc-card-head{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap}
.vrc-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.vrc-grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.vrc-field label{display:block;font-weight:700;font-size:12px;margin-bottom:7px}
.vrc-field input,.vrc-field textarea,.vrc-field select{width:100%;border:1px solid #dee2e6;border-radius:8px;padding:11px 12px;background:#fff}
.vrc-field textarea{min-height:110px;resize:vertical}
.vrc-image-row{display:flex;gap:12px;align-items:center;margin-bottom:12px;flex-wrap:wrap}
.vrc-image-row .vrc-thumb{flex-shrink:0}
.vrc-image-row input[type="file"],.vrc-image-row input[type="url"]{flex:1;min-width:200px}
.vrc-image-row select{width:160px}
.vrc-image-row span{font-size:13px;color:#6c757d}
.vrc-image-row label{white-space:nowrap}
.vrc-thumb{width:100px;height:70px;object-fit:cover;border-radius:8px;border:1px solid #ddd}
.vrc-sticky-actions{position:sticky;bottom:0;background:#fff;border-top:1px solid #e5e7eb;padding:14px;z-index:20;display:flex;justify-content:flex-end;gap:10px}
@media(max-width:900px){.vrc-grid-2,.vrc-grid-3{grid-template-columns:1fr}.vrc-image-row{grid-template-columns:80px 1fr}}
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

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="propertyForm">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- Publication Settings --}}
    <div class="card">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Property Publication Settings</h5>
                <p class="vrc-help">Create one fixed property record that can be published inside selected agency website designs.</p>
            </div>
            @if($isEdit)
            <span class="badge bg-light text-dark">Property ID: {{ $property->property_id }}</span>
            @endif
        </div>
        <div class="card-body">
            <div class="vrc-grid-3">
                <div class="vrc-field">
                    <label>Internal Property ID *</label>
                    <input type="text" name="property_id" value="{{ old('property_id', $property->property_id ?? 'VRC-') }}" required>
                </div>
                <div class="vrc-field">
                    <label>Property Status *</label>
                    <select name="status" required>
                        <option value="draft" @selected(old('status', $property->status ?? '') === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $property->status ?? '') === 'published')>Published</option>
                        <option value="reserved" @selected(old('status', $property->status ?? '') === 'reserved')>Reserved</option>
                        <option value="sold" @selected(old('status', $property->status ?? '') === 'sold')>Sold</option>
                    </select>
                </div>
                <div class="vrc-field">
                    <label>Affiliate Commission (%)</label>
                    <input type="number" name="commission_percent" step="0.01" value="{{ old('commission_percent', $property->commission_percent ?? 6) }}">
                </div>
                <div class="vrc-field">
                    <label>Affiliate Cookie Duration (days)</label>
                    <input type="number" name="cookie_duration_days" value="{{ old('cookie_duration_days', $property->cookie_duration_days ?? 180) }}">
                </div>
                <div class="vrc-field">
                    <label>Original Source Page</label>
                    <input type="url" name="source_url" value="{{ old('source_url', $property->source_url ?? 'https://villareadycroatia.com/villas-for-sale.php') }}">
                </div>
                <div class="vrc-field">
                    <label>Agency Content Editing</label>
                    <select name="agency_can_edit">
                        <option value="0" @selected(!old('agency_can_edit', $property->agency_can_edit ?? false))>Locked — agency cannot edit</option>
                        <option value="1" @selected(old('agency_can_edit', $property->agency_can_edit ?? false))>Limited fields</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Text and SEO --}}
    <div class="card">
        <div class="card-header">
            <h5 class="vrc-section-title">Main Text and SEO Fields</h5>
            <p class="vrc-help">Admin manually inserts and controls every text shown on the agency property page.</p>
        </div>
        <div class="card-body">
            <div class="vrc-grid-2">
                <div class="vrc-field">
                    <label>Property Title *</label>
                    <input type="text" name="title" value="{{ old('title', $property->title ?? '') }}" required>
                </div>
                <div class="vrc-field">
                    <label>Short Box Title</label>
                    <input type="text" name="short_title" value="{{ old('short_title', $property->short_title ?? '') }}">
                </div>
                <div class="vrc-field">
                    <label>Location *</label>
                    <input type="text" name="location" value="{{ old('location', $property->location ?? '') }}" required>
                </div>
                <div class="vrc-field">
                    <label>Address / Micro-location</label>
                    <input type="text" name="address" value="{{ old('address', $property->address ?? '') }}">
                </div>
                <div class="vrc-field">
                    <label>Net Price Display</label>
                    <input type="text" name="price_display" value="{{ old('price_display', $property->price_display ?? '') }}">
                </div>
                <div class="vrc-field">
                    <label>Property Type</label>
                    <input type="text" name="property_type" value="{{ old('property_type', $property->property_type ?? '') }}">
                </div>
            </div>
            <div class="vrc-grid-2 mt-3">
                <div class="vrc-field">
                    <label>Hero Introduction</label>
                    <textarea name="intro">{{ old('intro', $property->intro ?? '') }}</textarea>
                </div>
                <div class="vrc-field">
                    <label>Full Property Description</label>
                    <textarea name="description">{{ old('description', $property->description ?? '') }}</textarea>
                </div>
                <div class="vrc-field">
                    <label>Location Description</label>
                    <textarea name="location_description">{{ old('location_description', $property->location_description ?? '') }}</textarea>
                </div>
                <div class="vrc-field">
                    <label>Important Disclaimer</label>
                    <textarea name="disclaimer">{{ old('disclaimer', $property->disclaimer ?? '') }}</textarea>
                </div>
            </div>
            <div class="vrc-grid-2 mt-3">
                <div class="vrc-field">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $property->meta_title ?? '') }}">
                </div>
                <div class="vrc-field">
                    <label>Meta Description</label>
                    <textarea name="meta_description">{{ old('meta_description', $property->meta_description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Images --}}
    <div class="card">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Images, Drone Images, Plans and 360° Content</h5>
                <p class="vrc-help">Upload files or paste the final stored URL for every image.</p>
            </div>
        </div>
        <div class="card-body">
            <div class="vrc-field mb-3">
                <label>Featured Image</label>
                @if($isEdit && $property->featured_image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $property->featured_image) }}" alt="" class="vrc-thumb">
                </div>
                @endif
                <input type="file" name="featured_image" accept="image/*">
            </div>

            @if($isEdit && $property->images->count())
            <h6 class="mb-3">Existing Images</h6>
            <div id="existingImages">
                @foreach($property->images as $image)
                <div class="vrc-image-row" data-id="{{ $image->id }}">
                    <img src="{{ $image->image_url }}" alt="" class="vrc-thumb">
                    <span>{{ $image->image_type }}</span>
                    <span>{{ basename($image->image_path) }}</span>
                    <label class="d-flex align-items-center gap-2">
                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"> Delete
                    </label>
                </div>
                @endforeach
            </div>
            <hr>
            @endif

            <h6 class="mb-3">Add New Images (Upload)</h6>
            <div id="newImageUploads">
                <div class="vrc-image-row">
                    <div></div>
                    <input type="file" name="gallery_images[]" accept="image/*">
                    <select name="gallery_types[]">
                        <option value="gallery">Gallery image</option>
                        <option value="floor_plan">Floor plan</option>
                    </select>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.vrc-image-row').remove()">×</button>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addImageUpload()">Add Image Upload</button>

            <h6 class="mb-3 mt-4">Add New Images (URL)</h6>
            <div id="newImageUrls">
                <div class="vrc-image-row">
                    <div></div>
                    <input type="url" name="image_urls[]" placeholder="Paste image URL">
                    <select name="image_url_types[]">
                        <option value="gallery">Gallery image</option>
                        <option value="floor_plan">Floor plan</option>
                    </select>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.vrc-image-row').remove()">×</button>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addImageUrl()">Add Image URL</button>
        </div>
    </div>

    {{-- Project and Building Fields --}}
    <div class="card">
        <div class="card-header">
            <h5 class="vrc-section-title">Project and Building Fields</h5>
            <p class="vrc-help">Every building, floor, area, unit and price can be entered manually.</p>
        </div>
        <div class="card-body">
            <div class="vrc-grid-3">
                <div class="vrc-field">
                    <label>Number of Buildings</label>
                    <input type="number" name="buildings_count" value="{{ old('buildings_count', $property->buildings_count ?? '') }}">
                </div>
                <div class="vrc-field">
                    <label>Structure</label>
                    <input type="text" name="structure" value="{{ old('structure', $property->structure ?? '') }}">
                </div>
                <div class="vrc-field">
                    <label>Price per m²</label>
                    <input type="number" name="price_per_m2" step="0.01" value="{{ old('price_per_m2', $property->price_per_m2 ?? '') }}">
                </div>
                <div class="vrc-field">
                    <label>Ground-floor Range</label>
                    <input type="text" name="ground_floor_range" value="{{ old('ground_floor_range', $property->ground_floor_range ?? '') }}">
                </div>
                <div class="vrc-field">
                    <label>First-floor Range</label>
                    <input type="text" name="first_floor_range" value="{{ old('first_floor_range', $property->first_floor_range ?? '') }}">
                </div>
                <div class="vrc-field">
                    <label>Attic Range</label>
                    <input type="text" name="attic_range" value="{{ old('attic_range', $property->attic_range ?? '') }}">
                </div>
            </div>

            <h6 class="mt-4 mb-3">Units</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="unitTable">
                    <thead>
                        <tr>
                            <th>Building</th>
                            <th>Floor</th>
                            <th>Unit</th>
                            <th>Size m²</th>
                            <th>Net Price</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($isEdit)
                            @foreach($property->units as $unit)
                            <tr>
                                <td><input type="number" name="units[{{ $loop->index }}][building_number]" value="{{ $unit->building_number }}" class="form-control"></td>
                                <td>
                                    <select name="units[{{ $loop->index }}][floor]" class="form-control">
                                        <option value="Ground Floor" @selected($unit->floor === 'Ground Floor')>Ground Floor</option>
                                        <option value="First Floor" @selected($unit->floor === 'First Floor')>First Floor</option>
                                        <option value="Attic" @selected($unit->floor === 'Attic')>Attic</option>
                                    </select>
                                </td>
                                <td><input type="text" name="units[{{ $loop->index }}][unit_code]" value="{{ $unit->unit_code }}" class="form-control"></td>
                                <td><input type="number" name="units[{{ $loop->index }}][size_m2]" value="{{ $unit->size_m2 }}" step="0.01" class="form-control"></td>
                                <td><input type="number" name="units[{{ $loop->index }}][net_price]" value="{{ $unit->net_price }}" step="0.01" class="form-control"></td>
                                <td>
                                    <select name="units[{{ $loop->index }}][status]" class="form-control">
                                        <option value="available" @selected($unit->status === 'available')>Available</option>
                                        <option value="reserved" @selected($unit->status === 'reserved')>Reserved</option>
                                        <option value="sold" @selected($unit->status === 'sold')>Sold</option>
                                    </select>
                                </td>
                                <td><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">×</button></td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addUnitRow()">Add Unit</button>
            <label class="ms-3"><input type="checkbox" name="replace_units" value="1"> Replace all existing units</label>
        </div>
    </div>

    {{-- Purchase, VAT and Management --}}
    <div class="card">
        <div class="card-header">
            <h5 class="vrc-section-title">Purchase, VAT and Management Text</h5>
        </div>
        <div class="card-body">
            <div class="vrc-grid-2">
                <div class="vrc-field">
                    <label>Payment Structure</label>
                    <textarea name="payment_structure">{{ old('payment_structure', $property->payment_structure ?? '') }}</textarea>
                </div>
                <div class="vrc-field">
                    <label>VAT Information</label>
                    <textarea name="vat_info">{{ old('vat_info', $property->vat_info ?? '') }}</textarea>
                </div>
                <div class="vrc-field">
                    <label>Property Use Options</label>
                    <textarea name="use_options">{{ old('use_options', $property->use_options ?? '') }}</textarea>
                </div>
                <div class="vrc-field">
                    <label>Optional Management Service</label>
                    <textarea name="management_service">{{ old('management_service', $property->management_service ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Agency Publication --}}
    <div class="card">
        <div class="card-header">
            <h5 class="vrc-section-title">Agency Publication</h5>
            <p class="vrc-help">Select where the fixed page is published. Agencies can sell the property but cannot change locked content.</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Publish</th>
                            <th>Agency</th>
                            <th>Domain</th>
                            <th>Affiliate Code</th>
                            <th>Page URL</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agencies as $agency)
                        @php
                            $pub = $isEdit ? $property->publications->where('agency_profile_id', $agency->id)->first() : null;
                            $hasSftp = $agency->server_ip && $agency->sftp_username && $agency->sftp_password;
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" name="publish_agencies[]" value="{{ $agency->id }}" @checked($pub)>
                            </td>
                            <td>{{ $agency->agency_name }}</td>
                            <td>{{ $agency->custom_domain ?? $agency->subdomain ?? '—' }}</td>
                            <td>{{ $pub->affiliate_code ?? '—' }}</td>
                            <td>{{ $pub->page_slug ?? '/properties/' . ($property->slug ?? 'new-property') }}</td>
                            <td>
                                @if($isEdit && $pub && $hasSftp)
                                    <form action="{{ route('admin.villabit.villa-ready.properties.publish', [$property, $agency]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Deploy via SFTP</button>
                                    </form>
                                @elseif($isEdit && $pub && !$hasSftp)
                                    <span class="text-muted small">No SFTP configured</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sticky Actions --}}
    <div class="vrc-sticky-actions">
        <a href="{{ route('admin.villabit.villa-ready.properties.index') }}" class="btn btn-light">Cancel</a>
        <button type="submit" name="action" value="draft" class="btn btn-outline-primary">Save Draft</button>
        <button type="submit" name="action" value="publish" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Create' }} Property</button>
    </div>
</form>

<script>
let unitIndex = {{ $isEdit ? $property->units->count() : 0 }};

function addImageUpload() {
    const wrap = document.getElementById('newImageUploads');
    const row = document.createElement('div');
    row.className = 'vrc-image-row';
    row.innerHTML = `
        <div></div>
        <input type="file" name="gallery_images[]" accept="image/*">
        <select name="gallery_types[]">
            <option value="gallery">Gallery image</option>
            <option value="floor_plan">Floor plan</option>
        </select>
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.vrc-image-row').remove()">×</button>
    `;
    wrap.appendChild(row);
}

function addImageUrl() {
    const wrap = document.getElementById('newImageUrls');
    const row = document.createElement('div');
    row.className = 'vrc-image-row';
    row.innerHTML = `
        <div></div>
        <input type="url" name="image_urls[]" placeholder="Paste image URL">
        <select name="image_url_types[]">
            <option value="gallery">Gallery image</option>
            <option value="floor_plan">Floor plan</option>
        </select>
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.vrc-image-row').remove()">×</button>
    `;
    wrap.appendChild(row);
}

function addUnitRow() {
    const tbody = document.querySelector('#unitTable tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="number" name="units[${unitIndex}][building_number]" value="1" class="form-control"></td>
        <td>
            <select name="units[${unitIndex}][floor]" class="form-control">
                <option value="Ground Floor">Ground Floor</option>
                <option value="First Floor">First Floor</option>
                <option value="Attic">Attic</option>
            </select>
        </td>
        <td><input type="text" name="units[${unitIndex}][unit_code]" class="form-control"></td>
        <td><input type="number" name="units[${unitIndex}][size_m2]" step="0.01" class="form-control"></td>
        <td><input type="number" name="units[${unitIndex}][net_price]" step="0.01" class="form-control"></td>
        <td>
            <select name="units[${unitIndex}][status]" class="form-control">
                <option value="available">Available</option>
                <option value="reserved">Reserved</option>
                <option value="sold">Sold</option>
            </select>
        </td>
        <td><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">×</button></td>
    `;
    tbody.appendChild(tr);
    unitIndex++;
}
</script>
