<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\ExternalListing;
use App\Support\CountryCode;
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

        $countries = array_values(array_filter($profile['countries'] ?? []));
        $cities = array_map(fn ($v) => Str::lower((string) $v), $profile['cities'] ?? []);
        // "Croatia" on the wanted profile and "HR" on a scraped listing are the
        // same country, so both sides are reduced to an ISO code before comparing.
        $countryPass = $countries === [] || collect($countries)->contains(fn ($country) => CountryCode::matches($country, $listing->country_code));
        $cityPass = $cities === [] || in_array(Str::lower((string) $listing->city), $cities, true);
        $parts['location'] = $countryPass && $cityPass ? 25 : ($countryPass ? 10 : 0);
        if (! $countryPass) $conflicts[] = 'country';

        $priceTolerance = (float) ($profile['price_tolerance_percent'] ?? $profile['flexibility_percent'] ?? 10);
        $price = ToleranceBand::evaluate(
            (float) data_get($profile, 'price.max', 0) ?: null,
            $listing->price !== null ? (float) $listing->price : null,
            $priceTolerance,
            20,
            upperBound: true,
        );
        $parts['price'] = $price['points'];
        if ($price['status'] === 'outside') $conflicts[] = 'budget';

        $sizeTolerance = (float) ($profile['size_tolerance_percent'] ?? $profile['flexibility_percent'] ?? 10);
        $size = ToleranceBand::evaluate(
            (float) ($profile['target_size_m2'] ?? $profile['minimum_size_m2'] ?? 0) ?: null,
            $listing->size_m2 !== null ? (float) $listing->size_m2 : null,
            $sizeTolerance,
            10,
        );
        $bedsPass = empty($profile['minimum_bedrooms']) || ! $listing->bedrooms || $listing->bedrooms >= $profile['minimum_bedrooms'];
        $bathsPass = empty($profile['minimum_bathrooms']) || ! $listing->bathrooms || $listing->bathrooms >= $profile['minimum_bathrooms'];
        $parts['dimensions'] = $size['points'] + ($bedsPass ? 5 : 0) + ($bathsPass ? 5 : 0);
        if ($size['status'] === 'outside') $conflicts[] = 'minimum_size';
        if (! $bedsPass) $conflicts[] = 'minimum_bedrooms';
        if (! $bathsPass) $conflicts[] = 'minimum_bathrooms';

        $required = array_map(fn ($v) => Str::lower((string) $v), $profile['must_have_features'] ?? []);
        $actual = array_map(fn ($v) => Str::lower((string) $v), (array) data_get($listing->attributes, 'features', []));
        $missing = array_values(array_diff($required, $actual));
        $parts['features'] = $required === [] || $missing === [] ? 10 : 0;
        if ($missing !== []) $conflicts[] = 'missing_features:'.implode(',', $missing);

        $known = collect([$listing->property_type, $listing->country_code, $listing->city, $listing->price, $listing->size_m2, $listing->bedrooms, $listing->bathrooms])->filter(fn ($v) => $v !== null && $v !== '')->count();

        // Exact only when nothing was relaxed: no conflicts and neither the
        // price nor the size needed the tolerance window.
        $usedTolerance = in_array('tolerance', [$price['status'], $size['status']], true);
        $matchType = $conflicts !== [] ? 'conflict' : ($usedTolerance ? 'tolerance' : 'exact');

        return [
            'score' => round(array_sum($parts), 2),
            'data_confidence' => round($known / 7 * 100, 2),
            'breakdown' => $parts,
            'hard_conflicts' => $conflicts,
            'match_type' => $matchType,
            'tolerance' => [
                'price_percent' => $priceTolerance,
                'size_percent' => $sizeTolerance,
                'price_status' => $price['status'],
                'size_status' => $size['status'],
                'price_deviation' => $price['deviation'],
                'size_deviation' => $size['deviation'],
            ],
        ];
    }
}
