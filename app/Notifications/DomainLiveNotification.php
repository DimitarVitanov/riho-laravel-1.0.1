<?php

namespace App\Notifications;

use App\Models\AgencyProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainLiveNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public AgencyProfile $profile)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $name = $notifiable->first_name ?: $notifiable->name;
        $domain = $this->profile->custom_domain ?? 'your domain';
        $loginLink = url('/login');
        $signature = "Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai";

        return (new MailMessage)
            ->subject("Your Domain is Now LIVE on Villa Bit AI Server")
            ->greeting("Hello {$name},")
            ->line("Your domain {$domain} is now verified, connected, and LIVE on the Villa Bit AI Server.")
            ->line('All available features in your account can now be used. You can log in to your Villa Bit AI Server panel at any time to review your settings, activity, and available features.')
            ->action('LOGIN TO YOUR PANEL', $loginLink)
            ->salutation($signature);
    }
}
