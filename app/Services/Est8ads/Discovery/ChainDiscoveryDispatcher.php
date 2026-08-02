<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\AgencyListing;
use App\Models\Est8ads\Profile;
use App\Models\Est8ads\Property;
use App\Models\Est8ads\PropertyMove;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Turns "Property Chain: Seller is also looking to buy" into a live internet
 * search.
 *
 * The wanted criteria are mirrored into the EST8ADS graph (a property move plus
 * a `wanted` property) so the existing discovery, matching and chain-analysis
 * machinery can work with them unchanged. The mirror is keyed on the listing id
 * so repeated saves update rather than duplicate it.
 */
class ChainDiscoveryDispatcher
{
    public function __construct(
        private DiscoveryManager $discovery,
        private DiscoverySettings $settings,
    ) {
    }

    public function handle(AgencyListing $listing, ?int $userId = null): ?PropertyMove
    {
        if (! $listing->looking_to_buy) {
            $this->retire($listing);

            return null;
        }

        try {
            $move = $this->mirror($listing);
        } catch (Throwable $exception) {
            Log::error('Chain discovery mirror failed', ['listing' => $listing->id, 'error' => $exception->getMessage()]);

            return null;
        }

        if (! ($this->settings->get($move->agency_id)['enabled'] ?? false)) {
            return $move;
        }

        try {
            $this->discovery->dispatch($move, 'chain_listing', $userId);
        } catch (Throwable $exception) {
            // No approved provider yet, discovery disabled, etc. The mirror is
            // still stored so the search can be retried from the admin panel.
            Log::warning('Chain discovery not dispatched', ['listing' => $listing->id, 'reason' => $exception->getMessage()]);
        }

        return $move;
    }

    /**
     * The seller no longer wants to buy: deactivate the mirrored move and its
     * wanted property so no further discovery can be triggered from it. The
     * records are kept (not deleted) because past matches reference them.
     */
    private function retire(AgencyListing $listing): void
    {
        $move = PropertyMove::where('metadata->agency_listing_id', $listing->id)->first();

        if (! $move) {
            return;
        }

        $move->update(['status' => 'cancelled']);
        Property::where('property_move_id', $move->id)
            ->where('listing_type', 'wanted')
            ->update(['status' => 'inactive']);
    }

    /**
     * Create or refresh the property move + wanted property that represent the
     * seller's buying intent.
     */
    private function mirror(AgencyListing $listing): PropertyMove
    {
        $listing->loadMissing('agencyProfile.user');
        $locations = $this->locations($listing);
        $currency = $listing->looking_currency ?: ($listing->currency ?: 'EUR');

        $move = PropertyMove::withTrashed()
            ->where('metadata->agency_listing_id', $listing->id)
            ->first();

        $attributes = [
            'profile_id' => $this->profile($listing)->id,
            'move_type' => 'sell_and_buy',
            'status' => 'active',
            'title' => 'Chain: ' . $listing->title,
            'current_location' => $this->location($listing->primary_city ?: $listing->location, $listing->country),
            'target_location' => $locations[0] ?? null,
            'budget_min' => $listing->looking_budget_min,
            'budget_max' => $listing->looking_budget_max,
            'currency' => $currency,
            'notes' => $listing->looking_notes,
            'requirements' => [
                'property_types' => array_values(array_filter([$listing->looking_property_type])),
                'cities' => $locations,
                'countries' => array_values(array_filter([$listing->country])),
                'target_size_m2' => $listing->looking_min_size !== null ? (float) $listing->looking_min_size : null,
                'minimum_size_m2' => $listing->looking_min_size !== null ? (float) $listing->looking_min_size : null,
                'minimum_bedrooms' => $listing->looking_min_bedrooms,
                'timeline' => $listing->looking_timeline,
                'must_sell_first' => true,
                'flexibility_percent' => self::defaultTolerance(),
                'size_tolerance_percent' => self::defaultTolerance(),
                'price_tolerance_percent' => self::defaultTolerance(),
            ],
            'metadata' => [
                'source' => 'agency_listing_chain',
                'agency_listing_id' => $listing->id,
                'agency_profile_id' => $listing->agency_profile_id,
            ],
            'submitted_at' => now(),
        ];

        if ($move) {
            $move->restore();
            $move->update($attributes);
        } else {
            $move = PropertyMove::create($attributes + ['uuid' => (string) Str::uuid()]);
        }

        $this->wantedProperty($move, $listing, $locations, $currency);
        $this->sellProperty($move, $listing);

        return $move;
    }

