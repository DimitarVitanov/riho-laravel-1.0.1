<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocalSeoCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_profile_id',
        'name',
        'primary_city',
        'country',
        'latitude',
        'longitude',
        'coverage_area',
        'coverage_unit',
        'target_places',
        'positioning_note',
        'page_slug',
        'page_settings',
        'ai_generated_content',
        'content_generated_at',
        'content_uniqueness_status',
        'uniqueness_result',
        'status',
        'generated_page_id',
        'published_at',
    ];

    protected $casts = [
        'target_places' => 'array',
        'page_settings' => 'array',
        'ai_generated_content' => 'array',
        'uniqueness_result' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'content_generated_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    /**
     * Many-to-many relationship with listings
     */
    public function listings()
    {
        return $this->belongsToMany(AgencyListing::class, 'agency_listing_local_seo_campaign')
            ->withTimestamps();
    }

    public function generatedPage()
    {
        return $this->belongsTo(GeneratedPage::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
