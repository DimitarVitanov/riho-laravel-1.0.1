<?php

namespace App\Services\CompetitorIntelligence;

use App\Models\CompetitorEvent;
use App\Models\CompetitorDailyReport;
use App\Models\CompetitorDailyReportItem;
use App\Models\CompetitorDailyReportMetric;
use App\Services\AiService;
use Illuminate\Support\Facades\Log;

class EventAnalysisService
{
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function analyzeEvent(CompetitorEvent $event): void
    {
        $event->loadMissing('competitor');
        $prompt = $this->buildEventAnalysisPrompt($event);
        $provider = config('services.ai.default_provider', 'openai');
        $response = $this->aiService->generateJson($prompt, $provider, [
            'temperature' => 0.3,
            'max_tokens' => 1200,
        ]);

        if (!$response || empty($response['interpretation'])) {
            throw new \RuntimeException("AI returned no valid analysis for event {$event->id} using {$provider}");
        }

        $event->update([
            'ai_interpretation' => $response['interpretation'],
            'ai_opportunity' => $response['opportunity'] ?? null,
            'ai_action' => $response['recommended_action'] ?? null,
            'ai_confidence' => $response['confidence'] ?? null,
            'ai_evidence_event_ids' => $response['evidence_event_ids'] ?? [$event->id],
            'opportunity_status' => !empty($response['opportunity']) ? 'open' : null,
        ]);
    }

    public function generateDailyReport(int $agencyProfileId, \Carbon\Carbon $date): ?CompetitorDailyReport
    {
        $events = $this->getEventsForDate($agencyProfileId, $date);
        $metrics = $this->calculateDailyMetrics($events);

        $report = CompetitorDailyReport::updateOrCreate(
            [
                'agency_profile_id' => $agencyProfileId,
                'report_date' => $date->toDateString(),
            ],
            [
                'prompt_version' => 'v1.0',
                'ai_model' => 'gemini',
                'source_event_ids' => $events->pluck('id')->toArray(),
            ]
        );

        CompetitorDailyReportMetric::updateOrCreate(
            ['competitor_daily_report_id' => $report->id],
            $metrics
        );

        if ($events->isEmpty()) {
            $report->items()->delete();
            $report->update([
                'executive_summary' => 'No verified competitor changes were spotted on this date. Monitoring completed successfully.',
                'report_json' => ['status' => 'no_changes'],
            ]);

            return $report;
        }

        $prompt = $this->buildDailyReportPrompt($events, $metrics, $date);

        try {
            $response = $this->aiService->generateJson($prompt, 'gemini');

            if ($response) {
                $report->update([
                    'executive_summary' => $response['executive_summary'] ?? null,
                    'report_json' => $response,
                ]);

                $this->saveReportItems($report, $response);
            }
        } catch (\Exception $e) {
            Log::error("Daily report generation failed", ['error' => $e->getMessage()]);
        }

        return $report;
    }

    protected function getEventsForDate(int $agencyProfileId, \Carbon\Carbon $date): \Illuminate\Support\Collection
    {
        $competitorIds = \App\Models\Competitor::where('agency_profile_id', $agencyProfileId)
            ->where('is_active', true)
            ->pluck('id');

        return CompetitorEvent::whereIn('competitor_id', $competitorIds)
            ->whereDate('detected_at', $date)
            ->whereNotNull('verified_at')
            ->with('competitor')
            ->get();
    }

    protected function calculateDailyMetrics(\Illuminate\Support\Collection $events): array
    {
        return [
            'new_properties' => $events->where('event_type', 'new_property')->count(),
            'removed_properties' => $events->whereIn('event_type', ['property_removed', 'possibly_removed'])->count(),
            'price_increases' => $events->where('event_type', 'price_increase')->count(),
            'price_decreases' => $events->where('event_type', 'price_decrease')->count(),
            'new_seo_pages' => $events->whereIn('event_type', ['new_url', 'new_seo_page'])->where('entity_type', 'url')->count(),
            'new_blog_posts' => $events->where('event_type', 'new_blog_post')->count(),
            'new_reviews' => $events->where('event_type', 'new_review')->count(),
            'new_mentions' => $events->where('event_type', 'new_mention')->count(),
            'total_changes' => $events->count(),
        ];
    }

