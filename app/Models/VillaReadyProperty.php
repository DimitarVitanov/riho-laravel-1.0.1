<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VillaReadyProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'status',
        'title',
        'short_title',
        'location',
        'address',
        'price_display',
        'property_type',
        'intro',
        'description',
        'location_description',
        'disclaimer',
        'meta_title',
        'meta_description',
        'slug',
        'buildings_count',
        'structure',
        'price_per_m2',
        'ground_floor_range',
        'first_floor_range',
        'attic_range',
        'payment_structure',
        'vat_info',
        'use_options',
        'management_service',
        'commission_percent',
        'cookie_duration_days',
        'source_url',
        'agency_can_edit',
        'featured_image',
        // Extended fields
        'hero_eyebrow',
        'hero_chips',
        'building_1_description',
        'building_2_description',
        'building_3_description',
        'building_4_description',
    ];

    protected $casts = [
        'price_per_m2' => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'agency_can_edit' => 'boolean',
    ];

    // Content is stored in a separate table to avoid MySQL row size limits
    public function content(): HasOne
    {
        return $this->hasOne(VillaReadyPropertyContent::class);
    }

    // Helper to get or create content record
    public function getOrCreateContent(): VillaReadyPropertyContent
    {
        return $this->content ?? $this->content()->create([]);
    }

    public function images(): HasMany
    {
        return $this->hasMany(VillaReadyPropertyImage::class)->orderBy('sort_order');
    }

    public function units(): HasMany
    {
        return $this->hasMany(VillaReadyPropertyUnit::class)->orderBy('building_number')->orderBy('floor');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(VillaReadyPropertyReferral::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(VillaReadyAgencyPublication::class);
    }

    public function getMainImageAttribute()
    {
        $mainImage = $this->images()->where('image_type', 'main')->first();
        return $mainImage ? $mainImage->image_path : $this->featured_image;
    }

    public function getGalleryImagesAttribute()
    {
        return $this->images()->whereIn('image_type', ['gallery', 'main'])->get();
    }

    public function getDroneImagesAttribute()
    {
        return $this->images()->where('image_type', 'drone')->get();
    }

    public function getFloorPlansAttribute()
    {
        return $this->images()->where('image_type', 'floor_plan')->get();
    }

    public function get360ImagesAttribute()
    {
        return $this->images()->where('image_type', '360')->get();
    }

    public function getAvailableUnitsAttribute()
    {
        return $this->units()->where('status', 'available')->get();
    }

    public function getTotalSalesAttribute()
    {
        return $this->referrals()->where('status', 'paid')->sum('sale_amount');
    }

    public function getTotalCommissionsAttribute()
    {
        return $this->referrals()->where('status', 'paid')->sum('commission_amount');
    }
}