    private function wantedProperty(PropertyMove $move, AgencyListing $listing, array $locations, string $currency): void
    {
        Property::updateOrCreate(
            ['property_move_id' => $move->id, 'listing_type' => 'wanted'],
            [
                'uuid' => (string) Str::uuid(),
                'reference' => 'CHAIN-BUY-' . $listing->id,
                'status' => 'active',
                'property_type' => $listing->looking_property_type,
                'title' => trim('Wanted ' . ($listing->looking_property_type ?: 'property') . ' in ' . ($locations[0] ?? '')),
                'description' => $listing->looking_notes,
                'city' => $locations[0] ?? null,
                'asking_price' => $listing->looking_budget_max,
                'currency' => $currency,
                'floor_area' => $listing->looking_min_size,
                'bedrooms' => $listing->looking_min_bedrooms,
                'metadata' => [
                    'country' => $listing->country,
                    'source' => 'agency_listing_chain',
                    'agency_listing_id' => $listing->id,
                    'all_locations' => $locations,
                    'timeline' => $listing->looking_timeline,
                ],
            ]
        );
    }

    /**
     * Mirror the property being sold so the chain has both ends.
     */
    private function sellProperty(PropertyMove $move, AgencyListing $listing): void
    {
        Property::updateOrCreate(
            ['property_move_id' => $move->id, 'listing_type' => 'sell'],
            [
                'uuid' => (string) Str::uuid(),
                'reference' => 'CHAIN-SELL-' . $listing->id,
                'status' => $listing->status === 'active' ? 'active' : 'inactive',
                'property_type' => $listing->property_type,
                'title' => $listing->title,
                'description' => $listing->description,
                'city' => $listing->primary_city ?: $listing->location,
                'asking_price' => $listing->price,
                'currency' => $listing->currency ?: 'EUR',
                'floor_area' => $listing->living_area ?: $listing->size,
                'land_area' => $listing->plot_size,
                'bedrooms' => $listing->bedrooms,
                'bathrooms' => $listing->bathrooms,
                'metadata' => [
                    'country' => $listing->country,
                    'source' => 'agency_listing_chain',
                    'agency_listing_id' => $listing->id,
                    'listing_url' => $listing->external_url,
                ],
            ]
        );
    }

    private function profile(AgencyListing $listing): Profile
    {
        $user = $listing->agencyProfile?->user;

        if ($user && $profile = Profile::where('user_id', $user->id)->first()) {
            return $profile;
        }

        return Profile::create([
            'user_id' => $user?->id,
            'type' => 'agency_contact',
            'status' => 'active',
            'first_name' => $user?->first_name,
            'last_name' => $user?->last_name,
            'company_name' => $listing->agencyProfile?->agency_name,
            'email' => $listing->agencyProfile?->contact_email ?: $user?->email,
            'phone' => $user?->phone,
            'public_reference' => 'EST-' . strtoupper(Str::random(12)),
            'metadata' => ['source' => 'agency_listing_chain', 'agency_profile_id' => $listing->agency_profile_id],
        ]);
    }

    /** @return array<int, string> */
    private function locations(AgencyListing $listing): array
    {
        return collect($listing->looking_locations ?: [])
            ->push($listing->looking_location)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function location(?string $city, ?string $country): ?string
    {
        $location = implode(', ', array_filter([$city, $country]));

        return $location !== '' ? $location : null;
    }

    public static function defaultTolerance(): float
    {
        return (float) config('est8ads.discovery.tolerance_percent', 10);
    }
}
