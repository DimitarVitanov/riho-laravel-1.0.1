<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ManagerAddedNotification extends Notification implements ShouldQueue
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
            ->subject('You Have Been Added as a Villa Bit AI Server Manager')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('You have been added as a Manager inside the Villa Bit AI Server panel.')
            ->line('Your access level is: **Manager Account**')
            ->line('This means you can access and manage assigned areas of the Villa Bit AI Server panel, depending on the permissions given to you by the Administrator.')
            ->line('You can log in to your Manager panel using the link below:')
            ->action('Login to Manager Panel', $loginLink)
            ->line('Please keep your login details secure and do not share your access with anyone.')
            ->salutation("Kind regards,\nVilla Bit AI Server Team\nhttps://villabit.ai");
    }
}
