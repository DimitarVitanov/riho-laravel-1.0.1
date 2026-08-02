<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\PropertyMove;

class SearchProfileBuilder
{
    public function build(PropertyMove $move): array
    {
        $move->loadMissing('properties');
        $property = $move->properties->first(fn ($item) => $item->listing_type === 'wanted') ?? $move->properties->first();
        $requirements = $move->requirements ?? [];

        return array_filter([
            'request_id' => $move->id,
            'side' => $this->side((string) $move->move_type, $property?->listing_type),
            'property_types' => array_values(array_filter((array) ($requirements['property_types'] ?? $property?->property_type))),
            'countries' => array_values(array_filter((array) ($requirements['countries'] ?? $property?->country_code))),
            'cities' => array_values(array_filter((array) ($requirements['cities'] ?? $move->target_location ?? $property?->city))),
            'areas' => array_values(array_filter((array) ($requirements['areas'] ?? $property?->region))),
            'price' => ['min' => $move->budget_min, 'max' => $move->budget_max ?: $property?->asking_price, 'currency' => $move->currency],
            'target_size_m2' => $requirements['target_size_m2'] ?? $requirements['minimum_size_m2'] ?? $property?->floor_area,
            'minimum_size_m2' => $requirements['minimum_size_m2'] ?? $property?->floor_area,
            'minimum_bedrooms' => $requirements['minimum_bedrooms'] ?? $property?->bedrooms,
            'minimum_bathrooms' => $requirements['minimum_bathrooms'] ?? $property?->bathrooms,
            'condition' => $requirements['condition'] ?? null,
            'must_have_features' => array_values((array) ($requirements['must_have_features'] ?? [])),
            'flexibility_percent' => $this->tolerance($requirements['flexibility_percent'] ?? null),
            'size_tolerance_percent' => $this->tolerance($requirements['size_tolerance_percent'] ?? $requirements['flexibility_percent'] ?? null),
            'price_tolerance_percent' => $this->tolerance($requirements['price_tolerance_percent'] ?? $requirements['flexibility_percent'] ?? null),
        ], fn ($value) => $value !== null && $value !== [] && $value !== '');
    }

    private function side(string $moveType, ?string $listingType): string
    {
        if ($listingType === 'wanted' || str_contains(strtolower($moveType), 'buy')) return 'buy';
        return 'sell';
    }

    /**
     * Clamp a tolerance to a sane window, defaulting to the configured ±10%.
     */
    private function tolerance(mixed $value): float
    {
        $default = (float) config('est8ads.discovery.tolerance_percent', 10);

        return max(0.0, min(50.0, (float) ($value ?? $default)));
    }
}
