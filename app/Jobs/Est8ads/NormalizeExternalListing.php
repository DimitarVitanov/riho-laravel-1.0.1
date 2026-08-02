<?php

namespace App\Jobs\Est8ads;

use App\Models\Est8ads\DiscoveryJob;
use App\Models\Est8ads\ExternalListing;
use App\Models\Est8ads\ExternalListingSnapshot;
use App\Services\Est8ads\Discovery\ListingDeduplicator;
use App\Services\Est8ads\Discovery\ListingNormalizer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NormalizeExternalListing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;
    public array $backoff = [30, 180];

    public function __construct(public int $discoveryJobId, public array $record) { $this->onQueue('external-normalize'); }

    public function handle(ListingNormalizer $normalizer, ListingDeduplicator $deduplicator): void
    {
        $job = DiscoveryJob::with('internetSource')->findOrFail($this->discoveryJobId);
        $data = $normalizer->normalize($job->internetSource, $this->record);
        $listing = $deduplicator->find($job->internetSource, $data) ?? new ExternalListing();
        $sourceId = $listing->internet_source_id ?: $job->internet_source_id;
        $externalId = $listing->external_id ?: $data['external_id'];
        $listing->fill($data + ['internet_source_id' => $sourceId, 'discovery_job_id' => $job->id]);
        $listing->internet_source_id = $sourceId;
        $listing->external_id = $externalId;
        $listing->discovery_job_id = $job->id;
        if ($listing->trashed()) $listing->restore();
        $listing->save();
        ExternalListingSnapshot::firstOrCreate(
            ['external_listing_id' => $listing->id, 'content_hash' => $listing->content_hash],
            ['payload' => $data, 'price' => $listing->price, 'availability_status' => $listing->status, 'captured_at' => now()]
        );
        ScoreExternalListingMatch::dispatch($job->id, $listing->id)->onQueue('external-match');
    }
}
