<?php

namespace App\Console\Commands;

use App\Http\Controllers\Agency\AgencySettingsController;
use App\Services\Est8ads\PublicPageTranslator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class Est8adsTranslatePublicPages extends Command
{
    protected $signature = 'est8ads:translate-public-pages
        {--only= : Comma-separated locale codes to (re)generate, e.g. hr,de,fr}
        {--force : Regenerate a locale even if lang/{locale}.json already exists}
        {--concurrency=8 : How many languages to translate at the same time}';

    protected $description = 'AI-translates the EST8ADS public marketing pages (nav, footer, homepage, contact) into every supported language.';

    public function handle(PublicPageTranslator $translator): int
    {
        $strings = $translator->extractStrings();

        if ($strings === []) {
            $this->error('No translatable strings were found. Nothing to do.');

            return self::FAILURE;
        }

        $this->info(sprintf('Found %d translatable strings.', count($strings)));

        $languages = AgencySettingsController::supportedPanelLanguages();
        unset($languages['en']); // English is the source language, no file needed.

        if ($only = $this->option('only')) {
            $wanted = array_map('trim', explode(',', $only));
            $languages = array_intersect_key($languages, array_flip($wanted));
        }

        if (! $this->option('force')) {
            $languages = array_filter($languages, fn ($name, $code) => ! File::exists(lang_path("{$code}.json")), ARRAY_FILTER_USE_BOTH);
        }

        if ($languages === []) {
            $this->info('Nothing to do — every requested locale already has a translation file.');

            return self::SUCCESS;
        }

        File::ensureDirectoryExists(lang_path());

        // Every AI provider call is independent, so languages are batched and
        // fired concurrently instead of one request at a time.
        foreach (array_chunk($languages, (int) $this->option('concurrency'), true) as $batch) {
            $this->translateBatch($translator, $strings, $batch);
        }

        return self::SUCCESS;
    }

    /**
     * @param  array<int, string>  $strings
     * @param  array<string, string>  $batch  locale code => language name
     */
    private function translateBatch(PublicPageTranslator $translator, array $strings, array $batch): void
    {
        $this->line('Translating concurrently: ' . implode(', ', $batch));

        $prompts = [];
        foreach ($batch as $code => $name) {
            $prompts[$code] = $translator->buildPrompt($name, $strings);
        }

        $responses = $translator->requestMany($prompts);

        foreach ($batch as $code => $name) {
            $translated = $translator->parseResponse($responses[$code] ?? null);

            if ($translated === null) {
                $this->warn("  Failed to translate {$name} ({$code}) — check the AI provider configuration/API key.");

                continue;
            }

            // Never ship a partial dictionary: any string the AI dropped
            // falls back to the English original so nothing renders blank.
            $complete = [];
            foreach ($strings as $string) {
                $complete[$string] = $translated[$string] ?? $string;
            }

            File::put(lang_path("{$code}.json"), json_encode($complete, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->info("  Saved lang/{$code}.json ({$name}, " . count($complete) . ' strings).');
        }
    }
}
