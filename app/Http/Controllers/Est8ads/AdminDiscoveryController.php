<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Jobs\Est8ads\DiscoverInternetProperties;
use App\Models\Est8ads\DiscoveryJob;
use App\Models\Est8ads\ExternalListing;
use App\Models\Est8ads\ExternalListingMatch;
use App\Models\Est8ads\PropertyMove;
use App\Models\Est8ads\Setting;
use App\Services\Est8ads\Discovery\DiscoveryAudit;
use App\Services\Est8ads\Discovery\DiscoveryManager;
use App\Services\Est8ads\Discovery\DiscoveryPresenter;
use App\Services\Est8ads\Discovery\DiscoverySettings;
use App\Services\Est8ads\Discovery\ListingReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminDiscoveryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate(['status' => ['nullable', Rule::in(['queued', 'running', 'processing', 'completed', 'failed'])], 'property_move_id' => 'nullable|integer']);
        $jobs = DiscoveryJob::with(['internetSource:id,name,type,status', 'propertyMove:id,uuid,title'])
            ->when($validated['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($validated['property_move_id'] ?? null, fn ($q, $id) => $q->where('property_move_id', $id))
            ->latest()->paginate(min(100, max(1, $request->integer('per_page', 25))));
        return response()->json($jobs);
    }

    public function store(Request $request, DiscoveryManager $manager): JsonResponse
    {
        $validated = $request->validate([
            'request_id' => 'nullable|string|max:50',
            'property_move_id' => 'nullable|integer|exists:est8ads_property_moves,id',
            'source_ids' => 'nullable|array|max:50',
            'source_ids.*' => 'integer|exists:est8ads_internet_sources,id',
            'test' => 'sometimes|boolean',
        ]);
        $moveId = $validated['property_move_id'] ?? (int) preg_replace('/\D+/', '', (string) ($validated['request_id'] ?? ''));
        $moves = $moveId
            ? PropertyMove::whereKey($moveId)->get()
            : PropertyMove::whereIn('status', ['submitted', 'active'])->latest()->limit(100)->get();
        abort_if($moves->isEmpty(), 422, 'No active property requests are available for discovery.');

        $jobs = $moves->flatMap(fn ($move) => $manager->dispatch($move, ($validated['test'] ?? false) ? 'test' : 'manual', $request->user()?->id, $validated['source_ids'] ?? null));
        $formatted = $jobs->map(fn ($job) => $this->formatJob($job));

        return response()->json([
            'message' => ($validated['test'] ?? false) ? 'Discovery rule test queued.' : 'Internet discovery job queued.',
            'job' => $formatted->count() === 1 ? $formatted->first() : null,
            'jobs' => $formatted->count() > 1 ? $formatted->values() : [],
            'stats' => $this->stats(),
        ], 202);
    }

    public function show(DiscoveryJob $job): JsonResponse
    {
        return response()->json($job->load(['internetSource', 'propertyMove.properties'])->loadCount('externalListings'));
    }

    public function results(Request $request, DiscoveryJob $job): JsonResponse
    {
        $results = ExternalListingMatch::with(['externalListing.internetSource', 'connectedProperty'])
            ->where('discovery_job_id', $job->id)->orderByDesc('final_score')
            ->paginate(min(100, max(1, $request->integer('per_page', 25))));
        return response()->json($results);
    }

    public function retry(DiscoveryJob $job, DiscoveryAudit $audit): JsonResponse
    {
        abort_unless(in_array($job->status, ['failed', 'completed'], true), 409, 'Only failed or completed jobs can be retried.');
        $job->update(['status' => 'queued', 'error_message' => null, 'started_at' => null, 'finished_at' => null]);
        $audit->record('internet_discovery.retry_queued', $job, [], [], request()->user()?->id);
        DiscoverInternetProperties::dispatch($job->id)->onQueue('internet-discovery');
        return response()->json(['message' => 'Discovery job queued again.', 'job' => $this->formatJob($job), 'stats' => $this->stats()], 202);
    }

    public function review(Request $request, ExternalListing $listing, ListingReviewService $reviews): JsonResponse
    {
        $validated = $request->validate(['match_id' => 'required|integer', 'action' => ['required', Rule::in(['import', 'connect', 'reject'])], 'reason' => 'nullable|string|max:1000']);
        $match = $this->match($listing, (int) $validated['match_id']);
        $result = match ($validated['action']) {
            'import' => $reviews->import($match, $request->user()->id),
            'connect' => $reviews->connect($match, $request->user()->id),
            'reject' => $reviews->reject($match, $request->user()->id, $validated['reason'] ?? null),
        };
        return response()->json($result);
    }

    public function import(Request $request, ExternalListing $listing, ListingReviewService $reviews): JsonResponse
    {
        $validated = $request->validate(['match_id' => 'required|integer']);
        return response()->json($reviews->import($this->match($listing, (int) $validated['match_id']), $request->user()->id), 201);
    }

    public function connect(Request $request, ExternalListing $listing, ListingReviewService $reviews): JsonResponse
    {
        $validated = $request->validate(['match_id' => 'required|integer']);
        return response()->json($reviews->connect($this->match($listing, (int) $validated['match_id']), $request->user()->id));
    }

    public function reject(Request $request, ExternalListing $listing, ListingReviewService $reviews): JsonResponse
    {
        $validated = $request->validate(['match_id' => 'required|integer', 'reason' => 'nullable|string|max:1000']);
        return response()->json($reviews->reject($this->match($listing, (int) $validated['match_id']), $request->user()->id, $validated['reason'] ?? null));
    }

    public function importMatch(Request $request, ExternalListingMatch $match, ListingReviewService $reviews): JsonResponse
    {
        $reviews->import($match, $request->user()->id);
        return response()->json(['message' => 'Listing imported.', 'match' => $this->formatMatch($match->refresh())], 201);
    }

    public function connectMatch(Request $request, ExternalListingMatch $match, ListingReviewService $reviews): JsonResponse
    {
        return response()->json(['message' => 'Listing connected.', 'match' => $this->formatMatch($reviews->connect($match, $request->user()->id))]);
    }

    public function rejectMatch(Request $request, ExternalListingMatch $match, ListingReviewService $reviews): JsonResponse
    {
        $validated = $request->validate(['reason' => 'nullable|string|max:1000']);
        return response()->json(['message' => 'Listing rejected.', 'match' => $this->formatMatch($reviews->reject($match, $request->user()->id, $validated['reason'] ?? null))]);
    }

    public function bulkImport(Request $request, ListingReviewService $reviews): JsonResponse
    {
        return $this->bulk($request, function ($match) use ($reviews, $request) {
            $reviews->import($match, $request->user()->id);
            return $match->refresh();
        });
    }

    public function bulkConnect(Request $request, ListingReviewService $reviews): JsonResponse
    {
        return $this->bulk($request, fn ($match) => $reviews->connect($match, $request->user()->id));
    }

    public function settings(DiscoverySettings $settings): JsonResponse
    {
        return response()->json($settings->get(request()->integer('agency_id') ?: null));
    }

    public function updateSettings(Request $request, DiscoverySettings $settings, DiscoveryAudit $audit): JsonResponse
    {
        $validated = $request->validate([
            'agency_id' => 'nullable|integer|exists:est8ads_agencies,id', 'enabled' => 'sometimes|boolean',
            'enabled' => 'sometimes|boolean', 'automation_running' => 'sometimes|boolean',
            'trigger' => ['sometimes', Rule::in(['immediate', 'scheduled', 'manual'])],
            'refresh_interval' => ['sometimes', Rule::in(['6_hours', '12_hours', 'daily', 'weekly'])],
            'save_threshold' => 'sometimes|numeric|min:0|max:100',
            'auto_connect_threshold' => 'sometimes|numeric|min:0|max:100',
            'connect_threshold' => 'sometimes|numeric|min:0|max:100',
            'minimum_data_confidence' => 'sometimes|numeric|min:0|max:100', 'freshness_days' => 'sometimes|integer|min:1|max:90',
            'result_limit' => 'sometimes|integer|min:1|max:500',
            'radius' => ['sometimes', Rule::in(['exact', 'city_nearby', 'region', 'country'])],
            'sources' => 'sometimes|array|max:20', 'sources.*' => 'string|max:50',
        ]);
        $agencyId = $validated['agency_id'] ?? null; unset($validated['agency_id']);
        $values = $settings->put($validated, $agencyId);
        $row = Setting::where('scope', $agencyId ? 'agency' : 'global')->where('scope_id', $agencyId ?: 0)->where('group', 'internet_discovery')->where('key', 'policy')->firstOrFail();
        $audit->record('internet_discovery.settings_updated', $row, $values, [], $request->user()?->id);
        return response()->json($values);
    }

    private function match(ExternalListing $listing, int $matchId): ExternalListingMatch
    {
        return ExternalListingMatch::whereKey($matchId)->where('external_listing_id', $listing->id)->firstOrFail();
    }

    private function bulk(Request $request, callable $action): JsonResponse
    {
        $validated = $request->validate(['match_ids' => 'required|array|min:1|max:100', 'match_ids.*' => 'integer|distinct']);
        $results = []; $errors = [];
        foreach (ExternalListingMatch::whereIn('id', $validated['match_ids'])->get() as $match) {
            try { $results[] = $action($match); } catch (\Throwable $exception) { report($exception); $errors[$match->id] = $exception->getMessage(); }
        }
        return response()->json([
            'message' => $errors === [] ? 'Selected listings updated.' : 'Some listings could not be updated.',
            'matches' => collect($results)->filter(fn ($result) => $result instanceof ExternalListingMatch)->map(fn ($match) => $this->formatMatch($match))->values(),
            'errors' => $errors,
        ], $errors === [] ? 200 : 207);
    }

    private function formatJob(DiscoveryJob $job): array
    {
        return app(DiscoveryPresenter::class)->job($job);
    }

    private function stats(): array
    {
        return app(DiscoveryPresenter::class)->stats();
    }

    private function formatMatch(ExternalListingMatch $match): array
    {
        return app(DiscoveryPresenter::class)->match($match);
    }
}
