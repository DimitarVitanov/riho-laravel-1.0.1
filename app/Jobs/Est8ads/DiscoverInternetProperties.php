<?php

namespace App\Jobs\Est8ads;

use App\Models\Est8ads\DiscoveryJob;
use App\Services\Est8ads\Discovery\DiscoveryAudit;
use App\Services\Est8ads\Discovery\ProviderRegistry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class DiscoverInternetProperties implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 4;
    public int $timeout = 120;
    public array $backoff = [60, 300, 1800];

    public function __construct(public int $discoveryJobId) { $this->onQueue('internet-discovery'); }

    public function handle(ProviderRegistry $providers, DiscoveryAudit $audit, \App\Services\Est8ads\Discovery\SafeUrlPolicy $urls): void
    {
        $job = DiscoveryJob::with('internetSource')->findOrFail($this->discoveryJobId);
        $source = $job->internetSource;
        $expectedHost = data_get($source->configuration, 'allow_any_host', false)
            ? null
            : ($source->domain ?: parse_url($source->base_url, PHP_URL_HOST));
        $urls->assertAllowed($source->base_url, $expectedHost);
        $job->update(['status' => 'running', 'started_at' => $job->started_at ?: now(), 'error_message' => null]);
        $count = 0;
        foreach ($providers->for($job->internetSource)->search($job->internetSource, $job->parameters ?? []) as $record) {
            FetchExternalListing::dispatch($job->id, (array) $record)->onQueue('external-fetch');
            $count++;
        }
        $job->update(['status' => 'processing', 'listings_found' => $count]);
        $audit->record('internet_discovery.search_completed', $job, ['candidates' => $count]);
    }

    public function failed(Throwable $exception): void
    {
        if ($job = DiscoveryJob::find($this->discoveryJobId)) {
            $job->update(['status' => 'failed', 'errors_count' => $job->errors_count + 1, 'error_message' => mb_substr($exception->getMessage(), 0, 2000), 'finished_at' => now()]);
            $job->internetSource?->update(['last_error_at' => now()]);
            app(DiscoveryAudit::class)->record('internet_discovery.search_failed', $job, ['error' => $job->error_message]);
        }
    }
}
