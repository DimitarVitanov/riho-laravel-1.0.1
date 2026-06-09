<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyLeadController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->agencyProfile;
        $leads = collect();

        if ($profile) {
            $leads = Lead::where('agency_profile_id', $profile->id)
                ->latest()
                ->paginate(20);
        }

        return view('agency.leads.index', compact('leads'));
    }

    public function show(Lead $lead)
    {
        return view('agency.leads.show', compact('lead'));
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,qualified,converted,lost',
        ]);

        $lead->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Lead status updated.');
    }
}
