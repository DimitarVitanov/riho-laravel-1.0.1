<?php $__env->startSection('css'); ?>
    <!-- Toastr css-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/toastr.min.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main_content'); ?>
    <?php use \App\Helpers\Helpers; ?>
    <?php
        $settings = Helpers::getSettings();
    ?>
    <!-- loader starts-->
    <div class="loader-wrapper">
        <div class="loader">
            <div class="loader4"></div>
        </div>
    </div>
    <!-- loader ends-->
    <!-- login page start-->
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-12 p-0">
                <div class="login-card login-dark">
                    <div>
                        <div> 
                            <a class="logo" href="<?php echo e(route('admin.default_dashboard')); ?>">
                                <img class="img-fluid for-light" src="<?php echo e(asset($settings['general']['dark_logo'])); ?>"
                                    alt="looginpage">
                                <img class="img-fluid for-dark" src="<?php echo e(asset($settings['general']['light_logo'])); ?>"
                                    alt="looginpage">
                            </a>
                        </div>
                        <div class="login-main">
                            <form class="theme-form" method="POST" action="<?php echo e(route('login')); ?>" id="loginForm">
                                <?php echo csrf_field(); ?>
                                <h4>Sign in to Villa Bit AI</h4>
                                <p>Enter your email & password to access your panel</p>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <div class="form-group">
                                    <label class="col-form-label">Email Address</label>
                                    <input id="email" type="email" class="form-control" name="email"
                                        value="<?php echo e(old('email')); ?>" placeholder="Enter your email" autocomplete="email"
                                        autofocus required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger d-block" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">Password</label>
                                    <div class="form-input position-relative">
                                        <input id="password" type="password" class="form-control" name="password"
                                            placeholder="Enter password" autocomplete="current-password" required>
                                        <div class="show-hide"><span class="show"></span></div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger d-block" role="alert">
                                                <strong><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-end">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('password.request')): ?>
                                        <a class="checkbox1" href="<?php echo e(route('password.request')); ?>">Forgot Password?</a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div class="text-end mt-3">
                                        <button class="btn btn-primary btn-block w-100 spinner-btn" type="submit">Sign in</button>
                                    </div>
                                </div>

                                <p class="mt-4 mb-0 text-center">Don't have an account?
                                    <a class="ms-2" href="<?php echo e(route('register')); ?>">Create Account</a>
                                </p>
                            </form> 

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/bookmark/jquery.validate.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/custom-validation/validation.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/toastr.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.authentication.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/auth/login.blade.php ENDPATH**/ ?>