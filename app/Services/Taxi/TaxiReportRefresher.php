<?php

namespace App\Services\Taxi;

use App\Models\TaxiCountryReport;
use App\Models\TaxiReportPrompt;
use App\Models\TaxiSetting;
use App\Services\AiService;
use Illuminate\Support\Facades\Log;

/**
 * Refreshes the factual content of a stored country report section by section.
 * The original markup (tags, classes, ids, layout) is always preserved — only
 * the inner content of each analysis block can be rewritten by the AI.
 */
class TaxiReportRefresher
{
    public function __construct(private AiService $ai)
    {
    }

    public static function provider(): string
    {
        return (string) TaxiSetting::get('ai_provider', 'openai');
    }

    /**
     * @return array{status: string, sections: int, note: string}
     */
    public function refresh(TaxiCountryReport $report, ?callable $progress = null): array
    {
        $provider = self::provider();
        $doc = TaxiReportHtml::load($report->html_full);
        $blocks = $doc->contentBlocks();

        if (empty($blocks)) {
            return $this->finish($report, 'failed', 0, 'No content blocks found in stored HTML.', $provider);
        }

        $updated = 0;
        $failed = 0;

        foreach ($blocks as $sectionId => $element) {
            $original = $doc->innerHtml($element);

            $prompt = $this->buildPrompt($report, $sectionId, $original);
            $response = $this->ai->generate($prompt, $provider, [
                'temperature' => 0.2,
                'max_tokens' => 4096,
            ]);

            $candidate = $this->sanitize($response);

            if ($candidate === null || !$this->isPlausible($original, $candidate)) {
                $failed++;
                if ($progress) {
                    $progress($sectionId, false);
                }
                continue;
            }

            if (trim($candidate) === trim($original)) {
                if ($progress) {
                    $progress($sectionId, true);
                }
                continue;
            }

            if ($doc->replaceInnerHtml($element, $candidate)) {
                $updated++;
            } else {
                $failed++;
            }

            if ($progress) {
                $progress($sectionId, true);
            }
        }

        if ($updated === 0 && $failed > 0) {
            return $this->finish(
                $report,
                'failed',
                0,
                "AI returned no usable content ({$failed} sections failed).",
                $provider
            );
        }

        $html = $doc->save();

        $report->fill([
            'html_full' => $html,
            'content_hash' => hash('sha256', $html),
        ]);

        $status = $failed > 0 ? 'partial' : 'success';
        $note = "Refreshed {$updated} of " . count($blocks) . ' sections'
            . ($failed > 0 ? " ({$failed} skipped)." : '.');

        return $this->finish($report, $status, $updated, $note, $provider);
    }

    private function buildPrompt(TaxiCountryReport $report, string $sectionId, string $sectionHtml): string
    {
        $rules = TaxiReportPrompt::globalRules() ?? '';
        $sectionPrompt = TaxiReportPrompt::forSection($sectionId)?->prompt_text ?? '';

        $vars = [
            '{{COUNTRY}}' => $report->country,
            '{{COUNTRY_SLUG}}' => $report->country_slug,
            '{{YEAR}}' => now()->year,
            '{{CURRENT_DATE}}' => now()->toFormattedDateString(),
            '{{REPORT_NUMBER}}' => (string) $report->report_number,
            '{{GPG_URL}}' => 'https://www.globalpropertyguide.com/',
        ];

        $rules = strtr($rules, $vars);
        $sectionPrompt = strtr($sectionPrompt, $vars);

        return <<<PROMPT
{$rules}

SECTION-SPECIFIC INSTRUCTIONS ({$sectionId}):
{$sectionPrompt}

TASK:
You are performing the scheduled 30-day factual refresh of one section of the
{$report->country} residential real estate market report for {$vars['{{YEAR}}']}.

Below is the CURRENT inner HTML of that section.

STRICT OUTPUT RULES:
1. Return the inner HTML of the same section only — no markdown, no code fences, no commentary.
2. Keep every HTML tag, class, id, attribute, list length and structural element exactly as-is.
3. Only change the words and numbers inside the existing elements, and only where newer verified official data exists as of {$vars['{{CURRENT_DATE}}']}.
4. Never invent a figure. If you cannot verify a newer value, keep the existing text unchanged.
5. Keep the reference period next to every figure and keep the same language (English).
6. If nothing needs to change, return the current HTML exactly as received.

CURRENT SECTION HTML:
{$sectionHtml}
PROMPT;
    }

    private function sanitize(?string $response): ?string
    {
        if (!$response) {
            return null;
        }

        $html = trim($response);
        $html = preg_replace('/^```[a-z]*\s*/i', '', $html);
        $html = preg_replace('/```\s*$/', '', $html);
        $html = trim((string) $html);

        return $html === '' ? null : $html;
    }

    private function isPlausible(string $original, string $candidate): bool
    {
        if (!str_contains($candidate, '<')) {
            return false;
        }

        $originalLength = mb_strlen($original);
        $candidateLength = mb_strlen($candidate);

        if ($candidateLength < $originalLength * 0.5 || $candidateLength > $originalLength * 2.0) {
            Log::warning('Taxi refresh: implausible section length', [
                'original' => $originalLength,
                'candidate' => $candidateLength,
            ]);

            return false;
        }

        return substr_count($candidate, '<') > 1;
    }

    private function finish(
        TaxiCountryReport $report,
        string $status,
        int $sections,
        string $note,
        string $provider
    ): array {
        $report->fill([
            'last_refresh_status' => $status,
            'last_refresh_note' => $note,
            'sections_updated' => $sections,
            'ai_provider' => $provider,
            'next_refresh_at' => now()->addDays($report->refresh_interval_days ?: 30),
        ]);

        if ($status !== 'failed') {
            $report->last_generated_at = now();
        }

        $report->save();

        return ['status' => $status, 'sections' => $sections, 'note' => $note];
    }
}
