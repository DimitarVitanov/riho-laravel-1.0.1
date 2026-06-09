<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AffiliateReferral;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Services\AffiliateCommissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliateSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);
    }

    public function test_affiliate_click_middleware_sets_cookie(): void
    {
        $reseller = User::factory()->create([
            'role' => 'real_estate_agency',
            'is_reseller_enabled' => true,
            'referral_code' => 'TEST123',
        ]);

        $response = $this->get('/?ref=TEST123');
        $response->assertCookie('referral_code', 'TEST123');
    }

    public function test_affiliate_click_creates_referral_record(): void
    {
        $reseller = User::factory()->create([
            'role' => 'real_estate_agency',
            'is_reseller_enabled' => true,
            'referral_code' => 'TRACK456',
        ]);

        $this->get('/?ref=TRACK456');
        $this->assertDatabaseHas('affiliate_referrals', [
            'referral_code' => 'TRACK456',
            'reseller_user_id' => $reseller->id,
        ]);
    }

    public function test_commission_service_creates_commission(): void
    {
        $reseller = User::factory()->create([
            'role' => 'real_estate_agency',
            'is_reseller_enabled' => true,
            'referral_code' => 'COMM001',
        ]);
        $referred = User::factory()->create(['role' => 'real_estate_agency']);

        AffiliateReferral::create([
            'reseller_user_id' => $reseller->id,
            'referral_code' => 'COMM001',
            'converted_user_id' => $referred->id,
            'status' => 'signed_up',
            'converted_at' => now(),
        ]);

        $service = new AffiliateCommissionService();
        $commission = $service->createCommissionFromPayment($referred->id, 100.00, 'subscription', 1);

        $this->assertNotNull($commission);
        $this->assertEquals(10.00, (float) $commission->commission_amount);
        $this->assertEquals('pending', $commission->commission_status);
    }

    public function test_commission_not_created_for_non_referred_user(): void
    {
        $user = User::factory()->create(['role' => 'real_estate_agency']);

        $service = new AffiliateCommissionService();
        $commission = $service->createCommissionFromPayment($user->id, 100.00, 'subscription', 999);

        $this->assertNull($commission);
    }

    public function test_admin_can_approve_affiliate_payout(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $reseller = User::factory()->create(['role' => 'real_estate_agency', 'is_reseller_enabled' => true]);
        $payout = AffiliatePayout::create([
            'reseller_user_id' => $reseller->id,
            'payout_batch_month' => now()->format('Y-m'),
            'amount' => 50.00,
            'currency' => 'EUR',
            'payout_method' => 'bank_wire',
            'payout_status' => 'requested',
            'minimum_payout_reached' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.villabit.affiliate-payouts.approve', $payout));
        $response->assertRedirect();
        $this->assertDatabaseHas('affiliate_payouts', [
            'id' => $payout->id,
            'payout_status' => 'approved',
        ]);
    }

    public function test_admin_can_mark_affiliate_payout_paid(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $reseller = User::factory()->create(['role' => 'real_estate_agency', 'is_reseller_enabled' => true]);
        $payout = AffiliatePayout::create([
            'reseller_user_id' => $reseller->id,
            'payout_batch_month' => now()->format('Y-m'),
            'amount' => 50.00,
            'currency' => 'EUR',
            'payout_method' => 'bank_wire',
            'payout_status' => 'approved',
            'minimum_payout_reached' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.villabit.affiliate-payouts.mark-paid', $payout), [
            'transaction_reference' => 'TXN-12345',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('affiliate_payouts', [
            'id' => $payout->id,
            'payout_status' => 'paid',
        ]);
    }
}
