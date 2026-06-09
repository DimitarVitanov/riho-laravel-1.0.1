<?php use \App\Helpers\Helpers; ?>
<?php
    $settings = Helpers::getSettings();
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description"
    content="<?php echo e($settings['general']['site_name']); ?> admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
<meta name="keywords"
    content="admin template, <?php echo e($settings['general']['site_name']); ?> admin template, dashboard template, flat admin template, responsive admin template, web app">
<meta name="author" content="pixelstrap"><meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<link rel="icon" href="<?php echo e(asset($settings['general']['favicon'])); ?>" type="image/x-icon">
<link rel="shortcut icon" href="<?php echo e(asset($settings['general']['favicon'])); ?>" type="image/x-icon">
<title><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($settings['general']['site_name'])): ?><?php echo e($settings['general']['site_name']); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>  - Premium Admin Template</title>
<!-- Google font-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
<script>
    var baseUrl = "<?php echo e(asset('')); ?>";
</script>
<?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/layouts/simple/head.blade.php ENDPATH**/ ?>