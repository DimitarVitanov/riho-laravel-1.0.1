@extends('layouts.simple.master')
@section('title', 'Documents')
@section('breadcrumb-title')<h3>Documents</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('investor.dashboard') }}">Investor</a></li>
    <li class="breadcrumb-item active">Documents</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header pb-0"><h5>Upload Document</h5></div>
        <div class="card-body">
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            <form action="{{ route('investor.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8"><input type="file" name="document" class="form-control" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"></div>
                    <div class="col-md-4"><button class="btn btn-primary w-100">Upload</button></div>
                </div>
                <small class="text-muted">Accepted: PDF, DOC, DOCX, JPG, PNG. Max 10MB.</small>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header pb-0"><h5>My Documents</h5></div>
        <div class="card-body">
            @if(count($documents))
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>File</th><th>Size</th><th>Uploaded</th></tr></thead>
                    <tbody>
                    @foreach($documents as $doc)
                    <tr>
                        <td>{{ $doc['name'] }}</td>
                        <td>{{ number_format($doc['size'] / 1024, 1) }} KB</td>
                        <td>{{ \Carbon\Carbon::createFromTimestamp($doc['date'])->format('M d, Y H:i') }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted">No documents uploaded yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
