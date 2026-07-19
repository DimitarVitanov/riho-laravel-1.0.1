<?php

namespace App\Http\Controllers;

use App\Models\VillaReadyProperty;
use App\Models\VillaReadyPropertyReferral;
use App\Models\VillaReadyAgencyPublication;
use App\Models\AgencyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VillaReadyPropertyPageController extends Controller
{
    /**
     * Display the property page on the agency's domain.
     * This creates the affiliate cookie and tracks the visit.
     */
    public function show(Request $request, string $slug)
    {
        // Find the property
        $property = VillaReadyProperty::where('slug', $slug)
            ->with(['images', 'units'])
            ->firstOrFail();

        // Get the agency profile from the current domain
        $host = $request->getHost();
        $profile = AgencyProfile::where('custom_domain', $host)->first();

        // Allow preview mode for admins or when no agency context
        $isPreview = !$profile || $request->has('preview') || auth()->check();
        
        if (!$profile) {
            // Use a default/demo profile for preview
            $profile = AgencyProfile::first() ?? new AgencyProfile([
                'agency_name' => 'Villa Ready Croatia',
                'website_accent_color' => '#0A0B0D',
            ]);
        }

        $publication = null;
        if (!$isPreview) {
            // Check if this property is published for this agency
            $publication = VillaReadyAgencyPublication::where('villa_ready_property_id', $property->id)
                ->where('agency_profile_id', $profile->id)
                ->where('is_published', true)
                ->first();

            if (!$publication) {
                abort(404, 'Property not available on this agency.');
            }
        }

        // Handle affiliate cookie
        $cookieName = 'vrc_affiliate_' . $property->id;
        $cookieId = $request->cookie($cookieName);
        
        if (!$cookieId) {
            // Create new cookie and referral record
            $cookieId = 'vrc_' . Str::random(16);
            $expiresAt = now()->addDays($property->cookie_duration_days);

            VillaReadyPropertyReferral::create([
                'villa_ready_property_id' => $property->id,
                'agency_profile_id' => $profile->id,
                'cookie_id' => $cookieId,
                'visitor_ip' => $request->ip(),
                'visitor_user_agent' => $request->userAgent(),
                'first_visit_at' => now(),
                'last_visit_at' => now(),
                'cookie_expires_at' => $expiresAt,
                'status' => VillaReadyPropertyReferral::STATUS_VISITED,
                'commission_percent' => $property->commission_percent,
            ]);

            // Set the cookie
            $cookie = cookie($cookieName, $cookieId, $property->cookie_duration_days * 24 * 60);
        } else {
            // Update existing referral's last visit
            $referral = VillaReadyPropertyReferral::where('cookie_id', $cookieId)->first();
            if ($referral) {
                $referral->update(['last_visit_at' => now()]);
            }
            $cookie = null;
        }

        // Render the property page using the realestate.taxi style template
        $response = response()->view('realestate-taxi.villa-ready-property', [
            'property' => $property,
            'profile' => $profile,
            'publication' => $publication,
        ]);

        if ($cookie) {
            $response->withCookie($cookie);
        }

        return $response;
    }

    /**
     * Track a visit from villareadycroatia.com (read the affiliate cookie)
     */
    public function trackVisit(Request $request)
    {
        $propertyId = $request->input('property_id');
        $property = VillaReadyProperty::find($propertyId);
        
        if (!$property) {
            return response()->json(['tracked' => false, 'reason' => 'Property not found']);
        }

        $cookieName = 'vrc_affiliate_' . $property->id;
        $cookieId = $request->cookie($cookieName);

        if ($cookieId) {
            $referral = VillaReadyPropertyReferral::where('cookie_id', $cookieId)->first();
            if ($referral) {
                $referral->update(['last_visit_at' => now()]);
                return response()->json([
                    'tracked' => true,
                    'agency_id' => $referral->agency_profile_id,
                    'cookie_id' => $cookieId,
                ]);
            }
        }

        return response()->json(['tracked' => false, 'reason' => 'No affiliate cookie found']);
    }
}
