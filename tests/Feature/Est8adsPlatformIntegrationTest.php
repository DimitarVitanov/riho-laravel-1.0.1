<?php

namespace Tests\Feature;

use App\Models\AgencyListing;
use App\Models\AgencyProfile;
use App\Models\Est8ads\Agency as Est8adsAgency;
use App\Models\Est8ads\Profile as Est8adsProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Est8adsPlatformIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_villabit_agency_is_automatically_provisioned_for_est8ads_with_same_identity(): void
    {
        $user = User::factory()->create([
            'email' => 'agency@example.com',
            'role' => 'real_estate_agency',
            'account_type' => 'real_estate_agency',
            'status' => 'active',
            'has_villabit_access' => true,
            'has_est8ads_access' => false,
        ]);

        $agencyProfile = AgencyProfile::create([
            'user_id' => $user->id,
            'agency_name' => 'Shared Agency',
            'contact_email' => $user->email,
        ]);

        $this->assertTrue($user->fresh()->has_est8ads_access);
        $this->assertDatabaseHas('est8ads_agencies', [
            'agency_profile_id' => $agencyProfile->id,
            'name' => 'Shared Agency',
            'email' => 'agency@example.com',
        ]);
        $this->assertDatabaseHas('est8ads_profiles', [
            'user_id' => $user->id,
            'type' => 'agency',
            'email' => 'agency@example.com',
        ]);
        $this->assertDatabaseHas('est8ads_agency_memberships', [
            'agency_id' => Est8adsAgency::where('agency_profile_id', $agencyProfile->id)->value('id'),
            'user_id' => $user->id,
            'role' => 'owner',
        ]);
    }

    public function test_est8ads_only_agency_is_blocked_from_villabit_but_can_open_est8ads(): void
    {
        $user = User::factory()->create([
            'role' => 'real_estate_agency',
            'account_type' => 'real_estate_agency',
            'status' => 'active',
            'email_verified_at' => now(),
            'has_villabit_access' => false,
            'has_est8ads_access' => true,
        ]);

        AgencyProfile::withoutEvents(fn () => AgencyProfile::create([
            'user_id' => $user->id,
            'agency_name' => 'EST8ADS Only Agency',
        ]));
        Est8adsProfile::create([
            'user_id' => $user->id,
            'type' => 'agency',
            'status' => 'active',
            'email' => $user->email,
            'public_reference' => 'EST-ONLY-AGENCY',
        ]);

        $this->actingAs($user)
            ->get(route('agency.dashboard'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get('http://est8ads.com/dashboard')
            ->assertOk();
    }

    public function test_est8ads_only_registration_does_not_grant_villabit_access(): void
    {
        $this->post('http://est8ads.com/register', [
            'account_type' => 'individual',
            'first_name' => 'Est',
            'last_name' => 'User',
            'email' => 'est8ads-only@example.com',
            'country' => 'Croatia',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
            'terms' => '1',
        ])->assertRedirect(route('est8ads.dashboard'));

        $user = User::where('email', 'est8ads-only@example.com')->firstOrFail();
        $this->assertTrue($user->has_est8ads_access);
        $this->assertFalse($user->has_villabit_access);
    }

    public function test_listing_created_in_est8ads_is_created_in_villabit_agency_listings(): void
    {
        $user = User::factory()->create([
            'role' => 'real_estate_agency',
            'account_type' => 'real_estate_agency',
            'status' => 'active',
            'email_verified_at' => now(),
            'has_villabit_access' => true,
            'has_est8ads_access' => true,
        ]);
        $profile = AgencyProfile::create(['user_id' => $user->id, 'agency_name' => 'Shared Agency']);

        $this->actingAs($user)->postJson('http://est8ads.com/listings', [
            'side' => 'sell',
            'type' => 'villa',
            'title' => 'EST8ADS Villa',
            'country' => 'Croatia',
            'city' => 'Split',
            'area' => 'Meje',
            'price' => 750000,
            'currency' => 'EUR',
        ])->assertCreated();

        $this->assertDatabaseHas('agency_listings', [
            'agency_profile_id' => $profile->id,
            'title' => 'EST8ADS Villa',
            'property_type' => 'villa',
        ]);
    }

    public function test_admin_can_create_an_est8ads_only_agency(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active', 'email_verified_at' => now()]);

        $this->actingAs($admin)->post(route('admin.villabit.users.store-agency'), [
            'first_name' => 'Only',
            'last_name' => 'Agency',
            'email' => 'admin-created-est8ads@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
            'company_name' => 'EST8ADS Only Ltd',
            'country' => 'Croatia',
            'account_access' => 'est8ads_only',
        ])->assertRedirect(route('admin.villabit.users.index'));

        $user = User::where('email', 'admin-created-est8ads@example.com')->firstOrFail();
        $this->assertFalse($user->has_villabit_access);
        $this->assertTrue($user->has_est8ads_access);
        $this->assertDatabaseHas('est8ads_agencies', ['agency_profile_id' => $user->agencyProfile->id]);
    }

    public function test_admin_can_change_platform_access_without_creating_another_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active', 'email_verified_at' => now()]);
        $user = User::factory()->create([
            'role' => 'investor',
            'has_villabit_access' => true,
            'has_est8ads_access' => false,
        ]);

        $this->actingAs($admin)->post(route('admin.villabit.users.platform-access', $user), [
            'has_villabit_access' => true,
            'has_est8ads_access' => true,
        ])->assertRedirect();

        $this->assertTrue($user->fresh()->has_villabit_access);
        $this->assertTrue($user->fresh()->has_est8ads_access);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('est8ads_profiles', ['user_id' => $user->id]);
    }

    public function test_villabit_listing_is_the_same_record_used_by_est8ads(): void
    {
        $user = User::factory()->create([
            'role' => 'real_estate_agency',
            'account_type' => 'real_estate_agency',
            'status' => 'active',
        ]);
        $profile = AgencyProfile::create(['user_id' => $user->id, 'agency_name' => 'Shared Listings']);

        $listing = AgencyListing::create([
            'agency_profile_id' => $profile->id,
            'title' => 'Villa with sea view',
            'property_type' => 'villa',
            'status' => 'active',
            'looking_to_buy' => true,
            'looking_property_type' => 'apartment',
            'looking_locations' => ['Split', 'Solin'],
            'looking_budget_max' => 500000,
        ]);

        $this->assertSame($listing->id, $profile->agencyListings()->firstOrFail()->id);
        $this->assertSame(['Split', 'Solin'], $profile->agencyListings()->firstOrFail()->looking_locations);
    }
}
