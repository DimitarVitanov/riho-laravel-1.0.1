<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitorScanResult extends Model
{
    protected $fillable = [
        'agency_profile_id', 'competitor_website_id', 'scan_type', 'title',
        'summary', 'details_json', 'recommended_action', 'recommended_content',
        'status', 'scanned_at',
    ];

    protected function casts(): array
    {
        return [
            'details_json' => 'array',
            'scanned_at' => 'datetime',
        ];
    }

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function competitorWebsite()
    {
        return $this->belongsTo(CompetitorWebsite::class);
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scanTypeLabel(): string
    {
        return match($this->scan_type) {
            'new_properties' => 'New Properties',
            'seo_pages' => 'SEO Pages',
            'blog' => 'Blog & Content',
            'price_movement' => 'Price Movement',
            'gbp_reviews' => 'Google Reviews',
            'weakness_detection' => 'Competitor Weakness',
            default => ucfirst(str_replace('_', ' ', $this->scan_type)),
        };
    }

    public function scanTypeBadgeColor(): string
    {
        return match($this->scan_type) {
            'new_properties' => 'primary',
            'seo_pages' => 'info',
            'blog' => 'success',
            'price_movement' => 'warning',
            'gbp_reviews' => 'secondary',
            'weakness_detection' => 'danger',
            default => 'dark',
        };
    }
}
