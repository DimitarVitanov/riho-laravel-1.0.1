<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use App\Models\CompetitorDailyReport;
use App\Models\CompetitorEvent;
use App\Models\CompetitorProperty;
use App\Jobs\CompetitorIntelligence\GenerateDailyCompetitorReport;
use App\Jobs\CompetitorIntelligence\RunCompetitorDiscoveryCycle;
use App\Jobs\CompetitorIntelligence\ScanChangedUrls;
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
        return view('competitor-intelligence.competitors.create');
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

        return view('competitor-intelligence.competitors.edit', compact('competitor'));
    }

    public function update(Request $request, Competitor $competitor)
    {
        $this->authorizeCompetitor($competitor);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'primary_market' => 'nullable|string|max:255',
            'google_place_id' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

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

    public function properties(Competitor $competitor)
    {
        $this->authorizeCompetitor($competitor);

        $properties = $competitor->properties()
            ->with('latestSnapshot')
            ->orderByDesc('first_detected_at')
            ->paginate(30);

        return view('competitor-intelligence.properties.index', compact('competitor', 'properties'));
    }

    public function propertyDetail(Competitor $competitor, CompetitorProperty $property)
    {
        $this->authorizeCompetitor($competitor);

        if ($property->competitor_id !== $competitor->id) {
            abort(404);
        }

        $property->load(['snapshots' => fn($q) => $q->orderByDesc('captured_at'), 'events']);

        return view('competitor-intelligence.properties.show', compact('competitor', 'property'));
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

        $reports = CompetitorDailyReport::where('agency_profile_id', $agencyProfileId)
            ->with(['metrics', 'items'])
            ->orderByDesc('report_date')
            ->paginate(30);

        return view('competitor-intelligence.reports.index', compact('reports')); 
    }

    public function dailyReportShow(CompetitorDailyReport $report)
    {
        if ($report->agency_profile_id !== $this->getAgencyProfileId()) {
            abort(403);
        }

        $report->load(['items', 'metrics']);

        return view('competitor-intelligence.reports.show', compact('report'));
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

    public function allProperties()
    {
        $agencyProfileId = $this->getAgencyProfileId();

        $competitorIds = Competitor::where('agency_profile_id', $agencyProfileId)
            ->where('is_active', true)
            ->pluck('id');

        $properties = CompetitorProperty::whereIn('competitor_id', $competitorIds)
            ->with(['competitor', 'latestSnapshot'])
            ->orderByDesc('first_detected_at')
            ->paginate(30);

        $stats = [
            'total_active' => $properties->total(),
            'new_7d' => CompetitorProperty::whereIn('competitor_id', $competitorIds)
                ->where('first_detected_at', '>=', now()->subDays(7))
                ->count(),
        ];

        return view('competitor-intelligence.properties.index', compact('properties', 'stats'));
    }

    public function exportProperties()
    {
        // TODO: Implement CSV export
        return back()->with('info', 'Export coming soon.');
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
