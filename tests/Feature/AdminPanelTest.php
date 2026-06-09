<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AgencyProfile;
use App\Models\InvestorProfile;
use App\Models\ManagerProfile;
use App\Models\InvestmentProject;
use App\Models\CapitalCall;
use App\Models\InvestorPayout;
use App\Models\AiDailyReport;
use App\Models\UsageLimit;
use App\Models\AffiliateReferral;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.villabit.dashboard'));
        $response->assertOk();
        $response->assertViewIs('admin.villabit.dashboard');
    }

    public function test_admin_can_list_managers(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        ManagerProfile::create(['user_id' => $manager->id, 'department' => 'Sales']);

        $response = $this->actingAs($this->admin)->get(route('admin.villabit.managers.index'));
        $response->assertOk();
        $response->assertViewIs('admin.villabit.managers.index');
    }

    public function test_admin_can_show_manager(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        ManagerProfile::create(['user_id' => $manager->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.villabit.managers.show', $manager));
        $response->assertOk();
    }

    public function test_admin_can_list_agencies(): void
    {
        $agency = User::factory()->create(['role' => 'real_estate_agency']);
        AgencyProfile::create(['user_id' => $agency->id, 'agency_name' => 'Test Agency']);

        $response = $this->actingAs($this->admin)->get(route('admin.villabit.agencies.index'));
        $response->assertOk();
        $response->assertViewIs('admin.villabit.agencies.index');
    }

    public function test_admin_can_show_agency(): void
    {
        $agency = User::factory()->create(['role' => 'real_estate_agency']);
        AgencyProfile::create(['user_id' => $agency->id, 'agency_name' => 'Test Agency']);

        $response = $this->actingAs($this->admin)->get(route('admin.villabit.agencies.show', $agency));
        $response->assertOk();
    }

    public function test_admin_can_list_investors(): void
    {
        $investor = User::factory()->create(['role' => 'investor']);
        InvestorProfile::create(['user_id' => $investor->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.villabit.investors.index'));
        $response->assertOk();
    }

    public function test_admin_can_show_investor(): void
    {
        $investor = User::factory()->create(['role' => 'investor']);
        InvestorProfile::create(['user_id' => $investor->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.villabit.investors.show', $investor));
        $response->assertOk();
    }

    public function test_admin_can_list_investment_projects(): void
    {
        InvestmentProject::create([
            'project_name' => 'Test Project',
            'project_code' => 'TP-001',
            'project_status' => 'draft',
            'target_raise_amount' => 1000000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.villabit.investment-projects.index'));
        $response->assertOk();
    }

    public function test_admin_can_create_investment_project(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.villabit.investment-projects.create'));
        $response->assertOk();
    }

    public function test_admin_can_store_investment_project(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.villabit.investment-projects.store'), [
            'project_name' => 'New Project',
            'project_code' => 'NP-001',
            'project_status' => 'open',
            'minimum_investment_amount' => 10000,
            'target_raise_amount' => 500000,
            'max_raise_amount' => 750000,
            'preferred_return_percent' => 8.00,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('investment_projects', ['project_code' => 'NP-001']);
    }

    public function test_admin_can_list_capital_calls(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.villabit.capital-calls.index'));
        $response->assertOk();
    }

    public function test_admin_can_list_investor_payouts(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.villabit.investor-payouts.index'));
        $response->assertOk();
    }

    public function test_admin_can_list_ai_reports(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.villabit.ai-reports.index'));
        $response->assertOk();
    }

    public function test_admin_can_list_usage_limits(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.villabit.usage-limits.index'));
        $response->assertOk();
    }

    public function test_admin_can_view_affiliate_tracking(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.villabit.affiliate-tracking'));
        $response->assertOk();
    }

    public function test_admin_can_view_affiliate_commissions(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.villabit.affiliate-commissions'));
        $response->assertOk();
    }

    public function test_admin_can_view_affiliate_payouts(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.villabit.affiliate-payouts'));
        $response->assertOk();
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $agency = User::factory()->create(['role' => 'real_estate_agency', 'email_verified_at' => now()]);
        $response = $this->actingAs($agency)->get(route('admin.villabit.dashboard'));
        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get(route('admin.villabit.dashboard'));
        $response->assertRedirect(route('login'));
    }
}
