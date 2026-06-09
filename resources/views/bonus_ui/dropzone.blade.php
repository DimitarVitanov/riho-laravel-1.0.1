@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dropzone.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/filepond.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/filepond-plugin-image-preview.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Dropzone</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Bonus Ui</li>
                        <li class="breadcrumb-item active">Dropzone</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Default File Upload</h4>
                        <p class="f-m-light mt-1">We use the create method to turn a<code> &lt;input
                                type="file"&gt;</code> into a FilePond drop
                            area.<code>[https://pqina.nl/filepond/]</code></p>
                    </div>
                    <div class="card-body">
                        <input class="no-preview" type="file">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Image Preview </h4>
                        <p class="f-m-light mt-1">We use the create method to turn a<code> &lt;input
                                type="file"&gt;</code> into a FilePond drop
                            area.<code>[https://pqina.nl/filepond/]</code></p>
                    </div>
                    <div class="card-body">
                        <input class="show-preview" type="file">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Single File Upload</h4>
                        <p class="f-m-light mt-1"></p>Use the <code>.dropzone </code> class through create upload
                        files.<code>[https://www.dropzone.dev/].</code>
                    </div>
                    <div class="card-body">
                        <form class="dropzone" id="singleFileUpload" action="/upload.php">
                            <div class="dropzone-wrapper">
                                <div class="dz-message needsclick"><i class="icon-cloud-up"></i>
                                    <h6 class="f-w-600">Drop files here or click to upload.</h6><span
                                        class="note needsclick">(This is just a demo dropzone. Selected files are
                                        <strong>not</strong> actually uploaded.)</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Multi File Upload</h4>
                        <p class="f-m-light mt-1">Use the <code>.dropzone </code> class through create upload
                            files.<code>[https://www.dropzone.dev/].</code></p>
                    </div>
                    <div class="card-body">
                        <form class="dropzone dropzone-secondary" id="multiFileUpload" action="/upload.php">
                            <div class="dz-message needsclick"><i class="icon-cloud-up"></i>
                                <h6 class="f-w-600">Drop files here or click to upload.</h6><span
                                    class="note needsclick">(This is just a demo dropzone. Selected files are
                                    <strong>not</strong> actually uploaded.)</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <!-- calendar js-->
    <script src="{{ asset('assets/js/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone/dropzone-script.js') }}"></script>
    <script src="{{ asset('assets/js/filepond/filepond-plugin-image-preview.js') }}"></script>
    <script src="{{ asset('assets/js/filepond/filepond-plugin-file-rename.js') }}"></script>
    <script src="{{ asset('assets/js/filepond/filepond-plugin-image-transform.js') }}"></script>
    <script src="{{ asset('assets/js/filepond/filepond.js') }}"></script>
    <script src="{{ asset('assets/js/filepond/custom-filepond.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
@endsection
