@extends('layouts.simple.master')
@section('title', 'Website Design')

@section('css')
<style>
    .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        cursor: pointer;
    }
    .color-input-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .style-option {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }
    .style-option:hover {
        border-color: #9ca3af;
    }
    .style-option.selected {
        border-color: #111827;
        background: #f9fafb;
    }
    .style-option input[type="radio"] {
        display: none;
    }
    .preview-box {
        background: #f3f4f6;
        border-radius: 12px;
        padding: 24px;
        min-height: 200px;
    }
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

            <form action="{{ route('agency.settings.website-design.update') }}" method="POST">
                @csrf

                {{-- Brand Colors --}}
                <div class="card">
                    <div class="card-header pb-0">
                        <h5><i class="fa fa-palette me-2"></i>Brand Colors</h5>
                        <p class="text-muted mb-0">Define your brand colors for public-facing pages</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Primary Color</label>
                                <div class="color-input-group">
                                    <input type="color" name="primary_color" 
                                           value="{{ $profile->website_primary_color ?? '#111827' }}" 
                                           class="color-preview" id="primaryColor">
                                    <input type="text" class="form-control" 
                                           value="{{ $profile->website_primary_color ?? '#111827' }}" 
                                           id="primaryColorText" maxlength="7" style="max-width: 100px;">
                                </div>
                                <small class="text-muted">Main brand color for headers, buttons</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Secondary Color</label>
                                <div class="color-input-group">
                                    <input type="color" name="secondary_color" 
                                           value="{{ $profile->website_secondary_color ?? '#374151' }}" 
                                           class="color-preview" id="secondaryColor">
                                    <input type="text" class="form-control" 
                                           value="{{ $profile->website_secondary_color ?? '#374151' }}" 
                                           id="secondaryColorText" maxlength="7" style="max-width: 100px;">
                                </div>
                                <small class="text-muted">Secondary elements, text</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Accent Color</label>
                                <div class="color-input-group">
                                    <input type="color" name="accent_color" 
                                           value="{{ $profile->website_accent_color ?? '#3b82f6' }}" 
                                           class="color-preview" id="accentColor">
                                    <input type="text" class="form-control" 
                                           value="{{ $profile->website_accent_color ?? '#3b82f6' }}" 
                                           id="accentColorText" maxlength="7" style="max-width: 100px;">
                                </div>
                                <small class="text-muted">Links, highlights, CTAs</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Header Style --}}
                <div class="card">
                    <div class="card-header pb-0">
                        <h5><i class="fa fa-window-maximize me-2"></i>Header Style</h5>
                        <p class="text-muted mb-0">Choose how your page header appears</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="style-option {{ ($profile->website_header_style ?? 'standard') == 'minimal' ? 'selected' : '' }}">
                                    <input type="radio" name="header_style" value="minimal" 
                                           {{ ($profile->website_header_style ?? 'standard') == 'minimal' ? 'checked' : '' }}>
                                    <div class="mb-2">
                                        <i class="fa fa-minus fa-2x text-muted"></i>
                                    </div>
                                    <strong>Minimal</strong>
                                    <p class="text-muted small mb-0">Logo only, clean look</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="style-option {{ ($profile->website_header_style ?? 'standard') == 'standard' ? 'selected' : '' }}">
                                    <input type="radio" name="header_style" value="standard" 
                                           {{ ($profile->website_header_style ?? 'standard') == 'standard' ? 'checked' : '' }}>
                                    <div class="mb-2">
                                        <i class="fa fa-bars fa-2x text-muted"></i>
                                    </div>
                                    <strong>Standard</strong>
                                    <p class="text-muted small mb-0">Logo + navigation</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="style-option {{ ($profile->website_header_style ?? 'standard') == 'full' ? 'selected' : '' }}">
                                    <input type="radio" name="header_style" value="full" 
                                           {{ ($profile->website_header_style ?? 'standard') == 'full' ? 'checked' : '' }}>
                                    <div class="mb-2">
                                        <i class="fa fa-th-large fa-2x text-muted"></i>
                                    </div>
                                    <strong>Full</strong>
                                    <p class="text-muted small mb-0">Logo + nav + contact info</p>
                                </label>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_logo_in_header" value="1"
                                           id="showLogoHeader" {{ ($profile->website_show_logo_in_header ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="showLogoHeader">Show logo in header</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_contact_in_header" value="1"
                                           id="showContactHeader" {{ ($profile->website_show_contact_in_header ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="showContactHeader">Show contact info in header</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Style --}}
                <div class="card">
                    <div class="card-header pb-0">
                        <h5><i class="fa fa-window-minimize me-2"></i>Footer Style</h5>
                        <p class="text-muted mb-0">Choose how your page footer appears</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="style-option {{ ($profile->website_footer_style ?? 'standard') == 'minimal' ? 'selected' : '' }}">
                                    <input type="radio" name="footer_style" value="minimal" 
                                           {{ ($profile->website_footer_style ?? 'standard') == 'minimal' ? 'checked' : '' }}>
                                    <div class="mb-2">
                                        <i class="fa fa-minus fa-2x text-muted"></i>
                                    </div>
                                    <strong>Minimal</strong>
                                    <p class="text-muted small mb-0">Copyright only</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="style-option {{ ($profile->website_footer_style ?? 'standard') == 'standard' ? 'selected' : '' }}">
                                    <input type="radio" name="footer_style" value="standard" 
                                           {{ ($profile->website_footer_style ?? 'standard') == 'standard' ? 'checked' : '' }}>
                                    <div class="mb-2">
                                        <i class="fa fa-bars fa-2x text-muted"></i>
                                    </div>
                                    <strong>Standard</strong>
                                    <p class="text-muted small mb-0">Logo + links + copyright</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="style-option {{ ($profile->website_footer_style ?? 'standard') == 'full' ? 'selected' : '' }}">
                                    <input type="radio" name="footer_style" value="full" 
                                           {{ ($profile->website_footer_style ?? 'standard') == 'full' ? 'checked' : '' }}>
                                    <div class="mb-2">
                                        <i class="fa fa-th-large fa-2x text-muted"></i>
                                    </div>
                                    <strong>Full</strong>
                                    <p class="text-muted small mb-0">Multi-column with all info</p>
                                </label>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_social_in_footer" value="1"
                                           id="showSocialFooter" {{ ($profile->website_show_social_in_footer ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="showSocialFooter">Show social media links in footer</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Custom CSS --}}
                <div class="card">
                    <div class="card-header pb-0">
                        <h5><i class="fa fa-code me-2"></i>Custom CSS</h5>
                        <p class="text-muted mb-0">Add custom CSS for advanced styling (optional)</p>
                    </div>
                    <div class="card-body">
                        <textarea name="custom_css" class="form-control font-monospace" rows="8" 
                                  placeholder="/* Your custom CSS here */&#10;.my-class {&#10;    color: #333;&#10;}">{{ $profile->website_custom_css ?? '' }}</textarea>
                        <small class="text-muted">This CSS will be applied to all your public-facing pages</small>
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="card">
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
    // Sync color pickers with text inputs
    ['primary', 'secondary', 'accent'].forEach(function(type) {
        var colorInput = document.getElementById(type + 'Color');
        var textInput = document.getElementById(type + 'ColorText');
        
        if (colorInput && textInput) {
            colorInput.addEventListener('input', function() {
                textInput.value = this.value;
            });
            textInput.addEventListener('input', function() {
                if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                    colorInput.value = this.value;
                }
            });
        }
    });

    // Style option selection
    document.querySelectorAll('.style-option').forEach(function(option) {
        option.addEventListener('click', function() {
            var name = this.querySelector('input[type="radio"]').name;
            document.querySelectorAll('input[name="' + name + '"]').forEach(function(radio) {
                radio.closest('.style-option').classList.remove('selected');
            });
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
});
</script>
@endsection
