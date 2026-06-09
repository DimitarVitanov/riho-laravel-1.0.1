<?php $__env->startSection('title', 'New Support Ticket'); ?>

<?php $__env->startSection('main_content'); ?>
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>New Support Ticket</h1>
            <p>Create a new support ticket to communicate with your account manager.</p>
        </div>
        <a href="<?php echo e(route('investor.support.index')); ?>" class="vb-btn vb-btn-secondary">Back to Tickets</a>
    </div>

    <div class="vb-card" style="max-width:720px;">
        <form action="<?php echo e(route('investor.support.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="vb-field" style="margin-bottom:18px;">
                <label>Subject</label>
                <input type="text" name="subject" class="vb-input" value="<?php echo e(old('subject')); ?>" required placeholder="Brief description of your request">
            </div>
            <div class="vb-field" style="margin-bottom:22px;">
                <label>Message</label>
                <textarea name="message" class="vb-textarea" rows="6" required placeholder="Describe your issue or question in detail..."><?php echo e(old('message')); ?></textarea>
            </div>
            <div class="vb-actions">
                <button type="submit" class="vb-btn">Submit Ticket</button>
                <a href="<?php echo e(route('investor.support.index')); ?>" class="vb-btn vb-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/investor/support/create.blade.php ENDPATH**/ ?>