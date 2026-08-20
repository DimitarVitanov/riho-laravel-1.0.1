@extends('layouts.simple.master')

@section('title', 'Edit ' . $competitor->name)

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/competitor-intelligence.css') }}">
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-head">
        <div>
            <h1>Edit Competitor</h1>
            <p>Update competitor settings, monitoring sources and identity signals.</p>
        </div>
        <div class="vb-toolbar">
            <a href="{{ route('agency.competitor-intelligence.competitors.show', $competitor) }}" class="vb-btn vb-btn-light"><span class="text-dark">Cancel</span></a>
            <button type="submit" form="competitor-form" class="vb-btn">Save Changes</button>
        </div>
    </div>

    <form id="competitor-form" action="{{ route('agency.competitor-intelligence.competitors.update', $competitor) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="vb-grid-3">
            <div class="vb-card" style="grid-column:span 2">
                <div class="vb-card-head">
                    <h2>Competitor Identity</h2>
                </div>
                <div class="vb-card-body">
                    <div class="form-group">
                        <label>Competitor / Agency Name *</label>
                        <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $competitor->name) }}" required>
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Main Website URL *</label>
                        <input class="form-control @error('website_url') is-invalid @enderror" name="website_url" value="{{ old('website_url', $competitor->website_url) }}" required>
                        @error('website_url')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div>
                            <label>Legal Company Name</label>
                            <input class="form-control" name="legal_name" value="{{ old('legal_name', $competitor->legal_name) }}">
                        </div>
                        <div class="pm-field" style="position:relative">
                            <label>Primary Market</label>
                            <input class="form-control" id="pmSearch" name="primary_market" value="{{ old('primary_market', $competitor->primary_market) }}" placeholder="Search city, area or street…" autocomplete="off">
                            <div id="pmSuggestions" class="pm-suggest" style="display:none"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div>
                            <label>Country</label>
                            <select class="form-control" name="country">
                                <option value="" disabled {{ old('country', $competitor->country) ? '' : 'selected' }}>Select country</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->common_name }}" {{ old('country', \App\Helpers\Helpers::commonCountryName($competitor->country)) === $country->common_name ? 'selected' : '' }}>{{ $country->common_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Brand Variations / Aliases</label>
                        <input class="form-control" name="aliases" value="{{ old('aliases', $competitor->aliases->pluck('alias')->implode(', ')) }}" placeholder="Separate aliases with commas">
                    </div>
                </div>
            </div>

            <div class="vb-card">
                <div class="vb-card-head">
                    <h2>Monitoring Status</h2>
                </div>
                <div class="vb-card-body">
                    <label class="check"><input type="checkbox" name="is_active" value="1" {{ $competitor->is_active ? 'checked' : '' }}> Active monitoring</label>
                    <label class="check"><input type="checkbox" name="include_in_daily_report" value="1" {{ $competitor->include_in_daily_report ? 'checked' : '' }}> Include in daily report</label>
                    <label class="check"><input type="checkbox" name="include_in_comparison" value="1" {{ $competitor->include_in_comparison ? 'checked' : '' }}> Include in comparison</label>
                    <label style="margin-top:14px">Priority</label>
                    <select class="form-control" name="priority">
                        <option value="high" {{ $competitor->priority == 'high' ? 'selected' : '' }}>High</option>
                        <option value="normal" {{ $competitor->priority == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="low" {{ $competitor->priority == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="vb-grid-2" style="margin-top:18px">
            <div class="vb-card">
                <div class="vb-card-head">
                    <h2>Google & Reputation</h2>
                </div>
                <div class="vb-card-body">
                    <div class="form-group">
                        <label>Google Maps URL</label>
                        <input class="form-control" name="google_maps_url" value="{{ old('google_maps_url', $competitor->google_maps_url) }}">
                    </div>
                    <div class="form-group">
                        <label>Google Place ID</label>
                        <input class="form-control" name="google_place_id" value="{{ old('google_place_id', $competitor->google_place_id) }}">
                    </div>
                </div>
            </div>

            <div class="vb-card">
                <div class="vb-card-head">
                    <h2>Contact Fingerprints</h2>
                </div>
                <div class="vb-card-body">
                    <div class="form-group">
                        <label>Known Phone Numbers</label>
                        <textarea class="form-control" name="phones" rows="3">{{ old('phones', $competitor->phones) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Known Emails</label>
                        <textarea class="form-control" name="emails" rows="3">{{ old('emails', $competitor->emails) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:space-between;margin-top:18px">
            <button type="submit" form="delete-competitor-form" class="vb-btn vb-btn-danger text-white">Delete Competitor</button>
            <div class="vb-toolbar">
                <a href="{{ route('agency.competitor-intelligence.competitors.show', $competitor) }}" class="vb-btn vb-btn-light"><span class="text-dark">Cancel</span></a>
                <button type="submit" class="vb-btn">Save Changes</button>
            </div>
        </div>
    </form>
    <form id="delete-competitor-form" action="{{ route('agency.competitor-intelligence.competitors.destroy', $competitor) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this competitor?')">
        @csrf
        @method('DELETE')
    </form>
</div>

<style>
.pm-field{position:relative}
.pm-suggest{position:absolute;top:100%;left:0;right:0;z-index:50;background:#fff;border:1px solid #d7dbe1;border-top:none;border-radius:0 0 7px 7px;max-height:260px;overflow-y:auto;box-shadow:0 8px 20px rgba(18,24,40,.12)}
.pm-suggest button{display:block;width:100%;text-align:left;background:#fff;border:none;border-bottom:1px solid #eef0f3;padding:9px 11px;font-size:12px;cursor:pointer;color:#2b323d}
.pm-suggest button:last-child{border-bottom:none}
.pm-suggest button:hover{background:#f2f6ff}
.pm-loading{padding:9px 11px;font-size:12px;color:#8a92a0}
</style>

<script>
// Primary Market autocomplete — searches every real place via OpenStreetMap Nominatim.
(function () {
    var input = document.getElementById('pmSearch');
    var box = document.getElementById('pmSuggestions');
    if (!input || !box) return;

    var timer = null;

    function hideBox() { box.style.display = 'none'; box.innerHTML = ''; }

    input.addEventListener('input', function () {
        var q = input.value.trim();
        clearTimeout(timer);
        if (q.length < 3) { hideBox(); return; }
        box.style.display = 'block';
        box.innerHTML = '<div class="pm-loading">Searching…</div>';
        timer = setTimeout(function () {
            fetch('https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=8&q=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (results) {
                box.innerHTML = '';
                if (!results || !results.length) { hideBox(); return; }
                results.slice(0, 8).forEach(function (item) {
                    var addr = item.address || {};
                    var place = addr.city || addr.town || addr.village || addr.municipality ||
                                addr.suburb || addr.neighbourhood || addr.quarter ||
                                item.display_name.split(',')[0];
                    var country = addr.country || '';
                    var label = place + (country ? ', ' + country : '');
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = item.display_name;
                    btn.addEventListener('click', function () {
                        input.value = label;
                        hideBox();
                    });
                    box.appendChild(btn);
                });
                box.style.display = 'block';
            })
            .catch(function () { hideBox(); });
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (e.target !== input && !box.contains(e.target)) hideBox();
    });
})();
</script>
@endsection
