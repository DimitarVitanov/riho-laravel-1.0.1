<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_id',
        'competitor_source_id',
        'event_type',
        'entity_type',
        'entity_id',
        'detected_at',
        'verified_at',
        'old_value_json',
        'new_value_json',
        'fact_json',
        'evidence_url',
        'confidence',
        'importance_score',
        'ai_interpretation',
        'ai_opportunity',
        'ai_action',
        'ai_confidence',
        'ai_evidence_event_ids',
        'ai_priority',
        'opportunity_status',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'verified_at' => 'datetime',
        'old_value_json' => 'array',
        'new_value_json' => 'array',
        'fact_json' => 'array',
        'confidence' => 'integer',
        'importance_score' => 'integer',
        'ai_confidence' => 'integer',
        'ai_evidence_event_ids' => 'array',
        'ai_priority' => 'integer',
    ];

    public function competitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(CompetitorSource::class, 'competitor_source_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(CompetitorProperty::class, 'entity_id');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at');
    }

    public function scopeOfType($query, string $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('detected_at', today());
    }

    public function scopeWithOpenOpportunity($query)
    {
        return $query->where('opportunity_status', 'open');
    }

    public function scopePriceChanges($query)
    {
        return $query->whereIn('event_type', ['price_increase', 'price_decrease']);
    }

    public function scopePropertyEvents($query)
    {
        return $query->where('entity_type', 'property');
    }

    public function getEventTypeLabel(): string
    {
        return match($this->event_type) {
            'new_property' => 'New Property',
            'property_removed' => 'Property Removed',
            'possibly_removed' => 'Possibly Removed',
            'price_increase' => 'Price Increase',
            'price_decrease' => 'Price Drop',
            'description_changed' => 'Description Changed',
            'images_added' => 'Images Added',
            'images_removed' => 'Images Removed',
            'new_url' => 'New URL',
            'url_removed' => 'URL Removed',
            'title_changed' => 'Title Changed',
            'meta_description_changed' => 'Meta Description Changed',
            'h1_changed' => 'H1 Changed',
            'content_changed' => 'Content Changed',
            'schema_changed' => 'Schema Changed',
            'cta_changed' => 'CTA Changed',
            'new_seo_page' => 'New SEO Page',
            'new_blog_post' => 'New Blog Post',
            'new_review' => 'New Review',
            'rating_changed' => 'Rating Changed',
            'new_mention' => 'New Mention',
            'new_backlink' => 'New Backlink',
            default => ucfirst(str_replace('_', ' ', $this->event_type)),
        };
    }

    /**
     * Build a human-readable description of the event from its stored JSON data.
     * Falls back to a readable URL or AI interpretation when specifics are missing.
     */
    public function getDisplayTitle(): string
    {
        if (in_array($this->event_type, ['price_increase', 'price_decrease'], true)) {
            $oldPrice = (float) ($this->old_value_json['price'] ?? 0);
            $newPrice = (float) ($this->new_value_json['price'] ?? 0);
            $difference = abs((float) ($this->new_value_json['difference'] ?? ($newPrice - $oldPrice)));
            $percent = abs((float) ($this->new_value_json['percent_change'] ?? 0));
            $title = $this->fact_json['property_title'] ?? $this->property?->latestSnapshot?->title ?? 'Property';
            $verb = $this->event_type === 'price_decrease' ? 'reduced' : 'increased';
            $suffix = $difference > 0 ? ' by ' . $this->formatMoneyExact($difference) : '';
            $suffix .= $percent > 0 ? ' (' . ($this->event_type === 'price_decrease' ? '-' : '+') . rtrim(rtrim(number_format($percent, 1), '0'), '.') . '%)' : '';

            return "{$title} {$verb}{$suffix}";
        }

        return $this->getEventTypeLabel();
    }

    public function getDescription(): string
    {
        $old = $this->old_value_json ?? [];
        $new = $this->new_value_json ?? [];

        switch ($this->event_type) {
            case 'price_decrease':
            case 'price_increase':
                $o = $old['price'] ?? null;
                $n = $new['price'] ?? null;
                if ($o && $n) {
                    $description = $this->formatMoneyExact($o) . ' → ' . $this->formatMoneyExact($n);
                    if ($this->property?->first_detected_at) {
                        $days = $this->property->first_detected_at->diffInDays($this->detected_at);
                        $description .= ' · First listed ' . $days . ' ' . ($days === 1 ? 'day' : 'days') . ' ago';
                    }
                    return $description;
                }
                break;

            case 'new_property':
                $parts = array_filter([
                    $new['title'] ?? null,
                    $new['location'] ?? $new['city'] ?? null,
                    isset($new['price']) ? $this->formatMoney($new['price']) : null,
                ]);
                if (!empty($parts)) {
                    return implode(' · ', $parts);
                }
                return $this->readableUrl($new['url'] ?? $this->evidence_url) ?? 'New property listing detected';

            case 'property_removed':
            case 'possibly_removed':
                return $new['title'] ?? $old['title'] ?? $this->readableUrl($this->evidence_url) ?? 'Listing no longer found';

            case 'new_url':
            case 'new_seo_page':
            case 'new_blog_post':
                if (!empty($new['title'])) {
                    return '"' . $new['title'] . '" discovered via sitemap';
                }
                $readable = $this->readableUrl($new['url'] ?? $this->evidence_url);
                return $readable ? 'Discovered: ' . $readable : 'New page discovered via sitemap';

            case 'url_removed':
                $readable = $this->readableUrl($old['url'] ?? $this->evidence_url);
                return $readable ? 'Removed: ' . $readable : 'Page no longer available';

            case 'rating_changed':
                $o = $old['rating'] ?? null;
                $n = $new['rating'] ?? null;
                if ($o !== null && $n !== null) {
                    return 'Google rating: ' . $o . ' → ' . $n;
                }
                break;

            case 'new_review':
                $o = $old['review_count'] ?? null;
                $n = $new['review_count'] ?? null;
                if ($o !== null && $n !== null) {
                    return 'Google review count: ' . $o . ' → ' . $n;
                }
                break;

            case 'title_changed':
            case 'h1_changed':
            case 'meta_description_changed':
            case 'content_changed':
                $n = $new['value'] ?? $new['text'] ?? null;
                $date = $this->detected_at->format('j F Y');
                if ($n) {
                    return "Updated on {$date}: Changed to: \"" . \Illuminate\Support\Str::limit($n, 90) . '"';
                }
                return "Updated on {$date}: page content changed";

            case 'cta_changed':
                return $new['value'] ?? $new['text'] ?? 'Call-to-action content changed on the page';

            case 'new_mention':
            case 'new_backlink':
                $readable = $this->readableUrl($new['url'] ?? $this->evidence_url);
                return $readable ? 'Mentioned on: ' . $readable : 'New external mention detected';
        }

        return $this->ai_interpretation
            ?? $this->readableUrl($new['url'] ?? $this->evidence_url)
            ?? 'Event detected';
    }

    /**
     * Turn a URL into a short, readable label from its path segments.
     */
    protected function readableUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            return $url;
        }

        $segments = array_values(array_filter(explode('/', $path), function ($s) {
            if ($s === '') {
                return false;
            }
            // Drop numeric IDs (e.g. 300611016-72) and locale segments (e.g. hr-hr)
            if (preg_match('/^\d[\d-]*$/', $s)) {
                return false;
            }
            if (preg_match('/^[a-z]{2}-[a-z]{2}$/i', $s)) {
                return false;
            }
            return true;
        }));

        $segments = array_slice($segments, -3);

        if (empty($segments)) {
            return parse_url($url, PHP_URL_HOST) ?: $url;
        }

        $label = implode(' · ', array_map(function ($s) {
            return \Illuminate\Support\Str::title(str_replace(['-', '_'], ' ', urldecode($s)));
        }, $segments));

        return $label;
    }

    /**
     * Format a numeric value as a compact currency string (€1.75M, €690K, €500).
     */
    protected function formatMoney($value): string
    {
        $n = (float) preg_replace('/[^0-9.]/', '', (string) $value);

        if ($n >= 1000000) {
            return '€' . rtrim(rtrim(number_format($n / 1000000, 2), '0'), '.') . 'M';
        }
        if ($n >= 1000) {
            return '€' . number_format($n / 1000, 0) . 'K';
        }

        return '€' . number_format($n, 0);
    }

    protected function formatMoneyExact($value): string
    {
        $number = (float) preg_replace('/[^0-9.]/', '', (string) $value);
        return '€' . number_format($number, $number == floor($number) ? 0 : 2);
    }

    public function getEventIcon(): string
    {
        return match($this->event_type) {
            'new_property' => 'home-plus',
            'property_removed', 'possibly_removed' => 'home-minus',
            'price_increase' => 'trending-up',
            'price_decrease' => 'trending-down',
            'new_url', 'new_seo_page' => 'link',
            'new_blog_post' => 'file-text',
            'new_review', 'rating_changed' => 'star',
            'new_mention', 'new_backlink' => 'external-link',
            default => 'activity',
        };
    }

    public function getSecondaryBadge(): ?array
    {
        if ($this->event_type !== 'new_url') {
            return null;
        }

        $pageType = $this->new_value_json['page_type'] ?? null;
        $url = strtolower($this->new_value_json['url'] ?? $this->evidence_url ?? '');

        if (!$pageType || $pageType === 'other') {
            $pageType = match (true) {
                preg_match('/\/(property|properties|listing|listings|listinzi|nekretnina|nekretnine|vila|vile|villa|villas|apartment|apartments|stan|stanovi|kuca|kuce|house|houses|oglas)\b/i', $url) === 1 => 'property_listing',
                preg_match('/\/(location|area|region|grad|mjesto|lokacija|split|dubrovnik|zagreb|zadar|sibenik|primosten)\b/i', $url) === 1 => 'location_page',
                preg_match('/\/(blog|news|vijesti|novosti|article|clanak)\b/i', $url) === 1 => 'blog_post',
                preg_match('/\/(team|about|o-nama|tim|agent|agents)\b/i', $url) === 1 => 'team',
                preg_match('/\/(contact|kontakt)\b/i', $url) === 1 => 'contact',
                preg_match('/\/(faq|frequently-asked-questions)\b/i', $url) === 1 => 'faq',
                default => $pageType,
            };
        }

        return match ($pageType) {
            'property_detail' => ['label' => 'PROPERTY', 'color' => 'green'],
            'property_listing' => ['label' => 'PROPERTY LISTING', 'color' => 'green'],
            'location_page' => ['label' => 'LOCATION PAGE', 'color' => 'blue'],
            'blog_post' => ['label' => 'BLOG POST', 'color' => 'purple'],
            'homepage' => ['label' => 'HOMEPAGE', 'color' => 'dark'],
            'team' => ['label' => 'TEAM PAGE', 'color' => 'dark'],
            'contact' => ['label' => 'CONTACT PAGE', 'color' => 'dark'],
            'faq' => ['label' => 'FAQ PAGE', 'color' => 'blue'],
            default => null,
        };
    }

    public function getEventColor(): string
    {
        return match($this->event_type) {
            'new_property' => 'green',
            'property_removed', 'possibly_removed' => 'red',
            'price_increase' => 'amber',
            'price_decrease' => 'red',
            'new_seo_page', 'new_blog_post' => 'blue',
            'new_review', 'rating_changed' => 'purple',
            default => 'gray',
        };
    }
}
