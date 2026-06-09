<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AgencyProfile;
use App\Models\AiFeatureSetting;
use App\Models\UsageLimit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgencyPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $agencyUser;
    protected AgencyProfile $profile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);
        $this->agencyUser = User::factory()->create(['role' => 'real_estate_agency', 'status' => 'active', 'email_verified_at' => now()]);
        $this->profile = AgencyProfile::create([
            'user_id' => $this->agencyUser->id,
            'agency_name' => 'Test Agency',
            'subscription_status' => 'active',
        ]);
    }

    public function test_agency_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->agencyUser)->get(route('agency.dashboard'));
        $response->assertOk();
    }

    public function test_agency_can_view_feature(): void
    {
        AiFeatureSetting::create([
            'agency_profile_id' => $this->profile->id,
            'feature_key' => 'local_seo_presence_boost',
            'is_enabled' => true,
            'frequency' => 'daily',
        ]);

        $response = $this->actingAs($this->agencyUser)->get(route('agency.features.show', 'local_seo_presence_boost'));
        $response->assertOk();
    }

    public function test_agency_can_view_ai_reports(): void
    {
        $response = $this->actingAs($this->agencyUser)->get(route('agency.ai-reports.index'));
        $response->assertOk();
    }

    public function test_agency_can_view_usage_limits(): void
    {
        UsageLimit::create([
            'agency_profile_id' => $this->profile->id,
            'period_start' => now()->startOfMonth(),
            'period_end' => now()->endOfMonth(),
            'local_seo_pages_limit' => 50,
            'local_seo_pages_used' => 10,
            'competitor_scans_limit' => 20,
            'competitor_scans_used' => 5,
            'ai_search_freshness_updates_limit' => 30,
            'ai_search_freshness_updates_used' => 8,
            'authority_review_updates_limit' => 10,
            'authority_review_updates_used' => 2,
            'small_ai_content_actions_limit' => 100,
            'small_ai_content_actions_used' => 25,
        ]);

        $response = $this->actingAs($this->agencyUser)->get(route('agency.usage-limits.index'));
        $response->assertOk();
    }

    public function test_agency_can_view_billing(): void
    {
        $response = $this->actingAs($this->agencyUser)->get(route('agency.billing.index'));
        $response->assertOk();
    }

    public function test_agency_affiliate_disabled_without_reseller_flag(): void
    {
        $response = $this->actingAs($this->agencyUser)->get(route('agency.affiliate.index'));
        $response->assertOk();
        $response->assertViewIs('agency.affiliate.disabled');
    }

    public function test_agency_affiliate_enabled_with_reseller_flag(): void
    {
        $this->agencyUser->update(['is_reseller_enabled' => true]);

        $response = $this->actingAs($this->agencyUser)->get(route('agency.affiliate.index'));
        $response->assertOk();
        $response->assertViewIs('agency.affiliate.index');
    }

    public function test_agency_can_view_language_settings(): void
    {
        $response = $this->actingAs($this->agencyUser)->get(route('agency.settings.language'));
        $response->assertOk();
    }

    public function test_agency_can_view_feature_settings(): void
    {
        $response = $this->actingAs($this->agencyUser)->get(route('agency.settings.features'));
        $response->assertOk();
    }

    public function test_investor_cannot_access_agency_panel(): void
    {
        $investor = User::factory()->create(['role' => 'investor', 'email_verified_at' => now()]);
        $response = $this->actingAs($investor)->get(route('agency.dashboard'));
        $response->assertStatus(403);
    }
}
