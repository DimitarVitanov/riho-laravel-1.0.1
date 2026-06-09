<!DOCTYPE html>
<html lang="en" <?php if(Route::currentRouteName() == 'admin.rtl_layout'): ?> dir="rtl" <?php endif; ?>>

<head>
    <?php echo $__env->make('layouts.simple.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('layouts.simple.css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch(Route::currentRouteName()):
    case ('admin.box_layout'): ?>
        <body class="box-layout">
        <?php break; ?>

    <?php case ('admin.rtl_layout'): ?>
        <body class="rtl">
        <?php break; ?>

    <?php case ('admin.dark_layout'): ?>
        <body class="dark-only">
        <?php break; ?>

    <?php default: ?>
        <body>
<?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <!-- loader starts-->
                <div class="loader-wrapper">
                    <div class="loader">
                        <div class="loader4"></div>
                    </div>
                </div>
                <!-- loader ends-->

                <!-- tap on top starts-->
                <div class="tap-top"><i data-feather="chevrons-up"></i></div>
                <!-- tap on tap ends-->

                <!-- page-wrapper Start-->
                <div class="page-wrapper compact-wrapper" id="pageWrapper">

                    <!-- Page header start -->
                    <?php echo $__env->make('layouts.simple.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <!-- Page header end-->

                    <!-- Page Body Start-->
                    <div class="page-body-wrapper">

                        <!-- Page sidebar start-->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(str_starts_with(Route::currentRouteName() ?? '', 'admin.villabit.') || str_starts_with(Route::currentRouteName() ?? '', 'manager.') || str_starts_with(Route::currentRouteName() ?? '', 'agency.') || str_starts_with(Route::currentRouteName() ?? '', 'investor.')): ?>
                            <?php echo $__env->make('layouts.simple.sidebar-villabit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php else: ?>
                            <?php echo $__env->make('layouts.simple.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <!-- Page sidebar end-->

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch(Route::currentRouteName()):
                            case ('admin.checkout'): ?>
                                <div class="page-body checkout">
                                <?php break; ?>

                            <?php default: ?>
                                <div class="page-body">
                        <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php echo $__env->yieldContent('main_content'); ?>
                            </div>

                            <!-- footer start-->
                            <?php echo $__env->make('layouts.simple.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <!-- footer end-->
                        </div>
                    </div>
                    <!-- page-wrapper Ends-->

                    
                    <?php echo $__env->make('layouts.simple.script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('admin.inc.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    

            </body>

</html>
<?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/layouts/simple/master.blade.php ENDPATH**/ ?>