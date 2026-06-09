<?php $__env->startSection('title', 'Capital Calls'); ?>
<?php $__env->startSection('breadcrumb-title'); ?><h3>Capital Calls</h3><?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('investor.dashboard')); ?>">Investor</a></li>
    <li class="breadcrumb-item active">Capital Calls</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Call #</th><th>Project</th><th>Amount</th><th>Due Date</th><th>Phase</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $capitalCalls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($cc->call_number); ?></td>
                        <td><?php echo e($cc->project->project_name ?? '—'); ?></td>
                        <td><?php echo e(number_format($cc->requested_amount, 2)); ?></td>
                        <td><?php echo e($cc->due_date); ?></td>
                        <td><?php echo e($cc->construction_phase ?? '—'); ?></td>
                        <td><span class="badge bg-<?php echo e($cc->status === 'paid' ? 'success' : ($cc->status === 'overdue' ? 'danger' : 'warning')); ?>"><?php echo e(ucfirst($cc->status)); ?></span></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="6" class="text-center text-muted">No capital calls.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($capitalCalls->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/investor/capital-calls/index.blade.php ENDPATH**/ ?>