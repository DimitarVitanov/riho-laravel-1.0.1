<?php

namespace App\Jobs\Est8ads;

use App\Models\Est8ads\ExternalListingMatch;
use App\Services\Est8ads\Discovery\ListingReviewService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoConnectExternalListing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 2;
    public array $backoff = [120];

    public function __construct(public int $matchId) { $this->onQueue('external-match'); }

    public function handle(ListingReviewService $reviews): void
    {
        $reviews->connect(ExternalListingMatch::findOrFail($this->matchId), 0, true);
    }
}
