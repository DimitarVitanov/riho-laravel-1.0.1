@extends('layouts.simple.master')
@section('title', 'Capital Calls')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Capital Calls</h1>
            <p>Capital calls issued for your investment projects. Review amounts, due dates, and payment status.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    @php $pendingCount = ($capitalCalls instanceof \Illuminate\Pagination\LengthAwarePaginator ? $capitalCalls->getCollection() : $capitalCalls)->where('status', 'sent')->count(); @endphp
    @if($pendingCount)
    <div class="vb-notice" style="margin-bottom: 20px;">
        You have {{ $pendingCount }} capital call(s) awaiting payment. Please contact your account manager to arrange settlement.
    </div>
    @endif

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Project</th>
                    <th>Call #</th>
                    <th>Requested Amount</th>
                    <th>Reason / Phase</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($capitalCalls as $cc)
            <tr>
                <td>{{ $cc->created_at->format('Y-m-d') }}</td>
                <td><strong>{{ $cc->project->project_name ?? '—' }}</strong></td>
                <td>{{ $cc->call_number }}</td>
                <td>€{{ number_format($cc->requested_amount, 0) }}</td>
                <td>{{ $cc->construction_phase ?? $cc->reason ?? '—' }}</td>
                <td>{{ $cc->due_date ? \Carbon\Carbon::parse($cc->due_date)->format('Y-m-d') : '—' }}</td>
                <td>
                    @php
                        $cls = match($cc->status) {
                            'paid' => 'vb-badge-success',
                            'overdue' => 'vb-badge-danger',
                            'sent' => 'vb-badge-warning',
                            default => 'vb-badge-muted'
                        };
                    @endphp
                    <span class="vb-badge {{ $cls }}">{{ ucfirst($cc->status) }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="vb-empty">
                        <h3>No capital calls</h3>
                        <p>Capital calls will appear here when your project manager issues them.</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        <div style="margin-top: 20px;">{{ $capitalCalls->links() }}</div>
    </div>
</div>
@endsection
