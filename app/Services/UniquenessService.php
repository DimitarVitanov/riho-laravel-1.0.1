<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GeneratedPage;
use Illuminate\Support\Facades\Log;

/**
 * UniquenessService
 *
 * Combines internal uniqueness checking with optional Copyscape internet checking.
 * Use this as the main entry point for all uniqueness checks.
 */
final class UniquenessService
{
    private InternalUniquenessChecker $internalChecker;
    private CopyscapeChecker $copyscapeChecker;

    public function __construct()
    {
        $this->internalChecker = new InternalUniquenessChecker(
            shingleWords: 7,
            reviewAtPercent: 5.0,
            failAtPercent: 15.0,
            ignorePhrases: [
                'Villa Bit AI',
                'All rights reserved',
                'Contact us today',
            ],
        );

        $this->copyscapeChecker = new CopyscapeChecker();
    }

    /**
     * Run full uniqueness check (internal + optional Copyscape).
     *
     * @param string $text Text to check
     * @param int|null $agencyProfileId Limit internal check to this agency's content
     * @param bool $includeCopyscape Whether to also run Copyscape check
     * @return array{
     *   overall_verdict: 'passed'|'review'|'failed'|'error',
     *   internal: array,
     *   copyscape: array|null,
     *   summary: string
     * }
     */
    public function check(string $text, ?int $agencyProfileId = null, bool $includeCopyscape = false): array
    {
        $internalResult = $this->runInternalCheck($text, $agencyProfileId);
        $copyscapeResult = null;

        if ($includeCopyscape && $this->copyscapeChecker->isConfigured()) {
            $copyscapeResult = $this->copyscapeChecker->check($text);
        }

        $overallVerdict = $this->determineOverallVerdict($internalResult, $copyscapeResult);
        $summary = $this->generateSummary($internalResult, $copyscapeResult, $overallVerdict);

        return [
            'overall_verdict' => $overallVerdict,
            'internal' => $internalResult,
            'copyscape' => $copyscapeResult,
            'summary' => $summary,
        ];
    }

    /**
     * Quick internal-only check.
     */
    public function checkInternal(string $text, ?int $agencyProfileId = null): array
    {
        return $this->runInternalCheck($text, $agencyProfileId);
    }

    /**
     * Copyscape-only check.
     */
    public function checkCopyscape(string $text): array
    {
        if (!$this->copyscapeChecker->isConfigured()) {
            return [
                'success' => false,
                'verdict' => 'error',
                'error' => 'Copyscape not configured',
            ];
        }

        return $this->copyscapeChecker->check($text);
    }

    /**
     * Check if Copyscape is available.
     */
    public function isCopyscapeAvailable(): bool
    {
        return $this->copyscapeChecker->isConfigured();
    }

    /**
     * Get Copyscape credit balance.
     */
    public function getCopyscapeBalance(): ?int
    {
        return $this->copyscapeChecker->getBalance();
    }

    private function runInternalCheck(string $text, ?int $agencyProfileId): array
    {
        try {
            $existingDocuments = $this->getExistingDocuments($agencyProfileId);

            if (empty($existingDocuments)) {
                return [
                    'verdict' => 'passed',
                    'internal_only' => true,
                    'exact_duplicate' => false,
                    'words_checked' => str_word_count(strip_tags($text)),
                    'repeated_new_text_percent' => 0.0,
                    'comparison_count' => 0,
                    'matches' => [],
                    'note' => 'No existing content to compare against.',
                ];
            }

            return $this->internalChecker->check($text, $existingDocuments);

        } catch (\InvalidArgumentException $e) {
            return [
                'verdict' => 'error',
                'internal_only' => true,
                'error' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error('Internal uniqueness check failed', ['error' => $e->getMessage()]);

            return [
                'verdict' => 'error',
                'internal_only' => true,
                'error' => 'Internal check failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get existing published content for comparison.
     */
    private function getExistingDocuments(?int $agencyProfileId): array
    {
        $query = GeneratedPage::query()
            ->where('status', 'published')
            ->whereNotNull('content_html')
            ->select(['id', 'seo_title', 'content_html']);

        if ($agencyProfileId) {
            $query->where('agency_profile_id', $agencyProfileId);
        }

        return $query->limit(100)->get()->map(function ($page) {
            return [
                'id' => $page->id,
                'title' => $page->seo_title ?? 'Page #' . $page->id,
                'text' => $page->content_html,
            ];
        })->toArray();
    }

    private function determineOverallVerdict(array $internalResult, ?array $copyscapeResult): string
    {
        $internalVerdict = $internalResult['verdict'] ?? 'error';
        $copyscapeVerdict = $copyscapeResult['verdict'] ?? null;

        if ($internalVerdict === 'failed' || $copyscapeVerdict === 'failed') {
            return 'failed';
        }

        if ($internalVerdict === 'error' && $copyscapeVerdict === 'error') {
            return 'error';
        }

        if ($internalVerdict === 'review' || $copyscapeVerdict === 'review') {
            return 'review';
        }

        if ($internalVerdict === 'passed' && ($copyscapeVerdict === 'passed' || $copyscapeVerdict === null)) {
            return 'passed';
        }

        return 'review';
    }

    private function generateSummary(array $internalResult, ?array $copyscapeResult, string $overallVerdict): string
    {
        $parts = [];

        $internalVerdict = $internalResult['verdict'] ?? 'unknown';
        $internalPercent = $internalResult['repeated_new_text_percent'] ?? 0;
        $parts[] = "Internal: {$internalVerdict} ({$internalPercent}% overlap)";

        if ($copyscapeResult) {
            $csVerdict = $copyscapeResult['verdict'] ?? 'unknown';
            $csPercent = $copyscapeResult['percent_matched'] ?? 0;
            $parts[] = "Copyscape: {$csVerdict} ({$csPercent}% matched)";
        } else {
            $parts[] = "Copyscape: not checked";
        }

        $verdictLabel = match ($overallVerdict) {
            'passed' => '✓ Passed',
            'review' => '⚠ Needs Review',
            'failed' => '✗ Failed',
            default => '? Error',
        };

        return $verdictLabel . ' — ' . implode(' | ', $parts);
    }
}
