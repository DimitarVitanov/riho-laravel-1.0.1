<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocalSeoCampaign extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::created(function (LocalSeoCampaign $campaign) {
            // Schedule Authority Builder page for the next day
            if (!$campaign->is_sub_campaign) {
                AuthorityBuilderPage::create([
                    'agency_profile_id' => $campaign->agency_profile_id,
                    'source_type' => 'local_seo',
                    'source_id' => $campaign->id,
                    'source_title' => $campaign->name,
                    'title' => $campaign->name . ' - Real Estate Analysis',
                    'slug' => \Illuminate\Support\Str::slug($campaign->name . '-analysis'),
                    'location' => $campaign->primary_city,
                    'country' => $campaign->country,
                    'scheduled_for' => now()->addDay()->toDateString(),
                    'status' => 'pending',
                ]);
            }
        });
    }

    protected $fillable = [
        'agency_profile_id',
        'parent_campaign_id',
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
        'is_sub_campaign',
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
    
    public function parentCampaign()
    {
        return $this->belongsTo(LocalSeoCampaign::class, 'parent_campaign_id');
    }
    
    public function subCampaigns()
    {
        return $this->hasMany(LocalSeoCampaign::class, 'parent_campaign_id');
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

    /**
     * Get active listings that fall within this campaign's coverage radius.
     * Falls back to manually attached campaigns when coordinates are missing.
     */
    public function nearbyListings()
    {
        if (!is_numeric($this->latitude) || !is_numeric($this->longitude) || !$this->coverage_area) {
            return $this->listings()->where('status', 'active');
        }

        $radiusKm = $this->coverage_unit === 'mi' ? $this->coverage_area * 1.60934 : $this->coverage_area;

        return AgencyListing::where('agency_profile_id', $this->agency_profile_id)
            ->where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->withinDistance((float) $this->latitude, (float) $this->longitude, (float) $radiusKm);
    }
}
