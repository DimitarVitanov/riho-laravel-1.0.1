<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginImpersonationLog;
use App\Models\ImpersonationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ImpersonationController extends Controller
{
    /**
     * Generate a one-time token for impersonation (opens in new tab)
     */
    public function generateToken(Request $request, User $user)
    {
        $admin = Auth::user();

        // Debug: Return info about current user
        if (!$admin) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        // Allow admins or managers with can_login_as_user permission
        $canImpersonate = $admin->isAdmin() || 
            ($admin->role === 'manager' && $admin->managerProfile?->can_login_as_user);
        
        if (!$canImpersonate) {
            return response()->json([
                'error' => 'No permission',
                'user_role' => $admin->role,
                'is_admin' => $admin->isAdmin(),
            ], 403);
        }

        // Create one-time token valid for 60 seconds
        $token = ImpersonationToken::create([
            'token' => Str::random(64),
            'admin_user_id' => $admin->id,
            'target_user_id' => $user->id,
            'expires_at' => now()->addSeconds(60),
        ]);

        // Log the impersonation
        LoginImpersonationLog::create([
            'admin_user_id' => $admin->id,
            'target_user_id' => $user->id,
            'target_role' => $user->role,
            'reason' => $request->input('reason', 'Admin impersonation (new tab)'),
            'started_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Return the URL to open in new tab
        return response()->json([
            'url' => route('impersonate.login', ['token' => $token->token]),
        ]);
    }

    /**
     * Login using the one-time token (called in new tab)
     */
    public function loginWithToken(string $token)
    {
        $impersonationToken = ImpersonationToken::where('token', $token)->first();

        if (!$impersonationToken || !$impersonationToken->isValid()) {
            return redirect()->route('login')->with('error', 'Invalid or expired impersonation link.');
        }

        // Mark token as used
        $impersonationToken->update(['used_at' => now()]);

        // Store that this is an impersonated session
        Session::put('impersonated_by_admin_id', $impersonationToken->admin_user_id);
        Session::put('is_impersonated_session', true);

        // Login as target user
        Auth::login($impersonationToken->targetUser);

        $user = $impersonationToken->targetUser;
        return redirect()->route('dashboard')->with('info', "You are now logged in as {$user->first_name} {$user->last_name} (in new tab)");
    }

    /**
     * Original start method (same tab - kept for backwards compatibility)
     */
    public function start(Request $request, User $user)
    {
        $admin = Auth::user();

        // Allow admins or managers with can_login_as_user permission
        $canImpersonate = $admin->isAdmin() || 
            ($admin->role === 'manager' && $admin->managerProfile?->can_login_as_user);
        
        if (!$canImpersonate) {
            abort(403, 'You do not have permission to impersonate users.');
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
