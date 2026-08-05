<?php

namespace App\Notifications\Est8ads;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent once an EST8ADS-only account confirms its email address. Payment is
 * requested separately by PaymentRequestNotification so this stays a short,
 * simple welcome.
 */
class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $loginLink = route('est8ads.login');

        return (new MailMessage)
            ->mailer('est8ads')
            ->from(config('mail.est8ads_from.address'), config('mail.est8ads_from.name'))
            ->subject('Welcome to EST8ADS — Your Email Is Confirmed')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('Your email address has been confirmed successfully. Welcome to EST8ADS!')
            ->line('EST8ADS connects your property sale with the right next purchase by analyzing complete transaction chains, matching buyers, sellers and properties across the market.')
            ->line('You can log in to your panel here:')
            ->action('Log In to EST8ADS', $loginLink)
            ->line('Shortly you will receive a separate email with the details required to activate full access to your workspace.')
            ->salutation("Kind regards,\n\nThe EST8ADS Team\\\nProperty Chain Intelligence\\\nhttps://est8ads.com");
    }
}
