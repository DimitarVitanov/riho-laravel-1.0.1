@extends('layouts.simple.master')
@section('title', 'Affiliate / Reseller')
@section('breadcrumb-title')<h3>Affiliate Program</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency</a></li>
    <li class="breadcrumb-item active">Affiliate</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body text-center py-5">
            <h4 class="text-muted">Reseller / Affiliate program is not enabled for your account.</h4>
            <p class="text-muted">Contact your account manager or admin to enable this feature.</p>
        </div>
    </div>
</div>
@endsection
