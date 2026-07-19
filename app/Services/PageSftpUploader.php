<?php

namespace App\Services;

use App\Models\AgencyProfile;
use App\Models\LocalSeoCampaign;
use App\Models\AiAuthorityPage;
use App\Models\VillaReadyProperty;
use App\Models\VillaReadyAgencyPublication;
use League\Flysystem\Filesystem;
use League\Flysystem\PhpseclibV3\SftpConnectionProvider;
use League\Flysystem\PhpseclibV3\SftpAdapter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;

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
            // If no slug is set, generate one from primary_city using Str::slug()
            $slug = $campaign->page_slug;
            if (!$slug) {
                $slug = 'real-estate-' . \Illuminate\Support\Str::slug($campaign->primary_city ?? 'page');
            }
            $slug = trim($slug, '/');
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

            // Use same slug logic as upload
            $slug = $campaign->page_slug;
            if (!$slug) {
                $slug = 'real-estate-' . \Illuminate\Support\Str::slug($campaign->primary_city ?? 'page');
            }
            $slug = trim($slug, '/');
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

        $adapter = new SftpAdapter(
            $provider,
            '/',
            \League\Flysystem\UnixVisibility\PortableVisibilityConverter::fromArray([
                'file' => [
                    'public' => 0644,
                    'private' => 0600,
                ],
                'dir' => [
                    'public' => 0777,
                    'private' => 0700,
                ],
            ])
        );

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

    /**
     * Upload an AI Search Ranking page to the agency's server via SFTP.
     */
    public function uploadAiSearchPage(AiAuthorityPage $page, AgencyProfile $profile): array
    {
        if (!$profile->server_ip || !$profile->sftp_username || !$profile->sftp_password) {
            return [
                'success' => false,
                'message' => 'Missing SFTP credentials. Please configure server connection in Agency Settings.',
            ];
        }

        try {
            // Render the full HTML page
            $html = $this->renderAiSearchHtml($page, $profile);

            // Determine remote path
            $remotePath = rtrim($profile->sftp_path ?: '/public_html', '/');

            // Build the file path from slug
            $slug = $page->slug ?: 'ai-' . \Illuminate\Support\Str::slug($page->target_city . '-' . $page->name);
            $slug = trim($slug, '/');
            $filePath = $remotePath . '/' . $slug . '.html';

            // Create SFTP connection and upload
            $filesystem = $this->createSftpFilesystem($profile);

            // Write the HTML file
            $filesystem->write($filePath, $html);

            Log::info('AI Search page uploaded via SFTP', [
                'page_id' => $page->id,
                'path' => $filePath,
                'server' => $profile->server_ip,
            ]);

            $url = 'https://' . rtrim($profile->custom_domain, '/') . '/' . $slug . '.html';

            // Send email notification
            $this->sendPublishNotification($profile, $page->name, 'ai_search', $url, $page->target_city);

            return [
                'success' => true,
                'message' => "Page uploaded to {$filePath}",
                'path' => $filePath,
                'url' => $url,
            ];

        } catch (\Exception $e) {
            Log::error('AI Search SFTP upload failed', [
                'page_id' => $page->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'SFTP upload failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete an AI Search page from the server.
     */
    public function deleteAiSearchPage(AiAuthorityPage $page, AgencyProfile $profile): array
    {
        if (!$profile->server_ip || !$profile->sftp_username || !$profile->sftp_password) {
            return ['success' => false, 'message' => 'Missing SFTP credentials'];
        }

        try {
            $remotePath = rtrim($profile->sftp_path ?: '/public_html', '/');
            $slug = $page->slug ?: 'ai-' . \Illuminate\Support\Str::slug($page->target_city . '-' . $page->name);
            $slug = trim($slug, '/');
            $filePath = $remotePath . '/' . $slug . '.html';

            $filesystem = $this->createSftpFilesystem($profile);

            if ($filesystem->fileExists($filePath)) {
                $filesystem->delete($filePath);
            }

            return [
                'success' => true,
                'message' => "Page deleted from {$filePath}",
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'SFTP delete failed: ' . $e->getMessage(),
            ];
        }
    }

    protected function renderAiSearchHtml(AiAuthorityPage $page, AgencyProfile $profile): string
    {
        return View::make('realestate-taxi.ai-search-page', [
            'page' => $page,
            'profile' => $profile,
            'listing' => $page->listing,
            'preview' => false,
        ])->render();
    }

    /**
     * Upload a Villa Ready property page to the agency's server via SFTP.
     */
    public function uploadVillaReadyPropertyPage(
        VillaReadyProperty $property, 
        AgencyProfile $profile,
        VillaReadyAgencyPublication $publication
    ): array {
        if (!$profile->server_ip || !$profile->sftp_username || !$profile->sftp_password) {
            return [
                'success' => false,
                'message' => 'Missing SFTP credentials. Please configure server connection in Agency Settings.',
            ];
        }

        try {
            // Render the full HTML page
            $html = $this->renderVillaReadyPropertyHtml($property, $profile, $publication);

            // Determine remote path
            $remotePath = rtrim($profile->sftp_path ?: '/public_html', '/');

            // Build the file path from property slug
            $slug = 'properties/' . $property->slug;
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

            Log::info('Villa Ready property page uploaded via SFTP', [
                'property_id' => $property->id,
                'agency_id' => $profile->id,
                'path' => $filePath,
                'server' => $profile->server_ip,
            ]);

            $url = 'https://' . rtrim($profile->custom_domain, '/') . '/' . $slug . '/';

            // Update publication record
            $publication->update([
                'is_published' => true,
                'published_at' => now(),
                'published_url' => $url,
            ]);

            // Send email notification
            $this->sendPublishNotification($profile, $property->title, 'villa_ready', $url, $property->location);

            return [
                'success' => true,
                'message' => "Property page uploaded to {$filePath}",
                'path' => $filePath,
                'url' => $url,
            ];

        } catch (\Exception $e) {
            Log::error('Villa Ready SFTP upload failed', [
                'property_id' => $property->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'SFTP upload failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a Villa Ready property page from the server.
     */
    public function deleteVillaReadyPropertyPage(VillaReadyProperty $property, AgencyProfile $profile): array
    {
        if (!$profile->server_ip || !$profile->sftp_username || !$profile->sftp_password) {
            return ['success' => false, 'message' => 'Missing SFTP credentials'];
        }

        try {
            $remotePath = rtrim($profile->sftp_path ?: '/public_html', '/');
            $slug = 'properties/' . $property->slug;
            $directory = $remotePath . '/' . $slug;

            $filesystem = $this->createSftpFilesystem($profile);

            if ($filesystem->directoryExists($directory)) {
                $filesystem->deleteDirectory($directory);
            }

            return [
                'success' => true,
                'message' => "Property page deleted from {$directory}",
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'SFTP delete failed: ' . $e->getMessage(),
            ];
        }
    }

    protected function renderVillaReadyPropertyHtml(
        VillaReadyProperty $property, 
        AgencyProfile $profile,
        VillaReadyAgencyPublication $publication
    ): string {
        return View::make('realestate-taxi.villa-ready-property', [
            'property' => $property->load(['images', 'units']),
            'profile' => $profile,
            'publication' => $publication,
        ])->render();
    }

    /**
     * Send email notification when page is published.
     */
    public function sendPublishNotification(
        AgencyProfile $profile, 
        string $pageTitle, 
        string $pageType, 
        string $url,
        ?string $location = null
    ): void {
        if (empty($profile->contact_email)) {
            return;
        }

        try {
            $typeLabel = match ($pageType) {
                'local_seo' => 'Local SEO',
                'ai_search' => 'AI Search Ranking',
                'villa_ready' => 'Villa Ready Property',
                default => 'Generated',
            };

            $subject = "{$typeLabel} Page Published: {$pageTitle}";
            
            $locationInfo = $location ? "<p><strong>Location:</strong> {$location}</p>" : '';
            
            $body = "
                <h2>Your {$typeLabel} Page is Live!</h2>
                <p>Great news! Your page has been automatically generated and published.</p>
                <p><strong>Page Title:</strong> {$pageTitle}</p>
                {$locationInfo}
                <p><strong>Published URL:</strong> <a href=\"{$url}\">{$url}</a></p>
                <p><strong>Published At:</strong> " . now()->format('F j, Y \a\t g:i A') . "</p>
                <hr>
                <p>You can view and manage all your pages from your dashboard.</p>
                <br>
                <p>Best regards,<br>VillaBit AI Team</p>
            ";

            Mail::html($body, function ($message) use ($profile, $subject) {
                $message->to($profile->contact_email)
                    ->subject($subject);
            });

            Log::info('Publish notification sent', [
                'email' => $profile->contact_email,
                'page_type' => $pageType,
            ]);

        } catch (\Exception $e) {
            Log::warning('Failed to send publish notification', [
                'email' => $profile->contact_email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
