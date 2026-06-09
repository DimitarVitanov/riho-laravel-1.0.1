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
                                <a class="logo" href="<?php echo e(route('admin.default_dashboard')); ?>">
                                    <img class="img-fluid" src="<?php echo e(asset('assets/images/logo/villabit-logo.png')); ?>" alt="Villa Bit AI" style="height:80px;width:auto;">
                                </a>
                            </a>
                        </div>
                        <div class="login-main">
                            <form class="theme-form" method="POST" action="<?php echo e(route('register')); ?>" id="registerForm">
                                <?php echo csrf_field(); ?>
                                <h4>Create your account</h4>
                                <p>Join Villa Bit AI — select your account type and enter your details</p>

                                <div class="form-group">
                                    <label class="col-form-label">Account Type <span class="text-danger">*</span></label>
                                    <select id="account_type" name="account_type" class="form-control">
                                        <option value="">-- Select Account Type --</option>
                                        <option value="real_estate_agency" <?php echo e(old('account_type') == 'real_estate_agency' ? 'selected' : ''); ?>>Real Estate Agency</option>
                                        <option value="investor" <?php echo e(old('account_type') == 'investor' ? 'selected' : ''); ?>>Real Estate Investor</option>
                                    </select>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['account_type'];
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
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input id="first_name" type="text" class="form-control" name="first_name"
                                                value="<?php echo e(old('first_name')); ?>" placeholder="First name" required>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['first_name'];
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
                                        <div class="col-6">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input id="last_name" type="text" class="form-control" name="last_name"
                                                value="<?php echo e(old('last_name')); ?>" placeholder="Last name" required>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['last_name'];
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
                                </div>

                                <div class="form-group agency-fields" style="display: none;">
                                    <label class="col-form-label">Company / Agency Name <span class="text-danger">*</span></label>
                                    <input id="company_name" type="text" class="form-control" name="company_name"
                                        value="<?php echo e(old('company_name')); ?>" placeholder="Your agency name">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['company_name'];
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

                                <div class="form-group agency-fields" style="display: none;">
                                    <label class="col-form-label">Agency Website URL</label>
                                    <input id="agency_website_url" type="url" class="form-control" name="agency_website_url"
                                        value="<?php echo e(old('agency_website_url')); ?>" placeholder="https://youragency.com">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['agency_website_url'];
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

                                <div class="form-group agency-fields" style="display: none;">
                                    <label class="col-form-label">Target City</label>
                                    <input id="target_city" type="text" class="form-control" name="target_city"
                                        value="<?php echo e(old('target_city')); ?>" placeholder="Main city you operate in">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['target_city'];
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
                                    <label class="col-form-label">Country <span class="text-danger">*</span></label>
                                    <select id="country" name="country" class="form-control" required>
                                        <option value="">-- Select Country --</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <option value="<?php echo e($c->name); ?>" <?php echo e(old('country') == $c->name ? 'selected' : ''); ?>>
                                                <?php echo e($c->flag); ?> <?php echo e($c->name); ?>

                                            </option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </select>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['country'];
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
                                    <label class="col-form-label">Phone</label>
                                    <div class="input-group">
                                        <select id="phone_code" name="phone_code" class="form-control" style="max-width:150px; flex-shrink:0;">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($c->calling_code): ?>
                                                    <option value="+<?php echo e($c->calling_code); ?>" <?php echo e(old('phone_code') == '+'.$c->calling_code ? 'selected' : ''); ?>>
                                                        <?php echo e($c->flag); ?> <?php echo e($c->calling_code); ?>

                                                    </option>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                        <input id="phone" type="text" class="form-control" name="phone"
                                            value="<?php echo e(old('phone')); ?>" placeholder="Phone number">
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
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
                                    <label class="col-form-label">Email Address <span class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control" name="email"
                                        value="<?php echo e(old('email')); ?>" placeholder="Enter your email" required>
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
                                    <label class="col-form-label">Password <span class="text-danger">*</span></label>
                                    <div class="form-input position-relative">
                                        <input id="password" type="password" class="form-control" name="password"
                                            placeholder="Minimum 8 characters" required>
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

                                <div class="form-group">
                                    <label class="col-form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="form-input position-relative">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" placeholder="Repeat password" required>
                                        <div class="show-hide"><span class="show"></span></div>
                                    </div>
                                </div>

                                <input type="hidden" name="referral_code" value="<?php echo e(request()->cookie('referral_code')); ?>">

                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms_acceptance" id="terms_acceptance" value="1" <?php echo e(old('terms_acceptance') ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="terms_acceptance">
                                            I agree to the <a href="/terms" target="_blank">Terms of Service</a>
                                        </label>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['terms_acceptance'];
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="privacy_acceptance" id="privacy_acceptance" value="1" <?php echo e(old('privacy_acceptance') ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="privacy_acceptance">
                                            I agree to the <a href="/privacy" target="_blank">Privacy Policy</a>
                                        </label>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['privacy_acceptance'];
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

                                <div class="form-group mb-0">
                                    <button class="btn btn-primary btn-block w-100 spinner-btn" type="submit">Create Account</button>
                                </div>

                                <p class="mt-4 mb-0 text-center">Already have an account?<a class="ms-2"
                                        href="<?php echo e(route('login')); ?>">Sign in</a>
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
    <style>
        /* ── Brand color overrides ── */
        .login-card .login-main .btn-primary,
        .login-card .login-main button[type="submit"] {
            background-color: #0a0b0c !important;
            border-color: #0a0b0c !important;
            color: #ffffff !important;
        }
        .login-card .login-main .btn-primary:hover,
        .login-card .login-main button[type="submit"]:hover {
            background-color: #2a2b2c !important;
            border-color: #2a2b2c !important;
        }
        /* Kill teal on inputs — theme uses rgba(primary,0.1) */
        .login-card .login-main .form-control,
        .login-card .login-main input,
        .login-card .login-main select,
        .login-card .login-main textarea {
            background-color: #f0f0f1 !important;
            border: 1px solid #d8d8d8 !important;
            color: #0a0b0c !important;
        }
        .login-card .login-main .form-control::placeholder,
        .login-card .login-main input::placeholder {
            color: #999 !important;
        }
        .login-card .login-main .form-control:focus,
        .login-card .login-main input:focus {
            background-color: #f0f0f1 !important;
            border-color: #0a0b0c !important;
            box-shadow: 0 0 0 0.15rem rgba(10,11,12,0.12) !important;
        }
        /* Kill teal on show/hide icon */
        .login-card .login-main .show-hide {
            color: #666 !important;
        }
        .login-card .login-main a {
            color: #0a0b0c !important;
        }
        .login-card .login-main a:hover {
            color: #444 !important;
        }
        /* Form card white background */
        .login-card .login-main {
            background-color: #ffffff !important;
        }
        /* Force background image to show (overrides .login-dark which hides it) */
        .login-card.login-dark {
            background: url('/assets/images/login/login_bg.jpg') center center / cover no-repeat !important;
            background-color: #f5f6fa !important;
        }
        /* ── Checkbox fixes ── */
        .login-main .form-check {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            padding-left: 0 !important;
        }
        .login-main .form-check-input[type="checkbox"] {
            position: relative !important;
            float: none !important;
            opacity: 1 !important;
            visibility: visible !important;
            width: 18px !important;
            height: 18px !important;
            margin: 0 !important;
            flex-shrink: 0 !important;
            cursor: pointer !important;
            pointer-events: auto !important;
            z-index: 10 !important;
            appearance: auto !important;
            -webkit-appearance: checkbox !important;
            accent-color: #0a0b0c !important;
        }
        .login-main .form-check-label {
            margin: 0 !important;
            cursor: pointer !important;
        }
    </style>
    <script src="<?php echo e(asset('assets/js/bookmark/jquery.validate.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/toastr.min.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            function toggleAgencyFields() {
                var accountType = $('#account_type').val();
                if (accountType === 'real_estate_agency') {
                    $('.agency-fields').show();
                } else {
                    $('.agency-fields').hide();
                }
            }

            toggleAgencyFields();
            $('#account_type').on('change', toggleAgencyFields);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.authentication.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/auth/register.blade.php ENDPATH**/ ?>