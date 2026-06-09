<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageLimit extends Model
{
    protected $fillable = [
        'agency_profile_id', 'period_start', 'period_end',
        'local_seo_pages_limit', 'local_seo_pages_used',
        'competitor_scans_limit', 'competitor_scans_used',
        'ai_search_freshness_updates_limit', 'ai_search_freshness_updates_used',
        'authority_review_updates_limit', 'authority_review_updates_used',
        'small_ai_content_actions_limit', 'small_ai_content_actions_used',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
        ];
    }

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function remaining(string $key): int
    {
        $limit = $this->{$key . '_limit'} ?? 0;
        $used = $this->{$key . '_used'} ?? 0;
        return max(0, $limit - $used);
    }

    public function usagePeriodMonth(): string
    {
        return $this->period_start->format('F');
    }

    public function usagePeriodYear(): int
    {
        return (int) $this->period_start->format('Y');
    }

    public function usagePeriodStatus(): string
    {
        if (now()->between($this->period_start, $this->period_end)) {
            return 'Active';
        }
        return now()->lt($this->period_start) ? 'Upcoming' : 'Expired';
    }

    public function nextResetDate(): string
    {
        return $this->period_end->addDay()->format('F j, Y');
    }
}
