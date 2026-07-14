<?php

namespace App\Console\Commands;

use App\Models\AiAuthorityPage;
use App\Models\LocalSeoCampaign;
use App\Services\AiAuthorityContentGenerator;
use App\Services\LocalSeoContentGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefreshAiContent extends Command
{
    protected $signature = 'ai:refresh-content {--days=30 : Days since last refresh}';
    protected $description = 'Refresh AI-generated content for pages older than specified days';

    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Refreshing AI content for pages not updated since {$cutoffDate->format('Y-m-d')}...");

        $refreshedCount = 0;

        // Refresh AI Search Ranking pages
        $aiPages = AiAuthorityPage::where('status', 'published')
            ->where('updated_at', '<', $cutoffDate)
            ->whereHas('agencyProfile', function ($q) {
                $q->whereHas('user', fn($u) => $u->where('status', 'active'));
            })
            ->with('agencyProfile')
            ->get();

        foreach ($aiPages as $page) {
            try {
                $this->refreshAiPage($page);
                $refreshedCount++;
                $this->line("  ✓ Refreshed AI page: {$page->name}");
            } catch (\Exception $e) {
                Log::error("Failed to refresh AI page {$page->id}: " . $e->getMessage());
                $this->error("  ✗ Failed: {$page->name} - {$e->getMessage()}");
            }
        }

        // Refresh Local SEO Campaign pages
        $campaigns = LocalSeoCampaign::where('status', 'active')
            ->where('updated_at', '<', $cutoffDate)
            ->whereHas('agencyProfile', function ($q) {
                $q->whereHas('user', fn($u) => $u->where('status', 'active'));
            })
            ->with('agencyProfile')
            ->get();

        foreach ($campaigns as $campaign) {
            try {
                $this->refreshCampaign($campaign);
                $refreshedCount++;
                $this->line("  ✓ Refreshed campaign: {$campaign->name}");
            } catch (\Exception $e) {
                Log::error("Failed to refresh campaign {$campaign->id}: " . $e->getMessage());
                $this->error("  ✗ Failed: {$campaign->name} - {$e->getMessage()}");
            }
        }

        $this->info("Done! Refreshed {$refreshedCount} pages.");

        return Command::SUCCESS;
    }

    protected function refreshAiPage(AiAuthorityPage $page): void
    {
        $profile = $page->agencyProfile;
        
        $generator = new AiAuthorityContentGenerator();
        $content = $generator->generateForPage($page, $profile);

        $page->update([
            'ai_generated_content' => $content,
            'meta_title' => $content['meta_title'] ?? $page->name,
            'meta_description' => $content['meta_description'] ?? null,
        ]);

        // Increment freshness counter
        $usageLimit = $profile->currentUsageLimit;
        if ($usageLimit) {
            $usageLimit->increment('ai_search_freshness_updates_used');
        }
    }

    protected function refreshCampaign(LocalSeoCampaign $campaign): void
    {
        $profile = $campaign->agencyProfile;

        $generator = new LocalSeoContentGenerator();
        $content = $generator->generateForCampaign($campaign, $profile);

        $campaign->update([
            'ai_generated_content' => $content,
        ]);

        // Increment freshness counter
        $usageLimit = $profile->currentUsageLimit;
        if ($usageLimit) {
            $usageLimit->increment('ai_search_freshness_updates_used');
        }
    }
}
