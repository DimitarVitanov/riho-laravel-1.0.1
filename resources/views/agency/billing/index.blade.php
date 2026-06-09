@extends('layouts.simple.master')
@section('title', 'Billing')
@section('breadcrumb-title')<h3>Billing</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency</a></li>
    <li class="breadcrumb-item active">Billing</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header pb-0"><h5>Subscription</h5></div>
        <div class="card-body">
            <p><strong>Plan:</strong> {{ $profile->subscription_plan_id ?? 'No plan' }}</p>
            <p><strong>Status:</strong> <span class="badge bg-{{ ($profile->subscription_status ?? '') === 'active' ? 'success' : 'warning' }}">{{ ucfirst($profile->subscription_status ?? 'inactive') }}</span></p>
            <hr>
            <p class="text-muted">Billing integration (Stripe/PayPal) will be configured here. Contact admin for plan changes.</p>
        </div>
    </div>
</div>
@endsection
