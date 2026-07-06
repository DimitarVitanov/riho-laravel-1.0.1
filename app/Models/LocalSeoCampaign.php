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
        'status',
        'generated_page_id',
        'published_at',
    ];

    protected $casts = [
        'target_places' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'published_at' => 'datetime',
    ];

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function listings()
    {
        return $this->hasMany(AgencyListing::class);
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
