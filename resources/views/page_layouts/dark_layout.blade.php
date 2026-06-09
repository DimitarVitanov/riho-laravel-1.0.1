@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="page-title">
      <div class="row g-2">
        <div class="col-sm-6">
          <h4>Layout Dark</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.default_dashboard') }}">                                       
                <svg class="stroke-icon">
                  <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                </svg></a></li>
            <li class="breadcrumb-item">Page Layout</li>
            <li class="breadcrumb-item active"> Layout Dark</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card alert alert-primary" role="alert">
                    <h5>Tip!</h5>
                    <p>Add the class="dark-only" to the body tag to get this layout.</p>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Title</h4>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="fa fa-spin fa-cog"></i></li>
                                <li><i class="view-html fa fa-code"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li><i class="icofont icofont-refresh reload-card"></i></li>
                                <li><i class="icofont icofont-error close-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body"><span>Start Here</span>
                        <div class="code-box-copy">
                            <button class="code-box-copy__btn btn-clipboard" data-clipboard-target="#example-head"
                                title="Copy"><i class="icofont icofont-copy-alt"></i></button>
                            <pre><code class="language-html" id="example-head">&lt;!-- Cod Box Copy begin --&gt;
&lt;div class=&quot;card-body&quot;&gt;
&lt;span&gt;Start Here&lt;/span&gt;
&lt;/div&gt;    
&lt;!-- Cod Box Copy end --&gt;</code></pre>
                        </div>
                    </div>
                    <div class="card-footer">
                        <h6 class="mb-0">Card Footer</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/prism/prism.min.js') }}"></script>
    <script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom-card/custom-card.js') }}"></script>
    <!-- calendar js-->
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
@endsection
