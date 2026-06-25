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
     * Only allow viewing immediately after registration or login. On refresh,
     * log the user out so they must log in again.
     */
    public function show(Request $request)
    {
        if (! $request->session()->has('just_registered')) {
            Auth::logout();
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

        if ($user->hasVerifiedEmail()) {
            Auth::login($user);
            return redirect($this->redirectPath())
                ->with('verified_message', 'Your email is already verified. Welcome back!');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        Auth::login($user);

        return redirect($this->redirectPath())
            ->with('verified_message', 'Your email address has been confirmed successfully!');
    }
}
