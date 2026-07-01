<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminManagerController extends Controller
{
    public function index()
    {
        $managers = User::where('role', 'manager')
            ->with('managerProfile')
            ->latest()
            ->paginate(20);

        return view('admin.villabit.managers.index', compact('managers'));
    }

    public function show(User $user)
    {
        $user->load('managerProfile');
        $agencies = User::where('role', 'real_estate_agency')->where('status', 'active')->get();
        return view('admin.villabit.managers.show', compact('user', 'agencies'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'view_agency_user_id' => 'nullable|exists:users,id',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        if ($user->managerProfile) {
            $user->managerProfile->update([
                'job_title' => $request->job_title,
                'department' => $request->department,
                'can_manage_agencies' => $request->boolean('can_manage_agencies'),
                'can_manage_investors' => $request->boolean('can_manage_investors'),
                'can_review_ai_outputs' => $request->boolean('can_review_ai_outputs'),
                'can_prepare_payouts' => $request->boolean('can_prepare_payouts'),
                'can_view_financials' => $request->boolean('can_view_financials'),
                'can_login_as_user' => $request->boolean('can_login_as_user'),
                'can_view_agency_readonly' => $request->boolean('can_view_agency_readonly'),
                'view_agency_user_id' => $request->view_agency_user_id,
            ]);
        }

        return redirect()->route('admin.villabit.managers.show', $user)
            ->with('success', 'Manager updated successfully.');
    }
}
