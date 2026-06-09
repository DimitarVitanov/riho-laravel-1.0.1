@extends('layouts.simple.master')
@section('title', 'Capital Call Follow-up')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Capital Call Follow-up</h1>
            <p>Track and follow up on capital call requests for your assigned investors.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Investor</th>
                    <th>Project</th>
                    <th>Call #</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($capitalCalls as $call)
                <tr>
                    <td><strong>{{ $call->investorUser->full_name ?? '—' }}</strong></td>
                    <td>{{ $call->project->project_name ?? '—' }}</td>
                    <td>{{ $call->call_number }}</td>
                    <td>€{{ number_format($call->requested_amount, 0) }}</td>
                    <td>{{ $call->due_date ? \Carbon\Carbon::parse($call->due_date)->format('M d, Y') : '—' }}</td>
                    <td>
                        @php
                            $cClass = match($call->status) {
                                'paid' => 'vb-badge-success',
                                'overdue' => 'vb-badge-danger',
                                'sent', 'viewed' => 'vb-badge-warning',
                                default => 'vb-badge-muted'
                            };
                        @endphp
                        <span class="vb-badge {{ $cClass }}">{{ ucfirst($call->status) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="vb-empty">
                            <h3>No capital calls requiring follow-up</h3>
                            <p>All capital calls are up to date.</p>
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
