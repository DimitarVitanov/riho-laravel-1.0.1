<?php

namespace App\Notifications\Est8ads;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * EST8ADS-only accounts (no Villa Bit access) get their own branded
 * verification email, sent through EST8ADS's Proton Mail inbox instead of
 * Villa Bit AI's.
 */
class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->mailer('est8ads')
            ->from(config('mail.est8ads_from.address'), config('mail.est8ads_from.name'))
            ->subject('Confirm Your EST8ADS Account')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('Thank you for creating your EST8ADS account.')
            ->line('EST8ADS analyzes complete property-chain moves — connecting your sale with the right next purchase.')
            ->line('Before we activate your access, please confirm your email address by clicking the button below:')
            ->action('Confirm Email Address', $verificationUrl)
            ->line('If you did not create this account, you can safely ignore this email.')
            ->salutation("Kind regards,\n\nThe EST8ADS Team\\\nProperty Chain Intelligence\\\nhttps://est8ads.com");
    }

    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
