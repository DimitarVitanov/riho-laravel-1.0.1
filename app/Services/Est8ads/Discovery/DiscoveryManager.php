<?php

namespace App\Services\Est8ads\Discovery;

use App\Jobs\Est8ads\DiscoverInternetProperties;
use App\Models\Est8ads\DiscoveryJob;
use App\Models\Est8ads\InternetSource;
use App\Models\Est8ads\PropertyMove;
use Illuminate\Support\Str;
use RuntimeException;

class DiscoveryManager
{
    public function __construct(private SearchProfileBuilder $profiles, private DiscoverySettings $settings, private DiscoveryAudit $audit) {}

    /** @return array<int, DiscoveryJob> */
    public function dispatch(PropertyMove $move, string $trigger = 'manual', ?int $userId = null, ?array $sourceIds = null): array
    {
        $policy = $this->settings->get($move->agency_id);
        if (! $policy['enabled']) throw new RuntimeException('Internet discovery is disabled.');
        $sources = $this->selectSources($move, $sourceIds);
        // Nothing configured yet: turn on open-web discovery automatically so
        // every new listing is analyzed across the whole internet (AI query
        // generation + multi-engine web search + scraper) with no manual setup,
        // the same way Villa Bit AI analyzes a listing on creation. Only for
        // untargeted dispatches; can be disabled via config (off in tests).
        if ($sources->isEmpty() && $sourceIds === null && config('est8ads.discovery.auto_enable_open_web', true)) {
            $this->ensureOpenWebSource();
            $sources = $this->selectSources($move, null);
        }
        if ($sources->isEmpty()) throw new RuntimeException('No approved and enabled discovery providers are configured.');
        $profile = $this->profiles->build($move); $jobs = [];
        foreach ($sources as $source) {
            $job = DiscoveryJob::create([
                'uuid' => (string) Str::uuid(), 'internet_source_id' => $source->id, 'agency_id' => $move->agency_id,
                'property_move_id' => $move->id, 'requested_by_user_id' => $userId, 'trigger' => $trigger,
                'status' => 'queued', 'parameters' => $profile, 'save_threshold' => $policy['save_threshold'],
                'auto_connect_threshold' => $policy['auto_connect_threshold'],
            ]);
            $this->audit->record('internet_discovery.search_queued', $job, ['trigger' => $trigger, 'source_id' => $source->id], [], $userId);
            DiscoverInternetProperties::dispatch($job->id)->onQueue('internet-discovery');
            $jobs[] = $job;
        }
        return $jobs;
    }

    /** @return \Illuminate\Support\Collection<int, InternetSource> */
    private function selectSources(PropertyMove $move, ?array $sourceIds)
    {
        $query = InternetSource::query()->where('enabled', true)->where('status', 'active')
            ->whereIn('access_method', ['api', 'feed', 'sitemap'])->where('terms_status', 'approved')
            ->whereIn('robots_status', ['allowed', 'not_applicable'])
            ->where(fn ($q) => $q->whereNull('agency_id')->orWhere('agency_id', $move->agency_id));
        if ($sourceIds !== null) $query->whereIn('id', $sourceIds);
        return $query->get()->filter(fn (InternetSource $source) => $source->isApprovedForDiscovery())->values();
    }

    /**
     * Ensures the keyless "open web" discovery source exists so discovery runs
     * out of the box across the whole internet. Mirrors the source created by
     * the est8ads:enable-web-discovery command.
     */
    private function ensureOpenWebSource(): void
    {
        InternetSource::updateOrCreate(
            ['domain' => 'open-web'],
            [
                'name' => 'Open web (AI + scraper)',
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
                    'result_limit' => 25,
                    'ai_providers' => (array) config('est8ads.discovery.web_search_providers', ['openai', 'gemini']),
                ],
            ]
        );
    }
}
