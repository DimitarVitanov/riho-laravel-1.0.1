<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\InvestorStatusUpdatedAdminNotification;
use App\Notifications\InvestorStatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AdminInvestorController extends Controller
{
    public function index()
    {
        $investors = User::where('role', 'investor')
            ->with('investorProfile')
            ->latest()
            ->paginate(20);

        return view('admin.villabit.investors.index', compact('investors'));
    }

    public function show(User $user)
    {
        $user->load('investorProfile', 'investorProfile.investments', 'investorProfile.investments.project');
        return view('admin.villabit.investors.show', compact('user'));
    }

    public function updateReseller(Request $request, User $user)
    {
        $request->validate([
            'is_reseller_enabled' => 'required|boolean',
        ]);

        $enabled = (bool) $request->is_reseller_enabled;

        $user->update([
            'is_reseller_enabled' => $enabled,
            'referral_code' => $enabled && !$user->referral_code
                ? strtoupper(Str::random(8))
                : $user->referral_code,
        ]);

        return redirect()->route('admin.villabit.investors.show', $user)
            ->with('success', 'Reseller access ' . ($enabled ? 'enabled' : 'disabled') . ' for ' . $user->first_name . '.');
    }

    public function updateKycStatus(Request $request, User $user)
    {
        $request->validate([
            'kyc_status'           => 'nullable|in:pending,under_review,approved,rejected',
            'aml_status'           => 'nullable|in:pending,under_review,approved,rejected',
            'accreditation_status' => 'nullable|in:not_started,pending,verified,rejected',
            'eligible_structure'   => 'nullable|in:usa_llc,uk_llp,pending_review',
            'onboarding_phase'     => 'nullable|in:initial,eligibility_review,kyc_portal,documents_review,approved,rejected',
        ]);

        $profile = $user->investorProfile;
        if ($profile) {
            $changes = array_filter($request->only([
                'kyc_status', 'aml_status', 'accreditation_status',
                'eligible_structure', 'onboarding_phase',
            ]));

            $profile->update($changes);

            if ($request->onboarding_phase === 'approved' && !$profile->kyc_approved_at) {
                $profile->update(['kyc_approved_at' => now()]);
            }

            // Notify investor
            $user->notify(new InvestorStatusUpdatedNotification($changes));

            // Notify admin(s)
            $admins = User::whereIn('role', ['super_admin', 'admin'])->get();
            Notification::send($admins, new InvestorStatusUpdatedAdminNotification($user->email, $changes));
        }

        return redirect()->route('admin.villabit.investors.show', $user)
            ->with('success', 'Investor status updated successfully.');
    }
}
