<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ManagerProfile;
use App\Models\AgencyProfile;
use App\Models\InvestorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);
        $this->manager = User::factory()->create(['role' => 'manager', 'status' => 'active', 'email_verified_at' => now()]);
        ManagerProfile::create([
            'user_id' => $this->manager->id,
            'can_manage_agencies' => true,
            'can_manage_investors' => true,
            'can_review_ai_outputs' => true,
        ]);
    }

    public function test_manager_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->manager)->get(route('manager.dashboard'));
        $response->assertOk();
    }

    public function test_manager_can_list_assigned_agencies(): void
    {
        $response = $this->actingAs($this->manager)->get(route('manager.agencies.index'));
        $response->assertOk();
    }

    public function test_manager_can_list_assigned_investors(): void
    {
        $response = $this->actingAs($this->manager)->get(route('manager.investors.index'));
        $response->assertOk();
    }

    public function test_manager_can_list_ai_reports(): void
    {
        $response = $this->actingAs($this->manager)->get(route('manager.ai-reports.index'));
        $response->assertOk();
    }

    public function test_manager_can_access_support_notes(): void
    {
        $response = $this->actingAs($this->manager)->get(route('manager.support-notes.index'));
        $response->assertOk();
    }

    public function test_manager_can_store_support_note(): void
    {
        $user = User::factory()->create(['role' => 'real_estate_agency']);

        $response = $this->actingAs($this->manager)->post(route('manager.support-notes.store'), [
            'user_id' => $user->id,
            'note' => 'Test support note content.',
        ]);
        $response->assertRedirect();
    }

    public function test_agency_cannot_access_manager_panel(): void
    {
        $agency = User::factory()->create(['role' => 'real_estate_agency', 'email_verified_at' => now()]);
        $response = $this->actingAs($agency)->get(route('manager.dashboard'));
        $response->assertStatus(403);
    }
}
