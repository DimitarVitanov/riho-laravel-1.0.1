<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CompetitorDailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_profile_id',
        'report_date',
        'executive_summary',
        'report_json',
        'prompt_version',
        'ai_model',
        'source_event_ids',
    ];

    protected $casts = [
        'report_date' => 'date',
        'report_json' => 'array',
        'source_event_ids' => 'array',
    ];

    public function agencyProfile(): BelongsTo
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CompetitorDailyReportItem::class)->orderBy('sort_order');
    }

    public function metrics(): HasOne
    {
        return $this->hasOne(CompetitorDailyReportMetric::class);
    }

    public function whatChangedItems(): HasMany
    {
        return $this->items()->where('item_type', 'what_changed');
    }

    public function whyItMattersItems(): HasMany
    {
        return $this->items()->where('item_type', 'why_it_matters');
    }

    public function recommendedActions(): HasMany
    {
        return $this->items()->where('item_type', 'recommended_action');
    }

    public function getEventsAttribute()
    {
        if (empty($this->source_event_ids)) {
            return collect();
        }

        return CompetitorEvent::whereIn('id', $this->source_event_ids)
            ->with('competitor')
            ->orderByDesc('detected_at')
            ->get();
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('report_date', $date);
    }

    public function scopeForAgency($query, $agencyProfileId)
    {
        return $query->where('agency_profile_id', $agencyProfileId);
    }
}
