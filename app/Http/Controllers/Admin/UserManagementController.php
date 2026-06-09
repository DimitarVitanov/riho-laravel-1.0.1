<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ManagerProfile;
use App\Models\AgencyProfile;
use App\Models\InvestorProfile;
use App\Notifications\ManagerAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function createManager()
    {
        return view('admin.villabit.users.create-manager');
    }

    public function storeManager(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'job_title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'can_manage_agencies' => 'boolean',
            'can_manage_investors' => 'boolean',
            'can_review_ai_outputs' => 'boolean',
            'can_prepare_payouts' => 'boolean',
            'can_view_financials' => 'boolean',
            'can_login_as_user' => 'boolean',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'manager',
            'status' => 'active',
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
            'active_from' => now(),
        ]);

        $user->notify(new ManagerAddedNotification());

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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'assigned_manager_id' => 'nullable|exists:users,id',
        ]);

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
            'assigned_manager_id' => $request->assigned_manager_id,
            'created_by_admin_id' => auth()->id(),
        ]);

        AgencyProfile::create([
            'user_id' => $user->id,
            'agency_name' => $request->company_name,
            'country' => $request->country,
            'contact_email' => $request->email,
            'assigned_manager_id' => $request->assigned_manager_id,
        ]);

        return redirect()->route('admin.villabit.users.index')
            ->with('success', 'Agency user created successfully.');
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'country' => 'required|string|max:255',
            'assigned_manager_id' => 'nullable|exists:users,id',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country' => $request->country,
            'account_type' => 'investor',
            'role' => 'investor',
            'status' => 'active',
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

        return back()->with('success', "User status changed to {$newStatus}.");
    }

    public function enableReseller(User $user)
    {
        $user->update([
            'is_reseller_enabled' => true,
            'referral_code' => $user->referral_code ?? strtoupper(Str::random(8)),
        ]);

        return back()->with('success', 'Reseller enabled for user.');
    }
}
