@use('App\Helpers\Helpers')
@php
    $settings = Helpers::getSettings();
@endphp

<!-- footer start-->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 footer-copyright text-center">
                <p class="mb-0">{{ $settings['general']['footer'] }}</p>
            </div>
        </div>
    </div>
</footer>
<!-- footer ends-->
