<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\InvestorProfile;
use App\Models\InvestmentProject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestorPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $investorUser;
    protected InvestorProfile $profile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);
        $this->investorUser = User::factory()->create(['role' => 'investor', 'status' => 'active', 'email_verified_at' => now()]);
        $this->profile = InvestorProfile::create([
            'user_id' => $this->investorUser->id,
            'investor_type' => 'us_accredited',
            'kyc_status' => 'approved',
        ]);
    }

    public function test_investor_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->investorUser)->get(route('investor.dashboard'));
        $response->assertOk();
    }

    public function test_investor_can_list_projects(): void
    {
        InvestmentProject::create([
            'project_name' => 'Villa X',
            'project_code' => 'VX-001',
            'project_status' => 'open',
            'target_raise_amount' => 500000,
        ]);

        $response = $this->actingAs($this->investorUser)->get(route('investor.projects.index'));
        $response->assertOk();
    }

    public function test_investor_can_show_project(): void
    {
        $project = InvestmentProject::create([
            'project_name' => 'Villa Y',
            'project_code' => 'VY-001',
            'project_status' => 'open',
            'target_raise_amount' => 750000,
        ]);

        $response = $this->actingAs($this->investorUser)->get(route('investor.projects.show', $project));
        $response->assertOk();
    }

    public function test_investor_can_list_investments(): void
    {
        $response = $this->actingAs($this->investorUser)->get(route('investor.investments.index'));
        $response->assertOk();
    }

    public function test_investor_can_list_capital_calls(): void
    {
        $response = $this->actingAs($this->investorUser)->get(route('investor.capital-calls.index'));
        $response->assertOk();
    }

    public function test_investor_can_view_earnings(): void
    {
        $response = $this->actingAs($this->investorUser)->get(route('investor.earnings.index'));
        $response->assertOk();
    }

    public function test_investor_can_list_payouts(): void
    {
        $response = $this->actingAs($this->investorUser)->get(route('investor.payouts.index'));
        $response->assertOk();
    }

    public function test_investor_can_view_documents(): void
    {
        $response = $this->actingAs($this->investorUser)->get(route('investor.documents.index'));
        $response->assertOk();
    }

    public function test_investor_can_view_profile(): void
    {
        $response = $this->actingAs($this->investorUser)->get(route('investor.profile.show'));
        $response->assertOk();
    }

    public function test_investor_can_update_profile(): void
    {
        $response = $this->actingAs($this->investorUser)->put(route('investor.profile.update'), [
            'citizenship_country' => 'Croatia',
            'residence_country' => 'Croatia',
            'preferred_currency' => 'EUR',
            'payout_method' => 'bank_wire',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('investor_profiles', [
            'user_id' => $this->investorUser->id,
            'citizenship_country' => 'Croatia',
        ]);
    }

    public function test_agency_cannot_access_investor_panel(): void
    {
        $agency = User::factory()->create(['role' => 'real_estate_agency', 'email_verified_at' => now()]);
        $response = $this->actingAs($agency)->get(route('investor.dashboard'));
        $response->assertStatus(403);
    }
}
