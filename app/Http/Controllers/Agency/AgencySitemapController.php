<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AgencyProfile;
use App\Models\GeneratedPage;

class AgencySitemapController extends Controller
{
    public function showByDomain(\Illuminate\Http\Request $request)
    {
        $host = strtolower($request->getHost());

        $profile = AgencyProfile::whereRaw('LOWER(custom_domain) = ?', [$host])->first();

        if (!$profile) {
            abort(404, 'No agency found for this domain.');
        }

        return $this->show($profile->id);
    }

    public function showByDomainFolder(\Illuminate\Http\Request $request, string $folder)
    {
        $host = strtolower($request->getHost());
        $domainFolder = $host . '/' . $folder;

        $profile = AgencyProfile::whereRaw('LOWER(custom_domain) = ?', [$domainFolder])->first();

        if (!$profile) {
            abort(404, 'No agency found for this domain/folder.');
        }

        return $this->show($profile->id);
    }

    public function show(int $agencyId)
    {
        $profile = AgencyProfile::findOrFail($agencyId);

        $pages = GeneratedPage::where('agency_profile_id', $agencyId)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->select(['slug', 'target_url', 'published_at', 'updated_at'])
            ->cursor();

        $baseUrl = $profile->custom_domain
            ? 'https://' . rtrim($profile->custom_domain, '/')
            : ($profile->official_website_url
                ? rtrim($profile->official_website_url, '/')
                : config('app.url'));

        return response()->stream(function () use ($pages, $baseUrl) {
            echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            foreach ($pages as $page) {
                $loc = $page->target_url
                    ? htmlspecialchars($page->target_url)
                    : htmlspecialchars($baseUrl . '/' . ltrim($page->slug, '/'));

                $lastmod = ($page->published_at ?? $page->updated_at)?->toAtomString() ?? now()->toAtomString();

                echo "  <url>\n";
                echo "    <loc>{$loc}</loc>\n";
                echo "    <lastmod>{$lastmod}</lastmod>\n";
                echo "    <changefreq>monthly</changefreq>\n";
                echo "    <priority>0.7</priority>\n";
                echo "  </url>\n";
            }

            echo '</urlset>';
        }, 200, [
            'Content-Type'  => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
