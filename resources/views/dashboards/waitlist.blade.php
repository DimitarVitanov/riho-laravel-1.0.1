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
    .page-body-wrapper a.support-link:not(.sidebar-link):not(.menu-link):not(.dropdown-item):not(.btn):not(.nav-link):not(.logo-wrapper a) { color:#000000 !important; text-decoration:underline !important; }
    .page-body-wrapper a.support-link:not(.sidebar-link):not(.menu-link):not(.dropdown-item):not(.btn):not(.nav-link):not(.logo-wrapper a):hover { text-decoration:none !important; }
    /* Subtle box shadow like local-seo */
    .card { 
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04) !important; 
        border: 1px solid #e9ecef !important; 
        border-radius: 16px !important;
    }
    .card:hover { box-shadow: 0 6px 24px rgba(0, 0, 0, 0.07) !important; }
    .stat-box { 
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04) !important; 
        border: 1px solid #e9ecef !important; 
    }
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
                    $userType = match ($user->role) {
                        'real_estate_agency' => 'Real Estate Agency',
                        'investor'           => 'Real Estate Investor',
                        'manager'            => 'Manager Account',
                        'super_admin', 'admin' => 'Administrator',
                        default              => ucfirst($user->role ?? 'User'),
                    };
                    $subType = match ($user->agency_server_type ?? '') {
                        'subdomain_ai_server'     => 'Subdomain Villa Bit AI Server',
                        'domain_folder_ai_server' => 'Domain Folder Villa Bit AI Server',
                        default                   => null,
                    };
                    $price = $user->agency_server_price ? '$' . number_format($user->agency_server_price, 2) . ' per month' : null;
                    $paypalLink = match ($user->agency_server_type ?? '') {
                        'domain_folder_ai_server' => 'https://app.villabit.ai/folder.php',
                        'subdomain_ai_server'     => 'https://app.villabit.ai/subdomain.php',
                        default                   => null,
                    };
                @endphp

                {{-- Hero card --}}
                <div class="waitlist-hero mb-4">
                    @php
                        $heroStep = $user->onboarding_step ?? 1;
                        $heroBadge = match($heroStep) {
                            1 => 'Payment Required',
                            2 => 'Payment Confirmed',
                            3 => 'AI Server Setup',
                            4 => 'Domain Connection',
                            5 => 'Nameserver Pending',
                            default => 'Setup in Progress',
                        };
                    @endphp
                    <div class="waitlist-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {{ $heroBadge }}
                    </div>
                    <p class="mb-2" style="color:#ffffff !important; font-size:1.05rem; max-width:700px; line-height:1.7;">
                        Your account type: <strong>{{ $userType }}</strong>
                    </p>

                    @if($user->isInvestor())
                        <p class="mb-2" style="color:#ffffff !important; font-size:1.05rem;  line-height:1.7;">
                            Your account is currently on hold because KYC verification and payment are required before activation.
                        </p>
                        <p class="mb-3" style="color:#ffffff !important; font-size:1.05rem; line-height:1.7;">
                            To activate your account, first complete the KYC process. After your KYC is approved, complete your investment payment securely by bank wire transfer. Once both steps are completed, your account will be activated and your investment process will begin.
                        </p>
                        <a href="{{ route('investor.documents.index') }}" class="btn btn-warning fw-bold px-4 py-2 mt-2 mb-3" style="color:#0a0b0c !important; text-transform:uppercase; letter-spacing:0.5px;">
                            Complete KYC Verification
                        </a>
                        <p class="mb-3" style="color:#ffffff !important; font-size:0.95rem;line-height:1.7; opacity:0.85;">
                            Note: If you want to become a reseller only, your account does not need to be fully activated. Your affiliate link and all reseller features are fully active.
                        </p>
                     
                    @else
                        @php $onboardingStep = $user->onboarding_step ?? 1; @endphp
                        
                        @if($onboardingStep == 1)
                            {{-- Step 1: Payment Required --}}
                            <p class="mb-2" style="color:#ffffff !important; font-size:1.05rem; line-height:1.7;">
                                Your account is currently on hold because payment is required before activation.
                            </p>
                            @if($subType)
                                <p class="mb-2" style="color:#ffffff !important; font-size:1.05rem; max-width:520px; line-height:1.7;">
                                    Your subaccount type: <strong>{{ $subType }}</strong>
                                </p>
                            @endif
                            @if($price)
                                <p class="mb-3" style="color:#ffffff !important; font-size:1.05rem; max-width:520px; line-height:1.7;">
                                    Your selected monthly price: <strong>{{ $price }}</strong>
                                </p>
                            @endif
                            <p class="mb-3" style="color:#ffffff !important; font-size:1.05rem; max-width:520px; line-height:1.7;">
                                To activate your account and begin the Villa Bit AI Server setup process, complete your monthly payment securely through PayPal.
                            </p>
                            @if($paypalLink)
                                <a href="{{ $paypalLink }}" target="_blank" class="btn btn-warning fw-bold px-4 py-2 mt-2 mb-3" style="color:#0a0b0c !important; text-transform:uppercase; letter-spacing:0.5px;">
                                    Activate Your Account and Pay with PayPal
                                </a>
                            @endif
                            <p class="mb-0" style="color:#ffffff !important; font-size:15px; line-height:1.7;">
                                Once payment is completed, your account will be activated and your domain setup process will begin.
                            </p>
                        @elseif($onboardingStep == 2 || $onboardingStep == 3)
                            {{-- Step 2-3: Payment Confirmed / AI Server Setup --}}
                            <p class="mb-2" style="color:#ffffff !important; font-size:1.05rem; line-height:1.7;">
                                <strong>✓ Payment Received!</strong> Thank you for your payment.
                            </p>
                            <p class="mb-3" style="color:#ffffff !important; font-size:1.05rem; max-width:600px; line-height:1.7;">
                                Your Villa Bit AI Server is being configured by our team. This process typically takes 24-48 hours. You will receive an email notification when your server is ready.
                            </p>
                            <p class="mb-0" style="color:#ffffff !important; font-size:15px; line-height:1.7;">
                                Once setup is complete, you will be able to enter your domain name and connect it to your Villa Bit AI Server.
                            </p>
                        @elseif($onboardingStep == 4)
                            {{-- Step 4: Domain Connection --}}
                            <p class="mb-2" style="color:#ffffff !important; font-size:1.05rem; line-height:1.7;">
                                <strong>🎉 Your AI Server is Ready!</strong>
                            </p>
                            <p class="mb-3" style="color:#ffffff !important; font-size:1.05rem; max-width:600px; line-height:1.7;">
                                Please enter your domain name to connect it to your Villa Bit AI Server. After you enter your domain, we will provide you with Cloudflare nameservers to complete the connection.
                            </p>
                            <a href="{{ route('agency.settings.domain') }}" class="btn btn-warning fw-bold px-4 py-2 mt-2 mb-3" style="color:#0a0b0c !important; text-transform:uppercase; letter-spacing:0.5px;">
                                <i data-feather="globe" style="width:18px;height:18px;margin-right:8px;"></i> Enter Your Domain
                            </a>
                        @elseif($onboardingStep == 5)
                            {{-- Step 5: Nameserver Pending --}}
                            <p class="mb-2" style="color:#ffffff !important; font-size:1.05rem; line-height:1.7;">
                                <strong>⏳ Waiting for DNS Propagation</strong>
                            </p>
                            <p class="mb-3" style="color:#ffffff !important; font-size:1.05rem; max-width:600px; line-height:1.7;">
                                Your domain has been added. Please update your nameservers at your domain registrar. DNS propagation can take up to 24 hours.
                            </p>
                            <a href="{{ route('agency.settings.domain') }}" class="btn btn-warning fw-bold px-4 py-2 mt-2 mb-3" style="color:#0a0b0c !important; text-transform:uppercase; letter-spacing:0.5px;">
                                <i data-feather="settings" style="width:18px;height:18px;margin-right:8px;"></i> View Nameserver Instructions
                            </a>
                        @endif
                    @endif
                </div>

                {{-- Technical help --}}
                <div class="card mb-4">
                    <div class="card-body" style="padding: 24px 28px;">
                        <h6 class="fw-bold mb-2">NEED TECHNICAL HELP?</h6>
                        <p class="mb-0" style="line-height:1.75; font-size:0.97rem; color:#444;">
                            For any questions, please submit a support ticket here:<br>
                            @if(auth()->user()->isInvestor())
                            <a style="color:#000000;" href="{{ route('investor.support.index') }}" class="fw-semibold support-link">https://app.villabit.ai/investor/support</a>
                            @else
                            <a style="color:#000000;" href="{{ route('agency.support.index') }}" class="fw-semibold support-link">https://app.villabit.ai/agency/support</a>
                            @endif
                        </p>
                    </div>
                </div>

                @if($user->isInvestor())

                {{-- Investor stats row --}}
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
                            <div class="stat-label">KYC Required</div>
                        </div>
                    </div>
                </div>

                {{-- Investor steps card --}}
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-semibold">YOUR ACTIVATION PROGRESS</h6>
                    </div>
                    <div class="card-body px-4 waitlist-steps">
                        <div class="step-item">
                            <div class="step-icon done">
                                <i data-feather="check" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">✓ Account Created</div>
                                <div class="text-muted small">Your profile has been created successfully.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon done">
                                <i data-feather="check" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">✓ Email Verified</div>
                                <div class="text-muted small">Your email address has been confirmed successfully.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon active">
                                <i data-feather="clock" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">⏳ KYC Required</div>
                                <div class="text-muted small">Complete KYC verification to identify yourself as an investor, as required by law.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon pending">
                                <i data-feather="credit-card" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted">○ Payment Required</div>
                                <div class="text-muted small">Complete your investment payment to activate your Villa Bit AI Server account.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon pending">
                                <i data-feather="unlock" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted">○ Full Panel Access</div>
                                <div class="text-muted small">Your online Villa Bit AI investor system will become fully active.</div>
                            </div>
                        </div>
                    </div>
                </div>

                @else

                @php
                    $step = $user->onboarding_step ?? 1;
                    $agencySteps = [
                        ['num' => 1, 'key' => 'payment_required', 'label' => 'Payment Required', 'icon' => 'credit-card', 'desc' => 'Complete payment to activate your Villa Bit AI Server account.'],
                        ['num' => 2, 'key' => 'payment_confirmed', 'label' => 'Payment Confirmed', 'icon' => 'check-circle', 'desc' => 'Payment received! Your AI server setup is being prepared.'],
                        ['num' => 3, 'key' => 'ai_server_setup', 'label' => 'AI Server Setup', 'icon' => 'server', 'desc' => 'Your account is being added to the AI Server setup process.'],
                        ['num' => 4, 'key' => 'domain_connection', 'label' => 'Domain Connection', 'icon' => 'globe', 'desc' => 'Enter your domain for Villa Bit AI Server connection.'],
                        ['num' => 5, 'key' => 'nameserver_pending', 'label' => 'Nameserver Changes', 'icon' => 'settings', 'desc' => 'Update your domain nameservers to the Cloudflare nameservers provided.'],
                        ['num' => 6, 'key' => 'completed', 'label' => 'Full Panel Access', 'icon' => 'unlock', 'desc' => 'All AI tools and features are now active.'],
                    ];
                    $currentStepLabel = $agencySteps[$step - 1]['label'] ?? 'Payment Required';
                @endphp

                {{-- Agency stats row - dynamic based on step --}}
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
                            @if($step >= 2)
                                <div class="stat-num" style="color:#28a745;">✓</div>
                                <div class="stat-label">Payment Done</div>
                            @else
                                <div class="stat-num" style="color:#ffc107;">⏳</div>
                                <div class="stat-label">Payment Required</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Agency steps card - dynamic --}}
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-semibold">YOUR ACTIVATION PROGRESS — Step {{ $step }} of 6</h6>
                    </div>
                    <div class="card-body px-4 waitlist-steps">
                        {{-- Account Created - always done --}}
                        <div class="step-item">
                            <div class="step-icon done">
                                <i data-feather="check" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">✓ Account Created</div>
                                <div class="text-muted small">Your profile has been created successfully.</div>
                            </div>
                        </div>
                        {{-- Email Verified - always done --}}
                        <div class="step-item">
                            <div class="step-icon done">
                                <i data-feather="check" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">✓ Email Verified</div>
                                <div class="text-muted small">Your email address has been confirmed successfully.</div>
                            </div>
                        </div>
                        {{-- Dynamic steps based on onboarding_step --}}
                        @foreach($agencySteps as $s)
                            @php
                                $isDone = $step > $s['num'];
                                $isActive = $step == $s['num'];
                                $isPending = $step < $s['num'];
                            @endphp
                            <div class="step-item">
                                <div class="step-icon {{ $isDone ? 'done' : ($isActive ? 'active' : 'pending') }}">
                                    @if($isDone)
                                        <i data-feather="check" style="width:14px;height:14px;"></i>
                                    @elseif($isActive)
                                        <i data-feather="clock" style="width:14px;height:14px;"></i>
                                    @else
                                        <i data-feather="{{ $s['icon'] }}" style="width:14px;height:14px;"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-semibold {{ $isPending ? 'text-muted' : '' }}">
                                        @if($isDone) ✓ @elseif($isActive) ⏳ @else ○ @endif
                                        {{ $s['label'] }}
                                    </div>
                                    <div class="text-muted small">{{ $s['desc'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @endif

                {{-- Footer note + sign out --}}
                <div class="text-center mb-5">
                 
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
