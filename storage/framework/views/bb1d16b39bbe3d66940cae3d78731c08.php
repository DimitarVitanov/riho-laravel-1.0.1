<?php $__env->startSection('title', 'Payout Preparation'); ?>

<?php $__env->startSection('main_content'); ?>
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Payout Preparation</h1>
            <p>Prepare and review pending investor and affiliate payouts for processing.</p>
        </div>
    </div>

    <?php echo $__env->make('components.villabit.usage-banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="vb-card" style="margin-bottom: 28px;">
        <h2 class="vb-section-title">Pending Investor Payouts</h2>
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Investor</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $investorPayouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td><strong><?php echo e($payout->investorUser->full_name ?? '—'); ?></strong></td>
                    <td><?php echo e(ucfirst(str_replace('_', ' ', $payout->payout_type))); ?></td>
                    <td>€<?php echo e(number_format($payout->amount, 2)); ?></td>
                    <td><?php echo e(ucfirst($payout->payout_method)); ?></td>
                    <td><span class="vb-badge vb-badge-warning"><?php echo e(ucfirst($payout->payout_status)); ?></span></td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="5">
                        <div class="vb-empty">
                            <h3>No pending investor payouts</h3>
                            <p>All investor payouts have been processed.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="vb-card">
        <h2 class="vb-section-title">Requested Affiliate Payouts</h2>
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Reseller</th>
                    <th>Batch Month</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $affiliatePayouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td><strong><?php echo e($payout->resellerUser->full_name ?? '—'); ?></strong></td>
                    <td><?php echo e($payout->payout_batch_month); ?></td>
                    <td>€<?php echo e(number_format($payout->amount, 2)); ?></td>
                    <td><?php echo e(ucfirst($payout->payout_method)); ?></td>
                    <td><span class="vb-badge vb-badge-warning"><?php echo e(ucfirst($payout->payout_status)); ?></span></td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="5">
                        <div class="vb-empty">
                            <h3>No requested affiliate payouts</h3>
                            <p>No affiliate payouts are awaiting processing.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/manager/payout-preparation/index.blade.php ENDPATH**/ ?>