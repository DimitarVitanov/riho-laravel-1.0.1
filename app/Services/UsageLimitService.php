<?php

namespace App\Services;

use App\Models\AgencyProfile;
use App\Models\DailyUsageLog;
use App\Models\UsageLimit;

class UsageLimitService
{
    /**
     * Daily limits per feature (max uses per day)
     */
    protected array $dailyLimits = [
        'ai_search_ranking'          => 1,
        'local_seo_pages'            => 1,
        'competitor_scans'           => 1,
        'ai_search_freshness_updates'=> 1,
        'authority_review_updates'   => 1,
        'small_ai_content_actions'   => 5, // allow more small actions per day
    ];

    /**
     * Check if user can use a feature (both daily and monthly limits)
     */
    public function canUse(AgencyProfile $profile, string $feature): array
    {
        $today = now()->toDateString();

        // Check daily limit
        $dailyUsed = DailyUsageLog::where('agency_profile_id', $profile->id)
            ->where('feature_key', $feature)
            ->where('usage_date', $today)
            ->sum('count');

        $dailyLimit = $this->dailyLimits[$feature] ?? 1;

        if ($dailyUsed >= $dailyLimit) {
            return [
                'allowed' => false,
                'reason' => 'daily_limit_reached',
                'message' => __('messages.daily_limit_reached', ['feature' => $this->getFeatureLabel($feature)]),
                'daily_used' => $dailyUsed,
                'daily_limit' => $dailyLimit,
            ];
        }

        // Check monthly limit
        $usageLimit = $profile->currentUsageLimit;

        if (!$usageLimit) {
            return [
                'allowed' => false,
                'reason' => 'no_usage_limit',
                'message' => __('messages.no_usage_limit_configured'),
            ];
        }

        if (!$usageLimit->hasRemaining($feature)) {
            return [
                'allowed' => false,
                'reason' => 'monthly_limit_reached',
                'message' => __('messages.monthly_limit_reached', ['feature' => $this->getFeatureLabel($feature)]),
                'monthly_used' => $usageLimit->{$feature . '_used'},
                'monthly_limit' => $usageLimit->{$feature . '_limit'},
            ];
        }

        return [
            'allowed' => true,
            'daily_remaining' => $dailyLimit - $dailyUsed,
            'monthly_remaining' => $usageLimit->getRemaining($feature),
        ];
    }

    /**
     * Consume usage (both daily log and monthly counter)
     */
    public function consume(AgencyProfile $profile, string $feature, int $amount = 1): bool
    {
        $check = $this->canUse($profile, $feature);

        if (!$check['allowed']) {
            return false;
        }

        $today = now()->toDateString();

        // Log daily usage
        DailyUsageLog::updateOrCreate(
            [
                'agency_profile_id' => $profile->id,
                'feature_key' => $feature,
                'usage_date' => $today,
            ],
            []
        )->increment('count', $amount);

        // Increment monthly usage
        $usageLimit = $profile->currentUsageLimit;
        if ($usageLimit) {
            $usageLimit->consume($feature, $amount);
        }

        return true;
    }

    /**
     * Get usage status for dashboard display
     */
    public function getStatus(AgencyProfile $profile, string $feature): array
    {
        $today = now()->toDateString();

        $dailyUsed = DailyUsageLog::where('agency_profile_id', $profile->id)
            ->where('feature_key', $feature)
            ->where('usage_date', $today)
            ->sum('count');

        $dailyLimit = $this->dailyLimits[$feature] ?? 1;

        $usageLimit = $profile->currentUsageLimit;
        $monthlyUsed = $usageLimit ? ($usageLimit->{$feature . '_used'} ?? 0) : 0;
        $monthlyLimit = $usageLimit ? ($usageLimit->{$feature . '_limit'} ?? 0) : 0;

        return [
            'daily_used' => $dailyUsed,
            'daily_limit' => $dailyLimit,
            'daily_remaining' => max(0, $dailyLimit - $dailyUsed),
            'monthly_used' => $monthlyUsed,
            'monthly_limit' => $monthlyLimit,
            'monthly_remaining' => max(0, $monthlyLimit - $monthlyUsed),
            'can_use_today' => $dailyUsed < $dailyLimit && $monthlyUsed < $monthlyLimit,
        ];
    }

    /**
     * Get human-readable feature label
     */
    protected function getFeatureLabel(string $feature): string
    {
        return match($feature) {
            'ai_search_ranking' => 'AI Search Ranking',
            'local_seo_pages' => 'Local SEO Pages',
            'competitor_scans' => 'Competitor Scans',
            'ai_search_freshness_updates' => 'AI Freshness Updates',
            'authority_review_updates' => 'Authority Reviews',
            'small_ai_content_actions' => 'Small AI Actions',
            default => ucwords(str_replace('_', ' ', $feature)),
        };
    }

    /**
     * Set custom daily limit for a feature
     */
    public function setDailyLimit(string $feature, int $limit): void
    {
        $this->dailyLimits[$feature] = $limit;
    }
}
