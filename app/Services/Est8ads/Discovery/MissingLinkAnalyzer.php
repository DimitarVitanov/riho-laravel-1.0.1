<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\ExternalListingMatch;
use App\Models\Est8ads\PropertyMove;
use App\Services\AiService;
use Illuminate\Support\Str;
use Throwable;

/**
 * Turns a request that already has a matching candidate property into a
 * human-readable "missing link": the specific condition preventing that
 * match from becoming a completed chain.
 *
 * Deterministic rules always decide the raw facts (which conflict blocked
 * the closest candidate and how strong that candidate otherwise was). The AI
 * only writes the headline, the readable impact statement and picks a
 * priority inside the bounds those facts allow.
 */
class MissingLinkAnalyzer
{
    private const CONFLICT_LABELS = [
        'budget' => 'the buyer\'s budget',
        'minimum_size' => 'the minimum size requirement',
        'minimum_bedrooms' => 'the minimum bedroom count',
        'minimum_bathrooms' => 'the minimum bathroom count',
        'country' => 'the target country',
        'property_type' => 'the requested property type',
        'listing_direction' => 'the sell/buy direction',
        'listing_not_active' => 'listing availability',
    ];

    public function __construct(private AiService $ai)
    {
    }

    /**
     * A near-miss internet listing was found but a hard rule blocked it.
     *
     * @param  array<string, mixed>  $profile
     */
    public function analyzeConflict(PropertyMove $move, array $profile, ExternalListingMatch $match): array
    {
        $conflicts = (array) ($match->hard_conflicts ?? []);
        $fallback = $this->fallbackForConflict($move, $conflicts, $match);

        $response = $this->ask($this->conflictPrompt($move, $profile, $conflicts, $match));

        if ($response === null) {
            return $fallback;
        }

        return $this->merge($fallback, $response);
    }

    /**
     * @return array{score: float, explanation: string}|null
     */
    private function ask(string $prompt): ?array
    {
        try {
            $result = $this->ai->generateJson(
                $prompt,
                (string) config('est8ads.discovery.rank_provider', 'gemini'),
                ['temperature' => 0.2, 'max_tokens' => 400],
            );
        } catch (Throwable) {
            return null;
        }

        if (! is_array($result) || ! isset($result['title'])) {
            return null;
        }

        return $result;
    }

    /**
     * Overlays the AI's wording on top of the deterministic facts. The AI can
     * never invent a priority above what the facts support.
     */
    private function merge(array $fallback, array $response): array
    {
        $suggested = $this->normalizePriority((string) ($response['priority'] ?? $fallback['priority']));
        // The AI may only report priority as high as the deterministic facts
        // already allow, never higher.
        $priority = $this->priorityRank($suggested) > $this->priorityRank($fallback['priority']) ? $fallback['priority'] : $suggested;

        return [
            'title' => Str::limit((string) ($response['title'] ?? $fallback['title']), 120, ''),
            'location' => $fallback['location'],
            'impact' => Str::limit((string) ($response['impact'] ?? $fallback['impact']), 160, ''),
            'unlock_value' => $fallback['unlock_value'],
            'unlock_value_currency' => $fallback['unlock_value_currency'],
            'priority' => $priority,
            'priority_rank' => $this->priorityRank($priority),
            'blocking_conflicts' => $fallback['blocking_conflicts'],
            'explanation' => trim((string) ($response['explanation'] ?? '')) !== ''
                ? Str::limit((string) $response['explanation'], 500, '')
                : $fallback['explanation'],
        ];
    }

    private function conflictPrompt(PropertyMove $move, array $profile, array $conflicts, ExternalListingMatch $match): string
    {
        $listing = $match->externalListing;
        $request = json_encode([
            'move_type' => $move->move_type,
            'target_location' => $move->target_location,
            'budget_max' => $move->budget_max,
            'currency' => $move->currency,
            'notes' => $move->notes,
        ], JSON_UNESCAPED_UNICODE);

        $candidate = json_encode([
            'title' => $listing?->title,
            'city' => $listing?->city,
            'price' => $listing?->price,
            'size_m2' => $listing?->size_m2,
            'blocked_by' => array_values($conflicts),
            'score' => $match->final_score,
        ], JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
A property-chain platform found a near-match internet listing for a buyer's request, but a hard rule
blocked it from being connected automatically.

BUYER REQUEST:
{$request}

CLOSEST CANDIDATE LISTING (blocked):
{$candidate}

Write a short, actionable summary a real-estate broker could act on today. Explain in plain language
what specific condition is preventing this chain from completing (e.g. a price gap, a size shortfall,
a location mismatch) and what would need to change to unlock it.

Respond with JSON only:
{"title": "short headline, max 12 words", "impact": "one sentence describing what is blocked", "priority": "High|Medium|Low", "explanation": "one or two sentences a broker could read to the client"}
PROMPT;
    }

    private function fallbackForConflict(PropertyMove $move, array $conflicts, ExternalListingMatch $match): array
    {
        $labels = array_map(fn ($conflict) => self::CONFLICT_LABELS[Str::before($conflict, ':')] ?? Str::headline(Str::before($conflict, ':')), $conflicts);
        $location = $move->target_location ?: $match->externalListing?->city;
        $score = (float) ($match->final_score ?? 0);

        return [
            'title' => 'Near-miss listing blocked by ' . ($labels[0] ?? 'a hard rule'),
            'location' => $location,
            'impact' => $labels === [] ? 'A close candidate was found but could not be connected automatically.'
                : 'Blocked by ' . implode(' and ', $labels) . '.',
            'unlock_value' => (float) ($move->budget_max ?: $match->externalListing?->price ?: 0) ?: null,
            'unlock_value_currency' => $move->currency ?: $match->externalListing?->currency ?: 'EUR',
            'priority' => $score >= 70 ? 'High' : ($score >= 45 ? 'Medium' : 'Low'),
            'priority_rank' => $this->priorityRank($score >= 70 ? 'High' : ($score >= 45 ? 'Medium' : 'Low')),
            'blocking_conflicts' => array_values($conflicts),
            'explanation' => $labels === [] ? 'A close candidate was found but could not be connected automatically.'
                : sprintf('The best available match scored %s%% but was blocked by %s.', number_format($score), implode(' and ', $labels)),
        ];
    }

    private function normalizePriority(string $priority): string
    {
        $priority = ucfirst(strtolower(trim($priority)));

        return in_array($priority, ['High', 'Medium', 'Low'], true) ? $priority : 'Medium';
    }

    private function priorityRank(string $priority): int
    {
        return match ($priority) {
            'High' => 3,
            'Low' => 1,
            default => 2,
        };
    }
}
