<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Agency\AgencySettingsController;
use App\Models\TaxiCountryReport;
use App\Services\Taxi\TaxiReportRenderer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaxiGlobalDataController extends Controller
{
    public function __construct(private TaxiReportRenderer $renderer)
    {
    }

    /**
     * https://realestate.taxi/globaldata/  and  /{locale}/globaldata/
     */
    public function index(Request $request, ?string $locale = null): Response|RedirectResponse
    {
        return $this->serve($request, TaxiCountryReport::INDEX_SLUG, $locale);
    }

    /**
     * https://realestate.taxi/globaldata/{slug}/  and  /{locale}/globaldata/{slug}/
     */
    public function show(Request $request, string $slug, ?string $locale = null): Response|RedirectResponse
    {
        return $this->serve($request, $slug, $locale);
    }

    /**
     * https://realestate.taxi/{locale}/globaldata/
     */
    public function indexLocalized(Request $request, string $locale): Response|RedirectResponse
    {
        return $this->serve($request, TaxiCountryReport::INDEX_SLUG, $locale);
    }

    /**
     * https://realestate.taxi/{locale}/globaldata/{slug}/
     */
    public function showLocalized(Request $request, string $locale, string $slug): Response|RedirectResponse
    {
        return $this->serve($request, $slug, $locale);
    }

    private function serve(Request $request, string $slug, ?string $urlLocale): Response|RedirectResponse
    {
        // No locale in the URL: send Croatian visitors to /hr/globaldata/… etc.
        if ($urlLocale === null) {
            $detected = RealEstateTaxiController::detectLocale($request);

            if ($detected !== 'en' && TaxiCountryReport::findBySlug($slug, $detected)) {
                $base = TaxiReportRenderer::basePath($detected);

                return redirect($slug === TaxiCountryReport::INDEX_SLUG ? $base . '/' : $base . '/' . $slug . '/');
            }
        }

        $locale = $this->resolveLocale($request, $urlLocale);

        $report = TaxiCountryReport::findWithFallback($slug, $locale);

        if (!$report || !$report->is_published) {
            throw new NotFoundHttpException("Global Data report not found: {$slug}");
        }

        $available = TaxiCountryReport::where('country_slug', $slug)
            ->where('is_published', true)
            ->pluck('locale')
            ->all();

        $html = $this->renderer->render($report, $report->locale, $available);

        return response($html)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    private function resolveLocale(Request $request, ?string $locale): string
    {
        $supported = AgencySettingsController::supportedPanelLanguages();

        if ($locale && array_key_exists($locale, $supported)) {
            return $locale;
        }

        $cookie = $request->cookie('taxi_lang');

        return $cookie && array_key_exists($cookie, $supported) ? $cookie : 'en';
    }
}
