<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliatePayoutsCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);
    }

    public function test_generates_payout_for_reseller_above_minimum(): void
    {
        $reseller = User::factory()->create([
            'role' => 'real_estate_agency',
            'is_reseller_enabled' => true,
        ]);

        $referred = User::factory()->create(['role' => 'real_estate_agency']);

        AffiliateCommission::create([
            'reseller_user_id' => $reseller->id,
            'referred_user_id' => $referred->id,
            'source_payment_type' => 'subscription',
            'gross_payment_amount' => 200,
            'commission_percent' => 10,
            'commission_amount' => 20,
            'currency' => 'EUR',
            'commission_status' => 'approved',
            'earned_at' => now(),
        ]);

        $this->artisan('affiliate:payouts-generate')
            ->assertExitCode(0);

        $this->assertDatabaseHas('affiliate_payouts', [
            'reseller_user_id' => $reseller->id,
            'amount' => 20,
            'payout_status' => 'requested',
        ]);

        $this->assertDatabaseHas('affiliate_commissions', [
            'reseller_user_id' => $reseller->id,
            'commission_status' => 'payable',
        ]);
    }

    public function test_does_not_generate_payout_below_minimum(): void
    {
        $reseller = User::factory()->create([
            'role' => 'real_estate_agency',
            'is_reseller_enabled' => true,
        ]);

        $referred = User::factory()->create(['role' => 'real_estate_agency']);

        AffiliateCommission::create([
            'reseller_user_id' => $reseller->id,
            'referred_user_id' => $referred->id,
            'source_payment_type' => 'subscription',
            'gross_payment_amount' => 50,
            'commission_percent' => 10,
            'commission_amount' => 5, // Below $10 minimum
            'currency' => 'EUR',
            'commission_status' => 'approved',
            'earned_at' => now(),
        ]);

        $this->artisan('affiliate:payouts-generate')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('affiliate_payouts', [
            'reseller_user_id' => $reseller->id,
        ]);
    }

    public function test_does_not_generate_duplicate_payout_same_month(): void
    {
        $reseller = User::factory()->create([
            'role' => 'real_estate_agency',
            'is_reseller_enabled' => true,
        ]);

        $referred = User::factory()->create(['role' => 'real_estate_agency']);
        $referred2 = User::factory()->create(['role' => 'real_estate_agency']);

        AffiliateCommission::create([
            'reseller_user_id' => $reseller->id,
            'referred_user_id' => $referred->id,
            'source_payment_type' => 'subscription',
            'gross_payment_amount' => 200,
            'commission_percent' => 10,
            'commission_amount' => 20,
            'currency' => 'EUR',
            'commission_status' => 'approved',
            'earned_at' => now(),
        ]);

        // First run
        $this->artisan('affiliate:payouts-generate')->assertExitCode(0);

        // Add more approved commissions
        AffiliateCommission::create([
            'reseller_user_id' => $reseller->id,
            'referred_user_id' => $referred2->id,
            'source_payment_type' => 'subscription',
            'gross_payment_amount' => 100,
            'commission_percent' => 10,
            'commission_amount' => 10,
            'currency' => 'EUR',
            'commission_status' => 'approved',
            'earned_at' => now(),
        ]);

        // Second run same month — no duplicate
        $this->artisan('affiliate:payouts-generate')->assertExitCode(0);

        $this->assertEquals(1, AffiliatePayout::where('reseller_user_id', $reseller->id)->count());
    }
}
