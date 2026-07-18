<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgencyProfile;
use App\Notifications\AgencyOnboardingStepNotification;
use App\Notifications\DomainLiveNotification;
use App\Notifications\DomainNameserverNotification;
use App\Services\SitemapSftpUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AdminAgencyController extends Controller
{
    public function index()
    {
        $agencies = User::where('role', 'real_estate_agency')
            ->with('agencyProfile')
            ->latest()
            ->paginate(20);

        return view('admin.villabit.agencies.index', compact('agencies'));
    }

    public function show(User $user)
    {
        $user->load('agencyProfile', 'agencyProfile.aiFeatureSettings', 'agencyProfile.usageLimits');
        return view('admin.villabit.agencies.show', compact('user'));
    }

    public function toggleStatus(User $user)
    {
        if ($user->agencyProfile) {
            $newStatus = request('subscription_status') === 'active' ? 'active' : 'inactive';
            $user->agencyProfile->update(['subscription_status' => $newStatus]);
        }

        return redirect()->route('admin.villabit.agencies.show', $user)
            ->with('success', 'Agency status updated successfully.');
    }

    public function createUsageLimits(User $user)
    {
        if (!$user->agencyProfile) {
            return redirect()->route('admin.villabit.agencies.show', $user)
                ->with('error', 'Agency profile not found.');
        }

        // Check if limits already exist for current period
        $existing = $user->agencyProfile->usageLimits()
            ->where('period_start', '<=', now())
            ->where('period_end', '>=', now())
            ->first();

        if ($existing) {
            return redirect()->route('admin.villabit.usage-limits.edit', $existing)
                ->with('info', 'Usage limits already exist for this period.');
        }

        // Create default usage limits
        $usageLimit = \App\Models\UsageLimit::create([
            'agency_profile_id' => $user->agencyProfile->id,
            'period_start' => now()->startOfMonth(),
            'period_end' => now()->endOfMonth(),
            'local_seo_pages_limit' => 10,
            'local_seo_pages_used' => 0,
            'competitor_scans_limit' => 10,
            'competitor_scans_used' => 0,
            'ai_search_freshness_updates_limit' => 4,
            'ai_search_freshness_updates_used' => 0,
            'authority_review_updates_limit' => 1,
            'authority_review_updates_used' => 0,
            'small_ai_content_actions_limit' => 10,
            'small_ai_content_actions_used' => 0,
        ]);

        return redirect()->route('admin.villabit.usage-limits.edit', $usageLimit)
            ->with('success', 'Default usage limits created successfully.');
    }

    public function updateDomainSettings(Request $request, User $user)
    {
        $request->validate([
            'custom_domain' => 'nullable|string|max:255',
            'server_name' => 'nullable|string|max:255',
            'server_ip' => 'nullable|string|max:255',
            'sftp_username' => 'nullable|string|max:255',
            'sftp_password' => 'nullable|string|max:255',
            'sftp_path' => 'nullable|string|max:255',
            'nameserver_1' => 'nullable|string|max:255',
            'nameserver_2' => 'nullable|string|max:255',
        ]);

        if (!$user->agencyProfile) {
            return redirect()->route('admin.villabit.agencies.show', $user)
                ->with('error', 'Agency profile not found.');
        }

        $profile = $user->agencyProfile;
        $oldNameserver1 = $profile->nameserver_1;
        $oldNameserver2 = $profile->nameserver_2;

        $data = $request->only([
            'custom_domain',
            'server_name',
            'server_ip',
            'sftp_username',
            'sftp_path',
            'nameserver_1',
            'nameserver_2',
        ]);

        if ($request->filled('sftp_password')) {
            $data['sftp_password'] = $request->sftp_password;
        }

        $profile->update($data);

        $nameserversChanged = $request->nameserver_1 && $request->nameserver_2
            && ($request->nameserver_1 !== $oldNameserver1 || $request->nameserver_2 !== $oldNameserver2);

        if ($nameserversChanged && $user->email) {
            Notification::send($user, new DomainNameserverNotification(
                $request->custom_domain ?: $profile->custom_domain ?: 'yourdomain.com',
                $request->nameserver_1,
                $request->nameserver_2,
                $user->agency_server_type
            ));
        }

        return redirect()->route('admin.villabit.agencies.show', $user)
            ->with('success', 'Agency domain settings updated.');
    }

    public function assignViewOnlyManager(Request $request, User $user)
    {
        $request->validate(['manager_user_id' => 'required|exists:users,id']);

        $manager = User::findOrFail($request->manager_user_id);

        if (!$manager->managerProfile) {
            return redirect()->route('admin.villabit.agencies.show', $user)
                ->with('error', 'This user does not have a manager profile.');
        }

        $manager->managerProfile->update([
            'can_view_agency_readonly' => true,
            'view_agency_user_id' => $user->id,
        ]);

        return redirect()->route('admin.villabit.agencies.show', $user)
            ->with('success', "Manager {$manager->first_name} {$manager->last_name} assigned as view-only.");
    }

    public function removeViewOnlyManager(User $user)
    {
        $profile = \App\Models\ManagerProfile::where('view_agency_user_id', $user->id)
            ->where('can_view_agency_readonly', true)
            ->first();

        if ($profile) {
            $profile->update([
                'can_view_agency_readonly' => false,
                'view_agency_user_id' => null,
            ]);
        }

        return redirect()->route('admin.villabit.agencies.show', $user)
            ->with('success', 'View-only manager removed from this agency.');
    }

    public function uploadSitemap(User $user, SitemapSftpUploader $uploader)
    {
        if (!$user->agencyProfile) {
            return redirect()->route('admin.villabit.agencies.show', $user)
                ->with('error', 'Agency profile not found.');
        }

        $result = $uploader->upload($user->agencyProfile);

        if ($result['success']) {
            return redirect()->route('admin.villabit.agencies.show', $user)
                ->with('success', $result['message']);
        }

        return redirect()->route('admin.villabit.agencies.show', $user)
            ->with('error', $result['message']);
    }

    public function advanceOnboarding(User $user)
    {
        if (!$user->isAgency()) {
            return redirect()->back()->with('error', 'This action is only for agencies.');
        }

        $previousStep = $user->onboarding_step;
        $user->advanceOnboardingStep();
        $newStep = $user->onboarding_step;

        $stepLabel = $user->getOnboardingStepLabel();

        // If completed, also set status to active
        if ($user->isOnboardingComplete() && $user->status === 'waitlist') {
            $user->update(['status' => 'active']);
            
            // Mark DNS as verified
            if ($user->agencyProfile) {
                $user->agencyProfile->update(['is_dns_verified' => true]);
            }
        }

        // Send appropriate email notification
        if ($newStep == User::ONBOARDING_COMPLETED && $user->agencyProfile) {
            // Send welcome/domain live notification for completion
            $user->notify(new DomainLiveNotification($user->agencyProfile));
        } else {
            // Send step notification for intermediate steps
            $user->notify(new AgencyOnboardingStepNotification($newStep));
        }

        return redirect()->back()->with('success', "Agency advanced to step: {$stepLabel}. Email notification sent.");
    }

    public function setOnboardingStep(User $user, Request $request)
    {
        if (!$user->isAgency()) {
            return redirect()->back()->with('error', 'This action is only for agencies.');
        }

        $step = (int) $request->input('step');
        if ($step < 1 || $step > User::ONBOARDING_COMPLETED) {
            return redirect()->back()->with('error', 'Invalid onboarding step.');
        }

        $previousStep = $user->onboarding_step;
        $user->setOnboardingStep($step);

        // If completed, also set status to active
        if ($user->isOnboardingComplete() && $user->status === 'waitlist') {
            $user->update(['status' => 'active']);
            
            // Mark DNS as verified
            if ($user->agencyProfile) {
                $user->agencyProfile->update(['is_dns_verified' => true]);
            }
        }

        // Send email notification if step increased (not if going backwards)
        $sendEmail = $request->input('send_email', true);
        if ($sendEmail && $step > $previousStep) {
            if ($step == User::ONBOARDING_COMPLETED && $user->agencyProfile) {
                // Send welcome/domain live notification for completion
                $user->notify(new DomainLiveNotification($user->agencyProfile));
            } else {
                $user->notify(new AgencyOnboardingStepNotification($step));
            }
        }

        $stepLabel = $user->getOnboardingStepLabel();
        $emailSent = ($sendEmail && $step > $previousStep) ? ' Email notification sent.' : '';
        return redirect()->back()->with('success', "Agency onboarding set to: {$stepLabel}.{$emailSent}");
    }
}
