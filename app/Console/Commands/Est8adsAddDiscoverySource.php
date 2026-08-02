<?php

namespace App\Console\Commands;

use App\Models\Est8ads\InternetSource;
use Illuminate\Console\Command;

class Est8adsAddDiscoverySource extends Command
{
    protected $signature = 'est8ads:add-discovery-source
        {url : Base URL of the portal, e.g. https://www.example-portal.com}
        {--name= : Display name, defaults to the host}
        {--adapter=ai_web_search : ai_web_search or sitemap_scraper}
        {--sitemap=* : One or more sitemap URLs (sitemap_scraper only)}
        {--ai-provider= : openai or gemini, overrides the config default}
        {--limit=25 : Maximum listings to take per search}
        {--approve-terms : Record that the portal terms of use permit this access}';

    protected $description = 'Register an internet source for EST8ADS chain discovery.';

    public function handle(): int
    {
        $url = rtrim((string) $this->argument('url'), '/');
        $host = parse_url($url, PHP_URL_HOST);
        $adapter = (string) $this->option('adapter');

        if (! $host || ! str_starts_with($url, 'https://')) {
            $this->error('The URL must be an absolute https:// address.');

            return self::FAILURE;
        }

        if (! in_array($adapter, ['ai_web_search', 'sitemap_scraper'], true)) {
            $this->error('Adapter must be ai_web_search or sitemap_scraper.');

            return self::FAILURE;
        }

        if (! $this->option('approve-terms')) {
            $this->warn('Discovery will refuse to run until the terms of use are approved.');
            $this->line('Re-run with --approve-terms once you have confirmed the portal permits automated access.');
        }

        $configuration = array_filter([
            'adapter' => $adapter,
            'result_limit' => (int) $this->option('limit'),
            'sitemaps' => array_values((array) $this->option('sitemap')),
            'ai_provider' => $this->option('ai-provider'),
        ]);

        $source = InternetSource::updateOrCreate(
            ['domain' => $host],
            [
                'name' => $this->option('name') ?: $host,
                'base_url' => $url,
                'type' => 'portal',
                // api covers our structured adapters; feed/sitemap stay reserved
                // for native data feeds.
                'access_method' => $adapter === 'sitemap_scraper' ? 'sitemap' : 'api',
                'status' => 'active',
                'enabled' => true,
                'terms_status' => $this->option('approve-terms') ? 'approved' : 'pending',
                'robots_status' => 'not_applicable',
                'requests_per_minute' => 10,
                'configuration' => $configuration,
            ]
        );

        $this->info(sprintf('Source #%d (%s) saved with adapter %s.', $source->id, $host, $adapter));
        $this->line('Terms status: ' . $source->terms_status);

        return self::SUCCESS;
    }
}
