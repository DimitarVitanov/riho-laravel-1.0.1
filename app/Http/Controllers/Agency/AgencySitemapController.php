<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AgencyProfile;
use App\Models\GeneratedPage;

class AgencySitemapController extends Controller
{
    public function show(int $agencyId)
    {
        $profile = AgencyProfile::findOrFail($agencyId);

        $pages = GeneratedPage::where('agency_profile_id', $agencyId)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->select(['slug', 'target_url', 'published_at', 'updated_at'])
            ->cursor();

        $baseUrl = $profile->official_website_url
            ? rtrim($profile->official_website_url, '/')
            : config('app.url');

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
