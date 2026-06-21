@extends('layouts.simple.master')
@section('title', __('messages.affiliate'))

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>{{ __('messages.affiliate') }}</h1>
            <p>Reseller program — earn commissions by referring new users.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    <div class="vb-card" style="text-align:center;padding:48px 24px;">
        <div style="font-size:48px;margin-bottom:16px;">🔒</div>
        <h2 style="font-size:20px;font-weight:700;color:#111827;margin-bottom:8px;">Reseller Access Not Yet Enabled</h2>
        <p style="color:#6b7280;max-width:440px;margin:0 auto 20px;">
            The affiliate/reseller program is not currently enabled for your account. Please contact support if you are interested in joining the program.
        </p>
        <a href="{{ route('investor.support.create') }}" class="vb-btn vb-btn-primary">Contact Support</a>
    </div>
</div>
@endsection
