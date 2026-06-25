<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountApprovedNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $dashboardUrl = $notifiable->role === 'investor'
            ? url('/investor/dashboard')
            : url('/agency/dashboard');

        return (new MailMessage)
            ->subject('Payment Confirmed – Your Villa Bit AI Server Account Is Now Active')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('We have successfully received your payment.')
            ->line('Your Villa Bit AI Server account is now fully active, and all services and enabled features are available for use.')
            ->line('To allow your AI Server to start working, please log in to your account, open the Settings section, and enter your website domain name in the Server Panel.')
            ->line('Once your domain name is added, your AI Server can begin working for your real estate agency.')
            ->action('Log In to Your Account', $dashboardUrl)
            ->line('Thank you for choosing Villa Bit AI.')
            ->salutation("Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai");
    }
}
