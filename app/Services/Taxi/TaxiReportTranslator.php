<?php

namespace App\Services\Taxi;

use App\Models\TaxiCountryReport;
use App\Models\TaxiSetting;
use App\Services\AiService;

/**
 * Produces localized copies of an English master report. Only text nodes and
 * meta values are translated, so the layout stays identical to the original.
 */
class TaxiReportTranslator
{
    private const BATCH_SIZE = 40;

    public function __construct(private AiService $ai)
    {
    }

    public function translate(TaxiCountryReport $master, string $locale): ?TaxiCountryReport
    {
        if ($locale === 'en') {
            return $master;
        }

        $provider = (string) TaxiSetting::get('translation_provider', 'gemini');
        $doc = TaxiReportHtml::load($master->html_full);
        $nodes = $doc->textNodes();

        $strings = [];
        foreach ($nodes as $i => $node) {
            $strings[$i] = $node->nodeValue;
        }

        $translated = [];
        foreach (array_chunk($strings, self::BATCH_SIZE, true) as $chunk) {
            $result = $this->translateChunk($chunk, $locale, $provider);
            if ($result === null) {
                continue;
            }
            $translated += $result;
        }

        if (empty($translated)) {
            return null;
        }

        foreach ($nodes as $i => $node) {
            if (isset($translated[$i]) && trim((string) $translated[$i]) !== '') {
                $node->nodeValue = $translated[$i];
            }
        }

        $title = $this->translateLine($master->title, $locale, $provider) ?? $master->title;
        $description = $this->translateLine($master->meta_description, $locale, $provider) ?? $master->meta_description;

        if ($title) {
            $doc->setTitle($title);
        }
        if ($description) {
            $doc->setMetaContent('description', $description);
        }

        $html = $doc->save();

        return TaxiCountryReport::updateOrCreate(
            ['country_slug' => $master->country_slug, 'locale' => $locale],
            [
                'country' => $master->country,
                'iso2' => $master->iso2,
                'region' => $master->region,
                'report_number' => $master->report_number,
                'title' => $title,
                'meta_description' => $description,
                'canonical_url' => TaxiReportRenderer::publicUrl($master, $locale),
                'html_full' => $html,
                'source_file' => $master->source_file,
                'content_hash' => hash('sha256', $html),
                'is_published' => true,
                'refresh_interval_days' => $master->refresh_interval_days,
                'last_generated_at' => now(),
                'next_refresh_at' => $master->next_refresh_at,
                'last_refresh_status' => 'translated',
                'last_refresh_note' => 'Translated from the English master report.',
                'ai_provider' => $provider,
                'source_report_id' => $master->id,
            ]
        );
    }

    /**
     * @param  array<int, string>  $chunk
     * @return array<int, string>|null
     */
    private function translateChunk(array $chunk, string $locale, string $provider): ?array
    {
        $payload = json_encode($chunk, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $prompt = <<<PROMPT
Translate the following JSON object of website strings from English into the language with ISO code "{$locale}".

RULES:
1. Return ONLY a JSON object with exactly the same keys.
2. Translate values naturally and professionally for a real estate market report.
3. Never translate numbers, currency symbols, percentages, dates, institution names, URLs or country/city names that have no common local form.
4. Preserve leading and trailing whitespace of each value.
5. No markdown, no commentary.

JSON:
{$payload}
PROMPT;

        $result = $this->ai->generateJson($prompt, $provider, [
            'temperature' => 0.1,
            'max_tokens' => 8192,
        ]);

        if (!is_array($result)) {
            return null;
        }

        $out = [];
        foreach ($result as $key => $value) {
            if (is_string($value)) {
                $out[(int) $key] = $value;
            }
        }

        return $out;
    }

    private function translateLine(?string $text, string $locale, string $provider): ?string
    {
        if (!$text) {
            return null;
        }

        $response = $this->ai->generate(
            "Translate this text into the language with ISO code \"{$locale}\". Return only the translation, nothing else:\n\n{$text}",
            $provider,
            ['temperature' => 0.1, 'max_tokens' => 512]
        );

        return $response ? trim($response) : null;
    }
}
