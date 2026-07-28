<?php

namespace Tests\Feature;

use App\Models\TaxiCountryReport;
use App\Services\Taxi\TaxiReportHtml;
use App\Services\Taxi\TaxiReportRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxiGlobalDataTest extends TestCase
{
    use RefreshDatabase;

    private function makeReport(string $slug, string $locale = 'en'): TaxiCountryReport
    {
        $html = <<<HTML
<!doctype html>
<html lang="en"><head><title>Croatia Residential Real Estate Market Analysis 2026</title>
<meta name="description" content="test">
<link rel="canonical" href="https://villabit.ai/market-reports/croatia/">
</head><body>
<a href="/market-reports/countries/">Index</a>
<a href="/market-reports/spain/">Spain</a>
<main><section id="prices"><h2>Prices</h2><p>{$slug} prices grew 14.3% year-on-year in Q1 2026 according to the official national house price index published by the statistics office.</p></section></main>
</body></html>
HTML;

        return TaxiCountryReport::create([
            'country' => ucfirst($slug),
            'country_slug' => $slug,
            'locale' => $locale,
            'title' => 'Test report',
            'meta_description' => 'test',
            'html_full' => $html,
            'is_published' => true,
            'last_generated_at' => now(),
            'next_refresh_at' => now()->addDays(30),
        ]);
    }

    public function test_country_report_is_served_with_rewritten_links(): void
    {
        $this->makeReport('croatia');

        $response = $this->get('/realestate/globaldata/croatia');

        $response->assertOk();
        $response->assertSee('href="/globaldata/spain/"', false);
        $response->assertSee('href="/globaldata/"', false);
        $response->assertSee('<link rel="canonical" href="https://realestate.taxi/globaldata/croatia/">', false);
        $response->assertDontSee('/market-reports/', false);
    }

    public function test_index_page_is_served(): void
    {
        $this->makeReport(TaxiCountryReport::INDEX_SLUG);

        $this->get('/realestate/globaldata')->assertOk();
    }

    public function test_missing_report_returns_404(): void
    {
        $this->get('/realestate/globaldata/atlantis')->assertNotFound();
    }

    public function test_localized_url_falls_back_to_english_master(): void
    {
        $this->makeReport('croatia');

        $this->get('/realestate/hr/globaldata/croatia')->assertOk();
    }

    public function test_localized_url_serves_the_translation_when_present(): void
    {
        $master = $this->makeReport('croatia');
        $hr = $this->makeReport('croatia', 'hr');
        $hr->update(['source_report_id' => $master->id, 'title' => 'Hrvatska']);

        $response = $this->get('/realestate/hr/globaldata/croatia');

        $response->assertOk();
        $response->assertSee('<link rel="canonical" href="https://realestate.taxi/hr/globaldata/croatia/">', false);
        $response->assertSee('href="/hr/globaldata/spain/"', false);
    }

    public function test_unpublished_report_is_not_served(): void
    {
        $this->makeReport('croatia')->update(['is_published' => false]);

        $this->get('/realestate/globaldata/croatia')->assertNotFound();
    }

    public function test_content_blocks_are_detected_and_replaceable(): void
    {
        $report = $this->makeReport('croatia');
        $doc = TaxiReportHtml::load($report->html_full);

        $blocks = $doc->contentBlocks();
        $this->assertArrayHasKey('prices', $blocks);

        $inner = $doc->innerHtml($blocks['prices']);
        $this->assertTrue($doc->replaceInnerHtml($blocks['prices'], '<h2>Prices</h2><p>Updated copy.</p>'));
        $this->assertStringContainsString('Updated copy.', $doc->save());
        $this->assertStringContainsString('Prices', $inner);
    }

    public function test_renderer_builds_public_urls(): void
    {
        $report = $this->makeReport('croatia');

        $this->assertSame('https://realestate.taxi/globaldata/croatia/', TaxiReportRenderer::publicUrl($report, 'en'));
        $this->assertSame('https://realestate.taxi/hr/globaldata/croatia/', TaxiReportRenderer::publicUrl($report, 'hr'));
    }

    public function test_due_for_refresh_scope_uses_the_schedule(): void
    {
        $due = $this->makeReport('croatia');
        $this->makeReport('spain');

        $this->assertSame(0, TaxiCountryReport::dueForRefresh()->count());

        $due->update(['next_refresh_at' => now()->subDay()]);

        $this->assertSame(1, TaxiCountryReport::dueForRefresh()->count());
    }
}
