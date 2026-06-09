@extends('layouts.simple.master')

@section('css')
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Dashboard</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a>
                        </li>
                        <li class="breadcrumb-item active">Waiting List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#7366ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>

                        <h3 class="mb-3">You're on the Waiting List</h3>

                        <p class="text-muted mb-4" style="font-size: 1.1rem; max-width: 500px; margin: 0 auto;">
                            Thank you for registering, <strong>{{ $user->first_name }}</strong>!
                            Your account is currently being reviewed by our team.
                        </p>

                        <div class="alert alert-light-primary mb-4" style="max-width: 500px; margin: 0 auto;">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <div class="text-start">
                                    <strong>Account Type:</strong>
                                    {{ $user->account_type === 'real_estate_agency' ? 'Real Estate Agency' : 'Real Estate Investor' }}
                                </div>
                            </div>
                        </div>

                        <div class="card border mb-4" style="max-width: 500px; margin: 0 auto;">
                            <div class="card-body">
                                <h6 class="card-title">What happens next?</h6>
                                <ul class="list-unstyled text-start text-muted mb-0">
                                    <li class="mb-2">
                                        <i data-feather="check-circle" class="me-2 text-success" style="width: 16px; height: 16px;"></i>
                                        Account created successfully
                                    </li>
                                    <li class="mb-2">
                                        <i data-feather="check-circle" class="me-2 text-success" style="width: 16px; height: 16px;"></i>
                                        Email verified
                                    </li>
                                    <li class="mb-2">
                                        <i data-feather="clock" class="me-2 text-warning" style="width: 16px; height: 16px;"></i>
                                        Waiting for team activation (in progress)
                                    </li>
                                    <li class="mb-2">
                                        <i data-feather="circle" class="me-2 text-muted" style="width: 16px; height: 16px;"></i>
                                        AI features setup & onboarding
                                    </li>
                                    <li>
                                        <i data-feather="circle" class="me-2 text-muted" style="width: 16px; height: 16px;"></i>
                                        Full panel access granted
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <p class="text-muted small">
                            You'll receive an email notification once your account is activated.<br>
                            Questions? Contact us at <a href="mailto:support@villabit.ai">support@villabit.ai</a>
                        </p>

                        <div class="mt-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary">Sign Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
