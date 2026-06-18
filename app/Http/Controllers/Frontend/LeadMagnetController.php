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

        // Validate form data (similar to register but without account type)
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'investor_type' => 'nullable|string|in:cash_buyer,mortgage_buyer,investor,other',
            'interest_amount' => 'nullable|numeric|min:0',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create lead
        $lead = Lead::create([
            'agency_profile_id' => $agencyProfile->id,
            'source' => 'invisible_lead_magnet',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'investor_type' => $request->investor_type,
            'interest_amount' => $request->interest_amount,
            'message' => $request->message,
            'landing_page_url' => $request->fullUrl(),
            'status' => 'new',
        ]);

        // TODO: Notify AI Employee about new lead (can be done via event/job)

        return redirect()->back()
            ->with('success', 'Thank you for your interest! We will contact you shortly.');
    }
}
