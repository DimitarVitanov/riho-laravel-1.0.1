<?php

namespace App\Notifications\Est8ads;

use App\Models\Est8ads\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Asks a newly-verified EST8ADS individual user to pay their first 30-day
 * subscription invoice. Until it is paid, the user sees a "waiting for
 * payment" banner in the panel instead of full access.
 */
class PaymentRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private ?Invoice $invoice = null)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $amount = (float) ($this->invoice->total ?? config('est8ads.billing.monthly_amount', 12.00));
        $currency = $this->invoice->currency ?? config('est8ads.billing.currency', 'USD');
        $paypalLink = (string) config('est8ads.billing.paypal_link');
        $dashboardLink = route('est8ads.dashboard');

        $message = (new MailMessage)
            ->mailer('est8ads')
            ->from(config('mail.est8ads_from.address'), config('mail.est8ads_from.name'))
            ->subject('Activate Your EST8ADS Account by Making a Payment')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('Your account is set up and your email is confirmed, but your workspace is not fully unlocked yet.')
            ->line("EST8ADS charges {$currency} " . number_format($amount, 2) . ' every 30 days to keep your property-chain move active.');

        if ($this->invoice?->number) {
            $message->line("Invoice {$this->invoice->number} is due on {$this->invoice->due_on?->toDateString()}.");
        }

        return $message
            ->line('To activate full access, please complete payment securely through PayPal:')
            ->action('Pay Now via PayPal', $paypalLink)
            ->line('Payments are reconciled manually — your account is unlocked as soon as our team confirms the PayPal transaction. Until then you will see a "waiting for payment" banner in your panel.')
            ->line("You can check your payment status at any time here: {$dashboardLink}")
            ->salutation("Kind regards,\n\nThe EST8ADS Team\\\nProperty Chain Intelligence\\\nhttps://est8ads.com");
    }
}
