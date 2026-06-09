<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/scrollable.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main_content'); ?>
    <div class="container-fluid basic_table">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>Roles</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.default_dashboard')); ?>">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-home')); ?>"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Laravel Example</li>
                        <li class="breadcrumb-item active">Roles</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-6 box-col-12">
                <div class="card role-management">
                    <div class="card-body">
                        <div class="blog-card">
                            <div class="blog-card-content">
                                <div class="role-details">
                                    <div class="role-profile">
                                        <?php
                                            $image = auth()->user()->getFirstMedia('image');
                                        ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($image)): ?>
                                            <img src="<?php echo e($image->getUrl()); ?>" alt="Image">
                                        <?php else: ?>
                                            <img src="<?php echo e(asset('assets/images/avtar/3.jpg')); ?>" alt="Image">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div class="role-profile-details">
                                        <h4>Welcome back
                                            <?php echo e(\Illuminate\Support\Str::title(auth()->user()->first_name ?? '')); ?>

                                            <?php echo e(\Illuminate\Support\Str::title(auth()->user()->last_name ?? '')); ?>!</h4>
                                    </div>
                                </div>
                                <div class="blog-tags">
                                    <div class="tags-icon bg-primary">
                                        <svg class="stroke-icon">
                                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#multi-user')); ?>"></use>
                                        </svg>
                                    </div>
                                    <div class="tag-details">
                                        <h2 class="total-num counter">
                                            <?php echo e(sprintf('%02d', \App\Models\User::count())); ?>

                                        </h2>
                                        <p data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Total Users" data-bs-original-title="Total Users">Total Users</p>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-card-image">
                                <img src="<?php echo e(asset('assets/images/role-management.png')); ?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6 box-col-6">
                <div class="card">
                    <div class="card-body border-b-primary border-2">
                        <div class="upcoming-box">
                            <div class="upcoming-icon bg-primary">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-social')); ?>"></use>
                                </svg>
                            </div>
                            <p>Role</p>
                            <a href="<?php echo e(route('admin.role.create')); ?>" class="btn btn-primary"><?php echo e(__('Add Role')); ?></a>
                        </div>
                        <ul class="bubbles role">
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6 box-col-6">
                <div class="card">
                    <div class="card-body border-b-secondary border-2">
                        <div class="upcoming-box">
                            <div class="upcoming-icon bg-secondary">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#user-plus')); ?>"></use>
                                </svg>
                            </div>
                            <p>User</p>
                            <a href="<?php echo e(route('admin.user.create')); ?>" class="btn btn-secondary"><?php echo e(__('Add User')); ?></a>
                        </div>
                        <ul class="bubbles role role-user">
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                            <li class="bubble"></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Container-fluid starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-block row">
                        <div class="role-table">
                            <div class="table-responsive p-3">
                                <?php echo $dataTable->table(); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Container-fluid Ends-->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <!-- calendar js-->
    <script src="<?php echo e(asset('assets/js/scrollable/perfect-scrollbar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/scrollable/scrollable-custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/tooltip-init.js')); ?>"></script>    
    <?php echo $dataTable->scripts(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/admin/role/index.blade.php ENDPATH**/ ?>