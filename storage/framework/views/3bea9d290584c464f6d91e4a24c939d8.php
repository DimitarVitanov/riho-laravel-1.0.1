<?php
    $now = now();
    $periodStart = $now->copy()->startOfMonth();
    $periodEnd = $now->copy()->endOfMonth();
    $nextReset = $now->copy()->addMonth()->startOfMonth();
    $status = 'Active';
?>
<div class="vb-usage-status-title">Visible Usage Period Status</div>
<div class="vb-usage-status-banner">
    <div class="vb-item">
        <span>Usage month</span>
        <b><?php echo e($now->format('F Y')); ?></b>
    </div>
    <div class="vb-item">
        <span>Current period</span>
        <b><?php echo e($periodStart->format('M j')); ?>–<?php echo e($periodEnd->format('j, Y')); ?></b>
    </div>
    <div class="vb-item">
        <span>Account / usage status</span>
        <b><?php echo e($status); ?></b>
    </div>
    <div class="vb-item">
        <span>Next automatic reset</span>
        <b><?php echo e($nextReset->format('M j, Y')); ?></b>
    </div>
</div>
<?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/components/villabit/usage-banner.blade.php ENDPATH**/ ?>