<?php

namespace App\Services\CompetitorIntelligence;

use App\Models\AgencyListing;
use App\Models\AgencyProfile;
use App\Models\AiAuthorityPage;
use App\Models\CompetitorEvent;
use App\Models\LocalSeoCampaign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompetitorOpportunityPageService
{
    public function create(CompetitorEvent $event, AgencyProfile $profile): array
    {
        if ($event->created_page_id && $page = $this->findCreatedPage($event, $profile)) {
            return [
                'feature' => $event->created_page_feature,
                'page' => $page,
                'created' => false,
            ];
        }

        $feature = $event->getSuggestedPageFeature();
        $title = $this->extractTitle($event);
        $location = $this->extractLocation($event, $profile, $title);
        $propertyType = $this->extractPropertyType($event, $title);
        $brief = $this->buildBrief($event, $title, $location, $propertyType);

        return DB::transaction(function () use ($event, $profile, $feature, $title, $location, $propertyType, $brief) {
            $page = $feature === 'ai_search_ranking'
                ? $this->createAiSearchPage($event, $profile, $title, $location, $propertyType, $brief)
                : $this->createLocalSeoPage($profile, $title, $location, $brief);

            $event->update([
                'created_page_feature' => $feature,
                'created_page_id' => $page->id,
                'opportunity_status' => 'actioned',
            ]);

            return [
                'feature' => $feature,
                'page' => $page,
                'created' => true,
            ];
        });
    }

    public function findCreatedPage(CompetitorEvent $event, AgencyProfile $profile): ?Model
    {
        return match ($event->created_page_feature) {
            'ai_search_ranking' => AiAuthorityPage::where('agency_profile_id', $profile->id)->find($event->created_page_id),
            'local_seo_presence_boost' => LocalSeoCampaign::where('agency_profile_id', $profile->id)->find($event->created_page_id),
            default => null,
        };
    }

    protected function createLocalSeoPage(AgencyProfile $profile, string $title, string $location, string $brief): LocalSeoCampaign
    {
        $campaign = LocalSeoCampaign::create([
            'agency_profile_id' => $profile->id,
            'name' => $title,
            'primary_city' => $location,
            'country' => $profile->country ?: 'Croatia',
            'coverage_area' => 5,
            'coverage_unit' => 'km',
            'target_places' => [],
            'positioning_note' => $brief,
            'page_slug' => '/' . Str::slug($title) . '/',
            'page_settings' => [
                'show_lead_magnet' => true,
                'show_faq' => true,
                'show_listings' => true,
                'featured_listings_percent' => 10,
                'regular_listings_percent' => 6,
                'approval_status' => 'pending',
            ],
            'status' => 'draft',
        ]);

        $listingIds = AgencyListing::where('agency_profile_id', $profile->id)
            ->where('status', 'active')
            ->where(function ($query) use ($location) {
                $query->where('location', 'like', "%{$location}%")
                    ->orWhere('primary_city', 'like', "%{$location}%");
            })
            ->pluck('id');

        if ($listingIds->isNotEmpty()) {
            $campaign->listings()->syncWithoutDetaching($listingIds);
        }

        return $campaign;
    }

    protected function createAiSearchPage(CompetitorEvent $event, AgencyProfile $profile, string $title, string $location, ?string $propertyType, string $brief): AiAuthorityPage
    {
        $slug = Str::slug('ai-' . $location . '-' . $title);
        if (AiAuthorityPage::where('agency_profile_id', $profile->id)->where('slug', $slug)->exists()) {
            $slug .= '-' . $event->id;
        }

        return AiAuthorityPage::create([
            'agency_profile_id' => $profile->id,
            'name' => $title,
            'slug' => $slug,
            'target_city' => $location,
            'country' => $profile->country ?: 'Croatia',
            'property_type' => $propertyType,
            'page_type' => 'property',
            'generation_brief' => $brief,
            'status' => 'draft',
        ]);
    }

    protected function extractTitle(CompetitorEvent $event): string
    {
        $title = $event->new_value_json['title']
            ?? $event->fact_json['title']
            ?? $event->fact_json['page_title']
            ?? null;

        if (!$title && $event->evidence_url) {
            $path = trim((string) parse_url($event->evidence_url, PHP_URL_PATH), '/');
            $title = Str::title(str_replace(['-', '_', '/'], ' ', $path));
        }

        $title = trim((string) ($title ?: 'Competitor Opportunity Page'), " \t\n\r\0\x0B\"'“”");

        return Str::limit($title, 255, '');
    }

    protected function extractLocation(CompetitorEvent $event, AgencyProfile $profile, string $title): string
    {
        $location = $event->new_value_json['target_city']
            ?? $event->new_value_json['city']
            ?? $event->new_value_json['location']
            ?? $event->fact_json['target_city']
            ?? $event->fact_json['city']
            ?? $event->fact_json['location']
            ?? null;

        if (!$location && preg_match('/\b(?:in|at|near)\s+([\p{L}\p{M}][\p{L}\p{M}\s.\'-]{1,80})$/iu', $title, $match)) {
            $location = $match[1];
        }

        if (!$location && preg_match('/\b(?:guide|report|analysis|faq|overview)\s+for\s+([\p{L}\p{M}][\p{L}\p{M}\s.\'-]{1,80})$/iu', $title, $match)) {
            $location = $match[1];
        }

        if (!$location) {
            $context = implode(' ', array_filter([$event->ai_opportunity, $event->ai_action]));
            if (preg_match('/\bin\s+([\p{L}\p{M}][\p{L}\p{M}\s.\'-]{1,50}?)(?:\s+landing page|\s+page|\s+market|[.,]|$)/iu', $context, $match)) {
                $location = $match[1];
            }
        }

        $location = trim(explode(',', (string) ($location ?: $profile->target_city ?: $profile->city ?: 'Croatia'))[0]);

        return Str::limit($location, 255, '');
    }

    protected function extractPropertyType(CompetitorEvent $event, string $title): ?string
    {
        $propertyType = $event->new_value_json['property_type'] ?? $event->fact_json['property_type'] ?? null;
        if ($propertyType) {
            return Str::limit((string) $propertyType, 100, '');
        }

        return match (true) {
            preg_match('/\bvillas?\b/iu', $title) === 1 => 'Villas',
            preg_match('/\bapartments?\b/iu', $title) === 1 => 'Apartments',
            preg_match('/\bhouses?|homes?\b/iu', $title) === 1 => 'Houses',
            preg_match('/\bland|plots?\b/iu', $title) === 1 => 'Land',
            preg_match('/\bcommercial\b/iu', $title) === 1 => 'Commercial property',
            default => 'Real estate',
        };
    }

    protected function buildBrief(CompetitorEvent $event, string $title, string $location, ?string $propertyType): string
    {
        return implode("\n", array_filter([
            "Create a stronger {$propertyType} page for {$location} based on this verified competitor intelligence opportunity.",
            "Page concept: {$title}",
            $event->ai_opportunity ? 'Opportunity: ' . $event->ai_opportunity : null,
            $event->ai_action ? 'Recommended action: ' . $event->ai_action : null,
            $event->ai_interpretation ? 'Intelligence context: ' . $event->ai_interpretation : null,
            $event->competitor ? 'Observed competitor: ' . $event->competitor->name : null,
            $event->evidence_url ? 'Evidence URL: ' . $event->evidence_url : null,
            'Use only defensible agency data. Include useful market context, buyer questions, matching agency inventory when available, and a clear next step.',
        ]));
    }
}
