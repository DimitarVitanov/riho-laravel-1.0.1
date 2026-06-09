<?php $__env->startSection('title', 'My Investments'); ?>
<?php $__env->startSection('breadcrumb-title'); ?><h3>My Investments</h3><?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('investor.dashboard')); ?>">Investor</a></li>
    <li class="breadcrumb-item active">Investments</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Project</th><th>Committed</th><th>Funded</th><th>Pref Return</th><th>Total Earnings</th><th>Balance</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $investments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($inv->project->project_name ?? '—'); ?></td>
                        <td><?php echo e(number_format($inv->committed_amount, 2)); ?></td>
                        <td><?php echo e(number_format($inv->funded_amount, 2)); ?></td>
                        <td><?php echo e(number_format($inv->preferred_return_accrued_amount, 2)); ?></td>
                        <td><?php echo e(number_format($inv->total_earnings_accrued, 2)); ?></td>
                        <td><?php echo e(number_format($inv->current_balance, 2)); ?></td>
                        <td><span class="badge bg-info"><?php echo e(ucfirst($inv->investment_status)); ?></span></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="7" class="text-center text-muted">No investments yet.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($investments->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/investor/investments/index.blade.php ENDPATH**/ ?>