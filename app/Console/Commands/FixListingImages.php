<?php

namespace App\Console\Commands;

use App\Models\AgencyListing;
use Illuminate\Console\Command;

class FixListingImages extends Command
{
    protected $signature = 'listings:fix-images {--dry-run : Show what would be fixed without making changes}';
    protected $description = 'Fix listing image paths that have full URLs instead of relative paths';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $listings = AgencyListing::whereNotNull('images_json')->get();
        
        $this->info("Found {$listings->count()} listings with images");
        $this->newLine();

        $fixedCount = 0;

        foreach ($listings as $listing) {
            $images = is_array($listing->images) ? $listing->images : (is_string($listing->images_json) ? json_decode($listing->images_json, true) : []);
            if (empty($images) || !is_array($images)) continue;

            $needsFix = false;
            $fixedImages = [];

            foreach ($images as $img) {
                if (str_starts_with($img, 'http')) {
                    $needsFix = true;
                    // Extract relative path from full URL
                    // e.g., "https://app.villabit.ai/storage/agency-listings/1/file.jpg" -> "agency-listings/1/file.jpg"
                    if (preg_match('#/storage/(.+)$#', $img, $matches)) {
                        $fixedImages[] = $matches[1];
                        $this->line("  Fixing: {$img}");
                        $this->line("       -> {$matches[1]}");
                    } else {
                        $fixedImages[] = $img; // Keep as-is if can't parse
                        $this->warn("  Cannot parse: {$img}");
                    }
                } else {
                    $fixedImages[] = $img; // Already relative path
                }
            }

            if ($needsFix) {
                $this->info("Listing #{$listing->id}: {$listing->title}");
                
                if (!$dryRun) {
                    $listing->update(['images_json' => json_encode($fixedImages)]);
                    $this->info("  ✓ Fixed!");
                } else {
                    $this->comment("  [DRY RUN] Would fix");
                }
                
                $fixedCount++;
                $this->newLine();
            }
        }

        $this->newLine();
        if ($dryRun) {
            $this->info("Dry run complete. {$fixedCount} listings would be fixed.");
            $this->comment("Run without --dry-run to apply fixes.");
        } else {
            $this->info("Done! Fixed {$fixedCount} listings.");
        }

        return Command::SUCCESS;
    }
}
