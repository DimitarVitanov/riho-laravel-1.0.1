@extends('layouts.simple.master')
@section('title', 'Global AI Prompts')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Global AI Prompts</h1>
            <p>Manage the system AI prompts used by each feature. Changes affect all agencies.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Feature</th>
                    <th>Label</th>
                    <th>Provider</th>
                    <th>Model</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prompts as $prompt)
                <tr>
                    <td><strong>{{ ucfirst(str_replace('_', ' ', $prompt->feature_key)) }}</strong></td>
                    <td>{{ $prompt->label }}</td>
                    <td>{{ ucfirst($prompt->ai_model_provider) }}</td>
                    <td><code>{{ $prompt->ai_model_name }}</code></td>
                    <td>
                        <span class="vb-badge {{ $prompt->is_active ? 'vb-badge-success' : 'vb-badge-muted' }}">
                            {{ $prompt->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.villabit.ai-prompts.edit', $prompt) }}" class="vb-btn vb-btn-sm">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="vb-empty">
                            <h3>No prompts configured</h3>
                            <p>AI prompts will be seeded during initial setup.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
