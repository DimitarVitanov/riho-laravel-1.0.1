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
        $user = $this->profile->user;
        $agencyName = $this->profile->agency_name ?? 'Unknown agency';
        $domain = $this->profile->custom_domain ?? 'Not set';
        $email = $user?->email ?? 'N/A';
        $firstName = $user?->first_name ?? '';
        $lastName = $user?->last_name ?? '';
        $fullName = trim("{$firstName} {$lastName}") ?: 'N/A';
        
        $signature = "Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai";

        return (new MailMessage)
            ->subject("⚠️ DNS Disconnected: {$domain}")
            ->greeting('Hello Villa Bit AI Admin,')
            ->line("An agency's domain nameservers have been changed and are no longer pointing to Villa Bit AI.")
            ->line('**Agency Details:**')
            ->line("• **Agency Name:** {$agencyName}")
            ->line("• **Domain:** {$domain}")
            ->line("• **Contact Name:** {$fullName}")
            ->line("• **Email:** {$email}")
            ->line('The agency has been moved back to the "Nameserver Pending" step and their account is now on waitlist.')
            ->action('View Agency in Admin Panel', route('admin.villabit.agencies.show', $this->profile->user_id))
            ->salutation($signature);
    }
}
