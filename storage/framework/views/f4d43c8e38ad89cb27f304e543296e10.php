<?php $__env->startSection('title', 'AI Reports'); ?>
<?php $__env->startSection('breadcrumb-title'); ?><h3>AI Daily Reports</h3><?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('manager.dashboard')); ?>">Manager</a></li>
    <li class="breadcrumb-item active">AI Reports</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Date</th><th>Agency</th><th>Feature</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($r->report_date->format('M d, Y')); ?></td>
                        <td><?php echo e($r->agencyProfile->agency_name ?? '—'); ?></td>
                        <td><?php echo e(str_replace('_', ' ', ucfirst($r->feature_key))); ?></td>
                        <td><span class="badge bg-<?php echo e($r->status === 'completed' ? 'success' : 'warning'); ?>"><?php echo e(ucfirst($r->status)); ?></span></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="4" class="text-center text-muted">No reports for assigned agencies.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($reports->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/manager/ai-reports/index.blade.php ENDPATH**/ ?>