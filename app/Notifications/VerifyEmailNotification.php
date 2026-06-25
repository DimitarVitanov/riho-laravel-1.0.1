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

        $price = $notifiable->agency_server_price ? '$' . number_format($notifiable->agency_server_price, 2) . ' per month' : null;

        $message = (new MailMessage)
            ->subject('Confirm Your Villa Bit AI Server Account')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('Thank you for creating your Villa Bit AI Server account.')
            ->line("Your registered account type is: **{$userType}**");

        if ($price) {
            $message->line("Your selected monthly price is: **{$price}**");
        }

        return $message
            ->line('Before we activate your access, please confirm your email address by clicking the button below:')
            ->action('Confirm Email Address', $verificationUrl)
            ->line('If you did not create this account, you can safely ignore this email.')
            ->salutation("Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai");
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
