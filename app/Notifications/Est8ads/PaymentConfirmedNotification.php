<?php

namespace App\Notifications\Est8ads;

use App\Models\Est8ads\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent once an admin manually reconciles a PayPal payment against an
 * EST8ADS invoice. This is what removes the "waiting for payment" banner
 * from the user's panel.
 */
class PaymentConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(private Invoice $invoice)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $dashboardLink = route('est8ads.dashboard');

        return (new MailMessage)
            ->mailer('est8ads')
            ->from(config('mail.est8ads_from.address'), config('mail.est8ads_from.name'))
            ->subject('Payment Confirmed — Your EST8ADS Account Is Now Active')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('We have successfully received your payment.')
            ->line("Invoice {$this->invoice->number} for " . $this->invoice->currency . ' ' . number_format((float) $this->invoice->total, 2) . ' is now marked as paid.')
            ->line('Your EST8ADS account is now fully active. The "waiting for payment" banner has been removed from your panel.')
            ->action('Log In to Your Panel', $dashboardLink)
            ->line('Thank you for choosing EST8ADS.')
            ->salutation("Kind regards,\n\nThe EST8ADS Team\\\nProperty Chain Intelligence\\\nhttps://est8ads.com");
    }
}
