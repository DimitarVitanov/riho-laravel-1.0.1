<?php $__env->startSection('title', 'Ticket #' . $ticket->id); ?>

<?php $__env->startSection('main_content'); ?>
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1><?php echo e($ticket->subject); ?></h1>
            <p>Ticket #<?php echo e($ticket->id); ?> · Created <?php echo e($ticket->created_at->format('M d, Y H:i')); ?></p>
        </div>
        <a href="<?php echo e(route('investor.support.index')); ?>" class="vb-btn vb-btn-secondary">Back to Tickets</a>
    </div>

    <div class="vb-grid-2">
        <div>
            <!-- Original Message -->
            <div class="vb-message vb-message-own" style="margin-bottom:16px;">
                <div class="vb-message-meta">You · <?php echo e($ticket->created_at->format('M d, Y H:i')); ?></div>
                <div class="vb-message-body"><?php echo e($ticket->message); ?></div>
            </div>

            <!-- Thread Messages -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ticket->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="vb-message <?php echo e($msg->user_id === auth()->id() ? 'vb-message-own' : 'vb-message-other'); ?>">
                <div class="vb-message-meta"><?php echo e($msg->user->full_name ?? 'System'); ?> · <?php echo e($msg->created_at->format('M d, Y H:i')); ?></div>
                <div class="vb-message-body"><?php echo e($msg->message); ?></div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

            <!-- Reply Form -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($ticket->status, ['open', 'in_progress', 'waiting'])): ?>
            <div class="vb-card" style="margin-top:20px;">
                <form action="<?php echo e(route('investor.support.reply', $ticket)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="vb-field" style="margin-bottom:14px;">
                        <label>Reply</label>
                        <textarea name="message" class="vb-textarea" rows="3" placeholder="Type your reply..." required></textarea>
                    </div>
                    <button type="submit" class="vb-btn vb-btn-sm">Send Reply</button>
                </form>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Ticket Info Sidebar -->
        <div class="vb-card" style="align-self:start;">
            <h2 class="vb-section-title">Ticket Info</h2>
            <div style="display:grid;gap:14px;">
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Status</label>
                    <div>
                        <?php
                            $tClass = match($ticket->status) {
                                'resolved' => 'vb-badge-success',
                                'open' => 'vb-badge-info',
                                'in_progress' => 'vb-badge-warning',
                                default => 'vb-badge-muted'
                            };
                        ?>
                        <span class="vb-badge <?php echo e($tClass); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $ticket->status))); ?></span>
                    </div>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Priority</label>
                    <div style="font-size:14px;font-weight:600;color:#111827;"><?php echo e(ucfirst($ticket->priority)); ?></div>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Created</label>
                    <div style="font-size:14px;color:#111827;"><?php echo e($ticket->created_at->format('M d, Y H:i')); ?></div>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Messages</label>
                    <div style="font-size:14px;color:#111827;"><?php echo e($ticket->messages->count() + 1); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/investor/support/show.blade.php ENDPATH**/ ?>