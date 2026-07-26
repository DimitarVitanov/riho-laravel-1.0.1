<?php

namespace App\Services\CompetitorIntelligence;

use App\Models\Competitor;
use App\Models\CompetitorAlias;
use App\Models\CompetitorIdentifier;
use App\Models\CompetitorSource;
use App\Models\CompetitorSourceSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompetitorService
{
    public function create(array $data): Competitor
    {
        return DB::transaction(function () use ($data) {
            $competitor = Competitor::create([
                'agency_profile_id' => $data['agency_profile_id'],
                'name' => $data['name'],
                'legal_name' => $data['legal_name'] ?? null,
                'primary_market' => $data['primary_market'] ?? null,
                'country' => $data['country'] ?? null,
                'website_url' => $data['website_url'],
                'normalized_domain' => $this->normalizeDomain($data['website_url']),
                'google_place_id' => $this->normalizeGooglePlaceId($data['google_place_id'] ?? null),
                'google_maps_url' => $data['google_maps_url'] ?? null,
                'is_active' => $data['is_active'] ?? true,
                'include_in_daily_report' => $data['include_in_daily_report'] ?? true,
                'include_in_comparison' => $data['include_in_comparison'] ?? true,
                'priority' => $data['priority'] ?? 'normal',
                'scan_profile' => $data['scan_profile'] ?? 'full',
                'monitoring_sources' => $data['monitoring_sources'] ?? [],
            ]);

            if (!empty($data['aliases'])) {
                foreach ($data['aliases'] as $alias) {
                    $competitor->aliases()->create([
                        'alias' => $alias,
                        'is_user_confirmed' => true,
                        'confidence' => 100,
                    ]);
                }
            }

            if (!empty($data['identifiers'])) {
                foreach ($data['identifiers'] as $identifier) {
                    $this->addIdentifier($competitor, $identifier['type'], $identifier['value']);
                }
            }

            $this->initializeSourceSettings($competitor);
            $this->createInitialSource($competitor);

            return $competitor;
        });
    }

    public function update(Competitor $competitor, array $data): Competitor
    {
        return DB::transaction(function () use ($competitor, $data) {
            $updateData = array_filter([
                'name' => $data['name'] ?? null,
                'legal_name' => $data['legal_name'] ?? null,
                'primary_market' => $data['primary_market'] ?? null,
                'country' => $data['country'] ?? null,
                'google_place_id' => $this->normalizeGooglePlaceId($data['google_place_id'] ?? null),
                'google_maps_url' => $data['google_maps_url'] ?? null,
                'is_active' => $data['is_active'] ?? null,
                'include_in_daily_report' => $data['include_in_daily_report'] ?? null,
                'include_in_comparison' => $data['include_in_comparison'] ?? null,
                'priority' => $data['priority'] ?? null,
            ], fn($v) => $v !== null);

            if (isset($data['website_url']) && $data['website_url'] !== $competitor->website_url) {
                $updateData['website_url'] = $data['website_url'];
                $updateData['normalized_domain'] = $this->normalizeDomain($data['website_url']);
            }

            $competitor->update($updateData);

            return $competitor->fresh();
        });
    }

    public function addIdentifier(Competitor $competitor, string $type, string $value): CompetitorIdentifier
    {
        $normalizedValue = $this->normalizeIdentifier($type, $value);

        return $competitor->identifiers()->create([
            'type' => $type,
            'value' => $value,
            'normalized_value' => $normalizedValue,
            'display_value' => $this->formatIdentifierDisplay($type, $value),
        ]);
    }

    public function addAlias(Competitor $competitor, string $alias, bool $userConfirmed = false, int $confidence = 50): CompetitorAlias
    {
        return $competitor->aliases()->create([
            'alias' => $alias,
            'is_user_confirmed' => $userConfirmed,
            'confidence' => $confidence,
        ]);
    }

    public function initializeSourceSettings(Competitor $competitor): void
    {
        $sourceTypes = ['website', 'sitemaps', 'properties', 'google', 'portals', 'mentions'];

        foreach ($sourceTypes as $type) {
            CompetitorSourceSetting::firstOrCreate([
                'competitor_id' => $competitor->id,
                'source_type' => $type,
            ], [
                'is_enabled' => true,
            ]);
        }
    }

    public function createInitialSource(Competitor $competitor): CompetitorSource
    {
        return $competitor->sources()->create([
            'type' => 'website',
            'url' => $competitor->website_url,
            'status' => 'pending',
        ]);
    }

    protected function normalizeGooglePlaceId(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        if (preg_match('/ChI[A-Za-z0-9_-]+/', $value, $match)) {
            return $match[0];
        }

        return trim($value);
    }

    public function normalizeDomain(string $url): string
    {
        $parsed = parse_url($url);
        $host = $parsed['host'] ?? $url;

        $host = preg_replace('/^www\./', '', $host);
        $host = strtolower(trim($host));

        return $host;
    }

    protected function normalizeIdentifier(string $type, string $value): string
    {
        return match ($type) {
            'phone' => preg_replace('/[^0-9+]/', '', $value),
            'email' => strtolower(trim($value)),
            'email_domain' => strtolower(trim($value)),
            'vat_number', 'oib' => preg_replace('/[^A-Z0-9]/', '', strtoupper($value)),
            'person_name' => Str::lower(trim($value)),
            default => trim($value),
        };
    }

    protected function formatIdentifierDisplay(string $type, string $value): string
    {
        return match ($type) {
            'phone' => $this->formatPhoneDisplay($value),
            'email' => strtolower(trim($value)),
            default => $value,
        };
    }

    protected function formatPhoneDisplay(string $phone): string
    {
        $digits = preg_replace('/[^0-9+]/', '', $phone);

        if (str_starts_with($digits, '+385')) {
            $local = substr($digits, 4);
            return '+385 ' . substr($local, 0, 2) . ' ' . substr($local, 2, 3) . ' ' . substr($local, 5);
        }

        return $digits;
    }

    public function getStatistics(Competitor $competitor): array
    {
        $trackedProperties = $competitor->properties()->active()->count();
        $removedProperties90d = $competitor->properties()
            ->removed()
            ->where('removed_at', '>=', now()->subDays(90))
            ->count();

        $avgLifetime = $competitor->properties()
            ->whereNotNull('removed_at')
            ->whereNotNull('first_detected_at')
            ->selectRaw('AVG(DATEDIFF(removed_at, first_detected_at)) as avg_days')
            ->value('avg_days');

        $events30d = $competitor->events()
            ->where('detected_at', '>=', now()->subDays(30))
            ->count();

        return [
            'tracked_properties' => $trackedProperties,
            'removed_90d' => $removedProperties90d,
            'avg_listing_lifetime_days' => $avgLifetime ? round($avgLifetime) : null,
            'events_30d' => $events30d,
        ];
    }

    public function getTodayMetrics(int $agencyProfileId): array
    {
        $competitors = Competitor::where('agency_profile_id', $agencyProfileId)
            ->where('is_active', true)
            ->get();

        $competitorIds = $competitors->pluck('id');

        $baseQuery = fn() => \App\Models\CompetitorEvent::whereIn('competitor_id', $competitorIds)
            ->whereDate('detected_at', today());

        // Count new_url events that look like property listings (contain /listinzi/, /nekretnine/, etc.)
        $propertyUrlPatterns = ['listinzi', 'nekretnine', 'property', 'villa', 'apartment', 'house', 'kuca', 'stan'];
        $newPropertyUrls = (clone $baseQuery())
            ->where('event_type', 'new_url')
            ->where(function ($q) use ($propertyUrlPatterns) {
                foreach ($propertyUrlPatterns as $pattern) {
                    $q->orWhere('evidence_url', 'like', "%{$pattern}%");
                }
            })
            ->count();

        // Count new_url events that look like SEO/category pages
        $seoUrlPatterns = ['lokacija', 'location', 'category', 'prodaja', 'najam', 'rent', 'sale'];
        $newSeoUrls = (clone $baseQuery())
            ->where('event_type', 'new_url')
            ->whereJsonContains('new_value_json->page_type', 'location_page')
            ->orWhere(function ($q) use ($seoUrlPatterns, $competitorIds) {
                $q->whereIn('competitor_id', $competitorIds)
                  ->whereDate('detected_at', today())
                  ->where('event_type', 'new_url')
                  ->whereJsonContains('new_value_json->page_type', 'category');
            })
            ->count();

        return [
            // Row 1
            'new_properties' => (clone $baseQuery())
                ->where('event_type', 'new_property')
                ->count() + $newPropertyUrls,
            'price_changes' => (clone $baseQuery())
                ->whereIn('event_type', ['price_increase', 'price_decrease'])
                ->count(),
            'removed_properties' => (clone $baseQuery())
                ->whereIn('event_type', ['property_removed', 'possibly_removed'])
                ->count(),
            'total_changes' => (clone $baseQuery())->count(),

            // Row 2
            'new_seo_pages' => (clone $baseQuery())
                ->where('event_type', 'new_seo_page')
                ->count() + $newSeoUrls,
            'new_content' => (clone $baseQuery())
                ->whereIn('event_type', ['new_blog_post', 'content_changed'])
                ->count(),
            'review_signals' => (clone $baseQuery())
                ->whereIn('event_type', ['new_review', 'rating_changed'])
                ->count(),
            'external_mentions' => (clone $baseQuery())
                ->whereIn('event_type', ['new_mention', 'new_backlink'])
                ->count(),
        ];
    }
}
