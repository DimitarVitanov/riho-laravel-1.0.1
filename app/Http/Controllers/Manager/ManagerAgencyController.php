<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManagerAgencyController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = User::where('role', 'real_estate_agency')
            ->where('assigned_manager_id', $user->id)
            ->with('agencyProfile');

        // Also include the view-only agency if assigned
        if ($user->managerProfile?->can_view_agency_readonly && $user->managerProfile->view_agency_user_id) {
            $query->orWhere('id', $user->managerProfile->view_agency_user_id);
        }

        $agencies = $query->latest()->paginate(20);

        return view('manager.agencies.index', compact('agencies'));
    }
}
