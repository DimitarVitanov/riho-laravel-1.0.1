<?php

namespace App\Services\CompetitorIntelligence;

use App\Models\Competitor;
use App\Models\CompetitorEvent;
use App\Models\CompetitorProperty;
use App\Models\CompetitorPropertySnapshot;
use App\Models\CompetitorUrl;
use App\Models\CompetitorUrlSnapshot;

class DiffService
{
    public function detectUrlChanges(CompetitorUrl $url, CompetitorUrlSnapshot $newSnapshot): array
    {
        $previousSnapshot = $url->snapshots()
            ->where('id', '!=', $newSnapshot->id)
            ->orderByDesc('captured_at')
            ->first();

        if (!$previousSnapshot) {
            return $this->createNewUrlEvent($url, $newSnapshot);
        }

        $changes = $newSnapshot->getChangesFrom($previousSnapshot);
        $events = [];

        foreach ($changes as $changeType) {
            $events[] = $this->createUrlChangeEvent($url, $changeType, $previousSnapshot, $newSnapshot);
        }

        return $events;
    }

    public function detectPropertyChanges(CompetitorProperty $property, CompetitorPropertySnapshot $newSnapshot): array
    {
        $previousSnapshot = $property->snapshots()
            ->where('id', '!=', $newSnapshot->id)
            ->orderByDesc('captured_at')
            ->first();

        if (!$previousSnapshot) {
            return [$this->createNewPropertyEvent($property, $newSnapshot)];
        }

        $events = [];

        $priceChange = $newSnapshot->getPriceChangeFrom($previousSnapshot);
        if ($priceChange) {
            $events[] = $this->createPriceChangeEvent($property, $priceChange, $newSnapshot);
        }

        if ($this->hasDescriptionChanged($previousSnapshot, $newSnapshot)) {
            $events[] = $this->createDescriptionChangeEvent($property, $previousSnapshot, $newSnapshot);
        }

        $imageChanges = $this->detectImageChanges($previousSnapshot, $newSnapshot);
        if ($imageChanges) {
            $events[] = $this->createImageChangeEvent($property, $imageChanges, $newSnapshot);
        }

        return $events;
    }

    public function detectRemovedProperties(Competitor $competitor): array
    {
        $threshold = now()->subHours(48);

        $possiblyRemoved = $competitor->properties()
            ->where('current_status', 'active')
            ->where('last_seen_at', '<', $threshold)
            ->get();

        $events = [];

        foreach ($possiblyRemoved as $property) {
            $property->update(['current_status' => 'possibly_removed']);

            $events[] = CompetitorEvent::create([
                'competitor_id' => $competitor->id,
                'event_type' => 'possibly_removed',
                'entity_type' => 'property',
                'entity_id' => $property->id,
                'detected_at' => now(),
                'fact_json' => [
                    'property_id' => $property->id,
                    'last_seen_at' => $property->last_seen_at->toIso8601String(),
                    'hours_since_seen' => $property->last_seen_at->diffInHours(now()),
                ],
                'evidence_url' => $property->canonical_url,
                'confidence' => 70,
                'importance_score' => 60,
            ]);
        }

        return $events;
    }

    public function confirmRemovedProperties(Competitor $competitor): array
    {
        $threshold = now()->subDays(7);

        $confirmedRemoved = $competitor->properties()
            ->where('current_status', 'possibly_removed')
            ->where('last_seen_at', '<', $threshold)
            ->get();

        $events = [];

        foreach ($confirmedRemoved as $property) {
            $property->update([
                'current_status' => 'removed',
                'removed_at' => now(),
            ]);

            $events[] = CompetitorEvent::create([
                'competitor_id' => $competitor->id,
                'event_type' => 'property_removed',
                'entity_type' => 'property',
                'entity_id' => $property->id,
                'detected_at' => now(),
                'verified_at' => now(),
                'fact_json' => [
                    'property_id' => $property->id,
                    'first_detected_at' => $property->first_detected_at->toIso8601String(),
                    'last_seen_at' => $property->last_seen_at->toIso8601String(),
                    'listing_lifetime_days' => $property->listing_lifetime_days,
                ],
                'evidence_url' => $property->canonical_url,
                'confidence' => 95,
                'importance_score' => 80,
            ]);
        }

        return $events;
    }

