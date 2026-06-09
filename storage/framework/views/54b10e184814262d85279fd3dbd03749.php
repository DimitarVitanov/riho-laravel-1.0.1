<?php $__env->startSection('title', 'My Agencies'); ?>
<?php $__env->startSection('breadcrumb-title'); ?><h3>Assigned Agencies</h3><?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('manager.dashboard')); ?>">Manager</a></li>
    <li class="breadcrumb-item active">Agencies</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>#</th><th>Agency</th><th>Owner</th><th>City</th><th>AI Status</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $agencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($a->id); ?></td>
                        <td><?php echo e($a->agencyProfile->agency_name ?? $a->company_name ?? '—'); ?></td>
                        <td><?php echo e($a->first_name); ?> <?php echo e($a->last_name); ?></td>
                        <td><?php echo e($a->agencyProfile->city ?? '—'); ?></td>
                        <td><span class="badge bg-<?php echo e(($a->agencyProfile->ai_status ?? '') === 'active' ? 'success' : 'secondary'); ?>"><?php echo e(ucfirst($a->agencyProfile->ai_status ?? 'n/a')); ?></span></td>
                        <td><span class="badge bg-<?php echo e($a->status === 'active' ? 'success' : 'warning'); ?>"><?php echo e(ucfirst($a->status)); ?></span></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="6" class="text-center text-muted">No assigned agencies.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($agencies->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/manager/agencies/index.blade.php ENDPATH**/ ?>