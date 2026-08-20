<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use App\Models\CompetitorDailyReport;
use App\Models\CompetitorEvent;
use App\Models\CompetitorProperty;
use App\Jobs\CompetitorIntelligence\GenerateCompetitorOpportunityPage;
use App\Jobs\CompetitorIntelligence\GenerateDailyCompetitorReport;
use App\Jobs\CompetitorIntelligence\RefreshCompetitorReputation;
use App\Jobs\CompetitorIntelligence\RunCompetitorDiscoveryCycle;
use App\Jobs\CompetitorIntelligence\ScanChangedUrls;
use App\Services\CompetitorIntelligence\CompetitorOpportunityPageService;
use App\Services\CompetitorIntelligence\CompetitorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompetitorIntelligenceController extends Controller
{
    protected CompetitorService $competitorService;

    public function __construct(CompetitorService $competitorService)
    {
        $this->competitorService = $competitorService;
    }

    public function dashboard()
    {
        $agencyProfileId = $this->getAgencyProfileId();

        $competitors = Competitor::where('agency_profile_id', $agencyProfileId)
            ->where('is_active', true)
            ->withCount(['properties' => fn($q) => $q->where('current_status', 'active')])
            ->get();

        $todayMetrics = $this->competitorService->getTodayMetrics($agencyProfileId);

        $recentEvents = CompetitorEvent::whereIn('competitor_id', $competitors->pluck('id'))
            ->whereDate('detected_at', today())
            ->orderByDesc('detected_at')
            ->limit(20)
            ->with('competitor')
            ->get();

        $latestReport = CompetitorDailyReport::where('agency_profile_id', $agencyProfileId)
            ->orderByDesc('report_date')
            ->first();

        return view('competitor-intelligence.dashboard', compact(
            'competitors',
            'todayMetrics',
            'recentEvents',
            'latestReport'
        ));
    }

    public function index()
    {
        $agencyProfileId = $this->getAgencyProfileId();

        $competitors = Competitor::where('agency_profile_id', $agencyProfileId)
            ->withCount([
                'sources' => fn($q) => $q->where('status', 'active'),
                'events' => fn($q) => $q->whereDate('detected_at', today()),
            ])
            ->orderBy('name')
            ->paginate(20);

        return view('competitor-intelligence.competitors.index', compact('competitors'));
    }

    public function comparison()
    {
        $agencyProfileId = $this->getAgencyProfileId();

        $competitors = Competitor::where('agency_profile_id', $agencyProfileId)
            ->where('is_active', true)
            ->withCount(['properties', 'events' => fn($q) => $q->where('detected_at', '>=', now()->subDays(30))])
            ->orderByDesc('properties_count')
            ->get();

        // Calculate additional metrics for each competitor
        foreach ($competitors as $competitor) {
            $competitor->new_properties_30d = $competitor->properties()
                ->where('first_detected_at', '>=', now()->subDays(30))
                ->count();
            
            $competitor->price_reductions_30d = $competitor->events()
                ->where('event_type', 'price_decrease')
                ->where('detected_at', '>=', now()->subDays(30))
                ->count();
            
            $competitor->disappearances_30d = $competitor->events()
                ->where('event_type', 'property_removed')
                ->where('detected_at', '>=', now()->subDays(30))
                ->count();
        }

        // Calculate max values for highlighting winners
        $maxProperties = $competitors->max('properties_count') ?? 0;
        $maxNewProperties = $competitors->max('new_properties_30d') ?? 0;
        $maxEvents = $competitors->max('events_count') ?? 0;

        // Totals
        $totalProperties = $competitors->sum('properties_count');
        $totalEvents = $competitors->sum('events_count');
        $aiOpportunities = 0; // TODO: Calculate from AI analysis

        // Insights (placeholders - will be populated by AI)
        $inventoryInsight = $competitors->count() > 1 
            ? $competitors->first()->name . ' has the largest observed inventory.'
            : null;
        
        $fastestTurnover = $competitors->sortBy('avg_listing_lifetime')->first()?->name ?? '—';
        $mostPriceReductions = $competitors->sortByDesc('price_reductions_30d')->first()?->name ?? '—';
        $mostDisappearances = $competitors->sortByDesc('disappearances_30d')->first()?->name ?? '—';
        $mostActiveSegment = 'Split apartments'; // TODO: Calculate from data
        $mostSeoPages = '—';
        $mostMentions = '—';
        $highestRating = '—';
        $mostEvents = $competitors->sortByDesc('events_count')->first()?->name ?? '—';

        $crossCompetitorInsights = []; // TODO: Generate from AI

        return view('competitor-intelligence.comparison', compact(
            'competitors',
            'totalProperties',
            'totalEvents',
            'aiOpportunities',
            'maxProperties',
            'maxNewProperties',
            'maxEvents',
            'inventoryInsight',
            'fastestTurnover',
            'mostPriceReductions',
            'mostDisappearances',
            'mostActiveSegment',
            'mostSeoPages',
            'mostMentions',
            'highestRating',
            'mostEvents',
            'crossCompetitorInsights'
        ));
    }

    public function create()
    {
        $countries = $this->countryOptions();

        return view('competitor-intelligence.competitors.create', compact('countries'));
    }

    /**
     * Countries for the picker: clean common name as both value and label,
     * ordered alphabetically by that common name.
     */
    private function countryOptions()
    {
        return DB::table('countries')->get(['name', 'iso_3166_2'])
            ->map(function ($country) {
                $country->common_name = \App\Helpers\Helpers::commonCountryName($country->name);
                return $country;
            })
            ->sortBy('common_name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website_url' => 'required|url|max:2048',
            'legal_name' => 'nullable|string|max:255',
            'primary_market' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'google_place_id' => 'nullable|string|max:255',
            'google_maps_url' => 'nullable|url|max:2048',
            'aliases' => 'nullable|string',
            'phones' => 'nullable|string',
            'emails' => 'nullable|string',
            'agent_names' => 'nullable|string|max:1000',
            'portal_urls' => 'nullable|string',
            'social_urls' => 'nullable|string',
            'is_active' => 'nullable',
            'include_in_daily_report' => 'nullable',
            'include_in_comparison' => 'nullable',
            'priority' => 'nullable|in:high,normal,low',
            'scan_profile' => 'nullable|string',
            'sources' => 'nullable|array',
            'track_rating' => 'nullable',
            'track_reviews' => 'nullable',
            'analyze_reviews' => 'nullable',
        ]);

        $validated['agency_profile_id'] = $this->getAgencyProfileId();
        $validated['is_active'] = $request->has('is_active');
        $validated['include_in_daily_report'] = $request->has('include_in_daily_report');
        $validated['include_in_comparison'] = $request->has('include_in_comparison');

        // Parse comma-separated aliases
        $aliasesArray = [];
        if (!empty($validated['aliases'])) {
            $aliasesArray = array_filter(array_map('trim', explode(',', $validated['aliases'])));
        }
        $validated['aliases'] = $aliasesArray;

        // Parse phone numbers (one per line)
        $identifiers = [];
        if (!empty($validated['phones'])) {
            $phones = array_filter(array_map('trim', explode("\n", $validated['phones'])));
            foreach ($phones as $phone) {
                $identifiers[] = ['type' => 'phone', 'value' => $phone];
            }
        }
        
        // Parse emails (one per line)
        if (!empty($validated['emails'])) {
            $emails = array_filter(array_map('trim', explode("\n", $validated['emails'])));
            foreach ($emails as $email) {
                $identifiers[] = ['type' => 'email', 'value' => $email];
            }
        }
        $validated['identifiers'] = $identifiers;

        // Parse monitoring sources
        $validated['monitoring_sources'] = $request->input('sources', []);

        // Check for duplicate domain
        $normalizedDomain = $this->competitorService->normalizeDomain($validated['website_url']);
        $existing = Competitor::where('agency_profile_id', $validated['agency_profile_id'])
            ->where('normalized_domain', $normalizedDomain)
            ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->withErrors(['website_url' => 'You already have a competitor with this domain: ' . $existing->name]);
        }

        $competitor = $this->competitorService->create($validated);

        RunCompetitorDiscoveryCycle::dispatch($competitor->id);

        return redirect()
            ->route('agency.competitor-intelligence.competitors.show', $competitor)
            ->with('success', 'Competitor added successfully. Initial scan started.');
    }

    public function show(Competitor $competitor)
    {
        $this->authorizeCompetitor($competitor);

        $competitor->load([
            'aliases',
            'identifiers',
            'sources' => fn($q) => $q->where('status', 'active'),
            'sourceSettings',
        ]);

        $statistics = $this->competitorService->getStatistics($competitor);

        $recentEvents = $competitor->events()
            ->orderByDesc('detected_at')
            ->limit(50)
            ->get();

        $strategySummaries = $competitor->strategySummaries()
            ->orderByDesc('period_end')
            ->limit(4)
            ->get();

        return view('competitor-intelligence.competitors.show', compact(
            'competitor',
            'statistics',
            'recentEvents',
            'strategySummaries'
        ));
    }

    public function edit(Competitor $competitor)
    {
        $this->authorizeCompetitor($competitor);

        $competitor->load(['aliases', 'identifiers', 'sourceSettings']);
        $countries = $this->countryOptions();

        return view('competitor-intelligence.competitors.edit', compact('competitor', 'countries'));
    }

    public function update(Request $request, Competitor $competitor)
    {
        $this->authorizeCompetitor($competitor);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'primary_market' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'website_url' => 'required|url|max:2048',
            'google_place_id' => 'nullable|string|max:255',
            'google_maps_url' => 'nullable|url|max:2048',
            'is_active' => 'nullable|boolean',
            'include_in_daily_report' => 'nullable|boolean',
            'include_in_comparison' => 'nullable|boolean',
            'priority' => 'required|in:high,normal,low',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['include_in_daily_report'] = $request->boolean('include_in_daily_report');
        $validated['include_in_comparison'] = $request->boolean('include_in_comparison');

        $this->competitorService->update($competitor, $validated);

        return redirect()
            ->route('agency.competitor-intelligence.competitors.show', $competitor)
            ->with('success', 'Competitor updated successfully.');
    }

    public function destroy(Competitor $competitor)
    {
        $this->authorizeCompetitor($competitor);

        $competitor->delete();

        return redirect()
            ->route('agency.competitor-intelligence.competitors.index')
            ->with('success', 'Competitor deleted successfully.');
    }

    public function triggerScan(Competitor $competitor)
    {
        $this->authorizeCompetitor($competitor);

        ScanChangedUrls::dispatch($competitor->id);

        return back()->with('success', 'Scan triggered. Results will appear shortly.');
    }

    public function toggleStatus(Competitor $competitor)
    {
        $this->authorizeCompetitor($competitor);

        $competitor->update(['is_active' => !$competitor->is_active]);

        $status = $competitor->is_active ? 'activated' : 'paused';
        return back()->with('success', "Competitor monitoring {$status}.");
    }

    public function properties(Request $request, Competitor $competitor)
    {
        $this->authorizeCompetitor($competitor);

        $properties = $this->propertyQuery($request, $competitor->properties())->paginate(10)->withQueryString();
        $stats = $this->propertyStats($competitor->properties());

        return view('competitor-intelligence.properties.index', compact('competitor', 'properties', 'stats'));
    }

    public function propertyDetail(Competitor $competitor, CompetitorProperty $property)
    {
        $this->authorizeCompetitor($competitor);

        if ($property->competitor_id !== $competitor->id) {
            abort(404);
        }

        $property->load([
            'url',
            'latestSnapshot',
            'snapshots' => fn($query) => $query->orderByDesc('captured_at'),
            'events' => fn($query) => $query->orderByDesc('detected_at'),
        ]);

        $priceHistory = $property->snapshots
            ->sortBy('captured_at')
            ->filter(fn($snapshot) => $snapshot->price !== null)
            ->values();

        return view('competitor-intelligence.properties.show', compact('competitor', 'property', 'priceHistory'));
    }

    public function reputation(Request $request)
    {
        $competitors = Competitor::where('agency_profile_id', $this->getAgencyProfileId())
            ->where('is_active', true)
            ->with('latestGoogleMetric')
            ->orderBy('name')
            ->get();

        $competitorIds = $competitors->pluck('id');
        $selectedCompetitorId = $request->integer('competitor_id') ?: null;

        if ($selectedCompetitorId && !$competitorIds->contains($selectedCompetitorId)) {
            abort(403);
        }

        $scopedCompetitorIds = $selectedCompetitorId ? collect([$selectedCompetitorId]) : $competitorIds;
        $profileCompetitors = $selectedCompetitorId ? $competitors->where('id', $selectedCompetitorId) : $competitors;
        $reviewEvents = CompetitorEvent::whereIn('competitor_id', $scopedCompetitorIds)
            ->whereIn('event_type', ['new_review', 'rating_changed'])
            ->with('competitor')
            ->orderByDesc('detected_at')
            ->paginate(10, ['*'], 'reviews_page')
            ->withQueryString();

        $mentions = \App\Models\CompetitorMention::whereIn('competitor_id', $scopedCompetitorIds)
            ->with('competitor')
            ->orderByDesc('first_detected_at')
            ->paginate(10, ['*'], 'mentions_page')
            ->withQueryString();

        $recentMetrics = $profileCompetitors
            ->pluck('latestGoogleMetric')
            ->filter()
            ->sortByDesc('captured_at')
            ->values();

        $availableMetrics = $recentMetrics;
        $stats = [
            'profiles' => $availableMetrics->count(),
            'average_rating' => $availableMetrics->whereNotNull('rating')->avg('rating'),
            'review_profiles' => $availableMetrics->whereNotNull('review_count')->count(),
            'total_reviews' => $availableMetrics->sum('review_count'),
            'new_review_signals' => CompetitorEvent::whereIn('competitor_id', $scopedCompetitorIds)
                ->where('event_type', 'new_review')
                ->where('detected_at', '>=', now()->subDays(30))
                ->count(),
        ];

        return view('competitor-intelligence.reputation.index', compact(
            'competitors',
            'profileCompetitors',
            'selectedCompetitorId',
            'reviewEvents',
            'mentions',
            'recentMetrics',
            'stats'
        ));
    }

    public function refreshReputation(Request $request)
    {
        $competitors = Competitor::where('agency_profile_id', $this->getAgencyProfileId())
            ->where('is_active', true)
            ->when($request->integer('competitor_id'), fn ($query, $competitorId) => $query->where('id', $competitorId))
            ->get();

        if ($request->integer('competitor_id') && $competitors->isEmpty()) {
            abort(403);
        }

        $queued = 0;

        foreach ($competitors as $competitor) {
            if (!$competitor->google_maps_url && !$competitor->google_place_id) {
                continue;
            }

            RefreshCompetitorReputation::dispatch($competitor->id);
            $queued++;
        }

        if ($queued === 0) {
            return back()->with('reputation_error', 'No configured Google Maps profiles were found for the selected competitors.');
        }

        return back()->with(
            'reputation_success',
            "Queued {$queued} Google profile " . ($queued === 1 ? 'refresh.' : 'refreshes.') . ' Results will appear when the queue finishes.'
        );
    }

    public function todayIntelligence()
    {
        $agencyProfileId = $this->getAgencyProfileId();

        $competitors = Competitor::where('agency_profile_id', $agencyProfileId)
            ->where('is_active', true)
            ->withCount(['events' => fn($q) => $q->whereDate('detected_at', today())])
            ->get();

        $competitorIds = $competitors->pluck('id');

        $todayMetrics = $this->competitorService->getTodayMetrics($agencyProfileId);

        $todayEventCount = CompetitorEvent::whereIn('competitor_id', $competitorIds)
            ->whereDate('detected_at', today())
            ->count();

        $events = CompetitorEvent::whereIn('competitor_id', $competitorIds)
            ->orderByDesc('detected_at')
            ->orderByDesc('importance_score')
            ->with(['competitor', 'property.latestSnapshot'])
            ->paginate(50);

        $maxEvents = $competitors->max('events_count') ?? 1;

        $aiActions = []; // TODO: Generate from AI

        return view('competitor-intelligence.today', compact('todayMetrics', 'todayEventCount', 'events', 'competitors', 'maxEvents', 'aiActions'));
    }

    public function dailyReports()
    {
        $agencyProfileId = $this->getAgencyProfileId();

        $reportQuery = CompetitorDailyReport::where('agency_profile_id', $agencyProfileId)
            ->with(['metrics', 'items'])
            ->orderByDesc('report_date');

        $latestReport = (clone $reportQuery)->first();
        $reports = $reportQuery->paginate(30);

        return view('competitor-intelligence.reports.index', compact('reports', 'latestReport'));
    }

    public function dailyReportShow(CompetitorDailyReport $report)
    {
        if ($report->agency_profile_id !== $this->getAgencyProfileId()) {
            abort(403);
        }

        $report->load(['items', 'metrics']);

        return view('competitor-intelligence.reports.show', compact('report'));
    }

    public function createBetterPage(CompetitorEvent $event, CompetitorOpportunityPageService $pageService)
    {
        $event->load('competitor');
        if (!$event->competitor || $event->competitor->agency_profile_id !== $this->getAgencyProfileId()) {
            abort(403);
        }

        if (!$event->canCreateBetterPage()) {
            return back()->with('error', 'This intelligence event does not contain an actionable page opportunity.');
        }

        $user = \App\Models\User::findOrFail(Auth::id());
        $profile = $user->getEffectiveAgencyProfile();
        $result = $pageService->create($event, $profile);
        $page = $result['page'];

        if ($result['created']) {
            GenerateCompetitorOpportunityPage::dispatch($result['feature'], $page->id, $profile->id);
        }

        $label = $result['feature'] === 'ai_search_ranking' ? 'AI Search Ranking' : 'Local SEO';
        $route = route('agency.features.show', [
            'feature' => $result['feature'],
            $result['feature'] === 'ai_search_ranking' ? 'edit_page_id' : 'edit_campaign_id' => $page->id,
        ]);

        return redirect($route)->with(
            'success',
            $result['created']
                ? "{$label} page created from competitor intelligence. AI content generation is queued."
                : "Opening the existing {$label} page created from this opportunity."
        );
    }

    public function eventEvidence(CompetitorEvent $event)
    {
        $event->load(['competitor', 'source', 'property.latestSnapshot']);

        if (!$event->competitor || $event->competitor->agency_profile_id !== $this->getAgencyProfileId()) {
            abort(403);
        }

        $relatedEvents = collect([$event]);
        if ($event->entity_type === 'property' && $event->property) {
            $relatedEvents = $event->property->events()
                ->with(['competitor', 'property.latestSnapshot'])
                ->orderByDesc('detected_at')
                ->get();
        }

        return view('competitor-intelligence.events.evidence', compact('event', 'relatedEvents'));
    }

    public function scanCenter()
    {
        $agencyProfileId = $this->getAgencyProfileId();

        $competitors = Competitor::where('agency_profile_id', $agencyProfileId)
            ->where('is_active', true)
            ->get();

        // Load recent scan runs for each competitor separately to avoid window function issues
        foreach ($competitors as $competitor) {
            $competitor->recentScanRuns = $competitor->scanRuns()
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        }

        $failedJobs = DB::table('failed_jobs')
            ->where('queue', 'like', 'competitor%')
            ->count();

        return view('competitor-intelligence.scan-center', compact('competitors', 'failedJobs'));
    }

    public function runFullScan(Request $request)
    {
        $agencyProfileId = $this->getAgencyProfileId();

        $competitors = Competitor::where('agency_profile_id', $agencyProfileId)
            ->where('is_active', true)
            ->get();

        foreach ($competitors as $competitor) {
            // Level 1 (discovery) then Level 2 (deep scan) so events are generated
            // even when no brand-new URLs are found. Deep scan diffs against prior
            // snapshots and dispatches AI analysis for any detected changes.
            \Illuminate\Support\Facades\Bus::chain([
                new RunCompetitorDiscoveryCycle($competitor->id),
                new ScanChangedUrls($competitor->id),
            ])->dispatch();
        }

        return back()->with('success', 'Full scan started for all competitors. Results will appear shortly.');
    }

    public function runCustomScan(Request $request)
    {
        $validated = $request->validate([
            'competitor_id' => 'required|exists:competitors,id',
            'scan_types' => 'array',
        ]);

        $competitor = Competitor::findOrFail($validated['competitor_id']);
        $this->authorizeCompetitor($competitor);

        // Level 1 (discovery) then Level 2 (deep scan) so events are generated.
        \Illuminate\Support\Facades\Bus::chain([
            new RunCompetitorDiscoveryCycle($competitor->id),
            new ScanChangedUrls($competitor->id),
        ])->dispatch();

        return back()->with('success', 'Custom scan started for ' . $competitor->name . '. Results will appear shortly.');
    }

    public function generateReport()
    {
        $agencyProfileId = $this->getAgencyProfileId();
        $reportDate = today()->subDay()->toDateString();

        GenerateDailyCompetitorReport::dispatch($agencyProfileId, $reportDate);

        return back()->with('success', "Report generation started for {$reportDate}. Refresh shortly to view it.");
    }

    public function exportReport(CompetitorDailyReport $report)
    {
        if ($report->agency_profile_id !== $this->getAgencyProfileId()) {
            abort(403);
        }

        $report->load(['items', 'metrics']);
        $contents = view('competitor-intelligence.reports.export', compact('report'))->render();
        $filename = 'competitor-intelligence-report-' . $report->report_date->format('Y-m-d') . '.html';

        return response($contents)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function allProperties(Request $request)
    {
        $competitorIds = Competitor::where('agency_profile_id', $this->getAgencyProfileId())
            ->where('is_active', true)
            ->pluck('id');

        $baseQuery = CompetitorProperty::whereIn('competitor_id', $competitorIds);
        $properties = $this->propertyQuery($request, $baseQuery)->paginate(10)->withQueryString();
        $stats = $this->propertyStats($baseQuery);

        return view('competitor-intelligence.properties.index', compact('properties', 'stats'));
    }

    public function exportProperties(Request $request)
    {
        $competitorIds = Competitor::where('agency_profile_id', $this->getAgencyProfileId())
            ->where('is_active', true)
            ->pluck('id');

        $query = CompetitorProperty::whereIn('competitor_id', $competitorIds);
        if ($request->filled('competitor_id')) {
            $query->where('competitor_id', $request->integer('competitor_id'));
        }

        $properties = $this->propertyQuery($request, $query)->get();
        $filename = 'competitor-properties-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($properties) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, ['Competitor', 'Property', 'Reference', 'Location', 'Type', 'Price', 'Currency', 'Status', 'First Seen', 'Last Seen', 'Listing URL'], ',', '"', '\\');

            foreach ($properties as $property) {
                $snapshot = $property->latestSnapshot;
                fputcsv($stream, [
                    $property->competitor?->name,
                    $snapshot?->title,
                    $property->external_reference,
                    $snapshot?->location_text,
                    $snapshot?->property_type,
                    $snapshot?->price,
                    $snapshot?->currency,
                    $property->current_status,
                    $property->first_detected_at?->toDateTimeString(),
                    $property->last_seen_at?->toDateTimeString(),
                    $property->canonical_url ?? $property->url?->url,
                ], ',', '"', '\\');
            }

            fclose($stream);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    protected function propertyQuery(Request $request, $query)
    {
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');

        return $query
            ->with(['competitor', 'url', 'latestSnapshot'])
            ->when($status, fn($builder) => $builder->where('current_status', $status))
            ->when($search, function ($builder) use ($search) {
                $builder->where(function ($nested) use ($search) {
                    $nested->where('external_reference', 'like', "%{$search}%")
                        ->orWhere('canonical_url', 'like', "%{$search}%")
                        ->orWhereHas('latestSnapshot', fn($snapshot) => $snapshot
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('location_text', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('first_detected_at');
    }

    protected function propertyStats($query): array
    {
        return [
            'total' => (clone $query)->count(),
            'active' => (clone $query)->where('current_status', 'active')->count(),
            'new_7d' => (clone $query)->where('first_detected_at', '>=', now()->subDays(7))->count(),
            'possibly_removed' => (clone $query)->where('current_status', 'possibly_removed')->count(),
        ];
    }

    protected function getAgencyProfileId(): int
    {
        $user = Auth::user();

        if ($user->agencyProfile) {
            return $user->agencyProfile->id;
        }

        if ($user->managerProfile && $user->managerProfile->view_agency_user_id) {
            $agencyUser = \App\Models\User::find($user->managerProfile->view_agency_user_id);
            if ($agencyUser && $agencyUser->agencyProfile) {
                return $agencyUser->agencyProfile->id;
            }
        }

        abort(403, 'No agency profile found');
    }

    protected function authorizeCompetitor(Competitor $competitor): void
    {
        if ($competitor->agency_profile_id !== $this->getAgencyProfileId()) {
            abort(403);
        }
    }
}
