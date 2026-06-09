<?php $__env->startSection('title', 'Content Review'); ?>

<?php $__env->startSection('main_content'); ?>
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Content Review</h1>
            <p>Review AI-generated content for your assigned agencies before publication.</p>
        </div>
    </div>

    <?php echo $__env->make('components.villabit.usage-banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Draft</th>
                    <th>Agency</th>
                    <th>Feature</th>
                    <th>Uniqueness</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td><strong><?php echo e(Str::limit($page->title, 40)); ?></strong></td>
                    <td><?php echo e($page->agencyProfile->agency_name ?? '—'); ?></td>
                    <td><?php echo e(ucfirst(str_replace('_', ' ', $page->feature_key))); ?></td>
                    <td>
                        <?php
                            $uClass = match($page->content_uniqueness_status) {
                                'passed' => 'vb-badge-success',
                                'failed' => 'vb-badge-danger',
                                'checking' => 'vb-badge-warning',
                                default => 'vb-badge-info'
                            };
                        ?>
                        <span class="vb-badge <?php echo e($uClass); ?>"><?php echo e(strtoupper($page->content_uniqueness_status)); ?></span>
                    </td>
                    <td><span class="vb-badge vb-badge-warning"><?php echo e(ucfirst($page->status)); ?></span></td>
                    <td><a href="<?php echo e(route('manager.content-review.show', $page)); ?>" class="vb-btn vb-btn-sm">Review</a></td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="6">
                        <div class="vb-empty">
                            <h3>No content pending review</h3>
                            <p>All content has been reviewed or no new drafts are available.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <div style="margin-top: 20px;"><?php echo e($pages->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/manager/content-review/index.blade.php ENDPATH**/ ?>