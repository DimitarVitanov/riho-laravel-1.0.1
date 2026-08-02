<?php

namespace Tests\Feature;

use App\Models\AgencyListing;
use App\Models\AgencyProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgencyChainListingValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_maximum_budget_alone_is_accepted(): void
    {
        [$user] = $this->agency();

        $response = $this->actingAs($user)->post(route('agency.local-seo.listings.store'), $this->payload());

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('agency_listings', [
            'title' => 'Sea view villa',
            'looking_to_buy' => true,
            'looking_budget_max' => 500000,
        ]);
    }

    public function test_maximum_budget_below_a_submitted_minimum_is_rejected(): void
    {
        [$user] = $this->agency();

        $response = $this->actingAs($user)->post(route('agency.local-seo.listings.store'), $this->payload([
            'looking_budget_min' => 600000,
            'looking_budget_max' => 500000,
        ]));

        $response->assertSessionHasErrors('looking_budget_max');
        $this->assertDatabaseCount('agency_listings', 0);
    }

    public function test_chain_criteria_are_persisted(): void
    {
        [$user] = $this->agency();

        $this->actingAs($user)->post(route('agency.local-seo.listings.store'), $this->payload([
            'looking_locations' => ['Split', 'Solin'],
            'looking_min_size' => 350,
            'looking_min_bedrooms' => 4,
        ]))->assertSessionHasNoErrors();

        $listing = AgencyListing::firstOrFail();

        $this->assertSame(['Split', 'Solin'], $listing->looking_locations);
        $this->assertSame('Split', $listing->looking_location);
        $this->assertSame(350.0, (float) $listing->looking_min_size);
        $this->assertSame(4, $listing->looking_min_bedrooms);
    }

    public function test_unchecking_the_chain_box_clears_the_criteria(): void
    {
        [$user] = $this->agency();

        $this->actingAs($user)->post(route('agency.local-seo.listings.store'), $this->payload())->assertSessionHasNoErrors();
        $listing = AgencyListing::firstOrFail();

        $this->actingAs($user)
            ->put(route('agency.local-seo.listings.update', $listing), [
                'title' => 'Sea view villa',
                'property_type' => 'Villa',
                'location' => 'Split',
                'looking_budget_max' => 500000,
            ])
            ->assertSessionHasNoErrors();

        $listing->refresh();
        $this->assertFalse((bool) $listing->looking_to_buy);
        $this->assertNull($listing->looking_budget_max);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Sea view villa',
            'property_type' => 'Villa',
            'location' => 'Split',
            'country' => 'HR',
            'price' => 750000,
            'currency' => 'EUR',
            'looking_to_buy' => '1',
            'looking_property_type' => 'Apartment',
            'looking_locations' => ['Split'],
            'looking_budget_max' => 500000,
            'looking_currency' => 'EUR',
        ], $overrides);
    }

    /** @return array{0: User, 1: AgencyProfile} */
    private function agency(): array
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);

        $user = User::factory()->create([
            'role' => 'real_estate_agency',
            'status' => 'active',
            'email_verified_at' => now(),
            'has_villabit_access' => true,
        ]);
        $profile = AgencyProfile::create([
            'user_id' => $user->id,
            'agency_name' => 'Villa Ready',
            'contact_email' => 'chain@example.com',
            'subscription_status' => 'active',
        ]);

        return [$user, $profile];
    }
}
