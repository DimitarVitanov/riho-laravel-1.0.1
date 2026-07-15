<?php

namespace App\Console\Commands;

use App\Models\ScheduledPageGeneration;
use App\Models\GeneratedPage;
use App\Models\AgencyProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ProcessScheduledPageGenerations extends Command
{
    protected $signature = 'pages:process-scheduled';
    protected $description = 'Process scheduled page generations (run daily via cron)';

    public function handle()
    {
        $this->info('Processing scheduled page generations...');
        
        // Get all pending items due today or earlier
        $scheduled = ScheduledPageGeneration::pending()
            ->dueToday()
            ->with(['campaign', 'agencyProfile'])
            ->get();
        
        if ($scheduled->isEmpty()) {
            $this->info('No scheduled pages to process.');
            return 0;
        }
        
        $processed = 0;
        $failed = 0;
        
        // Group by agency to respect daily limits
        $byAgency = $scheduled->groupBy('agency_profile_id');
        
        foreach ($byAgency as $agencyId => $items) {
            $profile = AgencyProfile::find($agencyId);
            if (!$profile) {
                $this->warn("Agency profile {$agencyId} not found, skipping.");
                continue;
            }
            
            // Check today's usage for this agency
            $todayUsage = GeneratedPage::where('agency_profile_id', $agencyId)
                ->where('feature_key', 'local_seo_presence_boost')
                ->whereDate('created_at', now()->toDateString())
                ->count();
            
            $dailyLimit = $profile->plan_limits['local_seo_pages_per_day'] ?? 1;
            $canCreate = $dailyLimit - $todayUsage;
            
            if ($canCreate <= 0) {
                $this->warn("Agency {$profile->name} has reached daily limit, rescheduling...");
                // Reschedule all items for tomorrow
                foreach ($items as $item) {
                    $item->update(['scheduled_for' => now()->addDay()]);
                }
                continue;
            }
            
            // Process up to daily limit
            $toProcess = $items->take($canCreate);
            
            foreach ($toProcess as $item) {
                try {
                    $item->update(['status' => 'processing']);
                    
                    $campaign = $item->campaign;
                    if (!$campaign) {
                        throw new \Exception('Campaign not found');
                    }
                    
                    // Create the page
                    $pageName = "Real Estate in {$item->place_name}";
                    if ($campaign->primary_city && $item->place_name !== $campaign->primary_city) {
                        $pageName = "Real Estate in {$item->place_name}, {$campaign->primary_city}";
                    }
                    
                    $page = GeneratedPage::create([
                        'agency_profile_id' => $profile->id,
                        'local_seo_campaign_id' => $campaign->id,
                        'feature_key' => 'local_seo_presence_boost',
                        'name' => $pageName,
                        'title' => $pageName,
                        'slug' => Str::slug($pageName . '-' . uniqid()),
                        'target_city' => $campaign->primary_city,
                        'target_neighborhood' => $item->place_name,
                        'country' => $campaign->country,
                        'latitude' => $campaign->latitude,
                        'longitude' => $campaign->longitude,
                        'property_type' => 'apartment',
                        'status' => 'draft',
                        'page_type' => 'location_seo',
                    ]);
                    
                    $item->update([
                        'status' => 'completed',
                        'generated_page_id' => $page->id,
                    ]);
                    
                    $processed++;
                    $this->info("Created: {$pageName}");
                    
                } catch (\Exception $e) {
                    $item->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                    $failed++;
                    $this->error("Failed: {$item->place_name} - {$e->getMessage()}");
                }
            }
            
            // Reschedule remaining items
            $remaining = $items->skip($canCreate);
            $nextDate = now()->addDay();
            foreach ($remaining as $item) {
                $item->update(['scheduled_for' => $nextDate]);
                $nextDate = $nextDate->copy()->addDay();
            }
        }
        
        $this->info("Completed: {$processed} pages created, {$failed} failed.");
        return 0;
    }
}
