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
        return (new MailMessage)
            ->subject('Your Villa Bit AI Account Has Been Approved!')
            ->greeting("Hello {$notifiable->first_name}!")
            ->line('Great news — your Villa Bit AI account has been approved and you now have full access to the platform.')
            ->action('Access Your Dashboard', url('/admin/dashboard'))
            ->line('Thank you for your patience. We look forward to supporting your business.')
            ->salutation('The Villa Bit AI Team');
    }
}
