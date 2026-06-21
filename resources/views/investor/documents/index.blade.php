@extends('layouts.simple.master')
@section('title', 'Documents')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Documents</h1>
            <p>Your investment documents including subscription agreements, KYC files, and project materials.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    @if(session('success'))
    <div class="vb-notice" style="margin-bottom: 20px; background: #edf7ee; border-color: #86efac;">{{ session('success') }}</div>
    @endif

    <div class="vb-card" style="margin-bottom: 28px;">
        <h2 class="vb-section-title">Upload Document</h2>
        <form action="{{ route('investor.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: flex; gap: 12px; align-items: flex-end;">
                <div style="flex: 1;">
                    <input type="file" name="document" class="vb-input" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>
                <button type="submit" class="vb-btn">Upload</button>
            </div>
            <div class="vb-period" style="margin-top: 8px;">Accepted: PDF, DOC, DOCX, JPG, PNG. Max 10MB.</div>
        </form>
    </div>

    <div class="vb-card">
        <h2 class="vb-section-title">My Documents</h2>
        @if(count($documents))
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Project</th>
                    <th>Size</th>
                    <th>Uploaded</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($documents as $doc)
            <tr>
                <td><strong>{{ $doc['name'] }}</strong></td>
                <td>—</td>
                <td>{{ number_format($doc['size'] / 1024, 1) }} KB</td>
                <td>{{ \Carbon\Carbon::createFromTimestamp($doc['date'])->format('M d, Y H:i') }}</td>
                <td><span class="vb-badge vb-badge-success">Uploaded</span></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @if($documents instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="margin-top: 20px;">{{ $documents->links() }}</div>
        @endif
        @else
        <div class="vb-empty">
            <h3>No documents uploaded yet</h3>
            <p>Upload your subscription agreement, KYC documents, or other investment materials above.</p>
        </div>
        @endif
    </div>
</div>
@endsection
