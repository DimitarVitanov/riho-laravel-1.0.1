<?php $__env->startSection('title', 'Earnings'); ?>
<?php $__env->startSection('breadcrumb-title'); ?><h3>Earnings</h3><?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('investor.dashboard')); ?>">Investor</a></li>
    <li class="breadcrumb-item active">Earnings</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-3"><div class="card"><div class="card-body text-center"><h6>Preferred Return</h6><h3><?php echo e(number_format($totalPreferredReturn, 2)); ?></h3></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body text-center"><h6>Rental Profit</h6><h3><?php echo e(number_format($totalRentalProfit, 2)); ?></h3></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body text-center"><h6>Exit Profit</h6><h3><?php echo e(number_format($totalExitProfit, 2)); ?></h3></div></div></div>
        <div class="col-md-3"><div class="card bg-success text-white"><div class="card-body text-center"><h6>Total Earnings</h6><h3><?php echo e(number_format($totalEarnings, 2)); ?></h3></div></div></div>
    </div>
    <div class="card">
        <div class="card-header pb-0"><h5>Earnings by Project</h5></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Project</th><th>Pref Return</th><th>Rental</th><th>Exit</th><th>Total</th></tr></thead>
                    <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $investments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($inv->project->project_name ?? '—'); ?></td>
                        <td><?php echo e(number_format($inv->preferred_return_accrued_amount, 2)); ?></td>
                        <td><?php echo e(number_format($inv->rental_profit_accrued_amount, 2)); ?></td>
                        <td><?php echo e(number_format($inv->project_exit_profit_accrued_amount, 2)); ?></td>
                        <td><strong><?php echo e(number_format($inv->total_earnings_accrued, 2)); ?></strong></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/investor/earnings/index.blade.php ENDPATH**/ ?>