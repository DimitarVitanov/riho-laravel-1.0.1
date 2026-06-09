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
    .stat-box .stat-label { font-size: 0.78rem; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
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
                        <li class="breadcrumb-item active">Waiting List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-9 col-md-11">

                {{-- Hero card --}}
                <div class="waitlist-hero mb-4">
                    <div class="waitlist-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Waiting List
                    </div>
                    <h2 class="fw-bold mb-2" style="color:#ffffff;">Welcome, {{ $user->first_name }}!</h2>
                    <p class="mb-0" style="color:rgba(255,255,255,0.92); font-size:1.05rem; max-width:520px; line-height:1.7;">
                        Your account is currently on the waiting list because our AI server resources are already allocated to existing clients, and full panel access will be granted on a first-registered, first-served basis as additional capacity becomes available.
                    </p>
                </div>

                {{-- Info message --}}
                <div class="card mb-4">
                    <div class="card-body" style="padding: 24px 28px;">
                        <p class="mb-0" style="line-height:1.75; font-size:0.97rem; color:#444;">
                            You do not need to take any further action at this stage. Demand for <strong>Villa Bit AI Server</strong> is currently higher than the available AI server resources, which is a sign of growing interest, and our team is actively working to expand capacity and activate new accounts every week.
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
                            <div class="stat-label">Awaiting Activation</div>
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
                                <div class="fw-semibold">Account registered</div>
                                <div class="text-muted small">Your profile has been created successfully.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon done">
                                <i data-feather="check" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Email verified</div>
                                <div class="text-muted small">Your email address has been confirmed.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon active">
                                <i data-feather="clock" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Team activation <span class="badge bg-warning text-dark ms-1" style="font-size:0.72rem;">In Progress</span></div>
                                <div class="text-muted small">Our team reviews and activates accounts weekly.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon pending">
                                <i data-feather="zap" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted">AI features & onboarding</div>
                                <div class="text-muted small">Your AI tools and workspace will be configured.</div>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon pending">
                                <i data-feather="unlock" style="width:14px;height:14px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted">Full panel access granted</div>
                                <div class="text-muted small">You'll receive an email the moment your account is activated.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer note + sign out --}}
                <div class="text-center mb-5">
                    <p class="text-muted small mb-3">
                        Questions? Reach us at <a href="mailto:support@villabit.ai" class="text-dark fw-semibold">support@villabit.ai</a>
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
