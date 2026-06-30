<?php

namespace App\Services;

use App\Models\AgencyProfile;
use App\Models\GeneratedPage;
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

            return [
                'success' => true,
                'message' => "Sitemap uploaded to {$fullPath}",
                'path' => $fullPath,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'SFTP upload failed: ' . $e->getMessage(),
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

        $baseUrl = $profile->custom_domain
            ? 'https://' . rtrim($profile->custom_domain, '/')
            : ($profile->official_website_url
                ? rtrim($profile->official_website_url, '/')
                : config('app.url'));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

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

        $xml .= '</urlset>';

        return $xml;
    }
}
