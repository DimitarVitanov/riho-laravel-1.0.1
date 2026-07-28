<?php

namespace App\Console\Commands;

use App\Models\TaxiCountryReport;
use App\Models\TaxiSetting;
use App\Services\Taxi\TaxiReportRefresher;
use Illuminate\Console\Command;

class RefreshTaxiCountryReports extends Command
{
    protected $signature = 'taxi:refresh-reports
                            {--slug= : Refresh one country slug only}
                            {--limit=5 : Maximum number of reports to refresh in this run}
                            {--force : Ignore the 30-day schedule}';

    protected $description = 'Refresh the Global Data country reports with the latest verified figures (runs on the 30-day cycle)';

    public function handle(TaxiReportRefresher $refresher): int
    {
        if (TaxiSetting::get('auto_refresh_enabled', '1') !== '1' && !$this->option('force')) {
            $this->warn('Automatic Global Data refresh is disabled in the admin panel.');

            return self::SUCCESS;
        }

        $query = TaxiCountryReport::query()->where('locale', 'en');

        if ($slug = $this->option('slug')) {
            $query->where('country_slug', $slug);
        } elseif (!$this->option('force')) {
            $query->dueForRefresh();
        }

        $reports = $query->orderByRaw('next_refresh_at is null desc')
            ->orderBy('next_refresh_at')
            ->limit((int) $this->option('limit'))
            ->get();

        if ($reports->isEmpty()) {
            $this->info('No Global Data reports are due for refresh.');

            return self::SUCCESS;
        }

        foreach ($reports as $report) {
            $this->line("Refreshing {$report->country} ({$report->country_slug}) …");

            $result = $refresher->refresh($report, function (string $sectionId, bool $ok) {
                $this->line('  ' . ($ok ? '✓' : '✗') . " {$sectionId}");
            });

            $this->line("  → {$result['status']}: {$result['note']}");
        }

        TaxiSetting::put('last_cron_run_at', now()->toDateTimeString());

        return self::SUCCESS;
    }
}
