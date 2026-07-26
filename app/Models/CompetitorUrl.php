<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CompetitorUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_id',
        'competitor_source_id',
        'url',
        'canonical_url',
        'page_type',
        'status',
        'first_detected_at',
        'last_seen_at',
        'sitemap_lastmod',
    ];

    protected $casts = [
        'first_detected_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'sitemap_lastmod' => 'datetime',
    ];

    public function competitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(CompetitorSource::class, 'competitor_source_id');
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(CompetitorUrlSnapshot::class);
    }

    public function latestSnapshot(): HasOne
    {
        return $this->hasOne(CompetitorUrlSnapshot::class)->latestOfMany('captured_at');
    }

    public function property(): HasOne
    {
        return $this->hasOne(CompetitorProperty::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOfType($query, string $pageType)
    {
        return $query->where('page_type', $pageType);
    }
}
