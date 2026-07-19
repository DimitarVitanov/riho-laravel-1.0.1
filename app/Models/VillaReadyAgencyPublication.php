<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillaReadyAgencyPublication extends Model
{
    use HasFactory;

    protected $fillable = [
        'villa_ready_property_id',
        'agency_profile_id',
        'affiliate_code',
        'page_slug',
        'is_published',
        'published_at',
        'published_url',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(VillaReadyProperty::class, 'villa_ready_property_id');
    }

    public function agencyProfile(): BelongsTo
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function getFullUrlAttribute(): string
    {
        $domain = $this->agencyProfile->custom_domain ?? $this->agencyProfile->subdomain;
        return 'https://' . $domain . $this->page_slug;
    }

    public static function generateAffiliateCode(AgencyProfile $agency): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $agency->agency_name), 0, 6));
        $suffix = str_pad($agency->id, 3, '0', STR_PAD_LEFT);
        return $prefix . '-' . $suffix;
    }
}
