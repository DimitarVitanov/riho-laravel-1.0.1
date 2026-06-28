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
            <h5 class="mb-0">Connection Steps</h5>
        </div>
        <div class="card-body">
            @if($serverType === 'subdomain_ai_server')
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item">
                        Enter the “anyword” you want to use into the Villa Bit AI Server panel.
                        <br><small class="text-muted">Example: <code>app</code> to create <code>app.yourdomain.com</code></small>
                    </li>
                    <li class="list-group-item">
                        Villa Bit AI Server adds your domain to the Cloudflare DNS system.
                    </li>
                    <li class="list-group-item">
                        Your Villa Bit AI Server panel will show your new Cloudflare nameservers here.

                        @if($profile->nameserver_1 && $profile->nameserver_2)
                            <div class="alert alert-light border mt-2 mb-0">
                                <strong>Your nameservers are:</strong><br>
                                <code>{{ $profile->nameserver_1 }}</code><br>
                                <code>{{ $profile->nameserver_2 }}</code>
                            </div>
                            <small class="text-muted d-block mt-2">
                                Note: After you add the “anyword” in Step 1, wait up to 24 hours for the nameservers to appear.
                                When you see your new nameservers here, change them at your existing domain registrar.
                            </small>
                        @else
                            <div class="alert alert-light border mt-2 mb-0">
                                <em>[Nameservers will appear here once Villa Bit AI Server adds your domain to Cloudflare]</em>
                            </div>
                        @endif
                    </li>
                    <li class="list-group-item">
                        When DNS has fully propagated across the internet, you will see a green confirmation icon here showing that your DNS settings have been correctly propagated.
                        @if($isVerified)
                            <div class="mt-2"><span class="badge bg-success fs-6">✅ DNS verified</span></div>
                        @endif
                    </li>
                </ol>
                <div class="alert alert-info">
                    <strong>Note:</strong> Your domain can continue working on your existing hosting server without any changes. At the same time, Villa Bit AI Server is added to your domain name system.
                </div>
            @elseif($serverType === 'domain_folder_ai_server')
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item">
                        Enter the folder name you want to use into the Villa Bit AI Server panel.
                        <br><small class="text-muted">Example: <code>ai</code> to create <code>yourdomain.com/ai/</code></small>
                    </li>
                    <li class="list-group-item">
                        Villa Bit AI Server creates the Cloudflare Worker and DNS records for your folder.
                    </li>
                    <li class="list-group-item">
                        Your Villa Bit AI Server panel will show your new Cloudflare nameservers here.
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
                    </li>
                    <li class="list-group-item">
                        When DNS has fully propagated across the internet, you will see a green confirmation icon here showing that your DNS settings have been correctly propagated.
                        @if($isVerified)
                            <div class="mt-2"><span class="badge bg-success fs-6">✅ DNS verified</span></div>
                        @endif
                    </li>
                </ol>
                <div class="alert alert-info">
                    <strong>Important:</strong> Only the selected folder is routed to Villa Bit AI. The rest of your website, email, cPanel, and FTP stay on your existing hosting.
                </div>
            @else
                <div class="alert alert-info">
                    Your domain setup instructions will appear here once a server type is selected.
                </div>
            @endif

            <p class="mb-0">
                If you need any technical help, please ask or submit a support ticket here:
                <a href="{{ route('agency.support.index') }}">https://app.villabit.ai/agency/support</a>
            </p>
        </div>
    </div>
    @endif

</div>
@endsection
