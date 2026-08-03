<?php

namespace App\Jobs\Est8ads;

use App\Models\Est8ads\ExternalListingMatch;
use App\Models\Est8ads\MissingLink;
use App\Models\Est8ads\PropertyMove;
use App\Services\Est8ads\Discovery\MissingLinkAnalyzer;
use App\Services\Est8ads\Discovery\SearchProfileBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Scans every submitted property-move request that already has a real
 * candidate property lined up (a near-miss internet listing) and asks the AI
 * to explain the specific condition that is the missing link preventing it
 * from becoming a completed chain, storing the result for the admin panel's
 * "Missing links" section.
 *
 * A request with nothing found at all is not a missing link — there is no
 * chain to complete yet. Only requests where a matching property already
 * exists, but a hard rule is blocking the connection, qualify.
 *
 * Requests that already have a real (exact/tolerance) match are considered
 * resolved and their missing-link record, if any, is closed.
 */
class AnalyzeMissingLinks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 600;

    public function __construct()
    {
        $this->onQueue('missing-links');
    }

    public function handle(MissingLinkAnalyzer $analyzer, SearchProfileBuilder $profiles): void
    {
        $moveIds = PropertyMove::where('status', 'submitted')->pluck('id');

        if ($moveIds->isEmpty()) {
            return;
        }

        $resolvedMoveIds = ExternalListingMatch::whereIn('property_move_id', $moveIds)
            ->whereIn('match_type', ['exact', 'tolerance'])
            ->distinct()
            ->pluck('property_move_id');

        MissingLink::whereIn('property_move_id', $resolvedMoveIds)
            ->where('status', 'open')
            ->update(['status' => 'resolved', 'resolved_at' => now()]);

        $bestConflictByMove = ExternalListingMatch::whereIn('property_move_id', $moveIds)
            ->whereNotIn('property_move_id', $resolvedMoveIds)
            ->where('match_type', 'conflict')
            ->orderByDesc('final_score')
            ->get()
            ->groupBy('property_move_id')
            ->map(fn ($group) => $group->first());

        // Requests with no candidate property at all are not a "missing
        // link" — nothing has been found to connect in the first place —
        // so close out any stale record for them too.
        MissingLink::whereIn('property_move_id', $moveIds)
            ->whereNotIn('property_move_id', $bestConflictByMove->keys())
            ->where('status', 'open')
            ->update(['status' => 'resolved', 'resolved_at' => now()]);

        $moves = PropertyMove::whereIn('id', $bestConflictByMove->keys())
            ->with('properties')
            ->get();

        foreach ($moves as $move) {
            $profile = $profiles->build($move);
            $conflict = $bestConflictByMove->get($move->id);

            $result = $analyzer->analyzeConflict($move, $profile, $conflict);

            MissingLink::updateOrCreate(
                ['property_move_id' => $move->id, 'reason_type' => 'conflict'],
                array_merge($result, [
                    'external_listing_match_id' => $conflict->id,
                    'status' => 'open',
                    'resolved_at' => null,
                ])
            );
        }
    }
}
