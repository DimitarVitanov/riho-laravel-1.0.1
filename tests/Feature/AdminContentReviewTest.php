<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AgencyProfile;
use App\Models\GeneratedPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentReviewTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected AgencyProfile $profile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);
        $this->admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);

        $agency = User::factory()->create(['role' => 'real_estate_agency']);
        $this->profile = AgencyProfile::create([
            'user_id' => $agency->id,
            'agency_name' => 'Test Agency',
        ]);
    }

    public function test_admin_can_view_content_review_index(): void
    {
        GeneratedPage::create([
            'agency_profile_id' => $this->profile->id,
            'feature_key' => 'local_seo_presence_boost',
            'title' => 'Test SEO Page',
            'status' => 'pending_review',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.villabit.content-review.index'));
        $response->assertOk();
        $response->assertViewIs('admin.villabit.content-review.index');
    }

    public function test_admin_can_view_content_review_show(): void
    {
        $page = GeneratedPage::create([
            'agency_profile_id' => $this->profile->id,
            'feature_key' => 'local_seo_presence_boost',
            'title' => 'Test Page',
            'content_html' => '<p>Test content</p>',
            'status' => 'pending_review',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.villabit.content-review.show', $page));
        $response->assertOk();
    }

    public function test_admin_can_approve_content(): void
    {
        $page = GeneratedPage::create([
            'agency_profile_id' => $this->profile->id,
            'feature_key' => 'local_seo_presence_boost',
            'title' => 'Approvable Page',
            'status' => 'pending_review',
            'content_uniqueness_status' => 'passed',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.villabit.content-review.approve', $page));
        $response->assertRedirect();
        $this->assertDatabaseHas('generated_pages', [
            'id' => $page->id,
            'status' => 'published',
        ]);
    }

    public function test_admin_can_reject_content(): void
    {
        $page = GeneratedPage::create([
            'agency_profile_id' => $this->profile->id,
            'feature_key' => 'local_seo_presence_boost',
            'title' => 'Rejectable Page',
            'status' => 'pending_review',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.villabit.content-review.reject', $page));
        $response->assertRedirect();
        $this->assertDatabaseHas('generated_pages', [
            'id' => $page->id,
            'status' => 'draft',
        ]);
    }
}
