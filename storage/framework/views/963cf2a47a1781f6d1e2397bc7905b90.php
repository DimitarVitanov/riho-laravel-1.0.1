<?php $__env->startSection('title', 'Support'); ?>

<?php $__env->startSection('main_content'); ?>
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Messages & Support</h1>
            <p>Create and manage support tickets. Communicate with your account manager.</p>
        </div>
        <a href="<?php echo e(route('investor.support.create')); ?>" class="vb-btn">New Ticket</a>
    </div>

    <?php echo $__env->make('components.villabit.usage-banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td><?php echo e($ticket->id); ?></td>
                    <td><strong><?php echo e($ticket->subject); ?></strong></td>
                    <td>
                        <?php
                            $tClass = match($ticket->status) {
                                'resolved' => 'vb-badge-success',
                                'open' => 'vb-badge-info',
                                'in_progress' => 'vb-badge-warning',
                                default => 'vb-badge-muted'
                            };
                        ?>
                        <span class="vb-badge <?php echo e($tClass); ?>"><?php echo e(ucfirst($ticket->status)); ?></span>
                    </td>
                    <td><?php echo e(ucfirst($ticket->priority)); ?></td>
                    <td><?php echo e($ticket->created_at->format('M d, Y')); ?></td>
                    <td><a href="<?php echo e(route('investor.support.show', $ticket)); ?>" class="vb-btn vb-btn-sm">View</a></td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="6">
                        <div class="vb-empty">
                            <h3>No support tickets</h3>
                            <p>Click "New Ticket" to create your first support request.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <div style="margin-top: 20px;"><?php echo e($tickets->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/investor/support/index.blade.php ENDPATH**/ ?>