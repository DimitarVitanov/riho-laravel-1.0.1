<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxiCountryReport extends Model
{
    public const INDEX_SLUG = 'index';

    protected $fillable = [
        'country',
        'country_slug',
        'locale',
        'iso2',
        'region',
        'report_number',
        'title',
        'meta_description',
        'canonical_url',
        'html_full',
        'source_file',
        'content_hash',
        'is_published',
        'refresh_interval_days',
        'last_generated_at',
        'next_refresh_at',
        'last_refresh_status',
        'last_refresh_note',
        'sections_updated',
        'ai_provider',
        'source_report_id',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'last_generated_at' => 'datetime',
            'next_refresh_at' => 'datetime',
        ];
    }

    public function sourceReport(): BelongsTo
    {
        return $this->belongsTo(self::class, 'source_report_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(self::class, 'source_report_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeCountries(Builder $query): Builder
    {
        return $query->where('country_slug', '!=', self::INDEX_SLUG);
    }

    public function scopeDueForRefresh(Builder $query): Builder
    {
        return $query->where('locale', 'en')
            ->where('is_published', true)
            ->where(function (Builder $q) {
                $q->whereNull('next_refresh_at')
                    ->orWhere('next_refresh_at', '<=', now());
            });
    }

    public function isIndex(): bool
    {
        return $this->country_slug === self::INDEX_SLUG;
    }

    public function publicUrl(string $locale = 'en'): string
    {
        $base = $locale === 'en' ? '/globaldata' : '/' . $locale . '/globaldata';

        return $this->isIndex() ? $base . '/' : $base . '/' . $this->country_slug . '/';
    }

    public static function findBySlug(string $slug, string $locale = 'en'): ?self
    {
        return static::query()
            ->where('country_slug', $slug)
            ->where('locale', $locale)
            ->first();
    }

    public static function findWithFallback(string $slug, string $locale = 'en'): ?self
    {
        return static::findBySlug($slug, $locale) ?? static::findBySlug($slug, 'en');
    }

    public function daysSinceUpdate(): ?int
    {
        return $this->last_generated_at?->diffInDays(now());
    }
}
