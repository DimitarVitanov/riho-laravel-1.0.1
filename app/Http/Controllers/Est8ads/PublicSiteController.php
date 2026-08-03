<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Agency\AgencySettingsController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicSiteController extends Controller
{
    public function index(Request $request): Response
    {
        return $this->render($request, 'est8ads.public.index');
    }

    public function contact(Request $request): Response
    {
        return $this->render($request, 'est8ads.public.contact');
    }

    public function privacy(Request $request): Response
    {
        return $this->render($request, 'est8ads.public.privacy');
    }

    public function terms(Request $request): Response
    {
        return $this->render($request, 'est8ads.public.terms');
    }

    /**
     * Resolves the visitor's language (explicit choice > saved cookie >
     * English), applies it, and persists an explicit choice for a year —
     * same pattern used by the Real Estate Taxi public pages.
     */
    private function render(Request $request, string $view): Response
    {
        $languages = AgencySettingsController::supportedPanelLanguages();

        $locale = $request->query('lang')
            ?: $request->cookie('est8ads_lang')
            ?: 'en';

        if (! array_key_exists($locale, $languages)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        if ($request->query('debug_asset')) {
            dd(asset('est8ads-assets/styles.css'), $request->getBaseUrl(), $request->getRequestUri());
        }

        $response = response()->view($view, compact('locale', 'languages'));

        if ($request->query('lang')) {
            $response->cookie('est8ads_lang', $locale, 60 * 24 * 365);
        }

        return $response;
    }
}
