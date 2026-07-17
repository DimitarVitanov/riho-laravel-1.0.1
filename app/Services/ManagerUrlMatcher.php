<?php

namespace App\Services;

use App\Models\AgencyProfile;
use App\Models\ManagerAgencyUrl;
use App\Models\AffiliateReferral;
use Illuminate\Support\Facades\Log;

class ManagerUrlMatcher
{
    /**
     * Check if an agency's domain matches any manager URL.
     * If matched, create an affiliate referral as if the manager's referral code was used.
     */
    public static function matchAgencyToManager(AgencyProfile $agencyProfile): ?ManagerAgencyUrl
    {
        // Get domains to check from agency profile
        $domainsToCheck = [];
        
        if ($agencyProfile->official_website_url) {
            $domainsToCheck[] = self::extractDomain($agencyProfile->official_website_url);
        }
        
        if ($agencyProfile->custom_domain) {
            $domainsToCheck[] = self::extractDomain($agencyProfile->custom_domain);
        }
        
        $domainsToCheck = array_filter(array_unique($domainsToCheck));
        
        if (empty($domainsToCheck)) {
            return null;
        }
        
        // Find matching manager URL
        $matchedUrl = ManagerAgencyUrl::whereIn('url', $domainsToCheck)
            ->where('status', '!=', 'inactive')
            ->first();
        
        if (!$matchedUrl) {
            return null;
        }
        
        // Update the manager URL record
        $matchedUrl->update([
            'status' => 'matched',
            'agency_profile_id' => $agencyProfile->id,
        ]);
        
        // Assign the manager to the agency
        $agencyProfile->update([
            'assigned_manager_id' => $matchedUrl->manager_id,
        ]);
        
        // Create affiliate referral as if manager's referral code was used
        $manager = $matchedUrl->manager;
        if ($manager && $manager->referral_code) {
            // Check if referral already exists for this user
            $existingReferral = AffiliateReferral::where('converted_user_id', $agencyProfile->user_id)->first();
            
            if (!$existingReferral) {
                AffiliateReferral::create([
                    'reseller_user_id' => $manager->id,
                    'referral_code' => $manager->referral_code,
                    'converted_user_id' => $agencyProfile->user_id,
                    'converted_account_type' => 'real_estate_agency',
                    'converted_at' => now(),
                    'status' => 'signed_up',
                ]);
            }
        }
        
        Log::info('Manager URL matched to agency', [
            'agency_profile_id' => $agencyProfile->id,
            'manager_id' => $matchedUrl->manager_id,
            'matched_url' => $matchedUrl->url,
        ]);
        
        return $matchedUrl;
    }
    
    /**
     * Extract clean domain from URL.
     */
    public static function extractDomain(string $url): string
    {
        // Remove protocol
        $domain = preg_replace('#^https?://#', '', $url);
        // Remove www.
        $domain = preg_replace('#^www\.#', '', $domain);
        // Remove path and trailing slash
        $domain = explode('/', $domain)[0];
        $domain = rtrim($domain, '/');
        
        return strtolower($domain);
    }
}
