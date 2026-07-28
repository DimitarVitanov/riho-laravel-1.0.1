<?php

namespace App\Console\Commands;

use App\Models\TaxiCountryReport;
use App\Services\Taxi\TaxiReportTranslator;
use Illuminate\Console\Command;

class TranslateTaxiCountryReports extends Command
{
    protected $signature = 'taxi:translate-reports
                            {locale : Target locale, e.g. hr}
                            {--slug= : Translate one country slug only}
                            {--limit=5 : Maximum number of reports per run}
                            {--stale-only : Only (re)translate reports whose English master is newer}';

    protected $description = 'Create or update localized copies of the Global Data country reports';

    public function handle(TaxiReportTranslator $translator): int
    {
        $locale = $this->argument('locale');

        $query = TaxiCountryReport::query()->where('locale', 'en')->where('is_published', true);

        if ($slug = $this->option('slug')) {
            $query->where('country_slug', $slug);
        }

        $masters = $query->orderBy('country')->get();
        $done = 0;
        $limit = (int) $this->option('limit');

        foreach ($masters as $master) {
            if ($done >= $limit) {
                break;
            }

            if ($this->option('stale-only')) {
                $existing = TaxiCountryReport::findBySlug($master->country_slug, $locale);
                if ($existing && $existing->last_generated_at >= $master->last_generated_at) {
                    continue;
                }
            }

            $this->line("Translating {$master->country} → {$locale} …");

            $result = $translator->translate($master, $locale);

            $this->line($result ? '  ✓ saved' : '  ✗ translation failed');
            $done++;
        }

        $this->info("Finished. {$done} report(s) processed for {$locale}.");

        return self::SUCCESS;
    }
}
