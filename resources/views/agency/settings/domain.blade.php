@extends('layouts.simple.master')
@section('title', 'Domain Settings')
@section('breadcrumb-title')<h3>Domain Settings</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency</a></li>
    <li class="breadcrumb-item active">Domain</li>
@endsection
@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    @php
        $serverType = auth()->user()->agency_server_type;
        $hasDomain = $profile && $profile->custom_domain;
        $isVerified = $profile && $profile->is_dns_verified;
    @endphp

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">🌐 Your Domain</h5>
            <small class="text-muted">
                @if($serverType === 'subdomain_ai_server')
                    Configure your Villa Bit AI Server subdomain.
                @elseif($serverType === 'domain_folder_ai_server')
                    Configure your Villa Bit AI Server folder on your existing domain.
                @else
                    Enter the domain where your Villa Bit AI Server is hosted.
                @endif
            </small>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('agency.settings.domain.update') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Custom Domain</label>
                    @if($serverType === 'subdomain_ai_server')
                        <div class="input-group">
                            <span class="input-group-text">https://</span>
                            <input type="text" name="domain_part1" class="form-control @error('domain_part1') is-invalid @enderror"
                                placeholder="anyname" value="{{ old('domain_part1', $domainPart1 ?? '') }}">
                            <span class="input-group-text">.</span>
                            <input type="text" name="domain_part2" class="form-control @error('domain_part2') is-invalid @enderror"
                                placeholder="yourdomain.com" value="{{ old('domain_part2', $domainPart2 ?? '') }}">
                            @error('domain_part1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('domain_part2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted mt-1 d-block">For Subdomain AI Server: enter <code>anyname</code> and <code>yourdomain.com</code></small>
                    @elseif($serverType === 'domain_folder_ai_server')
                        <div class="input-group">
                            <span class="input-group-text">https://</span>
                            <input type="text" name="domain_part1" class="form-control @error('domain_part1') is-invalid @enderror"
                                placeholder="yourdomain.com" value="{{ old('domain_part1', $domainPart1 ?? '') }}">
                            <span class="input-group-text">/</span>
                            <input type="text" name="domain_part2" class="form-control @error('domain_part2') is-invalid @enderror"
                                placeholder="anyname" value="{{ old('domain_part2', $domainPart2 ?? '') }}">
                            <span class="input-group-text">/</span>
                            @error('domain_part1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('domain_part2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted mt-1 d-block">For Domain Folder AI Server: enter <code>yourdomain.com</code> and <code>anyname</code></small>
                    @else
                        <div class="input-group">
                            <span class="input-group-text">https://</span>
                            <input type="text" name="domain_part1" class="form-control @error('domain_part1') is-invalid @enderror"
                                placeholder="yourdomain.com" value="{{ old('domain_part1', $domainPart1 ?? '') }}">
                            @error('domain_part1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </div>

                @if($hasDomain)
                <div class="alert alert-info mb-3">
                    <strong>Current domain:</strong> {{ $profile->custom_domain }}<br>
                    <strong>Status:</strong>
                    @if($isVerified)
                        <span class="badge bg-success">LIVE — CONNECTED TO VILLA BIT AI SERVER</span>
                    @else
                        <span class="badge bg-warning text-dark">WAITING FOR DNS CONNECTION</span>
                    @endif
                    <br>
                    <strong>Sitemap URL:</strong>
                    <a href="https://{{ $profile->custom_domain }}/sitemap.xml" target="_blank">
                        https://{{ $profile->custom_domain }}/sitemap.xml
                    </a>
                </div>
                @else
                <div class="alert alert-warning mb-3">
                    No domain set yet. Save your domain to begin the connection process.
                </div>
                @endif

                <button type="submit" class="btn btn-dark">Save Domain</button>
            </form>
        </div>
    </div>

    @if($hasDomain)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">YOUR ACTIVATION PROCESS</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <span class="text-success fw-bold">✔ Account Created</span><br>
                <span>Your profile has been created successfully.</span>
            </div>
            <div class="mb-3">
                <span class="text-success fw-bold">✔ Email Verified</span><br>
                <span>Your email address has been confirmed successfully.</span>
            </div>
            <div class="mb-3">
                <span class="text-success fw-bold">✔ Payment Required</span><br>
                <span>Complete payment to activate your Villa Bit AI Server account.</span>
            </div>
            <div class="mb-3">
                @if($profile->nameserver_1 && $profile->nameserver_2)
                    <span class="text-success fw-bold">✔ AI Server Setup</span><br>
                @else
                    <span class="fw-bold">⏳ AI Server Setup</span><br>
                @endif
                <span>After payment, your account will be added to the AI Server setup process.</span>
            </div>
            <div class="mb-3">
                 @if($profile->nameserver_1 && $profile->nameserver_2)
                    <span class="text-success fw-bold">✔ Domain Connection</span><br>
                @else
                    <span class="fw-bold">○ Domain Connection<</span><br>
                @endif
                <span>You will enter the yourdomain.com/anyword you want to use for your Villa Bit AI Server connection. Your new Cloudflare nameservers will appear in this panel.</span>
                @if($profile->nameserver_1 && $profile->nameserver_2)
                    <div class="alert alert-light border mt-2 mb-0">
                        <strong>Your nameservers are:</strong><br>
                        <code>{{ $profile->nameserver_1 }}</code><br>
                        <code>{{ $profile->nameserver_2 }}</code>
                    </div>
                @else
                    <div class="alert alert-light border mt-2 mb-0">
                        <em>[Nameservers will appear here once Villa Bit AI Server adds your domain to Cloudflare]</em>
                    </div>
                @endif
            </div>
            <div class="mb-3">
                @if($profile->nameserver_1 && $profile->nameserver_2)
                    <span class="fw-bold">⏳ Nameserver Changes</span><br>
                @else
                    <span class="fw-bold">○ Nameserver Changes</span><br>
                @endif
                <span>You will copy the Cloudflare nameservers shown in this panel. Then, log in to the domain registrar where you originally registered your domain name and change the nameservers to the exact Cloudflare nameservers provided by Villa Bit AI.</span>
            </div>
            <div class="mb-0 border-top pt-3">
                <span>If you need any technical help, please ask or submit a support ticket here:
                <a href="{{ route('agency.support.index') }}" class="vb-domain-support-link">https://app.villabit.ai/agency/support</a></span>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
