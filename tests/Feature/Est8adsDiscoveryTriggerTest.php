<?php

namespace Tests\Feature;

use App\Events\Est8ads\PropertyMoveSubmitted;
use App\Models\Est8ads\Profile;
use App\Models\User;
use App\Services\Est8ads\BillingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Verifies that when an EST8ADS user adds a property they want to buy from
 * their panel, the AI internet-discovery pipeline is triggered for that
 * request (the same engine that powers the Villa Bit agency chain search).
 */
class Est8adsDiscoveryTriggerTest extends TestCase
{
    use RefreshDatabase;

    public function test_adding_a_buy_property_triggers_internet_discovery(): void
    {
        Event::fake([PropertyMoveSubmitted::class]);

        // A fully-activated (paid) individual user — an unpaid one is held on
        // the waiting-for-payment screen and blocked from creating listings.
        $user = User::factory()->create([
            'role' => 'investor',
            'account_type' => 'investor',
            'has_est8ads_access' => true,
            'email_verified_at' => now(),
        ]);
        $profile = Profile::create([
            'user_id' => $user->id,
            'type' => 'individual',
            'status' => 'active',
            'email' => $user->email,
            'public_reference' => 'EST-TEST-' . Str::random(6),
        ]);
        app(BillingService::class)->markPaid($profile->invoices()->latest('issued_on')->firstOrFail());

        $this->actingAs($user)->postJson('http://est8ads.com/listings', [
            'side' => 'buy',
            'type' => 'Apartment',
            'title' => 'Two-bed apartment near the sea',
            'country' => 'Croatia',
            'city' => 'Split',
            'price' => 300000,
            'currency' => 'EUR',
            'size' => 75,
            'beds' => 2,
            'baths' => 1,
        ])->assertCreated();

        // A wanted move + property are persisted for this profile...
        $this->assertDatabaseHas('est8ads_property_moves', [
            'profile_id' => $profile->id,
            'move_type' => 'buy',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('est8ads_properties', [
            'listing_type' => 'wanted',
            'property_type' => 'Apartment',
            'city' => 'Split',
        ]);

        // ...and the discovery pipeline is dispatched for that exact move.
        Event::assertDispatched(PropertyMoveSubmitted::class, function (PropertyMoveSubmitted $event) use ($profile) {
            return $event->propertyMove->profile_id === $profile->id
                && $event->propertyMove->move_type === 'buy';
        });
    }

    public function test_unpaid_user_cannot_create_a_listing_or_trigger_discovery(): void
    {
        Event::fake([PropertyMoveSubmitted::class]);

        $user = User::factory()->create([
            'role' => 'investor',
            'account_type' => 'investor',
            'has_est8ads_access' => true,
            'email_verified_at' => now(),
        ]);
        // Profile observer opens the first, unpaid invoice — user is awaiting
        // first payment.
        Profile::create([
            'user_id' => $user->id,
            'type' => 'individual',
            'status' => 'active',
            'email' => $user->email,
            'public_reference' => 'EST-TEST-' . Str::random(6),
        ]);

        $this->actingAs($user)->postJson('http://est8ads.com/listings', [
            'side' => 'buy',
            'type' => 'Apartment',
            'title' => 'Two-bed apartment near the sea',
            'country' => 'Croatia',
            'city' => 'Split',
            'price' => 300000,
            'currency' => 'EUR',
        ])->assertStatus(402);

        $this->assertDatabaseCount('est8ads_property_moves', 0);
        Event::assertNotDispatched(PropertyMoveSubmitted::class);
    }
}
