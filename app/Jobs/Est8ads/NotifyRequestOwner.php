<?php

namespace App\Jobs\Est8ads;

use App\Models\Est8ads\ExternalListingMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyRequestOwner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;
    public array $backoff = [60, 300];

    public function __construct(public int $matchId) { $this->onQueue('notifications'); }

    public function handle(): void
    {
        $match = ExternalListingMatch::with(['externalListing', 'propertyMove'])->findOrFail($this->matchId);
        $freshnessDays = app(\App\Services\Est8ads\Discovery\DiscoverySettings::class)->get($match->propertyMove?->agency_id)['freshness_days'];
        if ($match->status !== 'connected' || $match->externalListing->status !== 'active'
            || ! $match->externalListing->last_seen_at || $match->externalListing->last_seen_at->lt(now()->subDays($freshnessDays))) return;
        // Delivery remains disabled until an owner notification channel is explicitly configured.
    }
}
