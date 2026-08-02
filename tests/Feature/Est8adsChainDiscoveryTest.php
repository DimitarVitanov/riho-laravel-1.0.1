<?php

namespace Tests\Feature;

use App\Models\AgencyListing;
use App\Models\AgencyProfile;
use App\Models\Est8ads\ExternalListing;
use App\Models\Est8ads\InternetSource;
use App\Models\Est8ads\Property;
use App\Models\User;
use App\Services\Est8ads\Discovery\ChainDiscoveryDispatcher;
use App\Services\Est8ads\Discovery\DeterministicMatchScorer;
use App\Services\Est8ads\Discovery\SearchProfileBuilder;
use App\Services\Est8ads\Discovery\ToleranceBand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Est8adsChainDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_tolerance_band_accepts_near_size_and_rejects_beyond_window(): void
    {
        $exact = ToleranceBand::evaluate(200.0, 200.0, 10.0, 10.0);
        $near = ToleranceBand::evaluate(200.0, 190.0, 10.0, 10.0);
        $bigger = ToleranceBand::evaluate(200.0, 220.0, 10.0, 10.0);
        $outside = ToleranceBand::evaluate(200.0, 175.0, 10.0, 10.0);

        $this->assertSame('exact', $exact['status']);
        $this->assertSame('tolerance', $near['status']);
        $this->assertSame('exact', $bigger['status'], 'A larger property still satisfies a minimum size.');
        $this->assertSame('outside', $outside['status']);

        // A near miss must never outscore an exact hit.
        $this->assertLessThan($exact['points'], $near['points']);
    }

    public function test_price_over_budget_is_tolerated_only_inside_the_window(): void
    {
        $within = ToleranceBand::evaluate(500000.0, 520000.0, 10.0, 20.0, upperBound: true);
        $beyond = ToleranceBand::evaluate(500000.0, 560000.0, 10.0, 20.0, upperBound: true);

        $this->assertSame('tolerance', $within['status']);
        $this->assertSame('outside', $beyond['status']);
        $this->assertSame(0.0, $beyond['points']);
    }

    public function test_scorer_flags_tolerance_matches_separately_from_exact_matches(): void
    {
        $source = InternetSource::create([
            'name' => 'Test portal', 'base_url' => 'https://example.com', 'domain' => 'example.com',
            'type' => 'portal', 'access_method' => 'api', 'status' => 'active', 'enabled' => true,
            'terms_status' => 'approved', 'robots_status' => 'allowed',
        ]);

        $profile = [
            'property_types' => ['apartment'], 'countries' => ['HR'], 'cities' => ['split'],
            'price' => ['max' => 500000, 'currency' => 'EUR'],
            'target_size_m2' => 200, 'minimum_size_m2' => 200,
            'size_tolerance_percent' => 10, 'price_tolerance_percent' => 10,
        ];

        $scorer = app(DeterministicMatchScorer::class);

        $exact = $scorer->score($profile, $this->listing($source, ['size_m2' => 200, 'price' => 490000]));
        $tolerated = $scorer->score($profile, $this->listing($source, ['size_m2' => 190, 'price' => 490000, 'external_id' => 'b']));
        $rejected = $scorer->score($profile, $this->listing($source, ['size_m2' => 150, 'price' => 490000, 'external_id' => 'c']));

        $this->assertSame('exact', $exact['match_type']);
        $this->assertSame('tolerance', $tolerated['match_type']);
        $this->assertSame('conflict', $rejected['match_type']);
        $this->assertGreaterThan($tolerated['score'], $exact['score']);
        $this->assertSame(-5.0, (float) $tolerated['tolerance']['size_deviation']);
    }

    public function test_saving_a_chain_listing_mirrors_the_wanted_profile_with_tolerance(): void
    {
        $listing = $this->chainListing();

        $move = app(ChainDiscoveryDispatcher::class)->handle($listing);

        $this->assertNotNull($move);
        $this->assertSame(500000.0, (float) $move->budget_max);
        $this->assertSame(['Split'], $move->requirements['cities']);
        $this->assertSame(10.0, (float) $move->requirements['size_tolerance_percent']);

        $wanted = Property::where('property_move_id', $move->id)->where('listing_type', 'wanted')->first();
        $this->assertNotNull($wanted);
        $this->assertSame(350.0, (float) $wanted->floor_area);

        // The search profile handed to providers must carry the tolerance.
        $profile = app(SearchProfileBuilder::class)->build($move->fresh());
        $this->assertSame(350.0, (float) $profile['target_size_m2']);
        $this->assertSame(10.0, (float) $profile['size_tolerance_percent']);
        $this->assertSame(10.0, (float) $profile['price_tolerance_percent']);
    }

    public function test_repeated_saves_do_not_duplicate_the_mirrored_move(): void
    {
        $listing = $this->chainListing();
        $dispatcher = app(ChainDiscoveryDispatcher::class);

        $first = $dispatcher->handle($listing);
        $listing->update(['looking_min_size' => 400]);
        $second = $dispatcher->handle($listing->fresh());

        $this->assertSame($first->id, $second->id);
        $this->assertSame(1, Property::where('property_move_id', $first->id)->where('listing_type', 'wanted')->count());
        $this->assertSame(400.0, (float) $second->requirements['target_size_m2']);
    }

    public function test_listing_without_chain_criteria_is_ignored(): void
    {
        $listing = $this->chainListing(['looking_to_buy' => false]);

        $this->assertNull(app(ChainDiscoveryDispatcher::class)->handle($listing));
    }

    public function test_clearing_the_chain_flag_retires_the_mirrored_move(): void
    {
        $listing = $this->chainListing();
        $dispatcher = app(ChainDiscoveryDispatcher::class);

        $move = $dispatcher->handle($listing);
        $listing->update(['looking_to_buy' => false]);
        $dispatcher->handle($listing->fresh());

        $this->assertSame('cancelled', $move->fresh()->status);
        $this->assertSame('inactive', Property::where('property_move_id', $move->id)->where('listing_type', 'wanted')->first()->status);
    }

    private function chainListing(array $overrides = []): AgencyListing
    {
        $user = User::factory()->create();
        $profile = AgencyProfile::create([
            'user_id' => $user->id,
            'agency_name' => 'Villa Ready',
            'contact_email' => 'chain@example.com',
        ]);

        return $profile->agencyListings()->create(array_merge([
            'title' => 'Sea view villa',
            'property_type' => 'Villa',
            'location' => 'Split',
            'primary_city' => 'Split',
            'country' => 'HR',
            'price' => 750000,
            'currency' => 'EUR',
            'living_area' => 280,
            'bedrooms' => 5,
            'status' => 'active',
            'looking_to_buy' => true,
            'looking_property_type' => 'Apartment',
            'looking_location' => 'Split',
            'looking_locations' => ['Split'],
            'looking_budget_max' => 500000,
            'looking_currency' => 'EUR',
            'looking_min_bedrooms' => 4,
            'looking_min_size' => 350,
        ], $overrides));
    }

    private function listing(InternetSource $source, array $overrides = []): ExternalListing
    {
        return ExternalListing::create(array_merge([
            'internet_source_id' => $source->id,
            'external_id' => 'a',
            'canonical_url' => 'https://example.com/listing/' . ($overrides['external_id'] ?? 'a'),
            'status' => 'active',
            'title' => 'Apartment in Split',
            'property_type' => 'apartment',
            'city' => 'Split',
            'country_code' => 'HR',
            'currency' => 'EUR',
            'bedrooms' => 4,
            'bathrooms' => 2,
            'first_seen_at' => now(),
            'last_seen_at' => now(),
        ], $overrides));
    }
}
