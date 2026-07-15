<?php

namespace App\Jobs;

use App\Models\ScheduledPageGeneration;
use App\Models\LocalSeoCampaign;
use App\Services\LocalSeoContentGenerator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessScheduledPageJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = [60, 300, 900]; // Retry after 1min, 5min, 15min
    public $timeout = 300; // 5 minutes max per job

    protected int $scheduledItemId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $scheduledItemId)
    {
        $this->scheduledItemId = $scheduledItemId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $item = ScheduledPageGeneration::find($this->scheduledItemId);
        
        if (!$item || $item->status !== 'pending') {
            return; // Already processed or doesn't exist
        }
        
        $profile = $item->agencyProfile;
        $parentCampaign = $item->campaign;
        
        if (!$profile || !$parentCampaign) {
            $item->update([
                'status' => 'failed',
                'error_message' => 'Agency profile or parent campaign not found',
            ]);
            return;
        }
        
        // Check daily limit
        $todayUsage = LocalSeoCampaign::where('agency_profile_id', $profile->id)
            ->where('is_sub_campaign', true)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        
        $dailyLimit = $profile->plan_limits['local_seo_pages_per_day'] ?? 1;
        
        if ($todayUsage >= $dailyLimit) {
            // Reschedule for tomorrow
            $item->update(['scheduled_for' => now()->addDay()]);
            Log::info("Rescheduled {$item->place_name} for tomorrow - daily limit reached");
            return;
        }
        
        try {
            $item->update(['status' => 'processing']);
            
            // Create the sub-campaign
            $campaignName = "Real Estate in {$item->place_name}";
            if ($parentCampaign->primary_city && $item->place_name !== $parentCampaign->primary_city) {
                $campaignName = "Real Estate in {$item->place_name}, {$parentCampaign->primary_city}";
            }
            
            $subCampaign = LocalSeoCampaign::create([
                'agency_profile_id' => $profile->id,
                'parent_campaign_id' => $parentCampaign->id,
                'name' => $campaignName,
                'primary_city' => $item->place_name,
                'country' => $parentCampaign->country,
                'latitude' => $parentCampaign->latitude,
                'longitude' => $parentCampaign->longitude,
                'coverage_area' => 5,
                'coverage_unit' => $parentCampaign->coverage_unit ?? 'km',
                'positioning_note' => $parentCampaign->positioning_note,
                'status' => 'draft',
                'is_sub_campaign' => true,
            ]);
            
            // Generate AI content
            $generator = new LocalSeoContentGenerator();
            $generator->generateForCampaign($subCampaign, $profile);
            
            $item->update(['status' => 'completed']);
            
            Log::info("Created scheduled campaign: {$campaignName}");
            
        } catch (\Exception $e) {
            $item->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            
            Log::error("Failed to create scheduled campaign {$item->place_name}: " . $e->getMessage());
            
            throw $e; // Re-throw to trigger retry
        }
    }
}
