<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountSuspendedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Villa Bit AI Server Account Suspended – Action Required')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('Your Villa Bit AI Server account has been suspended.')
            ->line('Access to your account and AI Server services is currently unavailable.')
            ->line('The usual reasons for suspension are:')
            ->line('• An overdue or unsuccessful payment')
            ->line('• A breach of our Terms of Service')
            ->line('The reason for the suspension should be visible in the previous informational email or billing email.')
            ->line('If you cannot see the reason, or you believe this suspension was made by mistake, please contact our Support Team urgently.')
            ->action('Contact Support', 'mailto:inbox@villabit.ai')
            ->salutation("Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai");
    }
}
