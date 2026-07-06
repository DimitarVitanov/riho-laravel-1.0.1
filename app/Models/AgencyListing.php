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
    ];

    protected $casts = [
        'images_json' => 'array',
        'price' => 'decimal:2',
    ];

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function campaign()
    {
        return $this->belongsTo(LocalSeoCampaign::class, 'local_seo_campaign_id');
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
