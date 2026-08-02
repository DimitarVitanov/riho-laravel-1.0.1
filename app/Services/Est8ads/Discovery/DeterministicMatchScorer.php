<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\ExternalListing;
use Illuminate\Support\Str;

class DeterministicMatchScorer
{
    public function score(array $profile, ExternalListing $listing): array
    {
        $parts = []; $conflicts = [];
        $parts['availability'] = $listing->status === 'active' ? 10 : 0;
        if (! $parts['availability']) $conflicts[] = 'listing_not_active';

        $offerType = Str::lower((string) data_get($listing->attributes, 'offer_type', ''));
        $compatibleOffers = ($profile['side'] ?? 'buy') === 'buy' ? ['sale', 'sell', 'for_sale', 'offer'] : ['buy', 'wanted', 'request'];
        if ($offerType !== '' && ! in_array($offerType, $compatibleOffers, true)) $conflicts[] = 'listing_direction';

        $types =  array_map(fn ($v) => Str::lower((string) $v), $profile['property_types'] ?? []);
        $parts['type'] = $types === [] || in_array(Str::lower((string) $listing->property_type), $types, true) ? 15 : 0;
        if (! $parts['type'] && $types !== []) $conflicts[] = 'property_type';

        $countries = array_map('strtoupper', $profile['countries'] ?? []);
        $cities = array_map(fn ($v) => Str::lower((string) $v), $profile['cities'] ?? []);
        $countryPass = $countries === [] || in_array(strtoupper((string) $listing->country_code), $countries, true);
        $cityPass = $cities === [] || in_array(Str::lower((string) $listing->city), $cities, true);
        $parts['location'] = $countryPass && $cityPass ? 25 : ($countryPass ? 10 : 0);
        if (! $countryPass) $conflicts[] = 'country';

        $max = (float) data_get($profile, 'price.max', 0); $flex = (float) ($profile['flexibility_percent'] ?? 0);
        $pricePass = ! $max || ! $listing->price || (float) $listing->price <= $max * (1 + $flex / 100);
        $parts['price'] = $pricePass ? 20 : 0;
        if (! $pricePass) $conflicts[] = 'budget';

        $sizePass = empty($profile['minimum_size_m2']) || ! $listing->size_m2 || (float) $listing->size_m2 >= (float) $profile['minimum_size_m2'];
        $bedsPass = empty($profile['minimum_bedrooms']) || ! $listing->bedrooms || $listing->bedrooms >= $profile['minimum_bedrooms'];
        $bathsPass = empty($profile['minimum_bathrooms']) || ! $listing->bathrooms || $listing->bathrooms >= $profile['minimum_bathrooms'];
        $parts['dimensions'] = ($sizePass ? 10 : 0) + ($bedsPass ? 5 : 0) + ($bathsPass ? 5 : 0);
        if (! $sizePass) $conflicts[] = 'minimum_size';
        if (! $bedsPass) $conflicts[] = 'minimum_bedrooms';
        if (! $bathsPass) $conflicts[] = 'minimum_bathrooms';

        $required = array_map(fn ($v) => Str::lower((string) $v), $profile['must_have_features'] ?? []);
        $actual = array_map(fn ($v) => Str::lower((string) $v), (array) data_get($listing->attributes, 'features', []));
        $missing = array_values(array_diff($required, $actual));
        $parts['features'] = $required === [] || $missing === [] ? 10 : 0;
        if ($missing !== []) $conflicts[] = 'missing_features:'.implode(',', $missing);

        $known = collect([$listing->property_type, $listing->country_code, $listing->city, $listing->price, $listing->size_m2, $listing->bedrooms, $listing->bathrooms])->filter(fn ($v) => $v !== null && $v !== '')->count();
        return ['score' => array_sum($parts), 'data_confidence' => round($known / 7 * 100, 2), 'breakdown' => $parts, 'hard_conflicts' => $conflicts];
    }
}
