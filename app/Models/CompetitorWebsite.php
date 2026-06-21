<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitorWebsite extends Model
{
    protected $fillable = [
        'agency_profile_id', 'name', 'url', 'google_business_url',
        'notes', 'is_active', 'last_scanned_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_scanned_at' => 'datetime',
        ];
    }

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function scanResults()
    {
        return $this->hasMany(CompetitorScanResult::class);
    }

    public function latestScan()
    {
        return $this->hasOne(CompetitorScanResult::class)->latest('scanned_at');
    }
}
