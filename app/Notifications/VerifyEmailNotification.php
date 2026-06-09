<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

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

        $userType = match ($notifiable->role) {
            'real_estate_agency' => 'Real Estate Agency',
            'investor' => 'Real Estate Investor',
            'manager' => 'Manager Account',
            'super_admin', 'admin' => 'Administrator',
            default => ucfirst($notifiable->role ?? 'User'),
        };

        return (new MailMessage)
            ->subject('Confirm Your Villa Bit AI Server Account')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('Thank you for creating your Villa Bit AI Server account.')
            ->line("Your registered account type is: **{$userType}**")
            ->line('Before we activate your access, please confirm your email address by clicking the button below:')
            ->action('Confirm Email Address', $verificationUrl)
            ->line('This confirmation helps us protect your account and make sure that all future notifications, reports, access details, and important platform messages are delivered correctly.')
            ->line('Villa Bit AI Server is built as a serious real estate AI platform for business users, including real estate agencies and real estate investors. Our system is designed to support stronger real estate visibility, structured AI activity, local presence, AI search readiness, and long-term real estate relationship value.')
            ->line('If you did not create this account, you can safely ignore this email.')
            ->salutation("Kind regards,\nVilla Bit AI Server Team\nhttps://villabit.ai");
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
