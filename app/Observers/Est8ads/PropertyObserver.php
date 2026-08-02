<?php

namespace App\Observers\Est8ads;

use App\Events\Est8ads\PropertyRequestUpdated;
use App\Models\Est8ads\Property;

class PropertyObserver
{
    private const MATERIAL = ['listing_type', 'property_type', 'city', 'region', 'country_code', 'latitude', 'longitude', 'asking_price', 'currency', 'floor_area', 'land_area', 'bedrooms', 'bathrooms', 'metadata'];

    public function updated(Property $property): void
    {
        if ($property->property_move_id && $property->wasChanged(self::MATERIAL)) {
            PropertyRequestUpdated::dispatch($property->propertyMove);
        }
    }
}
