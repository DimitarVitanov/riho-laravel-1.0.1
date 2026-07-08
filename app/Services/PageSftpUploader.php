<?php

namespace App\Services;

use App\Models\AgencyProfile;
use App\Models\LocalSeoCampaign;
use League\Flysystem\Filesystem;
use League\Flysystem\PhpseclibV3\SftpConnectionProvider;
use League\Flysystem\PhpseclibV3\SftpAdapter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class PageSftpUploader
{
    /**
     * Upload a campaign page to the agency's server via SFTP.
     */
    public function uploadCampaignPage(LocalSeoCampaign $campaign, AgencyProfile $profile): array
    {
        if (!$profile->server_ip || !$profile->sftp_username || !$profile->sftp_password) {
            return [
                'success' => false,
                'message' => 'Missing SFTP credentials. Please configure server connection in Agency Settings.',
            ];
        }

        try {
            // Render the full HTML page
            $html = $this->renderCampaignHtml($campaign, $profile);

            // Determine remote path - use sftp_path directly, don't add /blog since it's already in the path
            $remotePath = rtrim($profile->sftp_path ?: '/public_html', '/');

            // Build the file path from slug (slug already contains just the page name like "real-estate-brac")
            $slug = trim($campaign->page_slug ?? 'real-estate-' . ($campaign->primary_city ?? 'page'), '/');
            $filePath = $remotePath . '/' . $slug . '/index.html';

            // Create SFTP connection and upload
            $filesystem = $this->createSftpFilesystem($profile);

            // Ensure directory exists
            $directory = dirname($filePath);
            if (!$filesystem->directoryExists($directory)) {
                $filesystem->createDirectory($directory);
            }

            // Write the HTML file
            $filesystem->write($filePath, $html);

            Log::info('Campaign page uploaded via SFTP', [
                'campaign_id' => $campaign->id,
                'path' => $filePath,
                'server' => $profile->server_ip,
            ]);

            return [
                'success' => true,
                'message' => "Page uploaded to {$filePath}",
                'path' => $filePath,
                'url' => 'https://' . rtrim($profile->custom_domain, '/') . '/' . $slug . '/',
            ];

        } catch (\Exception $e) {
            Log::error('SFTP upload failed', [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'SFTP upload failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a campaign page from the server.
     */
    public function deleteCampaignPage(LocalSeoCampaign $campaign, AgencyProfile $profile): array
    {
        if (!$profile->server_ip || !$profile->sftp_username || !$profile->sftp_password) {
            return ['success' => false, 'message' => 'Missing SFTP credentials'];
        }

        try {
            // Use sftp_path directly - it should already point to the correct folder
            $remotePath = rtrim($profile->sftp_path ?: '/public_html', '/');

            $slug = trim($campaign->page_slug ?? '', '/');
            if (!$slug) {
                return ['success' => false, 'message' => 'No slug to delete'];
            }

            $directory = $remotePath . '/' . $slug;

            $filesystem = $this->createSftpFilesystem($profile);

            if ($filesystem->directoryExists($directory)) {
                $filesystem->deleteDirectory($directory);
            }

            return [
                'success' => true,
                'message' => "Page deleted from {$directory}",
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'SFTP delete failed: ' . $e->getMessage(),
            ];
        }
    }

    protected function createSftpFilesystem(AgencyProfile $profile): Filesystem
    {
        $provider = new SftpConnectionProvider(
            $profile->server_ip,
            $profile->sftp_username,
            $profile->sftp_password,
            null,
            null,
            $profile->sftp_port ?: 22,
            false,
            30
        );

        $adapter = new SftpAdapter($provider, '/');

        return new Filesystem($adapter);
    }

    protected function renderCampaignHtml(LocalSeoCampaign $campaign, AgencyProfile $profile): string
    {
        // Use the same view as preview
        return View::make('realestate-taxi.campaign', [
            'campaign' => $campaign,
            'profile' => $profile,
            'page' => $campaign->generatedPage ?? new \App\Models\GeneratedPage([
                'title' => $campaign->name,
                'seo_title' => $campaign->name . ' | ' . $profile->agency_name,
            ]),
        ])->render();
    }
}
