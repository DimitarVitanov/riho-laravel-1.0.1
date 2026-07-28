<?php

namespace App\Console\Commands;

use App\Models\TaxiCountryReport;
use App\Services\Taxi\TaxiReportHtml;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportTaxiCountryReports extends Command
{
    protected $signature = 'taxi:import-reports
                            {--path= : Folder holding the original report HTML files}
                            {--force : Overwrite reports that already exist}';

    protected $description = 'Import the original Real Estate Taxi country report HTML files into the database (English master copies)';

    public function handle(): int
    {
        $path = $this->option('path') ?: database_path('data/taxi-reports');

        if (!is_dir($path)) {
            $this->error("Folder not found: {$path}");

            return self::FAILURE;
        }

        $files = glob(rtrim($path, '/') . '/*.html') ?: [];
        if (empty($files)) {
            $this->error("No HTML files found in {$path}");

            return self::FAILURE;
        }

        $imported = 0;
        $skipped = 0;

        foreach ($files as $file) {
            $name = basename($file);

            if (Str::startsWith($name, ['1.PROMPT', 'PROMPTS-'])) {
                continue;
            }

            $isIndex = Str::contains($name, 'country_market_reports');
            $slug = $isIndex ? TaxiCountryReport::INDEX_SLUG : $this->slugFromFilename($name);

            $existing = TaxiCountryReport::findBySlug($slug, 'en');
            if ($existing && !$this->option('force')) {
                $skipped++;
                continue;
            }

            $html = file_get_contents($file);
            $doc = TaxiReportHtml::load($html);

            $country = $isIndex
                ? 'All countries (index)'
                : $this->countryFromHeading($doc->heading(), $slug);

            TaxiCountryReport::updateOrCreate(
                ['country_slug' => $slug, 'locale' => 'en'],
                [
                    'country' => $country,
                    'title' => $doc->title(),
                    'meta_description' => $doc->metaContent('description'),
                    'canonical_url' => $doc->canonical(),
                    'html_full' => $html,
                    'source_file' => $name,
                    'content_hash' => hash('sha256', $html),
                    'is_published' => true,
                    'refresh_interval_days' => 30,
                    'last_generated_at' => now(),
                    'next_refresh_at' => now()->addDays(30),
                    'last_refresh_status' => 'imported',
                    'last_refresh_note' => 'Imported from original source file.',
                ]
            );

            $imported++;
            $this->line("  imported: {$slug}");
        }

        $this->info("Done. Imported {$imported}, skipped {$skipped} (use --force to overwrite).");

        return self::SUCCESS;
    }

    private function slugFromFilename(string $name): string
    {
        $slug = preg_replace('/\.html$/i', '', $name);
        $slug = preg_replace('/\(\d+\)$/', '', $slug);
        $slug = preg_replace('/_residential_real_estate_market_analysis.*$/i', '', $slug);

        return Str::slug(str_replace('_', ' ', $slug));
    }

    private function countryFromHeading(?string $heading, string $slug): string
    {
        if ($heading && preg_match('/^(.*?)\s+Residential Real Estate Market Analysis/i', $heading, $m)) {
            return trim($m[1]);
        }

        return Str::title(str_replace('-', ' ', $slug));
    }
}
