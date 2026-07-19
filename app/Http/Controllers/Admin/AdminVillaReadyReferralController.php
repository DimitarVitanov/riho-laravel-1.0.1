<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillaReadyPropertyReferral;
use App\Models\VillaReadyProperty;
use App\Models\AgencyProfile;
use Illuminate\Http\Request;

class AdminVillaReadyReferralController extends Controller
{
    public function index(Request $request)
    {
        $query = VillaReadyPropertyReferral::with(['property', 'agencyProfile.user']);

        // Filter by agency
        if ($request->filled('agency_id')) {
            $query->where('agency_profile_id', $request->agency_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by property
        if ($request->filled('property_id')) {
            $query->where('villa_ready_property_id', $request->property_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('cookie_id', 'like', "%{$search}%")
                  ->orWhere('visitor_email', 'like', "%{$search}%")
                  ->orWhere('visitor_name', 'like', "%{$search}%");
            });
        }

        $referrals = $query->latest()->paginate(20);

        // Summary stats
        $stats = [
            'total_visits' => VillaReadyPropertyReferral::count(),
            'paid_sales' => VillaReadyPropertyReferral::where('status', 'paid')->count(),
            'total_sale_value' => VillaReadyPropertyReferral::where('status', 'paid')->sum('sale_amount'),
            'total_commission' => VillaReadyPropertyReferral::where('status', 'paid')->sum('commission_amount'),
        ];

        $agencies = AgencyProfile::with('user')->get();
        $properties = VillaReadyProperty::all();

        return view('admin.villabit.villa-ready.referrals.index', compact('referrals', 'stats', 'agencies', 'properties'));
    }

    public function setViewed(VillaReadyPropertyReferral $referral)
    {
        $referral->markAsViewed();
        
        return redirect()->back()->with('success', 'Status changed to VIEWED.');
    }

    public function setPaid(Request $request, VillaReadyPropertyReferral $referral)
    {
        $request->validate([
            'sale_amount' => 'required|numeric|min:0.01',
        ]);

        $referral->markAsPaid($request->sale_amount);

        return redirect()->back()->with('success', 'Sale confirmed. The 6% commission has been recorded.');
    }

    public function updateStatus(Request $request, VillaReadyPropertyReferral $referral)
    {
        $request->validate([
            'status' => 'required|in:visited,viewed,paid',
            'sale_amount' => 'required_if:status,paid|nullable|numeric|min:0.01',
        ]);

        if ($request->status === 'paid') {
            $referral->markAsPaid($request->sale_amount);
        } elseif ($request->status === 'viewed') {
            $referral->markAsViewed();
        } else {
            $referral->update(['status' => 'visited']);
        }

        return redirect()->back()->with('success', 'Referral status updated.');
    }

    public function create()
    {
        $agencies = AgencyProfile::with('user')->get();
        $properties = VillaReadyProperty::where('status', 'published')->get();

        return view('admin.villabit.villa-ready.referrals.create', compact('agencies', 'properties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'villa_ready_property_id' => 'required|exists:villa_ready_properties,id',
            'agency_profile_id' => 'required|exists:agency_profiles,id',
            'visitor_email' => 'nullable|email',
            'visitor_name' => 'nullable|string|max:255',
            'status' => 'required|in:visited,viewed',
        ]);

        $property = VillaReadyProperty::find($request->villa_ready_property_id);

        VillaReadyPropertyReferral::create([
            'villa_ready_property_id' => $request->villa_ready_property_id,
            'agency_profile_id' => $request->agency_profile_id,
            'cookie_id' => 'manual_' . uniqid(),
            'visitor_email' => $request->visitor_email,
            'visitor_name' => $request->visitor_name,
            'first_visit_at' => now(),
            'cookie_expires_at' => now()->addDays($property->cookie_duration_days),
            'status' => $request->status,
            'commission_percent' => $property->commission_percent,
            'admin_notes' => 'Manually added by admin',
        ]);

        return redirect()->route('admin.villabit.villa-ready.referrals.index')
            ->with('success', 'Manual referral added.');
    }

    public function destroy(VillaReadyPropertyReferral $referral)
    {
        $referral->delete();

        return redirect()->back()->with('success', 'Referral deleted.');
    }
}
