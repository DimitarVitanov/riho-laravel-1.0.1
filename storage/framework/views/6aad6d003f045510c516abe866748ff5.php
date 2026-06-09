<!DOCTYPE html>
<html lang="en">
<?php use \App\Helpers\Helpers; ?>
<?php
    $content = Helpers::getLandingPage();
?>
<?php
    $settings = Helpers::getSettings();
?>

<head>
    <?php echo $__env->make('frontend.layouts.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>

<body class="landing-page">
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="landing-page"><span class="cursor"><span class="cursor-move-inner"><span
                    class="cursor-inner"></span></span><span class="cursor-move-outer"><span class="cursor-outer">
                </span></span></span>
        <!-- Page Body Start -->
        <div class="landing-home" id="home">
            <div class="container-fluid">
                <div class="sticky-header">
                    <?php echo $__env->make('frontend.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-sm-10">
                        <div class="best-selling"><img class="img-fluid" src="<?php echo e(asset(@$content['home']['left_top_image'])); ?>"
                                alt="selling-product">
                            <div class="img-shadow"></div>
                        </div>
                        <div class="nft-marketplace"> <img class="img-fluid"
                                src="<?php echo e(asset(@$content['home']['right_top_image'])); ?>" alt="nft-marketplace">
                            <div class="nft-marketplace-shadow"></div>
                        </div>
                        <div class="content text-center">
                            <div>
                                <?php echo @$content['home']['title']; ?>

                                <div class="arrow-animate">
                                    <svg>
                                        <use href="<?php echo e(asset('frontend/svg/icon-sprite.svg#animated-arrow')); ?>"></use>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="user-content">
                            <span class="text-center"><?php echo e(@$content['home']['description']); ?></span>
                        </div>
                        <div class="star-animate"> <img class="img-fluid"
                                src="<?php echo e(asset('frontend/images/landing/Vector.png')); ?>" alt="Vector"></div>
                        <div class="screen-1"> <img class="img-fluid" src="<?php echo e(asset(@$content['home']['main_img'])); ?>"
                                alt="dashboard-img"></div>
                        <div class="screen-2"> <img class="img-fluid sidebar-cuts-image"
                                src="<?php echo e(asset(@$content['home']['sidebar_cuts_image'])); ?>" alt="sidebarcuts">
                            <div class="screen-sidebar"></div>
                        </div>
                        <div class="total-revenue-img"><img class="img-fluid"
                                src="<?php echo e(asset(@$content['home']['left_bottom_image'])); ?>" alt="totalrevenue">
                            <div class="total-revenue-shadow"> </div>
                        </div>
                        <div class="star-img"> <img class="img-fluid start-animate fa-spin"
                                src="<?php echo e(asset('frontend/images/landing/star.png')); ?>" alt="star"></div>
                        <div class="new-user-img"><img class="img-fluid"
                                src="<?php echo e(asset(@$content['home']['right_bottom_image'])); ?>" alt="new-user">
                            <div class="new-user-shadow"> </div>
                        </div>
                        <div class="star-img-left"> <img class="img-fluid start-animate-rotate fa-spin"
                                src="<?php echo e(asset('frontend/images/landing/star.png')); ?>" alt="star"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <?php echo $__env->make('frontend.layouts.script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html>
<?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/frontend/layouts/master.blade.php ENDPATH**/ ?>