<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/jkanban.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main_content'); ?>
    <div class="container-fluid">
        <div class="page-title">
            <div class="row g-2">
                <div class="col-sm-6">
                    <h4>
                        Kanban Board</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.default_dashboard')); ?>">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-home')); ?>"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item"> Apps</li>
                        <li class="breadcrumb-item active"> Kanban Board</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid jkanban-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Default Demo </h4>
                    </div>
                    <div class="card-body">
                        <div id="demo1"></div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Custom Board </h4>
                        <p class="mb-0">| colors, gutter, click on board&apos;s item and restricting which boards to drag
                            items to </p>
                    </div>
                    <div class="card-body">
                        <div id="demo2"></div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>API</h4>
                        <p class="mb-0">add item, add board, delete board: </p>
                    </div>
                    <div class="card-body">
                        <div id="demo3"></div>
                        <button class="btn btn-success" id="addDefault">Add &quot;Default&quot; board</button>
                        <button class="btn btn-success" id="addToDo">Add element in &quot;To Do&quot; Board</button>
                        <button class="btn btn-danger" id="removeBoard">Remove &quot;Done&quot; Board</button>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card note p-20">jKanban is Pure agnostic Javascript plugin for Kanban boards for more options
                    please refer <a href="http://www.riccardotartaglia.it/jkanban/" target="_blank">jkanban Official site
                    </a>And <a href="https://github.com/riktar/jkanban" target="_blank">githup link</a></div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/jkanban/jkanban.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jkanban/custom.js')); ?>"></script>
    <!-- calendar js-->
    <script src="<?php echo e(asset('assets/js/typeahead/handlebars.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/typeahead/typeahead.bundle.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/typeahead/typeahead.custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/typeahead-search/handlebars.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/typeahead-search/typeahead-custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/kanban.blade.php ENDPATH**/ ?>