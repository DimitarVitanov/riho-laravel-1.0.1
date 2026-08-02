<?php

namespace App\Services\Est8ads\Discovery\Providers;

use App\Models\Est8ads\InternetSource;
use App\Services\AiService;
use App\Services\Est8ads\Discovery\Contracts\DiscoveryProvider;
use App\Services\Est8ads\Discovery\ListingPageScraper;
use Illuminate\Support\Facades\Log;

/**
 * AI-assisted discovery: the model proposes candidate listing URLs for the
 * wanted profile, then our own scraper visits each URL and extracts the real
 * figures.
 *
 * Only scraper-verified records are emitted. A model can invent a price or a
 * surface area, so nothing the AI claims about a property is trusted — the AI
 * is used purely to find candidates worth visiting.
 */
class AiWebSearchProvider implements DiscoveryProvider
{
    public function __construct(
        private AiService $ai,
        private ListingPageScraper $scraper,
    ) {
    }

    public function search(InternetSource $source, array $profile): iterable
    {
        $limit = (int) (data_get($source->configuration, 'result_limit', 25));
        $candidates = $this->candidates($source, $profile, $limit);

        if ($candidates === []) {
            return;
        }

        $host = $source->domain ?: parse_url($source->base_url, PHP_URL_HOST);
        $emitted = 0;

        foreach ($candidates as $candidate) {
            $url = trim((string) ($candidate['url'] ?? $candidate['canonical_url'] ?? ''));

            if ($url === '') {
                continue;
            }

            // The AI only nominates the page. The scraper decides the facts.
            $record = $this->scraper->scrape($url, $host);

            if (! $record) {
                continue;
            }

            $record['discovered_by'] = 'ai_web_search';
            $record['ai_reason'] = isset($candidate['reason']) ? (string) $candidate['reason'] : null;

            yield array_filter($record, fn ($value) => $value !== null);

            if (++$emitted >= $limit) {
                return;
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function candidates(InternetSource $source, array $profile, int $limit): array
    {
        $provider = (string) data_get($source->configuration, 'ai_provider', config('est8ads.discovery.ai_provider', 'openai'));

        $response = $this->ai->generateJson($this->prompt($source, $profile, $limit), $provider, [
            'temperature' => 0.2,
            'max_tokens' => 2500,
        ]);

        $candidates = $response['listings'] ?? $response['results'] ?? null;

        if (! is_array($candidates)) {
            Log::info('AI discovery returned no candidate list', ['source' => $source->id, 'provider' => $provider]);

            return [];
        }

        return array_values(array_filter($candidates, 'is_array'));
    }

    private function prompt(InternetSource $source, array $profile, int $limit): string
    {
        $host = $source->domain ?: parse_url($source->base_url, PHP_URL_HOST);
        $types = implode(', ', $profile['property_types'] ?? []) ?: 'any type';
        $cities = implode(', ', $profile['cities'] ?? []) ?: 'any location';
        $budget = (float) data_get($profile, 'price.max', 0);
        $currency = (string) data_get($profile, 'price.currency', 'EUR');
        $size = (float) ($profile['target_size_m2'] ?? 0);
        $sizeTolerance = (float) ($profile['size_tolerance_percent'] ?? 10);
        $priceTolerance = (float) ($profile['price_tolerance_percent'] ?? 10);

        $sizeWindow = $size > 0
            ? sprintf('%.0f m² (acceptable range %.0f–%.0f m², i.e. ±%.0f%%)', $size, $size * (1 - $sizeTolerance / 100), $size * (1 + $sizeTolerance / 100), $sizeTolerance)
            : 'no size requirement';

        $priceWindow = $budget > 0
            ? sprintf('up to %s %s, stretching to %s %s at most (+%.0f%%)', number_format($budget), $currency, number_format($budget * (1 + $priceTolerance / 100)), $currency, $priceTolerance)
            : 'no budget limit';

        return <<<PROMPT
You are helping a real estate chain-matching engine find candidate property listings.

Search ONLY the website {$host} and return property listing pages that are currently for sale and match this buyer profile:
- Property type: {$types}
- Location: {$cities}
- Living size: {$sizeWindow}
- Budget: {$priceWindow}
- Minimum bedrooms: {$this->value($profile['minimum_bedrooms'] ?? null)}
- Minimum bathrooms: {$this->value($profile['minimum_bathrooms'] ?? null)}

Rules:
- Return at most {$limit} results.
- Prefer exact matches on size and budget first, then near matches inside the tolerance range.
- Every url MUST be a real, currently reachable listing detail page on {$host}. Never invent a URL.
- Do not return search result pages, category pages or agency landing pages.

Respond with JSON only, in this exact shape:
{"listings": [{"url": "https://{$host}/...", "reason": "why it matches, one short sentence"}]}
PROMPT;
    }

    private function value(mixed $value): string
    {
        return $value === null || $value === '' ? 'not specified' : (string) $value;
    }
}
