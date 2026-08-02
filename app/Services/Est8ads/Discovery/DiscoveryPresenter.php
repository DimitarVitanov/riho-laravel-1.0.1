<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\DiscoveryJob;
use App\Models\Est8ads\ExternalListing;
use App\Models\Est8ads\ExternalListingMatch;
use App\Models\Est8ads\InternetSource;

/**
 * Single source of truth for the shapes the EST8ADS admin panel JavaScript
 * expects (public/est8ads/panel/panel.js), used both by the initial page
 * payload and by the JSON endpoints.
 */
class DiscoveryPresenter
{
    public function job(DiscoveryJob $job): array
    {
        $job->loadMissing(['propertyMove.profile', 'internetSource']);

        return [
            'id' => $job->id,
            'request_id' => 'MV-' . $job->property_move_id,
            'request' => 'MV-' . $job->property_move_id,
            'user' => $job->propertyMove?->profile?->company_name ?: $job->propertyMove?->profile?->email,
            'target' => $job->propertyMove?->title,
            'sources_count' => $job->provider_count ?: ($job->internet_source_id ? 1 : 0),
            'found_count' => $job->found_count ?: $job->listings_found,
            'connected_count' => $job->connected_count,
            'status' => ucfirst($job->status),
            'last_run' => $job->finished_at?->toDateTimeString() ?: $job->created_at?->toDateTimeString(),
        ];
    }

    public function match(ExternalListingMatch $match): array
    {
        $match->loadMissing('externalListing.internetSource');
        $listing = $match->externalListing;

        return [
            'id' => $match->id,
            'request_id' => 'MV-' . $match->property_move_id,
            'title' => $listing?->title,
            'source' => $listing?->internetSource?->name,
            'domain' => $listing?->internetSource?->domain,
            'source_url' => $listing?->canonical_url,
            'city' => $listing?->city,
            'price' => (float) ($listing?->price ?? 0),
            'currency' => $listing?->currency ?: 'EUR',
            'score' => (float) ($match->final_score ?? 0),
            'confidence' => (float) ($match->data_confidence ?? 0),
            'status' => ucfirst($match->status),
            'imported' => $match->connected_property_id !== null,
        ];
    }

    public function stats(): array
    {
        return [
            'queue' => DiscoveryJob::whereIn('status', ['queued', 'running', 'processing'])->count(),
            'sources' => InternetSource::count(),
            'active_sources' => InternetSource::where('enabled', true)->count(),
            'found_today' => ExternalListing::whereDate('created_at', today())->count(),
            'connected' => ExternalListingMatch::where('status', 'connected')->count(),
        ];
    }

    /**
     * Initial state for the admin panel discovery section.
     */
    public function panelState(DiscoverySettings $settings, int $jobLimit = 50, int $matchLimit = 100): array
    {
        $policy = $settings->get();

        $jobs = DiscoveryJob::with(['propertyMove.profile', 'internetSource'])
            ->latest()
            ->limit($jobLimit)
            ->get()
            ->map(fn (DiscoveryJob $job) => $this->job($job))
            ->values();

        $matches = ExternalListingMatch::with('externalListing.internetSource')
            ->orderByDesc('final_score')
            ->limit($matchLimit)
            ->get()
            ->map(fn (ExternalListingMatch $match) => $this->match($match))
            ->values();

        return [
            'settings' => $policy,
            'automation_running' => (bool) ($policy['automation_running'] ?? false),
            'stats' => $this->stats(),
            'jobs' => $jobs,
            'matches' => $matches,
        ];
    }
}