    protected function buildEventAnalysisPrompt(CompetitorEvent $event): string
    {
        $recentContext = CompetitorEvent::where('competitor_id', $event->competitor_id)
            ->where('id', '!=', $event->id)
            ->where('detected_at', '>=', $event->detected_at->copy()->subDays(30))
            ->where('detected_at', '<=', $event->detected_at)
            ->whereNotNull('verified_at')
            ->orderByDesc('detected_at')
            ->limit(15)
            ->get()
            ->map(fn (CompetitorEvent $relatedEvent) => [
                'event_id' => $relatedEvent->id,
                'event_type' => $relatedEvent->event_type,
                'detected_at' => $relatedEvent->detected_at->toIso8601String(),
                'old_value' => $relatedEvent->old_value_json,
                'new_value' => $relatedEvent->new_value_json,
                'fact' => $relatedEvent->fact_json,
            ])
            ->values()
            ->all();

        $eventData = [
            'event_id' => $event->id,
            'event_type' => $event->event_type,
            'competitor_name' => $event->competitor->name ?? 'Unknown',
            'detected_at' => $event->detected_at->toIso8601String(),
            'old_value' => $event->old_value_json,
            'new_value' => $event->new_value_json,
            'fact' => $event->fact_json,
            'evidence_url' => $event->evidence_url,
            'verified_competitor_events_previous_30_days' => $recentContext,
        ];

        $systemPrompt = <<<PROMPT
You are analyzing a competitor intelligence event for a real estate agency.
Provide a concise interpretation, opportunity, and recommended action based ONLY on the supplied verified facts.
Use the verified 30-day event context to identify repeated competitor behavior when evidence supports it.
For a new property, consider whether reviewing positioning, pricing, location coverage, or inventory overlap is useful, but never claim the agency has matching inventory because no agency inventory is supplied.
For a new location, category, or SEO page, explain the possible search-market opportunity and suggest reviewing whether a stronger page is warranted.
For price movement, explain the factual change and any supported repeated pattern.
Never invent facts or claim things not supported by the data.
Phrase strategic conclusions as hypotheses using words such as may, could, or suggests.

RETURN STRICT JSON:
{
  "interpretation": "Brief factual interpretation of what happened",
  "opportunity": "Business opportunity this creates (or null if none)",
  "recommended_action": "Specific action the agency should take (or null)",
  "confidence": 0-100,
  "evidence_event_ids": [event_id]
}
PROMPT;

        return $systemPrompt . "\n\nEVENT DATA:\n" . json_encode($eventData, JSON_PRETTY_PRINT);
    }

    protected function buildDailyReportPrompt(\Illuminate\Support\Collection $events, array $metrics, \Carbon\Carbon $date): string
    {
        $eventsJson = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'type' => $event->event_type,
                'competitor' => $event->competitor->name ?? 'Unknown',
                'old_value' => $event->old_value_json,
                'new_value' => $event->new_value_json,
                'fact' => $event->fact_json,
                'importance' => $event->importance_score,
            ];
        })->toArray();

        $systemPrompt = <<<PROMPT
SYSTEM:
Create the Villa Bit AI Daily Competitor Intelligence Report.
Use only supplied verified facts and pre-calculated metrics.
Do not repeat low-value noise.
Every conclusion and recommendation must cite evidence_event_ids.
Never claim SOLD without verified SOLD evidence.

USER:
REPORT_DATE: {$date->format('Y-m-d')}
METRICS: {json_encode($metrics)}
VERIFIED_EVENTS: {json_encode($eventsJson)}

RETURN STRICT JSON:
{
  "executive_summary": "2-3 sentence summary of the day's most important changes",
  "what_changed": [{"text": "...", "event_ids": []}],
  "why_it_matters": [{"text": "...", "evidence_event_ids": []}],
  "recommended_actions": [{"priority": "HIGH|MEDIUM|LOW", "action": "...", "reason": "...", "evidence_event_ids": []}]
}
PROMPT;

        return $systemPrompt;
    }

    protected function saveReportItems(CompetitorDailyReport $report, array $response): void
    {
        $report->items()->delete();

        $sortOrder = 0;

        if (!empty($response['what_changed'])) {
            foreach ($response['what_changed'] as $item) {
                CompetitorDailyReportItem::create([
                    'competitor_daily_report_id' => $report->id,
                    'item_type' => 'what_changed',
                    'content' => $item['text'] ?? $item,
                    'evidence_event_ids' => $item['event_ids'] ?? null,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }

        if (!empty($response['why_it_matters'])) {
            foreach ($response['why_it_matters'] as $item) {
                CompetitorDailyReportItem::create([
                    'competitor_daily_report_id' => $report->id,
                    'item_type' => 'why_it_matters',
                    'content' => $item['text'] ?? $item,
                    'evidence_event_ids' => $item['evidence_event_ids'] ?? null,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }

        if (!empty($response['recommended_actions'])) {
            foreach ($response['recommended_actions'] as $item) {
                CompetitorDailyReportItem::create([
                    'competitor_daily_report_id' => $report->id,
                    'item_type' => 'recommended_action',
                    'content' => $item['action'] ?? $item,
                    'priority' => strtolower($item['priority'] ?? 'medium'),
                    'reason' => $item['reason'] ?? null,
                    'evidence_event_ids' => $item['evidence_event_ids'] ?? null,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }
    }

    public function prioritizeEvents(\Illuminate\Support\Collection $events): \Illuminate\Support\Collection
    {
        return $events->sortByDesc(function ($event) {
            $baseScore = $event->importance_score ?? 50;

            $typeBonus = match ($event->event_type) {
                'new_property' => 20,
                'property_removed' => 15,
                'price_decrease' => 25,
                'price_increase' => 10,
                'new_seo_page' => 15,
                default => 0,
            };

            $recencyBonus = $event->detected_at->diffInHours(now()) < 6 ? 10 : 0;

            return $baseScore + $typeBonus + $recencyBonus;
        });
    }
}
