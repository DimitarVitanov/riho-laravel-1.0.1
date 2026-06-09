@extends('layouts.simple.master')
@section('title', 'Affiliate Tracking')
@section('breadcrumb-title')<h3>Affiliate Tracking</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Affiliate Tracking</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Reseller</th><th>Code</th><th>Landing URL</th><th>Converted User</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                    @forelse($referrals as $r)
                    <tr>
                        <td>{{ $r->resellerUser->first_name ?? '' }} {{ $r->resellerUser->last_name ?? '' }}</td>
                        <td><code>{{ $r->referral_code }}</code></td>
                        <td class="text-truncate" style="max-width:200px">{{ $r->landing_url ?? '—' }}</td>
                        <td>{{ $r->convertedUser ? $r->convertedUser->first_name . ' ' . $r->convertedUser->last_name : '—' }}</td>
                        <td><span class="badge bg-{{ $r->status === 'active_client' ? 'success' : ($r->status === 'signed_up' ? 'primary' : 'secondary') }}">{{ ucfirst($r->status) }}</span></td>
                        <td>{{ $r->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No referrals.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $referrals->links() }}
        </div>
    </div>
</div>
@endsection
