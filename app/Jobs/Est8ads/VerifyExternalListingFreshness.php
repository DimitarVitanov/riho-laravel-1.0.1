<?php

namespace App\Jobs\Est8ads;

use App\Models\Est8ads\ExternalListing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerifyExternalListingFreshness implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;
    public array $backoff = [300, 1800];

    public function __construct(public int $listingId) { $this->onQueue('stale-listing-check'); }

    public function handle(): void
    {
        $listing = ExternalListing::findOrFail($this->listingId);
        $freshnessDays = app(\App\Services\Est8ads\Discovery\DiscoverySettings::class)
            ->get($listing->discoveryJob?->agency_id)['freshness_days'];
        if (! $listing->last_seen_at || $listing->last_seen_at->lt(now()->subDays($freshnessDays))) {
            $listing->update(['status' => 'stale']);
        }
        // Network verification is intentionally delegated to an approved provider adapter.
    }
}
