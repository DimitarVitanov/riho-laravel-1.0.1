<?php

namespace App\Console\Commands;

use App\Models\Est8ads\InternetSource;
use Illuminate\Console\Command;

class Est8adsEnableWebDiscovery extends Command
{
    protected $signature = 'est8ads:enable-web-discovery
        {--limit=25 : Maximum verified listings to keep per search}
        {--providers= : Comma separated AI models, defaults to the config value}';

    protected $description = 'Enable open-web property discovery (AI models propose listings, our scraper verifies them).';

    public function handle(): int
    {
        $providers = $this->option('providers')
            ? array_values(array_filter(array_map('trim', explode(',', (string) $this->option('providers')))))
            : (array) config('est8ads.discovery.web_search_providers', ['openai', 'gemini']);

        $source = InternetSource::updateOrCreate(
            ['domain' => 'open-web'],
            [
                'name' => 'Open web (AI + scraper)',
                // Kept only so existing URL checks have something to parse; the
                // provider is not restricted to this host.
                'base_url' => 'https://www.google.com',
                'type' => 'aggregator',
                'access_method' => 'api',
                'status' => 'active',
                'enabled' => true,
                'terms_status' => 'approved',
                'robots_status' => 'not_applicable',
                'requests_per_minute' => 30,
                'configuration' => [
                    'adapter' => 'web_search',
                    'allow_any_host' => true,
                    'result_limit' => (int) $this->option('limit'),
                    'ai_providers' => $providers,
                ],
            ]
        );

        $this->info('Open-web discovery enabled (source #' . $source->id . ').');
        $this->line('Models: ' . implode(', ', $providers));
        $this->line('Result limit per search: ' . $source->configuration['result_limit']);

        return self::SUCCESS;
    }
}
