@extends('layouts.simple.master')
@section('title', 'Settings')

@section('main_content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="vb-page-header">
        <div>
            <h1>Settings</h1>
            <p>Manage your panel language, preferred currency, and payout preferences.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="vb-notice" style="margin-bottom:20px;background:#edf7ee;border-color:#86efac;">{{ session('success') }}</div>
    @endif

    {{-- KYC Documents Link --}}
    <div class="vb-notice" style="margin-bottom:24px;background:#eff6ff;border-color:#93c5fd;color:#1e40af;">
        Your KYC application and document uploads are on the KYC Documents page. &nbsp;
        <a href="{{ route('investor.documents.index') }}" style="font-weight:700;color:#1d4ed8;text-decoration:underline;">Go to KYC Documents &rarr;</a>
    </div>

    {{-- Language & Payout Settings --}}
    <div class="vb-card" style="margin-bottom:24px;">
        <h2 class="vb-section-title">Panel Language &amp; Payout Settings</h2>
        <div class="vb-grid-2">
            <form action="{{ route('investor.profile.update') }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="form_type" value="language">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div class="vb-field">
                        <label class="vb-label">Control Panel Language</label>
                        <select name="preferred_language" class="form-select" style="background:#f9fafb;border:1px solid #d9dde3;border-radius:10px;padding:10px;">
                            @foreach(\App\Http\Controllers\Agency\AgencySettingsController::supportedPanelLanguages() as $code => $name)
                                <option value="{{ $code }}" {{ ($user->preferred_language ?? 'en') === $code ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vb-field">
                        <label class="vb-label">Preferred Currency</label>
                        <input type="text" name="preferred_currency" class="vb-input" value="{{ $profile->preferred_currency ?? 'EUR' }}" maxlength="10" placeholder="EUR">
                    </div>
                </div>
                <button type="submit" class="vb-btn vb-btn-primary">Save Language Settings</button>
            </form>
            <form action="{{ route('investor.profile.update') }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="form_type" value="payout">
                <div style="display:grid;grid-template-columns:1fr;gap:16px;margin-bottom:16px;">
                    <div class="vb-field">
                        <label class="vb-label">Payout Method</label>
                        <select name="payout_method" class="form-select" style="background:#f9fafb;border:1px solid #d9dde3;border-radius:10px;padding:10px;">
                            @foreach(['bank_wire'=>'Bank Wire','paypal'=>'PayPal','stripe'=>'Stripe','crypto'=>'Crypto','other'=>'Other'] as $val => $label)
                                <option value="{{ $val }}" {{ ($profile->payout_method ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="vb-btn vb-btn-primary">Save Payout Settings</button>
            </form>
        </div>
    </div>

</div>
@endsection
