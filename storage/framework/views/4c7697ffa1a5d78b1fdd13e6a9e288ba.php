<div class="action-div">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data)): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($edit)): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->system_reserve) ? !$data->system_reserve : true): ?>
                <a href="<?php echo e(route($edit, $data)); ?>" class="edit-icon">
                    <i data-feather="edit"></i>
                <?php else: ?>
                    <a href="javascript:void(0)" class="lock-icon">
                        <i data-feather="lock"></i>
                    </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($delete)): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->system_reserve) ? !$data->system_reserve : true): ?>
                <a href="#confirmationModal<?php echo e($data->id); ?>" data-bs-toggle="modal" class="delete-svg">
                    <i data-feather="trash-2" class="remove-icon delete-confirmation"></i>
                </a>
                <!-- Remove File Confirmation-->
                <div class="modal fade" id="confirmationModal<?php echo e($data->id); ?>" tabindex="-1" role="dialog"
                    aria-labelledby="confirmationModalLabel<?php echo e($data->id); ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Confirm delete</h4>
                                <button class="btn-close py-0" type="button" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h4 class="mb-3"> Are you sure want to delete?</h4>
                                <p>This item and its associated users will be permanently deleted. You can't undo this action.</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                                <form action="<?php echo e(route($delete, $data->id)); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('delete'); ?>
                                    <button class="btn btn-danger delete spinner-btn"
                                        type="submit"><?php echo e(__('Delete')); ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($toggle)): ?>
        <label class="switch">
            <input data-route="<?php echo e(route($route, $toggle->id)); ?>" data-id="<?php echo e($toggle->id); ?>"
                class="form-check-input toggle-status" type="checkbox" name="<?php echo e($name); ?>"
                value="<?php echo e($value); ?>" <?php echo e($value ? 'checked' : ''); ?>

                <?php if($toggle->system_reserve): ?> disabled <?php endif; ?>>
            <span class="switch-state"></span>
        </label>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/admin/inc/role/action.blade.php ENDPATH**/ ?>