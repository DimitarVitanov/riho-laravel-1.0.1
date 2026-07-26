<?php

namespace App\Jobs\CompetitorIntelligence;

use App\Models\AgencyProfile;
use App\Models\AiAuthorityPage;
use App\Models\LocalSeoCampaign;
use App\Services\AiAuthorityContentGenerator;
use App\Services\LocalSeoContentGenerator;
use App\Services\UsageLimitService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class GenerateCompetitorOpportunityPage implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 600;
    public int $backoff = 120;
    public int $uniqueFor = 900;

    public function __construct(
        protected string $feature,
        protected int $pageId,
        protected int $agencyProfileId
    ) {
    }

    public function uniqueId(): string
    {
        return $this->feature . ':' . $this->pageId;
    }

    public function handle(
        AiAuthorityContentGenerator $aiSearchGenerator,
        LocalSeoContentGenerator $localSeoGenerator,
        UsageLimitService $usageService
    ): void {
        $profile = AgencyProfile::find($this->agencyProfileId);
        if (!$profile) {
            return;
        }

        $usageFeature = $this->feature === 'ai_search_ranking' ? 'ai_search_ranking' : 'local_seo_pages';
        $usage = $usageService->canUse($profile, $usageFeature);
        if (!$usage['allowed']) {
            throw new RuntimeException($usage['message'] ?? 'Page generation usage limit reached.');
        }

        if ($this->feature === 'ai_search_ranking') {
            $page = AiAuthorityPage::where('agency_profile_id', $profile->id)->find($this->pageId);
            if (!$page) {
                return;
            }

            $content = $aiSearchGenerator->generateForPage($page, $profile);
            $page->update([
                'ai_generated_content' => $content,
                'meta_title' => $content['meta_title'] ?? $page->name,
                'meta_description' => $content['meta_description'] ?? null,
            ]);
        } else {
            $page = LocalSeoCampaign::where('agency_profile_id', $profile->id)->find($this->pageId);
            if (!$page) {
                return;
            }

            $content = $localSeoGenerator->generateForCampaign($page, $profile);
            if (isset($content['error'])) {
                throw new RuntimeException($content['error']);
            }
        }

        $usageService->consume($profile, $usageFeature);

        Log::info('Competitor opportunity page generated', [
            'feature' => $this->feature,
            'page_id' => $this->pageId,
            'agency_profile_id' => $profile->id,
        ]);
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('Competitor opportunity page generation failed', [
            'feature' => $this->feature,
            'page_id' => $this->pageId,
            'agency_profile_id' => $this->agencyProfileId,
            'error' => $exception?->getMessage(),
        ]);
    }
}
