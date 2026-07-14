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
        'primary_city',
        'country',
        'latitude',
        'longitude',
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
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
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
        $images = $this->images_json;
        
        // If it's already an array, return it
        if (is_array($images)) {
            return $images;
        }
        
        // If it's a string, try to decode it
        if (is_string($images)) {
            $decoded = json_decode($images, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        
        return [];
    }

    public function getFormattedPriceAttribute()
    {
        if (!$this->price) {
            return null;
        }
        return number_format($this->price, 0) . ' ' . $this->currency;
    }

    /**
     * Filter listings within a given radius (km) from a center point.
     */
    public function scopeWithinDistance($query, float $lat, float $lng, float $radiusKm)
    {
        $earthRadius = 6371; // km

        return $query->selectRaw("*, (
            {$earthRadius} * acos(
                cos(radians(?)) *
                cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) +
                sin(radians(?)) *
                sin(radians(latitude))
            )
        ) AS distance", [$lat, $lng, $lat])
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance');
    }
}
