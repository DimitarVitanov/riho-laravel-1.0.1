<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AgencyProfile;
use App\Models\Lead;
use App\Models\AiFeatureSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadMagnetController extends Controller
{
    public function show(string $agency)
    {
        // Find agency by ID or slug
        $agencyProfile = AgencyProfile::find($agency) ?? 
            AgencyProfile::where('slug', $agency)->first() ??
            AgencyProfile::where('company_name', 'like', "%$agency%")->first();
        
        if (!$agencyProfile) {
            abort(404, 'Agency not found');
        }

        // Check if feature is enabled
        $featureSetting = AiFeatureSetting::where('agency_profile_id', $agencyProfile->id)
            ->where('feature_key', 'invisible_lead_magnet')
            ->first();

        if (!$featureSetting || !$featureSetting->is_enabled) {
            abort(404, 'Lead magnet not available');
        }

        return view('frontend.lead-magnet', compact('agencyProfile'));
    }

    public function store(Request $request, string $agency)
    {
        // Find agency
        $agencyProfile = AgencyProfile::find($agency) ?? 
            AgencyProfile::where('slug', $agency)->first() ??
            AgencyProfile::where('company_name', 'like', "%$agency%")->first();
        
        if (!$agencyProfile) {
            abort(404, 'Agency not found');
        }

        // Check if feature is enabled
        $featureSetting = AiFeatureSetting::where('agency_profile_id', $agencyProfile->id)
            ->where('feature_key', 'invisible_lead_magnet')
            ->first();

        if (!$featureSetting || !$featureSetting->is_enabled) {
            abort(404, 'Lead magnet not available');
        }

        // Validate form data - support both original and local SEO campaign forms
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'investor_type' => 'nullable|string|max:100',
            'interest_type' => 'nullable|string|max:100',
            'capital_range' => 'nullable|string|max:100',
            'buyer_profile' => 'nullable|string|max:100',
            'interest_amount' => 'nullable|numeric|min:0',
            'message' => 'nullable|string|max:2000',
            'source' => 'nullable|string|max:100',
            'campaign_id' => 'nullable|integer',
            'campaign_city' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Parse full_name into first/last if provided
        $firstName = $request->first_name;
        $lastName = $request->last_name;
        if ($request->full_name && !$firstName) {
            $nameParts = explode(' ', $request->full_name, 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';
        }

        // Build message with additional context
        $message = $request->message ?? '';
        if ($request->interest_type) {
            $message = "Interest: {$request->interest_type}\n" . $message;
        }
        if ($request->capital_range) {
            $message = "Capital: {$request->capital_range}\n" . $message;
        }
        if ($request->buyer_profile) {
            $message = "Profile: {$request->buyer_profile}\n" . $message;
        }
        if ($request->campaign_city) {
            $message = "City: {$request->campaign_city}\n" . $message;
        }

        // Create lead
        $lead = Lead::create([
            'agency_profile_id' => $agencyProfile->id,
            'source' => $request->source ?? 'invisible_lead_magnet',
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'investor_type' => $request->investor_type ?? $request->buyer_profile,
            'interest_amount' => $request->interest_amount,
            'message' => trim($message),
            'landing_page_url' => $request->headers->get('referer') ?? $request->fullUrl(),
            'status' => 'new',
        ]);

        // TODO: Notify AI Employee about new lead (can be done via event/job)

        return redirect()->back()
            ->with('success', 'Thank you for your interest! We will contact you shortly.');
    }
}
