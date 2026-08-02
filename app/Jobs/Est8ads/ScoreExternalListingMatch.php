<?php

namespace App\Jobs\Est8ads;

use App\Models\Est8ads\DiscoveryJob;
use App\Models\Est8ads\ExternalListing;
use App\Models\Est8ads\ExternalListingMatch;
use App\Services\Est8ads\Discovery\DeterministicMatchScorer;
use App\Services\Est8ads\Discovery\SemanticMatchRanker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScoreExternalListingMatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;
    public array $backoff = [60, 300];

    public function __construct(public int $discoveryJobId, public int $externalListingId) { $this->onQueue('external-match'); }

    public function handle(DeterministicMatchScorer $scorer, SemanticMatchRanker $ranker): void
    {
        $job = DiscoveryJob::with('propertyMove.properties')->findOrFail($this->discoveryJobId);
        $listing = ExternalListing::findOrFail($this->externalListingId);
        $property = $job->propertyMove?->properties->first(fn ($p) => $p->listing_type === 'wanted') ?? $job->propertyMove?->properties->first();
        if (! $property) return;
        $profile = $job->parameters ?? [];
        $result = $scorer->score($profile, $listing);
        if ($result['score'] < (float) $job->save_threshold) return;
        $ranked = $ranker->rank($profile, $listing, $result);
        $match = ExternalListingMatch::updateOrCreate(
            ['external_listing_id' => $listing->id, 'property_id' => $property->id],
            ['property_move_id' => $job->property_move_id, 'discovery_job_id' => $job->id,
                'status' => 'candidate', 'confidence_score' => $ranked['final_score'] / 100,
                'deterministic_score' => $result['score'], 'semantic_score' => $ranked['semantic_score'],
                'final_score' => $ranked['final_score'], 'match_type' => $result['match_type'],
                'data_confidence' => $result['data_confidence'], 'reasons' => $result['breakdown'],
                'tolerance' => $result['tolerance'],
                'hard_conflicts' => $result['hard_conflicts'], 'explanation' => $ranked['explanation']]
        );
        if ($result['match_type'] === 'exact' && $result['score'] >= (float) $job->auto_connect_threshold) {
            AutoConnectExternalListing::dispatch($match->id)->onQueue('external-match');
        }
        $job->update(['status' => 'completed', 'finished_at' => now()]);
        $job->internetSource?->update(['last_success_at' => now(), 'last_crawled_at' => now()]);
    }
}
