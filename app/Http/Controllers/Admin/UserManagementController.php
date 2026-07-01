<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ManagerProfile;
use App\Models\AgencyProfile;
use App\Models\InvestorProfile;
use App\Models\UsageLimit;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Notifications\ManagerAddedNotification;
use App\Notifications\AccountApprovedNotification;
use App\Notifications\AccountSuspendedNotification;
use App\Notifications\AccountClosedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function createManager()
    {
        $agencies = User::where('role', 'real_estate_agency')->where('status', 'active')->get();
        return view('admin.villabit.users.create-manager', compact('agencies'));
    }

    public function storeManager(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => 'required|min:8|confirmed',
            'job_title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'can_manage_agencies' => 'boolean',
            'can_manage_investors' => 'boolean',
            'can_review_ai_outputs' => 'boolean',
            'can_prepare_payouts' => 'boolean',
            'can_view_financials' => 'boolean',
            'can_login_as_user' => 'boolean',
            'can_view_agency_readonly' => 'boolean',
            'view_agency_user_id' => 'nullable|exists:users,id',
        ]);

        // Remove any soft-deleted user with the same email
        User::onlyTrashed()->where('email', $request->email)->forceDelete();

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'manager',
            'status' => 'active',
            'email_verified_at' => now(),
            'created_by_admin_id' => auth()->id(),
        ]);

        ManagerProfile::create([
            'user_id' => $user->id,
            'employee_code' => 'MGR-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'job_title' => $request->job_title,
            'department' => $request->department,
            'can_manage_agencies' => $request->boolean('can_manage_agencies', true),
            'can_manage_investors' => $request->boolean('can_manage_investors', false),
            'can_review_ai_outputs' => $request->boolean('can_review_ai_outputs', true),
            'can_prepare_payouts' => $request->boolean('can_prepare_payouts', false),
            'can_view_financials' => $request->boolean('can_view_financials', false),
            'can_login_as_user' => $request->boolean('can_login_as_user', false),
            'can_view_agency_readonly' => $request->boolean('can_view_agency_readonly', false),
            'view_agency_user_id' => $request->view_agency_user_id,
            'active_from' => now(),
        ]);

        if ($request->boolean('can_view_agency_readonly')) {
            $user->notify(new \App\Notifications\ViewOnlyManagerAddedNotification());
        } else {
            $user->notify(new ManagerAddedNotification());
        }

        return redirect()->route('admin.villabit.users.index')
            ->with('success', 'Manager created successfully.');
    }

    public function createAgency()
    {
        $managers = User::where('role', 'manager')->where('status', 'active')->get();
        return view('admin.villabit.users.create-agency', compact('managers'));
    }

    public function storeAgency(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => 'required|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'assigned_manager_id' => 'nullable|exists:users,id',
        ]);

        // Remove any soft-deleted user with the same email
        User::onlyTrashed()->where('email', $request->email)->forceDelete();

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_name' => $request->company_name,
            'country' => $request->country,
            'account_type' => 'real_estate_agency',
            'role' => 'real_estate_agency',
            'status' => 'active',
            'is_reseller_enabled' => true,
            'referral_code' => strtoupper(\Illuminate\Support\Str::random(8)),
            'assigned_manager_id' => $request->assigned_manager_id,
            'created_by_admin_id' => auth()->id(),
        ]);

        $agencyProfile = AgencyProfile::create([
            'user_id' => $user->id,
            'agency_name' => $request->company_name,
            'country' => $request->country,
            'contact_email' => $request->email,
            'assigned_manager_id' => $request->assigned_manager_id,
        ]);

        // Create default usage limits for the agency
        UsageLimit::create([
            'agency_profile_id' => $agencyProfile->id,
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

        return redirect()->route('admin.villabit.users.index')
            ->with('success', 'Agency user created successfully with default usage limits.');
    }

    public function createInvestor()
    {
        $managers = User::where('role', 'manager')->where('status', 'active')->get();
        return view('admin.villabit.users.create-investor', compact('managers'));
    }

    public function storeInvestor(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => 'required|min:8|confirmed',
            'country' => 'required|string|max:255',
            'assigned_manager_id' => 'nullable|exists:users,id',
        ]);

        // Remove any soft-deleted user with the same email
        User::onlyTrashed()->where('email', $request->email)->forceDelete();

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country' => $request->country,
            'account_type' => 'investor',
            'role' => 'investor',
            'status' => 'active',
            'is_reseller_enabled' => true,
            'referral_code' => strtoupper(\Illuminate\Support\Str::random(8)),
            'assigned_manager_id' => $request->assigned_manager_id,
            'created_by_admin_id' => auth()->id(),
        ]);

        InvestorProfile::create([
            'user_id' => $user->id,
            'citizenship_country' => $request->country,
            'residence_country' => $request->country,
            'assigned_manager_id' => $request->assigned_manager_id,
        ]);

        return redirect()->route('admin.villabit.users.index')
            ->with('success', 'Investor user created successfully.');
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(25);

        return view('admin.villabit.users.index', compact('users'));
    }

    public function toggleStatus(User $user)
    {
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Cannot change super admin status.');
        }

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        if ($newStatus === 'suspended') {
            $user->notify(new AccountSuspendedNotification());
        }

        return back()->with('success', "User status changed to {$newStatus}.");
    }

    public function approveWaitlist(User $user)
    {
        if ($user->status !== 'waitlist') {
            return back()->with('error', 'User is not on the waitlist.');
        }

        $user->update(['status' => 'active']);

        $user->notify(new AccountApprovedNotification());

        return back()->with('success', "{$user->first_name} {$user->last_name} has been approved and granted full access.");
    }

    public function enableReseller(User $user)
    {
        $user->update([
            'is_reseller_enabled' => true,
            'referral_code' => $user->referral_code ?? strtoupper(Str::random(8)),
        ]);

        return back()->with('success', 'Reseller enabled for user.');
    }

    public function destroy(User $user)
    {
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return back()->with('error', 'Administrator accounts cannot be deleted.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = trim($user->first_name . ' ' . $user->last_name) ?: $user->email;

        // Send the closure confirmation while the account still exists. This
        // notification is intentionally not queued so it is dispatched before
        // the record (and its email address) is removed below.
        $user->notify(new AccountClosedNotification());

        DB::transaction(function () use ($user) {
            // Support tickets have no DB-level cascade, so clean them up manually.
            $ticketIds = SupportTicket::where('user_id', $user->id)->pluck('id');
            if ($ticketIds->isNotEmpty()) {
                SupportTicketMessage::whereIn('support_ticket_id', $ticketIds)->delete();
                SupportTicket::whereIn('id', $ticketIds)->delete();
            }
            SupportTicketMessage::where('user_id', $user->id)->delete();

            // Remaining related data (profiles, usage limits, leads, generated pages,
            // AI suggestions/reports, investments, capital calls, payouts, referrals, etc.)
            // is removed automatically via onDelete('cascade') foreign keys.
            $user->delete();
        });

        return back()->with('success', "{$name} and all related data have been deleted.");
    }
}
