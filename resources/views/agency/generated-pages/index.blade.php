@extends('layouts.simple.master')
@section('title', 'Generated Pages')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Generated Pages</h1>
            <p>All AI-generated content pages for your agency, including uniqueness status and publish workflow.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Feature</th>
                    <th>Uniqueness</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr>
                    <td><strong>{{ Str::limit($page->title, 40) }}</strong></td>
                    <td>{{ ucfirst(str_replace('_', ' ', $page->feature_key)) }}</td>
                    <td>
                        @php
                            $uClass = match($page->content_uniqueness_status) {
                                'passed' => 'vb-badge-success',
                                'failed' => 'vb-badge-danger',
                                'checking' => 'vb-badge-warning',
                                default => 'vb-badge-info'
                            };
                        @endphp
                        <span class="vb-badge {{ $uClass }}">{{ strtoupper($page->content_uniqueness_status) }}</span>
                    </td>
                    <td>
                        <span class="vb-badge {{ $page->status === 'published' ? 'vb-badge-success' : 'vb-badge-muted' }}">{{ ucfirst($page->status) }}</span>
                    </td>
                    <td>{{ $page->published_at ? $page->published_at->format('M d, Y') : '—' }}</td>
                    <td>
                        <a href="{{ route('agency.generated-pages.show', $page) }}" class="vb-btn vb-btn-sm">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="vb-empty">
                            <h3>No generated pages yet</h3>
                            <p>AI will create pages as your features run.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($pages instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div style="margin-top: 20px;">{{ $pages->links() }}</div>
        @endif
    </div>
</div>
@endsection
