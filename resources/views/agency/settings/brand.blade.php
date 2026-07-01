@extends('layouts.simple.master')
@section('title', 'Brand Settings')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Brand Settings</h1>
            <p>Customize the colors for your public-facing pages (blog, authority pages, local SEO pages).</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="vb-card">
                <form action="{{ route('agency.settings.brand.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">Primary Brand Color</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="brand_primary_color" value="{{ $profile->brand_primary_color ?? '#0d8d8c' }}" style="width:50px; height:40px; border:1px solid #ddd; border-radius:8px; cursor:pointer;">
                            <input type="text" class="form-control" value="{{ $profile->brand_primary_color ?? '#0d8d8c' }}" style="max-width:120px; font-family:monospace;" onchange="this.previousElementSibling.value=this.value" oninput="this.previousElementSibling.value=this.value">
                        </div>
                        <small class="text-muted">Used for buttons, links, and accent elements on your public pages.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Secondary Brand Color</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="brand_secondary_color" value="{{ $profile->brand_secondary_color ?? '#086f70' }}" style="width:50px; height:40px; border:1px solid #ddd; border-radius:8px; cursor:pointer;">
                            <input type="text" class="form-control" value="{{ $profile->brand_secondary_color ?? '#086f70' }}" style="max-width:120px; font-family:monospace;" onchange="this.previousElementSibling.value=this.value" oninput="this.previousElementSibling.value=this.value">
                        </div>
                        <small class="text-muted">Used for hover states and darker accents.</small>
                    </div>

                    <button type="submit" class="vb-btn vb-btn-primary">Save Brand Settings</button>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="vb-card">
                <h6 class="mb-3" style="font-weight:800; color:#1e293b;">Preview</h6>
                <p style="color:#6b7280; font-size:14px; margin-bottom:16px;">This is how your brand colors will appear on your public pages:</p>
                <div style="padding:20px; border:1px solid #e5e7eb; border-radius:12px; background:#fafbfc;">
                    <div style="display:flex; gap:12px; margin-bottom:14px;">
                        <span style="display:inline-block; padding:6px 16px; border-radius:8px; background:{{ $profile->brand_primary_color ?? '#0d8d8c' }}; color:#fff; font-weight:800; font-size:13px;">Primary Button</span>
                        <span style="display:inline-block; padding:6px 16px; border-radius:8px; background:{{ $profile->brand_secondary_color ?? '#086f70' }}; color:#fff; font-weight:800; font-size:13px;">Secondary</span>
                    </div>
                    <p style="font-size:14px; color:#3e4348;">Your articles and public pages will use these colors for links, badges, and interactive elements.</p>
                    <a href="#" style="color:{{ $profile->brand_primary_color ?? '#0d8d8c' }}; font-weight:700; font-size:14px;">Example link color →</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
