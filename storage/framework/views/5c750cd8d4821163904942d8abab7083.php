<script>
    $(document).ready(function() {
        <?php if(session('error')): ?>
            toastr.error("<?php echo e(session('error')); ?>");
        <?php endif; ?>
        <?php if(session('success')): ?>
            toastr.success("<?php echo e(session('success')); ?>");
        <?php endif; ?>
        <?php if(session('warning')): ?>
            toastr.warning("<?php echo e(session('warning')); ?>");
        <?php endif; ?>
        <?php if(session('info')): ?>
            toastr.info("<?php echo e(session('info')); ?>");
        <?php endif; ?>
    });
</script>
<?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/admin/inc/alerts.blade.php ENDPATH**/ ?>