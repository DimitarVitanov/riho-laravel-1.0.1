<?php

namespace App\Services\Est8ads;

use App\Models\Est8ads\Invoice;
use App\Models\Est8ads\Payment;
use App\Models\Est8ads\Profile;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

/**
 * The $12/month EST8ADS fee for individual users (Profile type "individual").
 * Agency accounts have their own separate Villa Bit billing and are never
 * touched by this service.
 *
 * There is no PayPal webhook: an admin checks the PayPal account and marks
 * the matching invoice as paid from the admin panel. An account is locked
 * out of the workspace once an invoice has been unpaid for longer than the
 * configured grace period.
 */
class BillingService
{
    public function amount(): float
    {
        return (float) config('est8ads.billing.monthly_amount', 12.00);
    }

    public function currency(): string
    {
        return (string) config('est8ads.billing.currency', 'USD');
    }

    public function gracePeriodDays(): int
    {
        return (int) config('est8ads.billing.grace_period_days', 10);
    }

    public function isBillable(Profile $profile): bool
    {
        return $profile->type === 'individual';
    }

    /**
     * True once the account has ever had an invoice confirmed as paid. Used
     * to tell a brand-new, never-paid account apart from one whose latest
     * renewal invoice has simply lapsed.
     */
    public function hasActivated(Profile $profile): bool
    {
        return $profile->invoices()->where('status', 'paid')->exists();
    }

    /**
     * A billable account that has never completed its first payment. These
     * accounts are held on the "waiting for payment" screen — they cannot see
     * or use the workspace until an admin confirms the first PayPal payment,
     * regardless of the grace period.
     */
    public function awaitingFirstPayment(Profile $profile): bool
    {
        return $this->isBillable($profile) && ! $this->hasActivated($profile);
    }

    /**
     * Opens the first invoice for a newly registered individual user.
     */
    public function createFirstInvoice(Profile $profile): ?Invoice
    {
        if (! $this->isBillable($profile)) {
            return null;
        }

        return $this->openInvoice($profile, CarbonImmutable::now());
    }

    /**
     * Creates the next monthly invoice once the previous billing period has
     * elapsed, whether or not the previous invoice was ever paid.
     */
    public function createNextInvoiceIfDue(Profile $profile): ?Invoice
    {
        if (! $this->isBillable($profile)) {
            return null;
        }

        $latest = $profile->invoices()->latest('issued_on')->first();

        if (! $latest) {
            return $this->openInvoice($profile, CarbonImmutable::now());
        }

        $nextIssuedOn = CarbonImmutable::parse($latest->issued_on)->addDays(30);

        if (CarbonImmutable::now()->lessThan($nextIssuedOn)) {
            return null;
        }

        return $this->openInvoice($profile, $nextIssuedOn);
    }

    private function openInvoice(Profile $profile, CarbonImmutable $issuedOn): Invoice
    {
        $amount = $this->amount();
        $currency = $this->currency();

        return Invoice::create([
            'uuid' => (string) Str::uuid(),
            'profile_id' => $profile->id,
            'number' => $this->nextInvoiceNumber(),
            'status' => 'open',
            'currency' => $currency,
            'subtotal' => $amount,
            'tax_amount' => 0,
            'total' => $amount,
            'amount_due' => $amount,
            'line_items' => [[
                'description' => 'EST8ADS 30-day property move subscription',
                'amount' => $amount,
                'currency' => $currency,
            ]],
            'issued_on' => $issuedOn->toDateString(),
            // Due immediately: the grace period before suspension is counted
            // from this date, not from the next billing cycle.
            'due_on' => $issuedOn->toDateString(),
        ]);
    }

    private function nextInvoiceNumber(): string
    {
        return 'EST-' . now()->format('Ym') . '-' . strtoupper(Str::random(6));
    }

    /**
     * Records that an admin has manually verified the PayPal payment for an
     * invoice and closes it out.
     */
    public function markPaid(Invoice $invoice): Payment
    {
        $invoice->update([
            'status' => 'paid',
            'amount_due' => 0,
            'paid_at' => now(),
        ]);

        return Payment::create([
            'uuid' => (string) Str::uuid(),
            'invoice_id' => $invoice->id,
            'profile_id' => $invoice->profile_id,
            'provider' => 'paypal',
            'method' => 'manual_admin_review',
            'status' => 'succeeded',
            'amount' => $invoice->total,
            'currency' => $invoice->currency,
            'processed_at' => now(),
            'metadata' => ['recorded_by' => 'admin_panel'],
        ]);
    }

    /**
     * An account is suspended once its latest invoice has been unpaid for
     * longer than the grace period.
     */
    public function isSuspended(Profile $profile): bool
    {
        if (! $this->isBillable($profile)) {
            return false;
        }

        $invoice = $profile->invoices()->latest('issued_on')->first();

        if (! $invoice || $invoice->status === 'paid' || ! $invoice->due_on) {
            return false;
        }

        return CarbonImmutable::now()->greaterThan(
            CarbonImmutable::parse($invoice->due_on)->addDays($this->gracePeriodDays())
        );
    }

    /**
     * Real replacement for the subscription card shown on the user
     * dashboard, built from the profile's latest invoice.
     *
     * @return array<string, mixed>|null
     */
    public function subscriptionSummary(Profile $profile): ?array
    {
        if (! $this->isBillable($profile)) {
            return null;
        }

        $invoice = $profile->invoices()->latest('issued_on')->first();

        if (! $invoice) {
            return null;
        }

        $status = $invoice->status === 'paid'
            ? 'active'
            : ($this->isSuspended($profile) ? 'suspended' : 'pending');

        $lastPayment = $invoice->payments()->latest('processed_at')->first();

        return [
            'name' => 'EST8ADS 30-day property move',
            'description' => 'Your complete sell-and-buy requires daily active and can participate in property-chain analysis for 30 days.',
            'amount' => (float) $invoice->total,
            'currency' => $invoice->currency,
            'billing_period' => 30,
            'status' => $status,
            'period_start' => $invoice->issued_on?->toDateString(),
            'period_end' => $invoice->due_on?->toDateString(),
            'due_on' => $invoice->due_on?->toDateString(),
            'grace_period_days' => $this->gracePeriodDays(),
            'invoice_number' => $invoice->number,
            'paid_at' => $lastPayment?->processed_at?->toDateString(),
        ];
    }
}