    protected function createNewUrlEvent(CompetitorUrl $url, CompetitorUrlSnapshot $snapshot): array
    {
        $event = CompetitorEvent::create([
            'competitor_id' => $url->competitor_id,
            'competitor_source_id' => $url->competitor_source_id,
            'event_type' => 'new_url',
            'entity_type' => 'url',
            'entity_id' => $url->id,
            'detected_at' => now(),
            'verified_at' => now(),
            'new_value_json' => [
                'url' => $url->url,
                'title' => $snapshot->title,
                'page_type' => $url->page_type,
            ],
            'evidence_url' => $url->url,
            'confidence' => 100,
            'importance_score' => $this->calculateUrlImportance($url),
        ]);

        return [$event];
    }

    protected function createUrlChangeEvent(
        CompetitorUrl $url,
        string $changeType,
        CompetitorUrlSnapshot $previous,
        CompetitorUrlSnapshot $current
    ): CompetitorEvent {
        $oldValue = $this->getSnapshotFieldForChangeType($previous, $changeType);
        $newValue = $this->getSnapshotFieldForChangeType($current, $changeType);

        return CompetitorEvent::create([
            'competitor_id' => $url->competitor_id,
            'competitor_source_id' => $url->competitor_source_id,
            'event_type' => $changeType,
            'entity_type' => 'url',
            'entity_id' => $url->id,
            'detected_at' => now(),
            'verified_at' => now(),
            'old_value_json' => ['value' => $oldValue],
            'new_value_json' => ['value' => $newValue],
            'evidence_url' => $url->url,
            'confidence' => 100,
            'importance_score' => $this->calculateChangeImportance($changeType),
        ]);
    }

    protected function createNewPropertyEvent(CompetitorProperty $property, CompetitorPropertySnapshot $snapshot): CompetitorEvent
    {
        return CompetitorEvent::create([
            'competitor_id' => $property->competitor_id,
            'event_type' => 'new_property',
            'entity_type' => 'property',
            'entity_id' => $property->id,
            'detected_at' => now(),
            'verified_at' => now(),
            'new_value_json' => [
                'title' => $snapshot->title,
                'price' => $snapshot->price,
                'currency' => $snapshot->currency,
                'location' => $snapshot->location_text,
                'property_type' => $snapshot->property_type,
                'bedrooms' => $snapshot->bedrooms,
                'surface_m2' => $snapshot->surface_m2,
            ],
            'evidence_url' => $property->canonical_url,
            'confidence' => 100,
            'importance_score' => 90,
        ]);
    }

    protected function createPriceChangeEvent(
        CompetitorProperty $property,
        array $priceChange,
        CompetitorPropertySnapshot $snapshot
    ): CompetitorEvent {
        $eventType = $priceChange['direction'] === 'increase' ? 'price_increase' : 'price_decrease';

        return CompetitorEvent::create([
            'competitor_id' => $property->competitor_id,
            'event_type' => $eventType,
            'entity_type' => 'property',
            'entity_id' => $property->id,
            'detected_at' => now(),
            'verified_at' => now(),
            'old_value_json' => [
                'price' => $priceChange['old_price'],
                'currency' => $priceChange['currency'],
            ],
            'new_value_json' => [
                'price' => $priceChange['new_price'],
                'currency' => $priceChange['currency'],
                'difference' => $priceChange['difference'],
                'percent_change' => $priceChange['percent_change'],
            ],
            'fact_json' => [
                'property_title' => $snapshot->title,
                'location' => $snapshot->location_text,
            ],
            'evidence_url' => $property->canonical_url,
            'confidence' => 100,
            'importance_score' => $this->calculatePriceChangeImportance($priceChange),
        ]);
    }

