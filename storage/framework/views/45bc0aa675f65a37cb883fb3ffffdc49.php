<?php $__env->startSection('title', 'Capital Call Follow-up'); ?>

<?php $__env->startSection('main_content'); ?>
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Capital Call Follow-up</h1>
            <p>Track and follow up on capital call requests for your assigned investors.</p>
        </div>
    </div>

    <?php echo $__env->make('components.villabit.usage-banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Investor</th>
                    <th>Project</th>
                    <th>Call #</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $capitalCalls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $call): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td><strong><?php echo e($call->investorUser->full_name ?? '—'); ?></strong></td>
                    <td><?php echo e($call->project->project_name ?? '—'); ?></td>
                    <td><?php echo e($call->call_number); ?></td>
                    <td>€<?php echo e(number_format($call->requested_amount, 0)); ?></td>
                    <td><?php echo e($call->due_date ? \Carbon\Carbon::parse($call->due_date)->format('M d, Y') : '—'); ?></td>
                    <td>
                        <?php
                            $cClass = match($call->status) {
                                'paid' => 'vb-badge-success',
                                'overdue' => 'vb-badge-danger',
                                'sent', 'viewed' => 'vb-badge-warning',
                                default => 'vb-badge-muted'
                            };
                        ?>
                        <span class="vb-badge <?php echo e($cClass); ?>"><?php echo e(ucfirst($call->status)); ?></span>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="6">
                        <div class="vb-empty">
                            <h3>No capital calls requiring follow-up</h3>
                            <p>All capital calls are up to date.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <div style="margin-top: 20px;"><?php echo e($capitalCalls->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/manager/capital-calls/index.blade.php ENDPATH**/ ?>