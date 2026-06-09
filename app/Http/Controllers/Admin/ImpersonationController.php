<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginImpersonationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonationController extends Controller
{
    public function start(Request $request, User $user)
    {
        $admin = Auth::user();

        if (!$admin->isAdmin()) {
            abort(403);
        }

        Session::put('impersonator_id', $admin->id);

        LoginImpersonationLog::create([
            'admin_user_id' => $admin->id,
            'target_user_id' => $user->id,
            'target_role' => $user->role,
            'reason' => $request->input('reason', 'Admin impersonation'),
            'started_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('info', "You are now logged in as {$user->first_name} {$user->last_name}");
    }

    public function stop()
    {
        $impersonatorId = Session::pull('impersonator_id');

        if ($impersonatorId) {
            $log = LoginImpersonationLog::where('admin_user_id', $impersonatorId)
                ->whereNull('ended_at')
                ->latest()
                ->first();

            if ($log) {
                $log->update(['ended_at' => now()]);
            }

            Auth::login(User::find($impersonatorId));
        }

        return redirect()->route('admin.villabit.dashboard')->with('info', 'Impersonation ended.');
    }
}
