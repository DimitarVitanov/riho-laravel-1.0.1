<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgencyListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_profile_id',
        'local_seo_campaign_id',
        'title',
        'property_type',
        'location',
        'description',
        'price',
        'currency',
        'images_json',
        'status',
        'external_url',
        'size',
        'living_area',
        'plot_size',
        'bedrooms',
        'bathrooms',
        'features',
        'is_turnkey',
        'property_condition',
        'year_built',
    ];

    protected $casts = [
        'images_json' => 'array',
        'price' => 'decimal:2',
        'living_area' => 'decimal:2',
        'plot_size' => 'decimal:2',
        'is_turnkey' => 'boolean',
    ];

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    /**
     * @deprecated Use campaigns() instead
     */
    public function campaign()
    {
        return $this->belongsTo(LocalSeoCampaign::class, 'local_seo_campaign_id');
    }

    /**
     * Many-to-many relationship with campaigns
     */
    public function campaigns()
    {
        return $this->belongsToMany(LocalSeoCampaign::class, 'agency_listing_local_seo_campaign')
            ->withTimestamps();
    }

    public function getImagesAttribute()
    {
        return $this->images_json ?? [];
    }

    public function getFormattedPriceAttribute()
    {
        if (!$this->price) {
            return null;
        }
        return number_format($this->price, 0) . ' ' . $this->currency;
    }
}
