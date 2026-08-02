<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\ExternalListing;
use App\Services\AiService;
use Illuminate\Support\Str;
use Throwable;

/**
 * Optional AI pass over a deterministic match.
 *
 * The rules decide whether a listing is eligible; the AI only judges how well
 * the free-text side fits (requirements, description, condition) and writes the
 * human explanation. Its influence is capped by the configured weight, and it
 * can never rescue a listing that broke a hard rule.
 */
class SemanticMatchRanker
{
    public function __construct(private AiService $ai)
    {
    }

    /**
     * @param  array<string, mixed>  $profile
     * @param  array<string, mixed>  $scored  Output of DeterministicMatchScorer::score()
     * @return array{final_score: float, semantic_score: float|null, explanation: string}
     */
    public function rank(array $profile, ExternalListing $listing, array $scored): array
    {
        $deterministic = (float) $scored['score'];
        $fallback = $this->describe($scored);

        if ($scored['hard_conflicts'] !== []) {
            return ['final_score' => $deterministic, 'semantic_score' => null, 'explanation' => $fallback];
        }

        $weight = max(0.0, min(1.0, (float) config('est8ads.discovery.semantic_weight', 0.25)));

        if ($weight === 0.0) {
            return ['final_score' => $deterministic, 'semantic_score' => null, 'explanation' => $fallback];
        }

        $response = $this->ask($profile, $listing);

        if ($response === null) {
            return ['final_score' => $deterministic, 'semantic_score' => null, 'explanation' => $fallback];
        }

        $semantic = max(0.0, min(100.0, (float) $response['score']));
        $final = round($deterministic * (1 - $weight) + $semantic * $weight, 2);

        return [
            'final_score' => $final,
            'semantic_score' => $semantic,
            'explanation' => trim($response['explanation']) !== '' ? Str::limit($response['explanation'], 1000, '') : $fallback,
        ];
    }

    /**
     * @return array{score: float, explanation: string}|null
     */
    private function ask(array $profile, ExternalListing $listing): ?array
    {
        try {
            $result = $this->ai->generateJson(
                $this->prompt($profile, $listing),
                (string) config('est8ads.discovery.rank_provider', 'gemini'),
                ['temperature' => 0.1, 'max_tokens' => 500],
            );
        } catch (Throwable) {
            return null;
        }

        if (! is_array($result) || ! isset($result['score'])) {
            return null;
        }

        return ['score' => (float) $result['score'], 'explanation' => (string) ($result['explanation'] ?? '')];
    }

    private function prompt(array $profile, ExternalListing $listing): string
    {
        $wanted = json_encode([
            'property_types' => $profile['property_types'] ?? [],
            'cities' => $profile['cities'] ?? [],
            'budget_max' => data_get($profile, 'price.max'),
            'currency' => data_get($profile, 'price.currency', 'EUR'),
            'target_size_m2' => $profile['target_size_m2'] ?? null,
            'minimum_bedrooms' => $profile['minimum_bedrooms'] ?? null,
            'must_have_features' => $profile['must_have_features'] ?? [],
            'notes' => $profile['notes'] ?? null,
        ], JSON_UNESCAPED_UNICODE);

        $candidate = json_encode([
            'title' => $listing->title,
            'property_type' => $listing->property_type,
            'city' => $listing->city,
            'price' => $listing->price,
            'currency' => $listing->currency,
            'size_m2' => $listing->size_m2,
            'bedrooms' => $listing->bedrooms,
            'bathrooms' => $listing->bathrooms,
            'condition' => $listing->condition,
            'description' => Str::limit((string) $listing->description_excerpt, 700, ''),
        ], JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
Rate how well a discovered property listing fits a buyer's requirements.

BUYER WANTS:
{$wanted}

CANDIDATE LISTING:
{$candidate}

Judge only the qualitative fit (location suitability, condition, described features, overall
sensibility for this buyer). Numeric size and budget checks are already handled elsewhere.

Respond with JSON only:
{"score": 0-100, "explanation": "one or two sentences a broker could read to the client"}
PROMPT;
    }

    /**
     * Readable summary used whenever the AI is unavailable or skipped.
     */
    private function describe(array $scored): string
    {
        $tolerance = $scored['tolerance'] ?? [];
        $notes = [];

        foreach (['size' => 'Size', 'price' => 'Price'] as $key => $label) {
            $status = $tolerance[$key . '_status'] ?? null;
            $deviation = $tolerance[$key . '_deviation'] ?? null;

            if ($status === 'tolerance' && $deviation !== null) {
                $notes[] = sprintf('%s is %+.1f%% off target, inside the %.0f%% tolerance', $label, $deviation, (float) ($tolerance[$key . '_percent'] ?? 10));
            } elseif ($status === 'exact') {
                $notes[] = $label . ' meets the requirement';
            } elseif ($status === 'unknown') {
                $notes[] = $label . ' was not published by the source';
            }
        }

        if ($scored['hard_conflicts'] !== []) {
            $notes[] = 'Blocked by: ' . implode(', ', $scored['hard_conflicts']);
        }

        return $notes === [] ? 'Deterministic rule match.' : ucfirst(implode('. ', $notes)) . '.';
    }
}
