<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\ExternalListingMatch;
use App\Models\Est8ads\Property;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class ListingReviewService
{
    public function __construct(private DiscoverySettings $settings, private DiscoveryAudit $audit) {}

    public function import(ExternalListingMatch $match, int $userId, bool $automatic = false): Property
    {
        return DB::transaction(function () use ($match, $userId, $automatic) {
            $match->loadMissing(['externalListing', 'propertyMove']);
            if ($match->connected_property_id) return $match->connectedProperty;
            $listing = $match->externalListing;
            $property = Property::create([
                'uuid' => (string) Str::uuid(), 'agency_id' => $match->propertyMove?->agency_id,
                'property_move_id' => $match->property_move_id, 'reference' => 'EXT-'.strtoupper(Str::random(12)),
                'status' => 'draft', 'listing_type' => 'external', 'property_type' => $listing->property_type ?: 'other',
                'title' => $listing->title ?: 'Imported external property', 'description' => $listing->description_excerpt,
                'address' => $listing->address, 'city' => $listing->city, 'region' => $listing->area,
                'country_code' => $listing->country_code, 'latitude' => $listing->latitude, 'longitude' => $listing->longitude,
                'asking_price' => $listing->price, 'currency' => $listing->currency ?: 'EUR', 'floor_area' => $listing->size_m2,
                'land_area' => $listing->land_m2, 'bedrooms' => $listing->bedrooms, 'bathrooms' => $listing->bathrooms,
                'metadata' => ['external_listing_id' => $listing->id, 'source_url' => $listing->canonical_url, 'source_attribution' => $listing->internetSource?->name],
            ]);
            $match->update(['connected_property_id' => $property->id, 'status' => 'imported', 'is_manual' => ! $automatic, 'reviewed_by_user_id' => $userId ?: null, 'reviewed_at' => now()]);
            $match->discoveryJob?->increment('imported_count');
            $this->audit->record($automatic ? 'internet_discovery.auto_imported' : 'internet_discovery.imported', $match, ['property_id' => $property->id], [], $userId ?: null);
            return $property;
        });
    }

    public function connect(ExternalListingMatch $match, int $userId, bool $automatic = false): ExternalListingMatch
    {
        if ($automatic) $this->assertAutoConnectable($match);
        if (! $match->connected_property_id) $this->import($match, $userId, $automatic);
        $match->refresh()->update(['status' => 'connected', 'is_manual' => ! $automatic, 'reviewed_by_user_id' => $userId ?: null, 'reviewed_at' => now()]);
        $match->discoveryJob?->increment('connected_count');
        $this->audit->record($automatic ? 'internet_discovery.auto_connected' : 'internet_discovery.connected', $match, ['property_id' => $match->connected_property_id], [], $userId ?: null);
        \App\Jobs\Est8ads\NotifyRequestOwner::dispatch($match->id)->onQueue('notifications');
        return $match->refresh();
    }

    public function reject(ExternalListingMatch $match, int $userId, ?string $reason = null): ExternalListingMatch
    {
        $match->update(['status' => 'rejected', 'is_manual' => true, 'reviewed_by_user_id' => $userId, 'reviewed_at' => now(), 'explanation' => $reason ?: $match->explanation]);
        $this->audit->record('internet_discovery.rejected', $match, ['reason' => $reason], [], $userId);
        return $match;
    }

    private function assertAutoConnectable(ExternalListingMatch $match): void
    {
        $match->loadMissing(['externalListing', 'propertyMove']);
        $policy = $this->settings->get($match->propertyMove?->agency_id);
        if ((array) $match->hard_conflicts !== [] || $match->externalListing->status !== 'active'
            || (float) $match->data_confidence < (float) $policy['minimum_data_confidence']
            || (float) $match->final_score < (float) $policy['auto_connect_threshold']) {
            throw new RuntimeException('Candidate does not satisfy auto-connect confidence, score, availability, and hard-conflict rules.');
        }
        if (ExternalListingMatch::where('external_listing_id', $match->external_listing_id)->where('status', 'connected')->whereKeyNot($match->id)->exists()) {
            throw new RuntimeException('External listing is already connected.');
        }
    }
}
