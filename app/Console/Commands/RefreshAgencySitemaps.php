<?php

namespace App\Console\Commands;

use App\Models\AgencyProfile;
use App\Services\SitemapSftpUploader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefreshAgencySitemaps extends Command
{
    protected $signature = 'app:refresh-agency-sitemaps';
    protected $description = 'Regenerate and upload sitemaps for all DNS-verified agencies';

    public function handle(SitemapSftpUploader $uploader): int
    {
        $profiles = AgencyProfile::whereNotNull('dns_verified_at')
            ->whereNotNull('server_ip')
            ->whereNotNull('sftp_username')
            ->whereNotNull('sftp_password')
            ->get();

        $success = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($profiles as $profile) {
            if (!$profile->custom_domain) {
                $skipped++;
                continue;
            }

            $result = $uploader->upload($profile);

            if ($result['success']) {
                $profile->update(['sitemap_url' => $profile->custom_domain . '/sitemap.xml']);
                $success++;
                $this->info("{$profile->custom_domain}: sitemap refreshed at {$result['path']}");
                Log::info("Daily sitemap refresh succeeded for {$profile->custom_domain}: {$result['path']}");
            } else {
                $failed++;
                $this->error("{$profile->custom_domain}: {$result['message']}");
                Log::error("Daily sitemap refresh failed for {$profile->custom_domain}: {$result['message']}");
            }
        }

        $this->info("Done. Success: {$success}, Failed: {$failed}, Skipped: {$skipped}");

        return self::SUCCESS;
    }
}
