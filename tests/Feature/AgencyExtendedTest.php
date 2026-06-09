<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AgencyProfile;
use App\Models\GeneratedPage;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgencyExtendedTest extends TestCase
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
        ]);
    }

    public function test_agency_can_view_generated_pages_index(): void
    {
        $response = $this->actingAs($this->agencyUser)->get(route('agency.generated-pages.index'));
        $response->assertOk();
    }

    public function test_agency_can_view_generated_page_detail(): void
    {
        $page = GeneratedPage::create([
            'agency_profile_id' => $this->profile->id,
            'feature_key' => 'local_seo_presence_boost',
            'title' => 'Test SEO Page',
            'content_html' => '<p>Hello world</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($this->agencyUser)->get(route('agency.generated-pages.show', $page));
        $response->assertOk();
    }

    public function test_agency_can_view_leads_index(): void
    {
        $response = $this->actingAs($this->agencyUser)->get(route('agency.leads.index'));
        $response->assertOk();
    }

    public function test_agency_can_view_lead_detail(): void
    {
        $lead = Lead::create([
            'agency_profile_id' => $this->profile->id,
            'source' => 'invisible_lead_magnet',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->agencyUser)->get(route('agency.leads.show', $lead));
        $response->assertOk();
    }

    public function test_agency_can_update_lead_status(): void
    {
        $lead = Lead::create([
            'agency_profile_id' => $this->profile->id,
            'source' => 'invisible_lead_magnet',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->agencyUser)->patch(route('agency.leads.update-status', $lead), [
            'status' => 'contacted',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => 'contacted',
        ]);
    }

    public function test_non_agency_cannot_access_agency_routes(): void
    {
        $investor = User::factory()->create(['role' => 'investor', 'email_verified_at' => now()]);

        $response = $this->actingAs($investor)->get(route('agency.generated-pages.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($investor)->get(route('agency.leads.index'));
        $response->assertStatus(403);
    }
}
