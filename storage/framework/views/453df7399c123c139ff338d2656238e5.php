<?php $__env->startSection('title', 'Investor Dashboard'); ?>

<?php $__env->startSection('main_content'); ?>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="vb-page-header">
            <div>
                <h1>Investor Panel</h1>
                <p>Investor panel shows all investment amounts, committed capital, capital calls, accrued interest, profit participation, payouts, documents, and reports.</p>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($profile && $profile->kyc_status === 'approved'): ?>
                <span class="vb-badge vb-badge-success" style="font-size:13px;padding:8px 14px;">KYC Verified</span>
            <?php else: ?>
                <span class="vb-badge vb-badge-warning" style="font-size:13px;padding:8px 14px;">KYC Pending</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Usage Period Status Banner -->
        <?php echo $__env->make('components.villabit.usage-banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Financial Metric Cards -->
        <div class="vb-grid">
            <div class="vb-card">
                <div class="vb-label">Total Committed</div>
                <div class="vb-metric">€<?php echo e(number_format($totalCommitted, 0)); ?></div>
                <div class="vb-period">Financial status date: <?php echo e(now()->format('F Y')); ?> · Updated monthly</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">Total Invested</div>
                <div class="vb-metric">€<?php echo e(number_format($totalFunded, 0)); ?></div>
                <div class="vb-period">Financial status date: <?php echo e(now()->format('F Y')); ?> · Updated monthly</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">Preferred Return Accrued</div>
                <div class="vb-metric">€<?php echo e(number_format($totalEarnings, 0)); ?></div>
                <div class="vb-period">Financial status date: <?php echo e(now()->format('F Y')); ?> · Updated monthly</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">Payouts Received</div>
                <div class="vb-metric">€<?php echo e(number_format($totalPaid, 0)); ?></div>
                <div class="vb-period">Financial status date: <?php echo e(now()->format('F Y')); ?> · Updated monthly</div>
            </div>
        </div>

        <!-- Investments Table -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($investments->count()): ?>
        <div class="vb-card" style="margin-bottom: 28px;">
            <h2 class="vb-section-title">My Investments</h2>
            <table class="vb-table">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Committed</th>
                        <th>Funded</th>
                        <th>Earnings</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $investments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td><strong><?php echo e($inv->project->project_name ?? 'N/A'); ?></strong></td>
                        <td>€<?php echo e(number_format($inv->committed_amount, 0)); ?></td>
                        <td>€<?php echo e(number_format($inv->funded_amount, 0)); ?></td>
                        <td>€<?php echo e(number_format($inv->total_earnings_accrued, 0)); ?></td>
                        <td>
                            <?php
                                $iClass = match($inv->investment_status) {
                                    'active' => 'vb-badge-success',
                                    'pending' => 'vb-badge-warning',
                                    default => 'vb-badge-muted'
                                };
                            ?>
                            <span class="vb-badge <?php echo e($iClass); ?>"><?php echo e(ucfirst($inv->investment_status)); ?></span>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Pending Capital Calls -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingCalls->count()): ?>
        <div class="vb-card">
            <h2 class="vb-section-title">Pending Capital Calls</h2>
            <div class="vb-notice" style="margin-bottom:18px;">You have <?php echo e($pendingCalls->count()); ?> capital call(s) awaiting payment.</div>
            <table class="vb-table">
                <thead>
                    <tr>
                        <th>Call #</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pendingCalls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $call): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td><strong><?php echo e($call->call_number); ?></strong></td>
                        <td>€<?php echo e(number_format($call->requested_amount, 0)); ?></td>
                        <td><?php echo e($call->due_date->format('M j, Y')); ?></td>
                        <td><?php echo e($call->reason ?? '—'); ?></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/investor/dashboard.blade.php ENDPATH**/ ?>