    protected function createDescriptionChangeEvent(
        CompetitorProperty $property,
        CompetitorPropertySnapshot $previous,
        CompetitorPropertySnapshot $current
    ): CompetitorEvent {
        return CompetitorEvent::create([
            'competitor_id' => $property->competitor_id,
            'event_type' => 'description_changed',
            'entity_type' => 'property',
            'entity_id' => $property->id,
            'detected_at' => now(),
            'verified_at' => now(),
            'old_value_json' => ['description_length' => strlen($previous->description ?? '')],
            'new_value_json' => ['description_length' => strlen($current->description ?? '')],
            'evidence_url' => $property->canonical_url,
            'confidence' => 100,
            'importance_score' => 30,
        ]);
    }

    protected function createImageChangeEvent(
        CompetitorProperty $property,
        array $imageChanges,
        CompetitorPropertySnapshot $snapshot
    ): CompetitorEvent {
        $eventType = $imageChanges['added'] > 0 ? 'images_added' : 'images_removed';

        return CompetitorEvent::create([
            'competitor_id' => $property->competitor_id,
            'event_type' => $eventType,
            'entity_type' => 'property',
            'entity_id' => $property->id,
            'detected_at' => now(),
            'verified_at' => now(),
            'old_value_json' => ['image_count' => $imageChanges['old_count']],
            'new_value_json' => [
                'image_count' => $imageChanges['new_count'],
                'added' => $imageChanges['added'],
                'removed' => $imageChanges['removed'],
            ],
            'evidence_url' => $property->canonical_url,
            'confidence' => 100,
            'importance_score' => 40,
        ]);
    }

    protected function hasDescriptionChanged(CompetitorPropertySnapshot $previous, CompetitorPropertySnapshot $current): bool
    {
        $prevDesc = $previous->description ?? '';
        $currDesc = $current->description ?? '';

        if ($prevDesc === $currDesc) {
            return false;
        }

        $prevLen = strlen($prevDesc);
        $currLen = strlen($currDesc);

        if ($prevLen === 0 || $currLen === 0) {
            return $prevLen !== $currLen;
        }

        $changePct = abs($currLen - $prevLen) / $prevLen * 100;

        return $changePct > 10;
    }

    protected function detectImageChanges(CompetitorPropertySnapshot $previous, CompetitorPropertySnapshot $current): ?array
    {
        $prevImages = $previous->images_json ?? [];
        $currImages = $current->images_json ?? [];

        $prevCount = count($prevImages);
        $currCount = count($currImages);

        if ($prevCount === $currCount) {
            return null;
        }

        return [
            'old_count' => $prevCount,
            'new_count' => $currCount,
            'added' => max(0, $currCount - $prevCount),
            'removed' => max(0, $prevCount - $currCount),
        ];
    }

    protected function getSnapshotFieldForChangeType(CompetitorUrlSnapshot $snapshot, string $changeType): ?string
    {
        return match ($changeType) {
            'title_changed' => $snapshot->title,
            'meta_description_changed' => $snapshot->meta_description,
            'h1_changed' => $snapshot->h1,
            'cta_changed' => $snapshot->cta_text,
            default => null,
        };
    }

    protected function calculateUrlImportance(CompetitorUrl $url): int
    {
        return match ($url->page_type) {
            'property_detail' => 85,
            'property_listing' => 80,
            'location_page' => 75,
            'blog_post' => 60,
            'homepage' => 70,
            default => 50,
        };
    }

    protected function calculateChangeImportance(string $changeType): int
    {
        return match ($changeType) {
            'title_changed' => 70,
            'meta_description_changed' => 60,
            'h1_changed' => 65,
            'content_changed' => 55,
            'schema_changed' => 50,
            'cta_changed' => 75,
            default => 40,
        };
    }

    protected function calculatePriceChangeImportance(array $priceChange): int
    {
        $percentChange = abs($priceChange['percent_change']);

        if ($percentChange >= 20) return 95;
        if ($percentChange >= 10) return 85;
        if ($percentChange >= 5) return 75;

        return 60;
    }
}
