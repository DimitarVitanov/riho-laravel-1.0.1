<?php

namespace Tests\Feature;

use App\Models\AgencyProfile;
use App\Models\AiDailyReport;
use App\Models\User;
use App\Services\ContentPublishService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentPublishServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ContentPublishService $service;
    protected AiDailyReport $report;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);
        $this->service = new ContentPublishService();

        $agency = User::factory()->create(['role' => 'real_estate_agency']);
        $profile = AgencyProfile::create([
            'user_id' => $agency->id,
            'agency_name' => 'Test Agency',
        ]);

        $this->report = AiDailyReport::create([
            'agency_profile_id' => $profile->id,
            'feature_key' => 'local_seo',
            'report_date' => now(),
            'status' => 'completed',
            'content_uniqueness_status' => 'draft',
        ]);
    }

    public function test_process_content_auto_publish(): void
    {
        $result = $this->service->processContent($this->report, ContentPublishService::WORKFLOW_AUTO_PUBLISH);

        $this->assertEquals('published', $result);
        $this->assertEquals('published', $this->report->fresh()->content_uniqueness_status);
    }

    public function test_process_content_manual_review(): void
    {
        $result = $this->service->processContent($this->report, ContentPublishService::WORKFLOW_MANUAL_REVIEW);

        $this->assertEquals('ready_for_manual_review', $result);
        $this->assertEquals('ready_for_manual_review', $this->report->fresh()->content_uniqueness_status);
    }

    public function test_manual_publish_from_passed(): void
    {
        $this->report->update(['content_uniqueness_status' => 'passed']);

        $result = $this->service->manualPublish($this->report);

        $this->assertTrue($result);
        $this->assertEquals('published', $this->report->fresh()->content_uniqueness_status);
    }

    public function test_manual_publish_from_ready_for_review(): void
    {
        $this->report->update(['content_uniqueness_status' => 'ready_for_manual_review']);

        $result = $this->service->manualPublish($this->report);

        $this->assertTrue($result);
        $this->assertEquals('published', $this->report->fresh()->content_uniqueness_status);
    }

    public function test_cannot_manual_publish_from_draft(): void
    {
        $result = $this->service->manualPublish($this->report);

        $this->assertFalse($result);
        $this->assertEquals('draft', $this->report->fresh()->content_uniqueness_status);
    }

    public function test_rewrite_and_recheck(): void
    {
        $this->report->update(['content_uniqueness_status' => 'failed']);

        $result = $this->service->rewriteAndRecheck($this->report, ContentPublishService::WORKFLOW_AUTO_PUBLISH);

        $this->assertEquals('published', $result);
        $this->assertEquals('published', $this->report->fresh()->content_uniqueness_status);
    }
}
