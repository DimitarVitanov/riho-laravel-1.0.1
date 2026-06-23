@extends('layouts.simple.master')
@section('title', 'AI API Settings')
@section('breadcrumb-title')<h3>AI API Settings</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">AI API Settings</li>
@endsection
@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- API Call Stats --}}
    <div class="row mb-4">
        @php
            $providers = [
                'openai'    => ['name' => 'OpenAI',        'color' => 'primary'],
                'gemini'    => ['name' => 'Google Gemini', 'color' => 'success'],
                'anthropic' => ['name' => 'Anthropic',     'color' => 'warning'],
            ];
        @endphp
        @foreach($providers as $key => $prov)
        <div class="col-md-4">
            <div class="card border-{{ $prov['color'] }}">
                <div class="card-header bg-{{ $prov['color'] }} text-white py-2">
                    <strong>{{ $prov['name'] }}</strong> — This Month
                </div>
                <div class="card-body py-3">
                    @php $m = $monthly[$key] ?? null; $a = $allTime[$key] ?? null; @endphp
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="fs-4 fw-bold">{{ number_format($m['total_calls'] ?? 0) }}</div>
                            <small class="text-muted">Calls</small>
                        </div>
                        <div class="col-4">
                            <div class="fs-4 fw-bold">{{ number_format(($m['total_tokens_in'] ?? 0) + ($m['total_tokens_out'] ?? 0)) }}</div>
                            <small class="text-muted">Tokens</small>
                        </div>
                        <div class="col-4">
                            <div class="fs-4 fw-bold">${{ number_format($m['total_cost'] ?? 0, 4) }}</div>
                            <small class="text-muted">Est. Cost</small>
                        </div>
                    </div>
                    <hr class="my-2">
                    <small class="text-muted">All-time: {{ number_format($a['total_calls'] ?? 0) }} calls · ${{ number_format($a['total_cost'] ?? 0, 2) }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- API Keys Form --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">API Keys Configuration</h5>
            <small class="text-muted">Keys are stored in <code>.env</code>. Changes take effect after application restart.</small>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.villabit.ai-settings.update') }}">
                @csrf @method('PUT')

                {{-- OpenAI --}}
                <h6 class="text-primary mb-3 mt-2">🤖 OpenAI</h6>
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label">API Key</label>
                        <input type="text" name="OPENAI_API_KEY" class="form-control font-monospace"
                               value="{{ $keys['openai']['key'] ? substr($keys['openai']['key'], 0, 8) . str_repeat('*', 20) . substr($keys['openai']['key'], -4) : '' }}"
                               placeholder="sk-proj-...">
                        <small class="text-muted">Leave unchanged to keep current key. Paste new key to update.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Default Model</label>
                        <input type="text" name="OPENAI_DEFAULT_MODEL" class="form-control"
                               value="{{ $keys['openai']['model'] }}" placeholder="gpt-4o">
                    </div>
                </div>

                {{-- Google Gemini --}}
                <h6 class="text-success mb-3 mt-4">✨ Google Gemini</h6>
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label">API Key</label>
                        <input type="text" name="GOOGLE_AI_API_KEY" class="form-control font-monospace"
                               value="{{ $keys['gemini']['key'] ? substr($keys['gemini']['key'], 0, 6) . str_repeat('*', 20) . substr($keys['gemini']['key'], -4) : '' }}"
                               placeholder="AIza...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Default Model</label>
                        <input type="text" name="GOOGLE_AI_DEFAULT_MODEL" class="form-control"
                               value="{{ $keys['gemini']['model'] }}" placeholder="gemini-pro">
                    </div>
                </div>

                {{-- Anthropic --}}
                <h6 class="text-warning mb-3 mt-4">🔮 Anthropic (Claude)</h6>
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label">API Key</label>
                        <input type="text" name="ANTHROPIC_API_KEY" class="form-control font-monospace"
                               value="{{ $keys['anthropic']['key'] ? substr($keys['anthropic']['key'], 0, 8) . str_repeat('*', 20) . substr($keys['anthropic']['key'], -4) : '' }}"
                               placeholder="sk-ant-...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Default Model</label>
                        <input type="text" name="ANTHROPIC_DEFAULT_MODEL" class="form-control"
                               value="{{ config('ai.anthropic.default_model') }}" placeholder="claude-sonnet-4-...">
                    </div>
                </div>

                {{-- Copyscape --}}
                <h6 class="text-danger mb-3 mt-4">🔍 Copyscape (System-wide Uniqueness Check)</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Username</label>
                        <input type="text" name="COPYSCAPE_USERNAME" class="form-control"
                               value="{{ $keys['copyscape']['username'] }}" placeholder="your_username">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">API Key</label>
                        <input type="text" name="COPYSCAPE_API_KEY" class="form-control font-monospace"
                               value="{{ $keys['copyscape']['key'] ? str_repeat('*', 16) . substr($keys['copyscape']['key'], -4) : '' }}"
                               placeholder="Copyscape API key">
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">Save API Keys</button>
            </form>
        </div>
    </div>

    {{-- Recent API Call Log --}}
    @if($recentLogs->count())
    <div class="card mt-4">
        <div class="card-header"><h6 class="mb-0">Recent API Calls (last 50)</h6></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped mb-0">
                    <thead><tr><th>Time</th><th>Provider</th><th>Model</th><th>Feature</th><th>Tokens In</th><th>Tokens Out</th><th>Cost</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($recentLogs as $log)
                    <tr>
                        <td><small>{{ $log->created_at->format('d M H:i') }}</small></td>
                        <td><span class="badge bg-secondary">{{ $log->provider }}</span></td>
                        <td><small>{{ $log->model_name ?? '—' }}</small></td>
                        <td><small>{{ $log->feature_key ?? '—' }}</small></td>
                        <td>{{ number_format($log->tokens_input) }}</td>
                        <td>{{ number_format($log->tokens_output) }}</td>
                        <td>${{ number_format($log->cost_estimate_usd, 5) }}</td>
                        <td><span class="badge bg-{{ $log->status === 'success' ? 'success' : 'danger' }}">{{ $log->status }}</span></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card mt-4">
        <div class="card-body text-center text-muted py-4">
            No API calls logged yet. Logs will appear here once AI features start making real API calls.
        </div>
    </div>
    @endif

</div>
@endsection
