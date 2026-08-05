<?php

namespace Tests\Feature;

use App\Events\Est8ads\PropertyMoveSubmitted;
use App\Events\Est8ads\PropertyRequestUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * Proves all four EST8ADS intake types work end-to-end:
 *   1. Private person, one property
 *   2. Private person, two properties at the same time (sell + buy)
 *   3. Agency, one property
 *   4. Agency, two properties at the same time
 */
class Est8adsUserTypesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Focus on what the intake persists, not the web discovery it queues.
        Event::fake([PropertyMoveSubmitted::class, PropertyRequestUpdated::class]);
    }

    public function test_type_1_private_person_single_property(): void
    {
        $this->submit([
            'user_type' => 'Private buyer or seller',
            'move_type' => 'Only sell a property',
            'email' => 'type1@example.com',
        ] + $this->sellFields())->assertRedirect();

        $this->assertDatabaseHas('est8ads_profiles', ['email' => 'type1@example.com', 'type' => 'individual']);
        $this->assertSame(1, $this->propertiesFor('type1@example.com', 'sell'));
        $this->assertSame(0, $this->propertiesFor('type1@example.com', 'wanted'));
    }

    public function test_type_2_private_person_two_properties_at_the_same_time(): void
    {
        $this->submit([
            'user_type' => 'Buyer and seller',
            'move_type' => 'Both transactions at the same time',
            'email' => 'type2@example.com',
        ] + $this->sellFields() + $this->buyFields())->assertRedirect();

        $this->assertDatabaseHas('est8ads_profiles', ['email' => 'type2@example.com', 'type' => 'individual']);
        $this->assertDatabaseHas('est8ads_property_moves', ['move_type' => 'Both transactions at the same time', 'status' => 'submitted']);
        $this->assertSame(1, $this->propertiesFor('type2@example.com', 'sell'));
        $this->assertSame(1, $this->propertiesFor('type2@example.com', 'wanted'));
    }

    public function test_type_3_agency_single_property(): void
    {
        $this->submit([
            'user_type' => 'Real estate agency or agent',
            'move_type' => 'Only sell a property',
            'email' => 'type3@example.com',
        ] + $this->sellFields())->assertRedirect();

        $this->assertDatabaseHas('est8ads_profiles', ['email' => 'type3@example.com', 'type' => 'agency_contact']);
        $this->assertSame(1, $this->propertiesFor('type3@example.com', 'sell'));
        $this->assertSame(0, $this->propertiesFor('type3@example.com', 'wanted'));
    }

    public function test_type_4_agency_two_properties_at_the_same_time(): void
    {
        $this->submit([
            'user_type' => 'Real estate agency or agent',
            'move_type' => 'Both transactions at the same time',
            'email' => 'type4@example.com',
        ] + $this->sellFields() + $this->buyFields())->assertRedirect();

        $this->assertDatabaseHas('est8ads_profiles', ['email' => 'type4@example.com', 'type' => 'agency_contact']);
        $this->assertSame(1, $this->propertiesFor('type4@example.com', 'sell'));
        $this->assertSame(1, $this->propertiesFor('type4@example.com', 'wanted'));
    }

    public function test_buy_intake_succeeds_without_a_minimum_budget(): void
    {
        $fields = $this->buyFields();
        unset($fields['buy_budget_min']); // Buyer leaves the minimum blank.

        $this->submit([
            'user_type' => 'Private buyer or seller',
            'move_type' => 'Only buy a property',
            'email' => 'nomin@example.com',
        ] + $fields)->assertRedirect()->assertSessionHas('est8ads_success');

        $this->assertDatabaseHas('est8ads_profiles', ['email' => 'nomin@example.com', 'type' => 'individual']);
        $this->assertSame(1, $this->propertiesFor('nomin@example.com', 'wanted'));
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function submit(array $overrides)
    {
        return $this->post('http://est8ads.com/property-moves', $overrides + [
            'current_stage' => 'Planning only',
            'first_name' => 'Ana',
            'last_name' => 'Kovac',
            'phone' => '+385 91 555 1122',
            'language' => 'English',
            'terms' => 'on',
            'service_terms' => 'on',
        ]);
    }

    /** @return array<string, mixed> */
    private function sellFields(): array
    {
        return [
            'sell_title' => 'Two-bed apartment in Split',
            'sell_type' => 'Apartment',
            'sell_country' => 'Croatia',
            'sell_city' => 'Split',
            'sell_price' => 250000,
            'sell_currency' => 'EUR',
            'sell_description' => 'A bright two-bedroom apartment close to the old town.',
        ];
    }

    /** @return array<string, mixed> */
    private function buyFields(): array
    {
        return [
            'buy_type' => 'Villa',
            'buy_country' => 'Croatia',
            'buy_city' => 'Split',
            'buy_budget_min' => 300000,
            'buy_budget_max' => 500000,
            'buy_currency' => 'EUR',
            'buy_description' => 'Looking for a sea-view villa to move into next.',
        ];
    }

    private function propertiesFor(string $email, string $listingType): int
    {
        return (int) \DB::table('est8ads_properties')
            ->join('est8ads_property_moves', 'est8ads_properties.property_move_id', '=', 'est8ads_property_moves.id')
            ->join('est8ads_profiles', 'est8ads_property_moves.profile_id', '=', 'est8ads_profiles.id')
            ->where('est8ads_profiles.email', $email)
            ->where('est8ads_properties.listing_type', $listingType)
            ->count();
    }
}
