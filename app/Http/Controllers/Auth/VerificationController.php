<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('show', 'resend');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the email verification notice.
     *
     * EST8ADS-only accounts can come back to this page across several
     * sessions while they wait to verify and pay, so it is shown to them
     * without the "just registered" restriction below. Villa Bit accounts
     * keep the original behaviour: only allow viewing immediately after
     * registration or login, otherwise log the user out and redirect to
     * login.
     */
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user && $user->isEst8adsOnly()) {
            return view('est8ads.auth.verify');
        }

        if (! $request->session()->has('just_registered')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('warning', 'Please log in to continue.');
        }

        return view('auth.verify');
    }

    /**
     * Resend the verification email.
     *
     * Keep the one-time view flag so the user can see the success message.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true)->with('just_registered', true);
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * Overridden so the verification link works even when the user is not
     * currently logged in (e.g. opened in a different browser or incognito).
     */
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        $redirectTo = $user->isEst8adsOnly() ? route('est8ads.dashboard') : $this->redirectPath();

        if ($user->hasVerifiedEmail()) {
            Auth::login($user);
            return redirect($redirectTo)
                ->with('verified_message', 'Your email is already verified. Welcome back!');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        Auth::login($user);

        return redirect($redirectTo)
            ->with('verified_message', 'Your email address has been confirmed successfully!');
    }
}
