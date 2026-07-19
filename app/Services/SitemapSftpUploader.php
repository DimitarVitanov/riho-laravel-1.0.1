<?php

namespace App\Services;

use App\Models\AgencyProfile;
use App\Models\GeneratedPage;
use App\Models\VillaReadyProperty;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use League\Flysystem\Filesystem;
use League\Flysystem\PhpseclibV3\SftpConnectionProvider;
use League\Flysystem\PhpseclibV3\SftpAdapter;

class SitemapSftpUploader
{
    public function upload(AgencyProfile $profile): array
    {
        if (!$profile->server_ip || !$profile->sftp_username || !$profile->sftp_password) {
            return [
                'success' => false,
                'message' => 'Missing SFTP credentials (server_ip, sftp_username, or sftp_password)',
            ];
        }

        try {
            $sitemapContent = $this->generateSitemapXml($profile);

            $remotePath = rtrim($profile->sftp_path ?: '/public_html', '/');
            $fullPath = $remotePath . '/sitemap.xml';

            $filesystem = $this->createSftpFilesystem($profile);

            // Upload sitemap to the configured SFTP path root
            $filesystem->write($fullPath, $sitemapContent);

            // Upload Villa Ready property pages
            $propertyResults = $this->uploadVillaReadyPages($filesystem, $profile, $remotePath);

            $message = "Sitemap uploaded to {$fullPath}";
            if ($propertyResults['count'] > 0) {
                $message .= ". Uploaded {$propertyResults['count']} property page(s).";
            }

            return [
                'success' => true,
                'message' => $message,
                'path' => $fullPath,
                'properties_uploaded' => $propertyResults['count'],
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'SFTP upload failed: ' . $e->getMessage(),
            ];
        }
    }

    protected function uploadVillaReadyPages(Filesystem $filesystem, AgencyProfile $profile, string $remotePath): array
    {
        $properties = VillaReadyProperty::where('status', 'published')
            ->with(['images', 'units', 'content'])
            ->get();

        $count = 0;

        // Ensure properties directory exists with proper permissions
        $propertiesDir = $remotePath . '/properties';
        if (!$filesystem->directoryExists($propertiesDir)) {
            $filesystem->createDirectory($propertiesDir, [
                'visibility' => 'public',
                'directory_visibility' => 'public',
            ]);
        }
        // Set visibility to public (755 for dirs, 644 for files)
        $filesystem->setVisibility($propertiesDir, 'public');

        foreach ($properties as $property) {
            try {
                $html = $this->renderPropertyPage($property, $profile);
                $pagePath = $propertiesDir . '/' . $property->slug . '.html';
                $filesystem->write($pagePath, $html);
                $count++;
            } catch (\Exception $e) {
                Log::warning("Failed to upload property page {$property->slug}: " . $e->getMessage());
            }
        }

        return ['count' => $count];
    }

    public function deleteVillaReadyPages(AgencyProfile $profile): array
    {
        if (!$profile->server_ip || !$profile->sftp_username || !$profile->sftp_password) {
            return [
                'success' => false,
                'message' => 'Missing SFTP credentials',
            ];
        }

        try {
            $remotePath = rtrim($profile->sftp_path ?: '/public_html', '/');
            $propertiesDir = $remotePath . '/properties';

            $filesystem = $this->createSftpFilesystem($profile);

            if ($filesystem->directoryExists($propertiesDir)) {
                $filesystem->deleteDirectory($propertiesDir);
            }

            return [
                'success' => true,
                'message' => "Property pages deleted from {$propertiesDir}",
            ];

        } catch (\Exception $e) {
            Log::warning("Failed to delete Villa Ready pages: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to delete property pages: ' . $e->getMessage(),
            ];
        }
    }

    protected function renderPropertyPage(VillaReadyProperty $property, AgencyProfile $profile): string
    {
        return View::make('realestate-taxi.villa-ready-property', [
            'property' => $property,
            'profile' => $profile,
            'isStatic' => true,
        ])->render();
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

    protected function extractFolderFromDomain(?string $customDomain): ?string
    {
        if (!$customDomain || !str_contains($customDomain, '/')) {
            return null;
        }

        $parts = explode('/', $customDomain, 2);
        return $parts[1] ?? null;
    }

    protected function generateSitemapXml(AgencyProfile $profile): string
    {
        $pages = GeneratedPage::where('agency_profile_id', $profile->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->select(['slug', 'target_url', 'published_at', 'updated_at'])
            ->get();

        // Get ALL published Villa Ready properties (all agencies get all properties)
        $villaReadyProperties = VillaReadyProperty::where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->get();

        $baseUrl = $profile->custom_domain
            ? 'https://' . rtrim($profile->custom_domain, '/')
            : ($profile->official_website_url
                ? rtrim($profile->official_website_url, '/')
                : config('app.url'));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Add generated pages (Local SEO, AI Search, etc.)
        foreach ($pages as $page) {
            $loc = $page->target_url
                ? htmlspecialchars($page->target_url)
                : htmlspecialchars($baseUrl . '/' . ltrim($page->slug, '/'));

            $date = $page->published_at ?? $page->updated_at;
            $lastmod = $date ? $date->toAtomString() : now()->toAtomString();

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$loc}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        // Add ALL Villa Ready properties (automatically available to all agencies)
        foreach ($villaReadyProperties as $property) {
            $loc = htmlspecialchars($baseUrl . '/properties/' . $property->slug);
            $lastmod = $property->updated_at->toAtomString();

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$loc}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.9</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
