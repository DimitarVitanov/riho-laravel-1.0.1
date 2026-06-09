<?php

namespace App\Services;

use App\Models\AiDailyReport;
use Illuminate\Support\Facades\Log;

class ContentPublishService
{
    /**
     * Content uniqueness statuses (mandatory, not user-configurable):
     * DRAFT, CHECKING, PASSED, FAILED, REWRITE_REQUIRED, READY_FOR_MANUAL_REVIEW, PUBLISHED
     */

    const STATUS_DRAFT = 'draft';
    const STATUS_CHECKING = 'checking';
    const STATUS_PASSED = 'passed';
    const STATUS_FAILED = 'failed';
    const STATUS_REWRITE_REQUIRED = 'rewrite_required';
    const STATUS_READY_FOR_MANUAL_REVIEW = 'ready_for_manual_review';
    const STATUS_PUBLISHED = 'published';

    /**
     * Publish workflow options:
     * OPTION 1: Auto-publish after PASSED uniqueness check
     * OPTION 2: Keep as draft for manual review
     */
    const WORKFLOW_AUTO_PUBLISH = 'auto_publish';
    const WORKFLOW_MANUAL_REVIEW = 'manual_review';

    /**
     * Process a newly created AI content through the uniqueness check pipeline.
     */
    public function processContent(AiDailyReport $report, string $workflow = self::WORKFLOW_MANUAL_REVIEW): string
    {
        // Step 1: Mark as checking
        $report->update(['content_uniqueness_status' => self::STATUS_CHECKING]);

        // Step 2: Run uniqueness check (placeholder — integrate with Copyscape or similar API)
        $isUnique = $this->checkUniqueness($report);

        if ($isUnique) {
            $report->update([
                'content_uniqueness_status' => self::STATUS_PASSED,
                'uniqueness_checked_at' => now(),
            ]);

            // Step 3a: Based on workflow, either auto-publish or send to review
            if ($workflow === self::WORKFLOW_AUTO_PUBLISH) {
                $report->update(['content_uniqueness_status' => self::STATUS_PUBLISHED]);
                return self::STATUS_PUBLISHED;
            } else {
                $report->update(['content_uniqueness_status' => self::STATUS_READY_FOR_MANUAL_REVIEW]);
                return self::STATUS_READY_FOR_MANUAL_REVIEW;
            }
        } else {
            // Step 3b: Content failed — mark for rewrite
            $report->update([
                'content_uniqueness_status' => self::STATUS_FAILED,
                'uniqueness_checked_at' => now(),
            ]);

            return self::STATUS_FAILED;
        }
    }

    /**
     * Request AI to rewrite failed content and recheck.
     */
    public function rewriteAndRecheck(AiDailyReport $report, string $workflow = self::WORKFLOW_MANUAL_REVIEW): string
    {
        $report->update(['content_uniqueness_status' => self::STATUS_REWRITE_REQUIRED]);

        // Placeholder: call AI API to rewrite content
        // After rewrite, run uniqueness check again
        $report->update(['content_uniqueness_status' => self::STATUS_CHECKING]);

        $isUnique = $this->checkUniqueness($report);

        if ($isUnique) {
            $report->update([
                'content_uniqueness_status' => self::STATUS_PASSED,
                'uniqueness_checked_at' => now(),
            ]);

            if ($workflow === self::WORKFLOW_AUTO_PUBLISH) {
                $report->update(['content_uniqueness_status' => self::STATUS_PUBLISHED]);
                return self::STATUS_PUBLISHED;
            }

            $report->update(['content_uniqueness_status' => self::STATUS_READY_FOR_MANUAL_REVIEW]);
            return self::STATUS_READY_FOR_MANUAL_REVIEW;
        }

        // Still failed after rewrite
        $report->update([
            'content_uniqueness_status' => self::STATUS_FAILED,
            'uniqueness_checked_at' => now(),
        ]);

        Log::warning("Content #{$report->id} failed uniqueness check after rewrite.");

        return self::STATUS_FAILED;
    }

    /**
     * Placeholder for actual uniqueness check API call.
     * In production, integrate with Copyscape, Copyleaks, or similar service.
     */
    protected function checkUniqueness(AiDailyReport $report): bool
    {
        // TODO: Integrate with uniqueness checking API
        // For now, return true (content is considered unique)
        return true;
    }

    /**
     * Manually publish content that passed review.
     */
    public function manualPublish(AiDailyReport $report): bool
    {
        if (!in_array($report->content_uniqueness_status, [self::STATUS_PASSED, self::STATUS_READY_FOR_MANUAL_REVIEW])) {
            return false;
        }

        $report->update(['content_uniqueness_status' => self::STATUS_PUBLISHED]);
        return true;
    }
}
