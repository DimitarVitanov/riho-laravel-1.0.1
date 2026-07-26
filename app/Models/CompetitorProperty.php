<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CompetitorProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_id',
        'competitor_url_id',
        'external_reference',
        'canonical_url',
        'current_status',
        'first_detected_at',
        'last_seen_at',
        'removed_at',
    ];

    protected $casts = [
        'first_detected_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'removed_at' => 'datetime',
    ];

    public function competitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class);
    }

    public function url(): BelongsTo
    {
        return $this->belongsTo(CompetitorUrl::class, 'competitor_url_id');
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(CompetitorPropertySnapshot::class);
    }

    public function latestSnapshot(): HasOne
    {
        return $this->hasOne(CompetitorPropertySnapshot::class)->latestOfMany('captured_at');
    }

    public function events(): HasMany
    {
        return $this->hasMany(CompetitorEvent::class, 'entity_id')
            ->where('entity_type', 'property');
    }

    public function scopeActive($query)
    {
        return $query->where('current_status', 'active');
    }

    public function scopeRemoved($query)
    {
        return $query->whereIn('current_status', ['removed', 'sold', 'unlisted']);
    }

    public function scopeNewToday($query)
    {
        return $query->whereDate('first_detected_at', today());
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->latestSnapshot?->title) {
            return $this->latestSnapshot->title;
        }

        if ($this->external_reference) {
            return $this->external_reference;
        }

        $url = $this->canonical_url ?? $this->url?->url;
        $segments = array_values(array_filter(explode('/', trim((string) parse_url($url, PHP_URL_PATH), '/'))));
        $listingIndex = array_search('listinzi', $segments, true);

        if ($listingIndex !== false && isset($segments[$listingIndex + 3])) {
            $type = $this->formatUrlSegment($segments[$listingIndex + 1]);
            $location = $this->formatUrlSegment($segments[$listingIndex + 3]);

            return "{$type} in {$location}";
        }

        return 'Property listing';
    }

    protected function formatUrlSegment(string $segment): string
    {
        if (preg_match('//u', $segment) !== 1) {
            return 'Property';
        }

        return ucfirst(str_replace('-', ' ', $segment));
    }

    public function getDisplayFirstDetectedAtAttribute()
    {
        if ($this->first_detected_at && $this->last_seen_at && $this->first_detected_at->greaterThan($this->last_seen_at)) {
            return $this->last_seen_at;
        }

        return $this->first_detected_at;
    }

    public function getListingLifetimeDaysAttribute(): ?int
    {
        if (!$this->first_detected_at) {
            return null;
        }

        $endDate = $this->removed_at ?? $this->last_seen_at ?? now();
        return $this->first_detected_at->diffInDays($endDate);
    }

    public function getCurrentPriceAttribute()
    {
        return $this->latestSnapshot?->price;
    }

    public function getCurrentCurrencyAttribute()
    {
        return $this->latestSnapshot?->currency ?? 'EUR';
    }
}
