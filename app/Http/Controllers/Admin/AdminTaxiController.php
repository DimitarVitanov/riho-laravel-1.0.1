<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxiCountryReport;
use App\Models\TaxiReportPrompt;
use App\Models\TaxiSetting;
use App\Services\Taxi\TaxiReportRefresher;
use App\Services\Taxi\TaxiReportRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminTaxiController extends Controller
{
    /**
     * TAXI → Global Data: every country report with its last update and next cron run.
     */
    public function globalData(Request $request)
    {
        $locale = $request->query('locale', 'en');

        $reports = TaxiCountryReport::query()
            ->where('locale', $locale)
            ->orderByRaw("country_slug = 'index' desc")
            ->orderBy('country')
            ->get();

        $locales = TaxiCountryReport::query()
            ->select('locale')
            ->distinct()
            ->orderBy('locale')
            ->pluck('locale');

        $stats = [
            'total' => TaxiCountryReport::where('locale', 'en')->count(),
            'published' => TaxiCountryReport::where('locale', 'en')->where('is_published', true)->count(),
            'due' => TaxiCountryReport::dueForRefresh()->count(),
            'locales' => $locales->count(),
            'oldest' => TaxiCountryReport::where('locale', 'en')->min('last_generated_at'),
            'newest' => TaxiCountryReport::where('locale', 'en')->max('last_generated_at'),
        ];

        $settings = [
            'auto_refresh_enabled' => TaxiSetting::get('auto_refresh_enabled', '1'),
            'refresh_interval_days' => TaxiSetting::get('refresh_interval_days', '30'),
            'reports_per_run' => TaxiSetting::get('reports_per_run', '5'),
            'ai_provider' => TaxiSetting::get('ai_provider', 'openai'),
            'translation_provider' => TaxiSetting::get('translation_provider', 'gemini'),
            'last_cron_run_at' => TaxiSetting::get('last_cron_run_at'),
        ];

        return view('admin.villabit.taxi.global-data', compact('reports', 'locale', 'locales', 'stats', 'settings'));
    }

    public function show(TaxiCountryReport $report)
    {
        $translations = TaxiCountryReport::where('country_slug', $report->country_slug)
            ->where('locale', '!=', $report->locale)
            ->get();

        $publicUrl = TaxiReportRenderer::publicUrl($report, $report->locale);

        return view('admin.villabit.taxi.show', compact('report', 'translations', 'publicUrl'));
    }

    /**
     * Serve the stored HTML exactly as it will be published (admin preview).
     */
    public function preview(TaxiCountryReport $report, TaxiReportRenderer $renderer)
    {
        return response($renderer->render($report, $report->locale))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'auto_refresh_enabled' => 'nullable|in:0,1',
            'refresh_interval_days' => 'required|integer|min:1|max:365',
            'reports_per_run' => 'required|integer|min:1|max:100',
            'ai_provider' => 'required|in:openai,gemini,anthropic',
            'translation_provider' => 'required|in:openai,gemini,anthropic',
        ]);

        TaxiSetting::put('auto_refresh_enabled', $request->input('auto_refresh_enabled', '0'));
        TaxiSetting::put('refresh_interval_days', $data['refresh_interval_days']);
        TaxiSetting::put('reports_per_run', $data['reports_per_run']);
        TaxiSetting::put('ai_provider', $data['ai_provider']);
        TaxiSetting::put('translation_provider', $data['translation_provider']);

        TaxiCountryReport::query()->update(['refresh_interval_days' => $data['refresh_interval_days']]);

        return back()->with('success', 'Global Data cron settings saved.');
    }

    /**
     * Run the AI refresh for one report immediately.
     */
    public function refresh(TaxiCountryReport $report, TaxiReportRefresher $refresher)
    {
        $result = $refresher->refresh($report);

        return back()->with(
            $result['status'] === 'failed' ? 'error' : 'success',
            "{$report->country}: {$result['note']}"
        );
    }

    /**
     * Queue the standard cron batch on demand.
     */
    public function runCron(Request $request)
    {
        $limit = (int) TaxiSetting::get('reports_per_run', 5);

        Artisan::queue('taxi:refresh-reports', ['--limit' => $limit, '--force' => true]);

        return back()->with('success', "Global Data refresh queued for up to {$limit} reports.");
    }

    public function translate(Request $request, TaxiCountryReport $report)
    {
        $data = $request->validate(['locale' => 'required|string|size:2']);

        Artisan::queue('taxi:translate-reports', [
            'locale' => $data['locale'],
            '--slug' => $report->country_slug,
            '--limit' => 1,
        ]);

        return back()->with('success', "Translation to {$data['locale']} queued for {$report->country}.");
    }

    public function togglePublished(TaxiCountryReport $report)
    {
        $report->update(['is_published' => !$report->is_published]);

        return back()->with('success', $report->country . ' is now ' . ($report->is_published ? 'published' : 'hidden') . '.');
    }

    public function prompts()
    {
        $prompts = TaxiReportPrompt::orderBy('sort_order')->get();

        return view('admin.villabit.taxi.prompts', compact('prompts'));
    }

    public function updatePrompt(Request $request, TaxiReportPrompt $prompt)
    {
        $data = $request->validate([
            'prompt_text' => 'required|string',
            'section_id' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $prompt->update([
            'prompt_text' => $data['prompt_text'],
            'section_id' => $data['section_id'] ?? null,
            'is_active' => (bool) $request->input('is_active', false),
        ]);

        return back()->with('success', "Prompt {$prompt->key} updated.");
    }
}
