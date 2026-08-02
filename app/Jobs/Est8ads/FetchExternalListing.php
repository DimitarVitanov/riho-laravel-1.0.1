<?php

namespace App\Jobs\Est8ads;

use App\Models\Est8ads\DiscoveryJob;
use App\Services\Est8ads\Discovery\SafeUrlPolicy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\RateLimiter;
use RuntimeException;

class FetchExternalListing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 4;
    public array $backoff = [30, 120, 600];

    public function __construct(public int $discoveryJobId, public array $record) { $this->onQueue('external-fetch'); }

    public function handle(SafeUrlPolicy $urls): void
    {
        $job = DiscoveryJob::with('internetSource')->findOrFail($this->discoveryJobId);
        $source = $job->internetSource;
        if (! $source->isApprovedForDiscovery()) throw new RuntimeException('Source approval changed before fetch.');
        $url = (string) ($this->record['canonical_url'] ?? $this->record['url'] ?? '');
        // Open-web sources are not pinned to a single domain. SSRF, scheme and
        // private-network checks still apply to every URL.
        $expectedHost = data_get($source->configuration, 'allow_any_host', false)
            ? null
            : ($source->domain ?: parse_url($source->base_url, PHP_URL_HOST));
        $urls->assertAllowed($url, $expectedHost);
        $key = 'internet-discovery-source:'.$source->id;
        if (! RateLimiter::attempt($key, max(1, $source->requests_per_minute), fn () => true, 60)) {
            $this->release(60);
            return;
        }
        // Providers must return permitted structured records. URL-only records are not scraped here.
        if (count($this->record) <= 2) throw new RuntimeException('URL-only candidate requires a configured official provider adapter.');
        NormalizeExternalListing::dispatch($job->id, $this->record)->onQueue('external-normalize');
    }
}
