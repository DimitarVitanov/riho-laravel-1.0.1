@extends('layouts.simple.master')

@section('css')
<style>
    .waitlist-hero {
        background: linear-gradient(135deg, #0a0b0c 0%, #1a1b1c 100%);
        border-radius: 16px;
        color: #fff;
        padding: 48px 40px;
        position: relative;
        overflow: hidden;
    }
    .waitlist-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }
    .waitlist-hero::after {
        content: '';
        position: absolute;
        bottom: -40px; left: -40px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,0.03);
    }
    .waitlist-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,193,7,0.15);
        border: 1px solid rgba(255,193,7,0.4);
        color: #ffc107;
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 20px;
    }
    .waitlist-steps .step-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f1f2f3;
    }
    .waitlist-steps .step-item:last-child { border-bottom: none; }
    .step-icon {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: 14px;
    }
    .step-icon.done   { background: #d4edda; color: #28a745; }
    .step-icon.active { background: #fff3cd; color: #ffc107; }
    .step-icon.pending{ background: #f0f0f1; color: #aaa; }
    .stat-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
    }
    .stat-box .stat-num { font-size: 1.8rem; font-weight: 700; color: #0a0b0c; }
    .stat-box .stat-label { font-size: 0.78rem; color: #0a0b0c; text-transform: uppercase; letter-spacing: 0.5px; }
</style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6"><h4>Dashboard</h4></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">On Hold</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-9 col-md-11">

                @if(session('verified_message'))
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
                        <i data-feather="check-circle" style="width:18px;height:18px;"></i>
                        <span>{{ session('verified_message') }}</span>
                    </div>
                @endif

                @php
                    $paypalLink = match ($user->agency_server_type ?? '') {
                        'domain_folder_ai_server' => 'https://app.villabit.ai/folder.php',
                        'subdomain_ai_server'     => 'https://app.villabit.ai/subdomain.php',
                        default                   => null,
                    };
                @endphp

                {{-- Hero card --}}
                <div class="waitlist-hero mb-4">
                    <div class="waitlist-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Payment Required
                    </div>
                    <p class="mb-3" style="color:#ffffff !important; font-size:1.05rem; max-width:520px; line-height:1.7;">
                        Your account is currently on hold because payment is required before activation.
                    </p>
                    <p class="mb-3" style="color:#ffffff !important; font-size:1.05rem; max-width:520px; line-height:1.7;">
                        To activate your account and begin the Villa Bit AI Server setup process, complete your monthly payment securely through PayPal.
                    </p>
                    @if($paypalLink)
                        <a href="{{ $paypalLink }}" target="_blank" class="btn btn-warning fw-bold px-4 py-2 mt-2 mb-3" style="color:#0a0b0c !important; text-transform:uppercase; letter-spacing:0.5px;">
                            Activate Your Account and Pay with PayPal
                        </a>
                    @endif
                    <p class="mb-0" style="color:#ffffff !important; font-size:1.05rem; max-width:520px; line-height:1.7;">
                        Once payment is completed, your account will be activated and your domain setup process will begin.
                    </p>
                </div>

                {{-- Technical help --}}
                <div class="card mb-4">
                    <div class="card-body" style="padding: 24px 28px;">
                        <h6 class="fw-bold mb-2">NEED TECHNICAL HELP?</h6>
                        <p class="mb-0" style="line-height:1.75; font-size:0.97rem; color:#444;">
                            For any questions, please submit a support ticket here:<br>
                            <a href="https://app.villabit.ai/agency/support" class="fw-semibold">https://app.villabit.ai/agency/support</a>
                        </p>
                    </div>
                </div>

                {{-- Stats row --}}
                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="stat-box">
                            <div class="stat-num">✓</div>
                            <div class="stat-label">Account Created</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-box">
                            <div class="stat-num">✓</div>
                            <div class="stat-label">Email Verified</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-box">
                            <div class="stat-num" style="color:#ffc107;">⏳</div>
                            <div class="stat-label">Payment Required</div>
                        </div>
                    </div>
                </div>

                {{-- Steps card --}}
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-semibold">Your activation progress</h6>
                    </div>
                    <div class="card-body px-4 waitlist-steps">
                        <div class="step-item">
                            <div class="step-icon done">
                                <i data-feather="check" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Account Created</div>
                                <div class="text-muted small">Your profile has been created successfully.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon done">
                                <i data-feather="check" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Email Verified</div>
                                <div class="text-muted small">Your email address has been confirmed successfully.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon active">
                                <i data-feather="clock" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Payment Required <span class="badge bg-warning text-dark ms-1" style="font-size:0.72rem;">In Progress</span></div>
                                <div class="text-muted small">Complete payment to activate your Villa Bit AI Server account.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon pending">
                                <i data-feather="server" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted">AI Server Setup</div>
                                <div class="text-muted small">After payment, your account will be added to the AI Server setup process.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon pending">
                                <i data-feather="globe" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted">Domain Connection</div>
                                <div class="text-muted small">You will enter yourdomain.com/anyword for your Villa Bit AI Server connection.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon pending">
                                <i data-feather="settings" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted">Nameserver Changes</div>
                                <div class="text-muted small">Copy the Cloudflare nameservers and update them at your domain registrar.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon pending">
                                <i data-feather="unlock" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted">Full Panel Access</div>
                                <div class="text-muted small">Your AI tools, workspace, and onboarding will become active after full DNS propagation.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer note + sign out --}}
                <div class="text-center mb-5">
                    <p class="text-muted small mb-3">
                        Need technical help? <a href="https://app.villabit.ai/agency/support" class="text-dark fw-semibold">Submit a support ticket here</a>.
                    </p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary px-4">Sign Out</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
