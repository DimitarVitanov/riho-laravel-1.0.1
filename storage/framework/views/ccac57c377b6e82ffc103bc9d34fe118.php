<div class="form theme-form">
    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label>Name<span class="text-danger">*</span></label>
                <input class="form-control" type="text" placeholder="Enter Role" name="name"
                    value="<?php echo e(isset($role->name) ? $role->name : old('name')); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger d-block">
                        <strong><?php echo e($message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="validation m-2">Permissions<span class="text-danger">*</span></label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="mb-2 card-wrapper border rounded-3 checkbox-checked">
                            <h6 class="sub-title"><?php echo e(ucfirst($module->name)); ?>:</h6>
                            <div class="form-check-size rtl-input">
                                <?php
                                    $permissions = @$role?->getAllPermissions()->pluck('name')->toArray() ?? [];
                                    $isAllSelected =count(array_diff(array_values($module->actions), $permissions)) === 0;
                                ?>
                                <label class="d-block" for="all-<?php echo e($module->name); ?>">
                                    <input type="checkbox"
                                        class="checkbox_animated select-all-permission select-all-for-<?php echo e($module->name); ?>"
                                        id="all-<?php echo e($module->name); ?>" value="<?php echo e($module->name); ?>"
                                        <?php echo e($isAllSelected ? 'checked' : ''); ?>><?php echo e(__('All')); ?>

                                </label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $module->actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action => $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <label class="d-block" for="<?php echo e($permission); ?>" data-action="<?php echo e($action); ?>"
                                        data-module="<?php echo e($module->name); ?>">
                                        <input type="checkbox" name="permissions[]"
                                            class="checkbox_animated module_<?php echo e($module->name); ?> module_<?php echo e($module->name); ?>_<?php echo e($action); ?>"
                                            value="<?php echo e($permission); ?>" id="<?php echo e($permission); ?>"
                                            <?php echo e(in_array($permission, $permissions) ? 'checked' : ''); ?>><?php echo e(ucfirst($action)); ?>

                                    </label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['permissions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger d-block">
                        <strong><?php echo e($message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card-footer text-end pb-0 px-0">
        <div class="col-sm-9 offset-sm-3">
            <button class="btn btn-primary spinner-btn" type="submit">Save</button>
        </div>
    </div>
</div>
<?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/admin/role/fields.blade.php ENDPATH**/ ?>