<?php

namespace Tests\Feature;

use App\Models\Est8ads\Profile;
use App\Models\User;
use App\Services\Est8ads\BillingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class Est8adsSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_est8ads_domain_serves_the_exact_public_pages(): void
    {
        $this->get('http://est8ads.com/')->assertOk()->assertSee('Property Chain Intelligence');
        $this->get('http://est8ads.com/contact')->assertOk()->assertSee('Send a message');
        $this->get('http://est8ads.com/privacy')->assertOk()->assertSee('Privacy Policy');
        $this->get('http://est8ads.com/terms')->assertOk()->assertSee('Terms of Use');
        $this->get('/est8ads')->assertOk()->assertSee('Property Chain Intelligence');
    }

    public function test_public_contact_form_persists_an_inquiry(): void
    {
        $response = $this->post('http://est8ads.com/contact', [
            'full_name' => 'Ana Kovac',
            'email' => 'ana@example.com',
            'phone' => '+385 91 555 1122',
            'country' => 'Croatia',
            'role' => 'Both buying and selling',
            'subject' => 'Start a property chain',
            'message' => 'I want to coordinate my property sale and next purchase.',
        ]);

        $response->assertRedirect()->assertSessionHas('est8ads_contact_success');
        $this->assertDatabaseHas('est8ads_contact_inquiries', [
            'email' => 'ana@example.com',
            'subject' => 'Start a property chain',
            'status' => 'new',
        ]);
    }

    public function test_buy_only_intake_creates_a_profile_move_and_wanted_property(): void
    {
        $response = $this->post('http://est8ads.com/property-moves', [
            'user_type' => 'Private buyer or seller',
            'move_type' => 'Only buy a property',
            'current_stage' => 'Planning only',
            'buy_type' => 'Villa',
            'buy_country' => 'Croatia',
            'buy_city' => 'Split',
            'buy_budget_min' => 400000,
            'buy_budget_max' => 800000,
            'buy_currency' => 'EUR',
            'buy_description' => 'A sea-view villa near Split.',
            'first_name' => 'Ana',
            'last_name' => 'Kovac',
            'email' => 'ana@example.com',
            'phone' => '+385 91 555 1122',
            'language' => 'English',
            'terms' => 'on',
            'service_terms' => 'on',
        ]);

        $response->assertRedirect()->assertSessionHas('est8ads_success');
        $this->assertDatabaseHas('est8ads_profiles', ['email' => 'ana@example.com', 'status' => 'pending']);
        $this->assertDatabaseHas('est8ads_property_moves', ['move_type' => 'Only buy a property', 'status' => 'submitted']);
        $this->assertDatabaseHas('est8ads_properties', ['listing_type' => 'wanted', 'property_type' => 'Villa']);
    }

    public function test_login_rejects_a_workspace_that_does_not_match_the_account(): void
    {
        User::factory()->create([
            'email' => 'user@est8ads.com',
            'password' => Hash::make('secret-password'),
            'role' => 'investor',
            'account_type' => 'investor',
            'has_est8ads_access' => true,
            'email_verified_at' => now(),
        ]);

        $this->from('http://est8ads.com/login')->post('http://est8ads.com/login', [
            'email' => 'user@est8ads.com',
            'password' => 'secret-password',
            'role' => 'admin',
        ])->assertRedirect('http://est8ads.com/login')->assertSessionHasErrors('role');

        $this->assertGuest();
    }

    public function test_private_user_can_sign_in_to_the_est8ads_dashboard(): void
    {
        User::factory()->create([
            'email' => 'user@est8ads.com',
            'password' => Hash::make('secret-password'),
            'role' => 'investor',
            'account_type' => 'investor',
            'has_est8ads_access' => true,
            'email_verified_at' => now(),
        ]);

        $this->post('http://est8ads.com/login', [
            'email' => 'user@est8ads.com',
            'password' => 'secret-password',
            'role' => 'user',
        ])->assertRedirect(route('est8ads.dashboard'));

        $this->get('http://est8ads.com/dashboard')->assertOk()->assertSee('Your property move');
    }

    public function test_unpaid_individual_user_is_held_on_the_waiting_for_payment_screen(): void
    {
        $user = User::factory()->create([
            'role' => 'investor',
            'account_type' => 'investor',
            'has_est8ads_access' => true,
            'email_verified_at' => now(),
        ]);

        // The Profile observer opens the first (unpaid) invoice automatically.
        Profile::create([
            'user_id' => $user->id,
            'type' => 'individual',
            'status' => 'active',
            'email' => $user->email,
            'public_reference' => 'EST-TEST-' . Str::random(6),
        ]);

        $this->actingAs($user)
            ->get('http://est8ads.com/dashboard')
            ->assertOk()
            ->assertSee('WAITING FOR PAYMENT')
            ->assertDontSee('Your property move');
    }

    public function test_individual_user_reaches_the_dashboard_once_the_first_invoice_is_paid(): void
    {
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

        // An admin confirms the PayPal payment for the first invoice.
        app(BillingService::class)->markPaid($profile->invoices()->latest('issued_on')->firstOrFail());

        $this->actingAs($user)
            ->get('http://est8ads.com/dashboard')
            ->assertOk()
            ->assertSee('Your property move')
            ->assertDontSee('WAITING FOR PAYMENT');
    }

    public function test_admin_users_payload_carries_billing_status_for_the_mark_as_paid_switch(): void
    {
        $individual = User::factory()->create([
            'role' => 'investor',
            'account_type' => 'investor',
            'has_est8ads_access' => true,
            'email_verified_at' => now(),
        ]);

        // The Profile observer opens the first (unpaid) invoice automatically.
        $profile = Profile::create([
            'user_id' => $individual->id,
            'type' => 'individual',
            'status' => 'active',
            'email' => $individual->email,
            'public_reference' => 'EST-TEST-' . Str::random(6),
        ]);
        $invoice = $profile->invoices()->latest('issued_on')->firstOrFail();

        $before = collect(app(\App\Services\Est8ads\PanelData::class)->forAdmin()['users'])
            ->firstWhere('id', 'U-' . $individual->id);

        $this->assertNotNull($before);
        $this->assertTrue($before['billable']);
        $this->assertFalse($before['paid']);
        $this->assertSame($invoice->id, $before['invoice_id']);

        // Admin flips the switch → invoice is paid, nothing left to charge.
        app(BillingService::class)->markPaid($invoice);

        $after = collect(app(\App\Services\Est8ads\PanelData::class)->forAdmin()['users'])
            ->firstWhere('id', 'U-' . $individual->id);

        $this->assertTrue($after['paid']);
        $this->assertNull($after['invoice_id']);
    }
}
