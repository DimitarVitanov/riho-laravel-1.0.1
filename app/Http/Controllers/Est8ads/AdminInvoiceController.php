<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Models\Est8ads\Invoice;
use App\Notifications\Est8ads\PaymentConfirmedNotification;
use App\Services\Est8ads\BillingService;
use App\Services\Est8ads\Discovery\DiscoveryManager;
use Illuminate\Http\JsonResponse;
use Throwable;

class AdminInvoiceController extends Controller
{
    /**
     * Records that an admin has manually checked the PayPal account and
     * confirms the invoice was paid. There is no PayPal webhook, so this is
     * the only way a payment is ever reconciled.
     */
    public function markPaid(Invoice $invoice, BillingService $billing, DiscoveryManager $discovery): JsonResponse
    {
        if ($invoice->status === 'paid') {
            return response()->json(['message' => 'This invoice is already marked as paid.'], 422);
        }

        $billing->markPaid($invoice->refresh());
        $invoice->load('profile.user');

        $user = $invoice->profile?->user;

        if ($user && $user->isEst8adsOnly()) {
            $user->notify(new PaymentConfirmedNotification($invoice));
        }

        // Now that payment is confirmed, run the AI discovery that was held back
        // while the account was unpaid — so credits are only spent on paying
        // members, and their matches are ready as soon as they are unlocked.
        if ($profile = $invoice->profile) {
            foreach ($profile->propertyMoves()->whereIn('status', ['active', 'submitted'])->get() as $move) {
                try {
                    $discovery->dispatch($move, 'payment_confirmed');
                } catch (Throwable $exception) {
                    report($exception);
                }
            }
        }

        return response()->json([
            'message' => 'Invoice marked as paid.',
            'payment' => [
                'id' => 'INV-' . $invoice->id,
                'date' => $invoice->paid_at?->toDateString(),
                'customer' => $invoice->profile?->company_name ?: $invoice->profile?->email,
                'item' => 'EST8ADS 30-day subscription',
                'amount' => (float) $invoice->total,
                'currency' => $invoice->currency,
                'status' => 'Paid',
                'invoice_id' => $invoice->id,
            ],
        ]);
    }
}
