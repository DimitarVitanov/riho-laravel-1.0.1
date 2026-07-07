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
    private GoogleFirstPageChecker $googleChecker;
    private UniqueContentRewriter $rewriter;

    public function __construct(?string $copyscapeUsername = null, ?string $copyscapeApiKey = null)
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

        $this->copyscapeChecker = new CopyscapeChecker($copyscapeUsername, $copyscapeApiKey);
        $this->googleChecker = new GoogleFirstPageChecker(maxPhrases: 3, delayBetweenRequestsMs: 2000);
        $this->rewriter = new UniqueContentRewriter();
    }

    /**
     * Run full uniqueness check (internal + optional Google/Copyscape).
     * If duplicates found and autoRewrite=true, automatically rewrites problematic phrases.
     *
     * @param string $text Text to check
     * @param int|null $agencyProfileId Limit internal check to this agency's content
     * @param bool $includeGoogle Whether to check Google first page (FREE)
     * @param bool $includeCopyscape Whether to also run Copyscape check (PAID)
     * @param bool $autoRewrite If true, automatically rewrite when duplicates found
     * @return array{
     *   overall_verdict: 'passed'|'review'|'failed'|'error',
     *   internal: array,
     *   google: array|null,
     *   copyscape: array|null,
     *   summary: string,
     *   rewrite: array|null
     * }
     */
    public function check(string $text, ?int $agencyProfileId = null, bool $includeGoogle = true, bool $includeCopyscape = false, bool $autoRewrite = false): array
    {
        $internalResult = $this->runInternalCheck($text, $agencyProfileId);
        $googleResult = null;
        $copyscapeResult = null;
        $rewriteResult = null;

        // Google first page check (FREE)
        if ($includeGoogle) {
            $googleResult = $this->googleChecker->check($text);
        }

        // Copyscape check (PAID)
        if ($includeCopyscape && $this->copyscapeChecker->isConfigured()) {
            $copyscapeResult = $this->copyscapeChecker->check($text);
        }

        $overallVerdict = $this->determineOverallVerdict($internalResult, $googleResult, $copyscapeResult);

        // Auto-rewrite if duplicates found and rewrite requested
        if ($autoRewrite && in_array($overallVerdict, ['review', 'failed'])) {
            $problematicPhrases = $this->extractProblematicPhrases($googleResult, $copyscapeResult);
            
            if (!empty($problematicPhrases)) {
                $rewriteResult = $this->rewriter->rewrite($text, $problematicPhrases);
                
                // If rewrite successful, update verdict
                if ($rewriteResult['success'] && $rewriteResult['rewritten_text']) {
                    $rewriteResult['original_verdict'] = $overallVerdict;
                    $rewriteResult['message'] = 'Text has been automatically rewritten to be unique. ' . count($problematicPhrases) . ' phrase(s) were modified.';
                }
            }
        }

        $summary = $this->generateSummary($internalResult, $googleResult, $copyscapeResult, $overallVerdict);

        return [
            'overall_verdict' => $overallVerdict,
            'internal' => $internalResult,
            'google' => $googleResult,
            'copyscape' => $copyscapeResult,
            'summary' => $summary,
            'rewrite' => $rewriteResult,
        ];
    }

    /**
     * Extract problematic phrases from Google/Copyscape results.
     */
    private function extractProblematicPhrases(?array $googleResult, ?array $copyscapeResult): array
    {
        $phrases = [];

        // From Google results - extract the search phrases that had matches
        if ($googleResult && isset($googleResult['results'])) {
            foreach ($googleResult['results'] as $phraseResult) {
                if (!empty($phraseResult['google_results'])) {
                    $phrases[] = $phraseResult['phrase'];
                }
            }
        }

        // From Copyscape - extract snippets that matched
        if ($copyscapeResult && isset($copyscapeResult['matches'])) {
            foreach ($copyscapeResult['matches'] as $match) {
                if (!empty($match['textsnippet'])) {
                    // Extract a meaningful phrase from the snippet
                    $snippet = strip_tags($match['textsnippet']);
                    if (strlen($snippet) > 20) {
                        $phrases[] = substr($snippet, 0, 100);
                    }
                }
            }
        }

        return array_unique($phrases);
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

    private function determineOverallVerdict(array $internalResult, ?array $googleResult, ?array $copyscapeResult): string
    {
        $internalVerdict = $internalResult['verdict'] ?? 'error';
        $googleVerdict = $googleResult['verdict'] ?? null;
        $copyscapeVerdict = $copyscapeResult['verdict'] ?? null;

        // Any failed = overall failed
        if ($internalVerdict === 'failed' || $googleVerdict === 'failed' || $copyscapeVerdict === 'failed') {
            return 'failed';
        }

        // All errors = overall error
        $allErrors = ($internalVerdict === 'error') 
            && ($googleVerdict === 'error' || $googleVerdict === null)
            && ($copyscapeVerdict === 'error' || $copyscapeVerdict === null);
        if ($allErrors) {
            return 'error';
        }

        // Any review = overall review
        if ($internalVerdict === 'review' || $googleVerdict === 'review' || $copyscapeVerdict === 'review') {
            return 'review';
        }

        // All passed (or not checked) = overall passed
        $internalOk = $internalVerdict === 'passed';
        $googleOk = $googleVerdict === 'passed' || $googleVerdict === null;
        $copyscapeOk = $copyscapeVerdict === 'passed' || $copyscapeVerdict === null;

        if ($internalOk && $googleOk && $copyscapeOk) {
            return 'passed';
        }

        return 'review';
    }

    private function generateSummary(array $internalResult, ?array $googleResult, ?array $copyscapeResult, string $overallVerdict): string
    {
        $parts = [];

        // Internal
        $internalVerdict = $internalResult['verdict'] ?? 'unknown';
        $internalPercent = $internalResult['repeated_new_text_percent'] ?? 0;
        $parts[] = "Internal: {$internalVerdict} ({$internalPercent}%)";

        // Google
        if ($googleResult) {
            $googleVerdict = $googleResult['verdict'] ?? 'unknown';
            $googleSimilarity = $googleResult['highest_similarity'] ?? 0;
            $parts[] = "Google: {$googleVerdict} ({$googleSimilarity}% similar)";
        }

        // Copyscape
        if ($copyscapeResult) {
            $csVerdict = $copyscapeResult['verdict'] ?? 'unknown';
            $csPercent = $copyscapeResult['percent_matched'] ?? 0;
            $parts[] = "Copyscape: {$csVerdict} ({$csPercent}%)";
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
