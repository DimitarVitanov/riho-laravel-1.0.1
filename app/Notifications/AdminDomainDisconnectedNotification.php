<?php

namespace App\Notifications;

use App\Models\AgencyProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminDomainDisconnectedNotification extends Notification implements ShouldQueue
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
        $agency = $this->profile->agency_name ?? $this->profile->user?->name ?? 'Unknown agency';
        $domain = $this->profile->custom_domain ?? 'Not set';

        return (new MailMessage)
            ->subject('Villa Bit AI: Agency domain not connected')
            ->greeting('Hello Admin,')
            ->line("The domain for agency '{$agency}' is not connected.")
            ->line("Domain: {$domain}")
            ->line('AI features are paused for this agency until DNS is verified.')
            ->action('View Agency', route('admin.villabit.agencies.show', $this->profile->user_id))
            ->salutation("Kind regards,\n\nVILLA BIT AI Server Team");
    }
}
