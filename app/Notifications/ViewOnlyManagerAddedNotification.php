<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ViewOnlyManagerAddedNotification extends Notification
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $loginLink = url('/login');

        return (new MailMessage)
            ->subject('You Have Been Added as a View-Only Manager')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('You have been added as a **View-Only Manager** inside the Villa Bit AI Server panel.')
            ->line('Your access level is: **View-Only Manager Account**')
            ->line('This means you can view and browse the Real Estate Agency panel, but you **cannot** submit, edit, or change any data.')
            ->line('You can log in to your panel using the link below:')
            ->action('Login to Panel', $loginLink)
            ->line('Please keep your login details secure and do not share your access with anyone.')
            ->salutation("Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai");
    }
}
