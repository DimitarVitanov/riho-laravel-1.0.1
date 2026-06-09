@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/quill.snow.css') }}">
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="page-title">
      <div class="row g-2">
        <div class="col-sm-6">
          <h4>Blogs</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">                                       
                <svg class="stroke-icon">
                  <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                </svg></a></li>
            <li class="breadcrumb-item">Blogs</li>
            <li class="breadcrumb-item active">Create</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card height-equal">
                    <div class="card-header">
                        <h4>Add Blog</h4>
                    </div>
                    <div class="card-body add-post">
                        <form class="row g-3 custom-input" id="createBlogForm" action="{{ route('admin.blog.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @include('admin.blog.fields')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script src="{{ asset('assets/js/editors/quill.js') }}"></script>
    <script src="{{ asset('assets/js/bookmark/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom-add-product4.js') }}"></script>
    <script src="{{ asset('assets/js/custom-validation/validation.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select-placeholder.js') }}"></script>
@endsection
