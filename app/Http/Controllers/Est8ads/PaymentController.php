<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    /**
     * Redirect to PayPal payment link for EST8ADS subscription.
     *
     * This is a simple redirect to PayPal - easy to update the link later.
     * Same system as VillaBit AI billing. There is no PayPal webhook/IPN
     * integration: an admin reconciles the payment manually from the admin
     * panel by marking the matching invoice as paid.
     */
    public function redirectToPayPal(): RedirectResponse
    {
        return redirect()->away((string) config('est8ads.billing.paypal_link'));
    }

    /**
     * Handle successful payment callback from PayPal.
     */
    public function paymentSuccess()
    {
        return redirect()
            ->route('est8ads.user.dashboard')
            ->with('success', 'Payment submitted! Your subscription is pending admin approval.');
    }

    /**
     * Handle cancelled payment from PayPal.
     */
    public function paymentCancelled()
    {
        return redirect()
            ->route('est8ads.user.dashboard')
            ->with('error', 'Payment was cancelled. Please try again.');
    }
}
