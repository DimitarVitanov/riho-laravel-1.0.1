<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AgencyProfile;
use App\Models\GeneratedPage;
use App\Models\CapitalCall;
use App\Models\InvestmentProject;
use App\Models\InvestorInvestment;
use App\Models\InvestorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerExtendedTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);
        $this->manager = User::factory()->create(['role' => 'manager', 'email_verified_at' => now()]);
    }

    public function test_manager_can_view_content_review(): void
    {
        $response = $this->actingAs($this->manager)->get(route('manager.content-review.index'));
        $response->assertOk();
    }

    public function test_manager_can_view_capital_calls(): void
    {
        $response = $this->actingAs($this->manager)->get(route('manager.capital-calls.index'));
        $response->assertOk();
    }

    public function test_manager_can_view_payout_preparation(): void
    {
        $response = $this->actingAs($this->manager)->get(route('manager.payout-preparation.index'));
        $response->assertOk();
    }

    public function test_manager_sees_assigned_capital_calls(): void
    {
        $investor = User::factory()->create([
            'role' => 'investor',
            'assigned_manager_id' => $this->manager->id,
            'email_verified_at' => now(),
        ]);

        $profile = InvestorProfile::create([
            'user_id' => $investor->id,
            'investor_type' => 'us_accredited',
            'kyc_status' => 'approved',
        ]);

        $project = InvestmentProject::create([
            'project_name' => 'Test Project',
            'project_code' => 'TP-001',
            'project_status' => 'open',
        ]);

        $investment = InvestorInvestment::create([
            'investor_user_id' => $investor->id,
            'investor_profile_id' => $profile->id,
            'investment_project_id' => $project->id,
            'committed_amount' => 50000,
            'investment_status' => 'active',
        ]);

        CapitalCall::create([
            'investment_project_id' => $project->id,
            'investor_investment_id' => $investment->id,
            'investor_user_id' => $investor->id,
            'call_number' => 1,
            'requested_amount' => 10000,
            'due_date' => now()->addDays(30),
            'status' => 'sent',
        ]);

        $response = $this->actingAs($this->manager)->get(route('manager.capital-calls.index'));
        $response->assertOk();
    }

    public function test_non_manager_cannot_access_manager_routes(): void
    {
        $agency = User::factory()->create(['role' => 'real_estate_agency', 'email_verified_at' => now()]);

        $response = $this->actingAs($agency)->get(route('manager.content-review.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($agency)->get(route('manager.capital-calls.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($agency)->get(route('manager.payout-preparation.index'));
        $response->assertStatus(403);
    }
}
