@extends('layouts.simple.master')
@section('title', 'Add Villa Ready Property')
@section('breadcrumb-title')<h3>Add Villa Ready Property</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.villa-ready.properties.index') }}">Villa Ready Properties</a></li>
    <li class="breadcrumb-item active">Add Property</li>
@endsection
@section('content')
<div class="container-fluid">
    @include('admin.villabit.villa-ready.properties._form', ['property' => null])
</div>
@endsection
