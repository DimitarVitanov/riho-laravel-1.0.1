<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Competitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_profile_id',
        'name',
        'legal_name',
        'country',
        'website_url',
        'normalized_domain',
        'google_place_id',
        'google_maps_url',
        'is_active',
        'include_in_daily_report',
        'include_in_comparison',
        'priority',
        'scan_profile',
        'monitoring_sources',
        'last_scan_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'include_in_daily_report' => 'boolean',
        'include_in_comparison' => 'boolean',
        'monitoring_sources' => 'array',
        'last_scan_at' => 'datetime',
    ];

    public function agencyProfile(): BelongsTo
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function aliases(): HasMany
    {
        return $this->hasMany(CompetitorAlias::class);
    }

    public function identifiers(): HasMany
    {
        return $this->hasMany(CompetitorIdentifier::class);
    }

    public function sources(): HasMany
    {
        return $this->hasMany(CompetitorSource::class);
    }

    public function sourceSettings(): HasMany
    {
        return $this->hasMany(CompetitorSourceSetting::class);
    }

    public function urls(): HasMany
    {
        return $this->hasMany(CompetitorUrl::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(CompetitorProperty::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(CompetitorEvent::class);
    }

    public function scanRuns(): HasMany
    {
        return $this->hasMany(CompetitorScanRun::class);
    }

    public function mentions(): HasMany
    {
        return $this->hasMany(CompetitorMention::class);
    }

    public function googleMetrics(): HasMany
    {
        return $this->hasMany(CompetitorGoogleMetric::class);
    }

    public function latestGoogleMetric(): HasOne
    {
        return $this->hasOne(CompetitorGoogleMetric::class)->latestOfMany('captured_at');
    }

    public function strategySummaries(): HasMany
    {
        return $this->hasMany(CompetitorStrategySummary::class);
    }

    public function discoveryCandidates(): HasMany
    {
        return $this->hasMany(CompetitorDiscoveryCandidate::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForAgency($query, $agencyProfileId)
    {
        return $query->where('agency_profile_id', $agencyProfileId);
    }

    public function getActiveSourceTypesAttribute(): array
    {
        return $this->sources()
            ->where('status', 'active')
            ->pluck('type')
            ->unique()
            ->values()
            ->toArray();
    }

    public function getTodayEventsCountAttribute(): int
    {
        return $this->events()
            ->whereDate('detected_at', today())
            ->count();
    }

    public function getLastSuccessfulScanAttribute()
    {
        return $this->scanRuns()
            ->where('status', 'success')
            ->latest('finished_at')
            ->first();
    }
}
