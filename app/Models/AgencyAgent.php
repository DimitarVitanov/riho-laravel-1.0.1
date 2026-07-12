<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyAgent extends Model
{
    protected $fillable = [
        'agency_profile_id',
        'agency_listing_id',
        'name',
        'email',
        'phone',
        'photo',
        'agency_name',
        'tagline',
        'license',
        'rating',
        'reviews_count',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:1',
            'is_primary' => 'boolean',
        ];
    }

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function listing()
    {
        return $this->belongsTo(AgencyListing::class, 'agency_listing_id');
    }
}
