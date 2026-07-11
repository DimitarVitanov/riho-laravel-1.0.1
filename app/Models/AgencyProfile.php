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
        'server_name',
        'server_ip',
        'sftp_username',
        'sftp_password',
        'sftp_path',
        'sftp_port',
        'nameserver_1',
        'nameserver_2',
        'dns_verified_at',
        'last_dns_check_at',
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
        'uniqueness_check_method',
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
        'website_primary_color',
        'website_secondary_color',
        'website_accent_color',
        'website_header_style',
        'website_footer_style',
        'website_show_logo_in_header',
        'website_show_contact_in_header',
        'website_show_social_in_footer',
        'website_custom_css',
        'header_topbar_text',
        'header_topbar_color',
        'header_topbar_bg_color',
        'header_topbar_enabled',
        'header_logo_path',
        'header_logo_url',
        'header_logo_type',
        'header_logo_text',
        'header_bg_color',
        'header_text_color',
        'header_cta_enabled',
        'header_cta_text',
        'header_cta_url',
        'header_cta_bg_color',
        'header_cta_text_color',
        'header_nav_items',
        'footer_bg_color',
        'footer_text_color',
        'footer_col1_title',
        'footer_col1_links',
        'footer_col2_title',
        'footer_col2_text',
        'footer_copyright_text',
        'footer_terms_url',
        'footer_privacy_url',
        'sidebar_enabled',
        'sidebar_title',
        'sidebar_show_last_updated',
    ];

    protected function casts(): array
    {
        return [
            'dns_verified_at'        => 'datetime',
            'last_dns_check_at'      => 'datetime',
            'sftp_password'          => 'encrypted',
            'header_nav_items'       => 'array',
            'footer_col1_links'      => 'array',
            'header_topbar_enabled'  => 'boolean',
            'header_cta_enabled'     => 'boolean',
            'sidebar_enabled'        => 'boolean',
            'sidebar_show_last_updated' => 'boolean',
        ];
    }

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

    public function localSeoCampaigns()
    {
        return $this->hasMany(LocalSeoCampaign::class);
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

    public function dailyUsageLogs()
    {
        return $this->hasMany(DailyUsageLog::class);
    }

    public function getIsDnsVerifiedAttribute(): bool
    {
        return $this->dns_verified_at !== null;
    }
}
