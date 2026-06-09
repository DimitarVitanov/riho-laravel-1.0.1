<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/photoswipe.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main_content'); ?>
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Gallery</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.default_dashboard')); ?>">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-home')); ?>"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Gallery</li>
                        <li class="breadcrumb-item active">Gallery</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>IMAGE GALLERY</h4>
                    </div>
                    <div class="gallery my-gallery card-body row" itemscope="">
                        <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope=""><a
                                href="<?php echo e(asset('assets/images/big-lightgallry/01.jpg')); ?>" itemprop="contentUrl"
                                data-size="1600x950"><img class="img-thumbnail"
                                    src="<?php echo e(asset('assets/images/lightgallry/01.jpg')); ?>" itemprop="thumbnail"
                                    alt="Image description"></a>
                            <figcaption itemprop="caption description">Image caption 1</figcaption>
                        </figure>
                        <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope=""><a
                                href="<?php echo e(asset('assets/images/big-lightgallry/02.jpg')); ?>" itemprop="contentUrl"
                                data-size="1600x950"><img class="img-thumbnail"
                                    src="<?php echo e(asset('assets/images/lightgallry/02.jpg')); ?>" itemprop="thumbnail"
                                    alt="Image description"></a>
                            <figcaption itemprop="caption description">Image caption 2</figcaption>
                        </figure>
                        <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope=""><a
                                href="<?php echo e(asset('assets/images/big-lightgallry/03.jpg')); ?>" itemprop="contentUrl"
                                data-size="1600x950"><img class="img-thumbnail"
                                    src="<?php echo e(asset('assets/images/lightgallry/03.jpg')); ?>" itemprop="thumbnail"
                                    alt="Image description"></a>
                            <figcaption itemprop="caption description">Image caption 3</figcaption>
                        </figure>
                        <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope=""><a
                                href="<?php echo e(asset('assets/images/big-lightgallry/04.jpg')); ?>" itemprop="contentUrl"
                                data-size="1600x950"><img class="img-thumbnail"
                                    src="<?php echo e(asset('assets/images/lightgallry/04.jpg')); ?>" itemprop="thumbnail"
                                    alt="Image description"></a>
                            <figcaption itemprop="caption description">Image caption 4</figcaption>
                        </figure>
                        <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope=""><a
                                href="<?php echo e(asset('assets/images/big-lightgallry/05.jpg')); ?>" itemprop="contentUrl"
                                data-size="1600x950"><img class="img-thumbnail"
                                    src="<?php echo e(asset('assets/images/lightgallry/05.jpg')); ?>" itemprop="thumbnail"
                                    alt="Image description"></a>
                            <figcaption itemprop="caption description">Image caption 5</figcaption>
                        </figure>
                        <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope=""><a
                                href="<?php echo e(asset('assets/images/big-lightgallry/06.jpg')); ?>" itemprop="contentUrl"
                                data-size="1600x950"><img class="img-thumbnail"
                                    src="<?php echo e(asset('assets/images/lightgallry/06.jpg')); ?>" itemprop="thumbnail"
                                    alt="Image description"></a>
                            <figcaption itemprop="caption description">Image caption 8</figcaption>
                        </figure>
                        <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope=""><a
                                href="<?php echo e(asset('assets/images/big-lightgallry/07.jpg')); ?>" itemprop="contentUrl"
                                data-size="1600x950"><img class="img-thumbnail"
                                    src="<?php echo e(asset('assets/images/lightgallry/07.jpg')); ?>" itemprop="thumbnail"
                                    alt="Image description"></a>
                            <figcaption itemprop="caption description">Image caption 9</figcaption>
                        </figure>
                        <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope=""><a
                                href="<?php echo e(asset('assets/images/big-lightgallry/08.jpg')); ?>" itemprop="contentUrl"
                                data-size="1600x950"><img class="img-thumbnail"
                                    src="<?php echo e(asset('assets/images/lightgallry/08.jpg')); ?>" itemprop="thumbnail"
                                    alt="Image description"></a>
                            <figcaption itemprop="caption description">Image caption 10</figcaption>
                        </figure>
                        <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope=""><a
                                href="<?php echo e(asset('assets/images/big-lightgallry/09.jpg')); ?>" itemprop="contentUrl"
                                data-size="1600x950"><img class="img-thumbnail"
                                    src="<?php echo e(asset('assets/images/lightgallry/09.jpg')); ?>" itemprop="thumbnail"
                                    alt="Image description"></a>
                            <figcaption itemprop="caption description">Image caption 11</figcaption>
                        </figure>
                        <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope=""><a
                                href="<?php echo e(asset('assets/images/big-lightgallry/010.jpg')); ?>" itemprop="contentUrl"
                                data-size="1600x950"><img class="img-thumbnail"
                                    src="<?php echo e(asset('assets/images/lightgallry/010.jpg')); ?>" itemprop="thumbnail"
                                    alt="Image description"></a>
                            <figcaption itemprop="caption description">Image caption 12</figcaption>
                        </figure>
                        <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope=""><a
                                href="<?php echo e(asset('assets/images/big-lightgallry/011.jpg')); ?>" itemprop="contentUrl"
                                data-size="1600x950"><img class="img-thumbnail"
                                    src="<?php echo e(asset('assets/images/lightgallry/011.jpg')); ?>" itemprop="thumbnail"
                                    alt="Image description"></a>
                            <figcaption itemprop="caption description">Image caption 11</figcaption>
                        </figure>
                        <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope=""><a
                                href="<?php echo e(asset('assets/images/big-lightgallry/012.jpg')); ?>" itemprop="contentUrl"
                                data-size="1600x950"><img class="img-thumbnail"
                                    src="<?php echo e(asset('assets/images/lightgallry/012.jpg')); ?>" itemprop="thumbnail"
                                    alt="Image description"></a>
                            <figcaption itemprop="caption description">Image caption 12</figcaption>
                        </figure>
                    </div>
                    <!-- Root element of PhotoSwipe. Must have class pswp.-->
                    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="pswp__bg"></div>
                        <div class="pswp__scroll-wrap">
                            <div class="pswp__container">
                                <div class="pswp__item"></div>
                                <div class="pswp__item"></div>
                                <div class="pswp__item"></div>
                            </div>
                            <div class="pswp__ui pswp__ui--hidden">
                                <div class="pswp__top-bar">
                                    <div class="pswp__counter"></div>
                                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                                    <button class="pswp__button pswp__button--share" title="Share"></button>
                                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                                    <div class="pswp__preloader">
                                        <div class="pswp__preloader__icn">
                                            <div class="pswp__preloader__cut">
                                                <div class="pswp__preloader__donut"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                    <div class="pswp__share-tooltip"></div>
                                </div>
                                <button class="pswp__button pswp__button--arrow--left"
                                    title="Previous (arrow left)"></button>
                                <button class="pswp__button pswp__button--arrow--right"
                                    title="Next (arrow right)"></button>
                                <div class="pswp__caption">
                                    <div class="pswp__caption__center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <!-- calendar js-->
    <script src="<?php echo e(asset('assets/js/photoswipe/photoswipe.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/photoswipe/photoswipe-ui-default.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/photoswipe/photoswipe.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/gallery/gallery.blade.php ENDPATH**/ ?>