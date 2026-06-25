<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgencyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agency_name',
        'official_website_url',
        'custom_domain',
        'country',
        'city',
        'main_service_area',
        'target_city',
        'target_radius_km',
        'main_property_types',
        'buyer_types',
        'seller_services',
        'rental_management_services',
        'investment_services',
        'foreign_buyer_support',
        'property_management_support',
        'google_business_profile_url',
        'sitemap_url',
        'copyscape_username',
        'copyscape_api_key',
        'contact_email',
        'contact_phone',
        'agency_logo_path',
        'brand_primary_color',
        'brand_secondary_color',
        'subscription_plan_id',
        'subscription_status',
        'assigned_manager_id',
        'ai_status',
        'ai_content_language',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'assigned_manager_id');
    }

    public function aiFeatureSettings()
    {
        return $this->hasMany(AiFeatureSetting::class);
    }

    public function aiDailyReports()
    {
        return $this->hasMany(AiDailyReport::class);
    }

    public function aiSuggestions()
    {
        return $this->hasMany(AiSuggestion::class);
    }

    public function usageLimits()
    {
        return $this->hasMany(UsageLimit::class);
    }

    public function currentUsageLimit()
    {
        return $this->hasOne(UsageLimit::class)
            ->where('period_start', '<=', now())
            ->where('period_end', '>=', now())
            ->latest('period_start');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function localSeoTargets()
    {
        return $this->hasMany(LocalSeoTarget::class);
    }

    public function generatedPages()
    {
        return $this->hasMany(GeneratedPage::class);
    }

    public function agencyListings()
    {
        return $this->hasMany(AgencyListing::class);
    }

    public function competitorWebsites()
    {
        return $this->hasMany(CompetitorWebsite::class);
    }

    public function competitorScanResults()
    {
        return $this->hasMany(CompetitorScanResult::class);
    }
}
