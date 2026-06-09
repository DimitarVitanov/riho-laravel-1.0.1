@extends('layouts.simple.master')
@section('title', 'Support Notes')
@section('breadcrumb-title')<h3>Support Notes</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('manager.dashboard') }}">Manager</a></li>
    <li class="breadcrumb-item active">Support Notes</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header pb-0"><h5>Add Support Note</h5></div>
        <div class="card-body">
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            <form action="{{ route('manager.support-notes.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">User ID</label>
                        <input type="number" name="user_id" class="form-control" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Note</label>
                        <textarea name="note" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <button class="btn btn-primary">Add Note</button>
            </form>
        </div>
    </div>
</div>
@endsection
