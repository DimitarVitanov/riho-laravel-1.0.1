@extends('layouts.simple.master')
@section('title', 'Website Design')

@section('css')
<style>
.wd-color-group { display:flex; align-items:center; gap:10px; }
.wd-color-swatch { width:38px; height:38px; border-radius:8px; border:2px solid #e5e7eb; cursor:pointer; padding:0; }
.wd-section-title { font-size:13px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; margin:0 0 16px; }
.wd-row-item { display:flex; gap:8px; align-items:center; margin-bottom:8px; }
.wd-row-item .form-control { font-size:13px; }
.wd-add-btn { background:none; border:1px dashed #d1d5db; border-radius:8px; padding:8px 14px; font-size:13px; color:#6b7280; cursor:pointer; width:100%; margin-top:6px; transition: all 0.2s; }
.wd-add-btn:hover { border-color:#111827; color:#111827; }
.wd-remove-btn { background:none; border:none; color:#ef4444; font-size:18px; line-height:1; cursor:pointer; padding:0 4px; flex-shrink:0; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Website Design</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">Website Design</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form action="{{ route('agency.settings.website-design.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ===== BRAND COLORS ===== --}}
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">🎨 Brand Colors</h5></div>
                    <div class="card-body">
                        <div class="row g-4">
                            @foreach([
                                ['name'=>'primary_color','id'=>'primaryColor','label'=>'Primary Color','val'=>$profile->website_primary_color ?? '#111827','hint'=>'Header, hero, CTA background'],
                                ['name'=>'secondary_color','id'=>'secondaryColor','label'=>'Secondary Color','val'=>$profile->website_secondary_color ?? '#374151','hint'=>'Muted text, descriptions'],
                                ['name'=>'accent_color','id'=>'accentColor','label'=>'Accent Color','val'=>$profile->website_accent_color ?? '#3b82f6','hint'=>'Links, buttons, highlights'],
                            ] as $c)
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ $c['label'] }}</label>
                                <div class="wd-color-group">
                                    <input type="color" name="{{ $c['name'] }}" value="{{ $c['val'] }}" class="wd-color-swatch" id="{{ $c['id'] }}">
                                    <input type="text" class="form-control" value="{{ $c['val'] }}" id="{{ $c['id'] }}Text" maxlength="7" style="max-width:100px;">
                                </div>
                                <small class="text-muted">{{ $c['hint'] }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ===== TOP BAR ===== --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">📢 Top Bar</h5>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="header_topbar_enabled" value="1" id="topbarEnabled"
                                    {{ ($profile->header_topbar_enabled ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="topbarEnabled">Enable</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-9">
                                <label class="form-label fw-semibold">Top Bar Message</label>
                                <input type="text" name="header_topbar_text" class="form-control"
                                    value="{{ $profile->header_topbar_text ?? '' }}"
                                    placeholder="e.g. Real Estate Taxi is your FREE rule through the global real estate market!">
                                <small class="text-muted">Text aligned left, shown above the header</small>
                            </div>
                            <div class="col-md-3">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">BG Color</label>
                                        <div class="wd-color-group">
                                            <input type="color" name="header_topbar_bg_color" value="{{ $profile->header_topbar_bg_color ?? '#0a0a0a' }}" class="wd-color-swatch" id="topbarBgColor">
                                            <input type="text" class="form-control" value="{{ $profile->header_topbar_bg_color ?? '#0a0a0a' }}" id="topbarBgColorText" maxlength="7" style="max-width:80px;">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Text Color</label>
                                        <div class="wd-color-group">
                                            <input type="color" name="header_topbar_color" value="{{ $profile->header_topbar_color ?? '#ffffff' }}" class="wd-color-swatch" id="topbarColor">
                                            <input type="text" class="form-control" value="{{ $profile->header_topbar_color ?? '#ffffff' }}" id="topbarColorText" maxlength="7" style="max-width:80px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== HEADER ===== --}}
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">🔝 Header</h5></div>
                    <div class="card-body">
                        <div class="row g-4">

                            {{-- Logo --}}
                            <div class="col-md-6">
                                <p class="wd-section-title">Logo</p>

                                {{-- Toggle --}}
                                <div class="d-flex gap-2 mb-3">
                                    <button type="button" id="logoTabImage" onclick="switchLogoTab('image')"
                                        class="btn btn-sm {{ ($profile->header_logo_type ?? 'image') === 'text' ? 'btn-outline-secondary' : 'btn-dark' }}">
                                        🖼 Image
                                    </button>
                                    <button type="button" id="logoTabText" onclick="switchLogoTab('text')"
                                        class="btn btn-sm {{ ($profile->header_logo_type ?? 'image') === 'text' ? 'btn-dark' : 'btn-outline-secondary' }}">
                                        🔤 Text
                                    </button>
                                </div>
                                <input type="hidden" name="header_logo_type" id="header_logo_type" value="{{ $profile->header_logo_type ?? 'image' }}">

                                {{-- Image upload panel --}}
                                <div id="logoPanelImage" style="{{ ($profile->header_logo_type ?? 'image') === 'text' ? 'display:none;' : '' }}">
                                    @if($profile->header_logo_path)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $profile->header_logo_path) }}" style="max-height:48px;border-radius:6px;border:1px solid #e5e7eb;">
                                        </div>
                                    @endif
                                    <input type="file" name="header_logo" class="form-control" accept="image/*">
                                </div>

                                {{-- Text logo panel --}}
                                <div id="logoPanelText" style="{{ ($profile->header_logo_type ?? 'image') !== 'text' ? 'display:none;' : '' }}">
                                    <input type="text" name="header_logo_text" class="form-control"
                                        value="{{ $profile->header_logo_text ?? $profile->agency_name }}"
                                        placeholder="e.g. Real Estate Taxi">
                                    <small class="text-muted">This text will be shown as the logo in the header</small>
                                </div>

                                <label class="form-label fw-semibold mt-3">Logo Link (URL)</label>
                                <input type="url" name="header_logo_url" class="form-control"
                                    value="{{ $profile->header_logo_url ?? '' }}"
                                    placeholder="https://yourwebsite.com">
                            </div>

                            {{-- Header Colors --}}
                            <div class="col-md-6">
                                <p class="wd-section-title">Header Colors</p>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Background</label>
                                        <div class="wd-color-group">
                                            <input type="color" name="header_bg_color" value="{{ $profile->header_bg_color ?? '#111827' }}" class="wd-color-swatch" id="headerBgColor">
                                            <input type="text" class="form-control" value="{{ $profile->header_bg_color ?? '#111827' }}" id="headerBgColorText" maxlength="7" style="max-width:100px;">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Text / Links</label>
                                        <div class="wd-color-group">
                                            <input type="color" name="header_text_color" value="{{ $profile->header_text_color ?? '#ffffff' }}" class="wd-color-swatch" id="headerTextColor">
                                            <input type="text" class="form-control" value="{{ $profile->header_text_color ?? '#ffffff' }}" id="headerTextColorText" maxlength="7" style="max-width:100px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Nav Menu --}}
                            <div class="col-12">
                                <p class="wd-section-title">Navigation Menu</p>
                                <div id="navItemsList">
                                    @foreach($profile->header_nav_items ?? [] as $i => $item)
                                    <div class="wd-row-item">
                                        <input type="text" name="nav_label[]" class="form-control" placeholder="Label (e.g. Explore)" value="{{ $item['label'] ?? '' }}">
                                        <input type="text" name="nav_url[]" class="form-control" placeholder="URL (e.g. /explore)" value="{{ $item['url'] ?? '' }}">
                                        <button type="button" class="wd-remove-btn" onclick="this.closest('.wd-row-item').remove()">×</button>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="wd-add-btn" onclick="addNavItem()">+ Add Menu Item</button>
                                <small class="text-muted d-block mt-1">Add navigation links shown in the header</small>
                            </div>

                            {{-- CTA Button --}}
                            <div class="col-12">
                                <p class="wd-section-title">CTA Button</p>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="header_cta_enabled" value="1" id="ctaEnabled"
                                        {{ ($profile->header_cta_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="ctaEnabled">Show CTA Button</label>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Button Text</label>
                                        <input type="text" name="header_cta_text" class="form-control"
                                            value="{{ $profile->header_cta_text ?? 'Get Free Report' }}"
                                            placeholder="Get Free Report">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Button URL</label>
                                        <input type="text" name="header_cta_url" class="form-control"
                                            value="{{ $profile->header_cta_url ?? '#' }}"
                                            placeholder="#contact">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Button BG</label>
                                        <div class="wd-color-group">
                                            <input type="color" name="header_cta_bg_color" value="{{ $profile->header_cta_bg_color ?? '#f59e0b' }}" class="wd-color-swatch" id="ctaBgColor">
                                            <input type="text" class="form-control" value="{{ $profile->header_cta_bg_color ?? '#f59e0b' }}" id="ctaBgColorText" maxlength="7" style="max-width:80px;">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Button Text</label>
                                        <div class="wd-color-group">
                                            <input type="color" name="header_cta_text_color" value="{{ $profile->header_cta_text_color ?? '#ffffff' }}" class="wd-color-swatch" id="ctaTextColor">
                                            <input type="text" class="form-control" value="{{ $profile->header_cta_text_color ?? '#ffffff' }}" id="ctaTextColorText" maxlength="7" style="max-width:80px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ===== FOOTER ===== --}}
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">🔻 Footer</h5></div>
                    <div class="card-body">
                        <div class="row g-4">

                            {{-- Footer Colors --}}
                            <div class="col-12">
                                <p class="wd-section-title">Footer Colors</p>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Background</label>
                                        <div class="wd-color-group">
                                            <input type="color" name="footer_bg_color" value="{{ $profile->footer_bg_color ?? '#111827' }}" class="wd-color-swatch" id="footerBgColor">
                                            <input type="text" class="form-control" value="{{ $profile->footer_bg_color ?? '#111827' }}" id="footerBgColorText" maxlength="7" style="max-width:100px;">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Text Color</label>
                                        <div class="wd-color-group">
                                            <input type="color" name="footer_text_color" value="{{ $profile->footer_text_color ?? '#ffffff' }}" class="wd-color-swatch" id="footerTextColor">
                                            <input type="text" class="form-control" value="{{ $profile->footer_text_color ?? '#ffffff' }}" id="footerTextColorText" maxlength="7" style="max-width:100px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Col 1 - Links --}}
                            <div class="col-md-6">
                                <p class="wd-section-title">Column 1 — Links</p>
                                <label class="form-label fw-semibold">Section Title</label>
                                <input type="text" name="footer_col1_title" class="form-control mb-3"
                                    value="{{ $profile->footer_col1_title ?? '' }}"
                                    placeholder="e.g. WE GLAD TO OFFER">
                                <label class="form-label fw-semibold">Links</label>
                                <div id="footerLinksList">
                                    @foreach($profile->footer_col1_links ?? [] as $link)
                                    <div class="wd-row-item">
                                        <input type="text" name="footer_link_label[]" class="form-control" placeholder="Link text" value="{{ $link['label'] ?? '' }}">
                                        <input type="text" name="footer_link_url[]" class="form-control" placeholder="URL" value="{{ $link['url'] ?? '' }}">
                                        <button type="button" class="wd-remove-btn" onclick="this.closest('.wd-row-item').remove()">×</button>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="wd-add-btn" onclick="addFooterLink()">+ Add Link</button>
                                <small class="text-muted d-block mt-1">Links are underlined, underline removed on hover</small>
                            </div>

                            {{-- Footer Col 2 - About text --}}
                            <div class="col-md-6">
                                <p class="wd-section-title">Column 2 — About Text</p>
                                <label class="form-label fw-semibold">Section Title</label>
                                <input type="text" name="footer_col2_title" class="form-control mb-3"
                                    value="{{ $profile->footer_col2_title ?? '' }}"
                                    placeholder="e.g. ABOUT US">
                                <label class="form-label fw-semibold">Text</label>
                                <textarea name="footer_col2_text" class="form-control" rows="4"
                                    placeholder="Short description about your agency...">{{ $profile->footer_col2_text ?? '' }}</textarea>
                            </div>

                            {{-- Footer Bottom --}}
                            <div class="col-12">
                                <p class="wd-section-title">Bottom Bar</p>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Copyright Text</label>
                                        <input type="text" name="footer_copyright_text" class="form-control"
                                            value="{{ $profile->footer_copyright_text ?? '' }}"
                                            placeholder="e.g. © 2026 My Agency. All rights reserved.">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Terms of Use URL</label>
                                        <input type="text" name="footer_terms_url" class="form-control"
                                            value="{{ $profile->footer_terms_url ?? '' }}"
                                            placeholder="/terms">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Privacy Policy URL</label>
                                        <input type="text" name="footer_privacy_url" class="form-control"
                                            value="{{ $profile->footer_privacy_url ?? '' }}"
                                            placeholder="/privacy">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ===== SIDEBAR SETTINGS ===== --}}
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">📋 Sidebar Navigation Box</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="sidebar_enabled" value="0">
                                    <input class="form-check-input" type="checkbox" name="sidebar_enabled" value="1" id="sidebarEnabled"
                                        {{ ($profile->sidebar_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="sidebarEnabled">Show Sidebar Navigation</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="sidebar_show_last_updated" value="0">
                                    <input class="form-check-input" type="checkbox" name="sidebar_show_last_updated" value="1" id="sidebarShowLastUpdated"
                                        {{ ($profile->sidebar_show_last_updated ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="sidebarShowLastUpdated">Show "Last Updated" Box</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Box Title</label>
                                <input type="text" name="sidebar_title" class="form-control"
                                    value="{{ $profile->sidebar_title ?? '' }}"
                                    placeholder="e.g. Page Sections">
                                <small class="text-muted">The list items are auto-generated anchor links to page sections</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SIDEBAR PROMO BOX ===== --}}
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">🏠 Sidebar Property Promo Box</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="sidebar_promo_enabled" value="0">
                                    <input class="form-check-input" type="checkbox" name="sidebar_promo_enabled" value="1" id="sidebarPromoEnabled"
                                        {{ ($profile->sidebar_promo_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="sidebarPromoEnabled">Enable Property Promo Box</label>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Promo Image</label>
                                <input type="file" name="sidebar_promo_image" class="form-control" accept="image/*">
                                @if($profile->sidebar_promo_image)
                                    <small class="text-muted">Current: {{ basename($profile->sidebar_promo_image) }}</small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Promo Title</label>
                                <input type="text" name="sidebar_promo_title" class="form-control"
                                    value="{{ $profile->sidebar_promo_title ?? '' }}"
                                    placeholder="e.g. View All Properties">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Button Text</label>
                                <input type="text" name="sidebar_promo_button_text" class="form-control"
                                    value="{{ $profile->sidebar_promo_button_text ?? '' }}"
                                    placeholder="e.g. Get Property Options">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Promo Description</label>
                                <textarea name="sidebar_promo_text" class="form-control" rows="2"
                                    placeholder="e.g. Browse our complete collection of premium properties for sale.">{{ $profile->sidebar_promo_text ?? '' }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Link URL</label>
                                <input type="url" name="sidebar_promo_url" class="form-control"
                                    value="{{ $profile->sidebar_promo_url ?? '' }}"
                                    placeholder="e.g. https://villareadycroatia.com/villas-for-sale.php">
                                <small class="text-muted">Where the image and button should link to</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== CUSTOM CSS ===== --}}
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">💻 Custom CSS</h5></div>
                    <div class="card-body">
                        <textarea name="custom_css" class="form-control font-monospace" rows="6"
                            placeholder="/* Your custom CSS here */">{{ $profile->website_custom_css ?? '' }}</textarea>
                        <small class="text-muted">Applied to all public-facing pages</small>
                    </div>
                </div>

                {{-- Save --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Save Design Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var colorPairs = [
        ['primaryColor','primaryColorText'],
        ['secondaryColor','secondaryColorText'],
        ['accentColor','accentColorText'],
        ['headerBgColor','headerBgColorText'],
        ['headerTextColor','headerTextColorText'],
        ['ctaBgColor','ctaBgColorText'],
        ['ctaTextColor','ctaTextColorText'],
        ['footerBgColor','footerBgColorText'],
        ['footerTextColor','footerTextColorText'],
        ['topbarColor','topbarColorText'],
        ['topbarBgColor','topbarBgColorText'],
    ];
    colorPairs.forEach(function(pair) {
        var picker = document.getElementById(pair[0]);
        var text   = document.getElementById(pair[1]);
        if (!picker || !text) return;
        picker.addEventListener('input', function() { text.value = this.value; });
        text.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) picker.value = this.value;
        });
    });
});
</script>
@endsection

{{-- Inline scripts for dynamic row adding - must be outside @section to ensure availability --}}
<script>
function switchLogoTab(type) {
    document.getElementById('header_logo_type').value = type;
    document.getElementById('logoPanelImage').style.display = (type === 'image') ? '' : 'none';
    document.getElementById('logoPanelText').style.display  = (type === 'text')  ? '' : 'none';
    document.getElementById('logoTabImage').className = 'btn btn-sm ' + (type === 'image' ? 'btn-dark' : 'btn-outline-secondary');
    document.getElementById('logoTabText').className  = 'btn btn-sm ' + (type === 'text'  ? 'btn-dark' : 'btn-outline-secondary');
}

function addNavItem() {
    var list = document.getElementById('navItemsList');
    if (!list) return;
    var div = document.createElement('div');
    div.className = 'wd-row-item';
    div.innerHTML = '<input type="text" name="nav_label[]" class="form-control" placeholder="Label (e.g. Explore)">'
        + '<input type="text" name="nav_url[]" class="form-control" placeholder="URL (e.g. /explore)">'
        + '<button type="button" class="wd-remove-btn" onclick="this.closest(\'.wd-row-item\').remove()">&#215;</button>';
    list.appendChild(div);
}

function addFooterLink() {
    var list = document.getElementById('footerLinksList');
    if (!list) return;
    var div = document.createElement('div');
    div.className = 'wd-row-item';
    div.innerHTML = '<input type="text" name="footer_link_label[]" class="form-control" placeholder="Link text">'
        + '<input type="text" name="footer_link_url[]" class="form-control" placeholder="URL">'
        + '<button type="button" class="wd-remove-btn" onclick="this.closest(\'.wd-row-item\').remove()">&#215;</button>';
    list.appendChild(div);
}

</script>
