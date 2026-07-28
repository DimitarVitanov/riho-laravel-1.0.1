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
                        <div>
                            <label>Country</label>
                            <select class="form-control" name="country">
                                <option value="" disabled {{ old('country', $competitor->country) ? '' : 'selected' }}>Select country</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->name }}" {{ old('country', $competitor->country) === $country->name ? 'selected' : '' }}>{{ $country->iso_3166_2 }} — {{ $country->name }}</option>
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
@endsection
