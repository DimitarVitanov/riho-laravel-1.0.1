<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainNameserverNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $domain,
        public ?string $nameserver1,
        public ?string $nameserver2,
        public ?string $serverType = null
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $name = $notifiable->first_name ?: $notifiable->name;

        $domain = $this->domain;
        $ns1 = $this->nameserver1 ?: '';
        $ns2 = $this->nameserver2 ?: '';
        $signature = "Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai";

        $message = (new MailMessage)
            ->subject("Action Required: Change Nameservers for {$domain}")
            ->greeting("Hello {$name},")
            ->line("Your domain {$domain} has now been added to the Villa Bit AI Server Cloudflare DNS system for the connection:")
            ->line("**{$domain}**")
            ->line('You now need to complete the final step of the Villa Bit AI Server connection process.')
            ->line("Please log in to the company where you registered {$domain}, open the domain nameserver settings, and replace your current nameservers with these new Cloudflare nameservers:")
            ->line("**{$ns1}**")
            ->line("**{$ns2}**")
            ->line('Please save the changes after replacing the old nameservers.')
            ->line('You are only changing the nameservers so they can manage both your existing domain and the Villa Bit AI Server connection at the same time. You are not moving your existing website hosting or pages, and nothing on your existing website should change.')
            ->line('After you save the nameservers, DNS propagation can take up to 24 hours across the internet. During this period, Villa Bit AI Server will verify your connection automatically.')
            ->line('When the DNS connection has fully propagated, you will see a green confirmation icon in the Domain section of your Villa Bit AI Server panel: **LIVE — CONNECTED TO VILLA BIT AI SERVER**')
            ->line('This confirms that your domain has been connected successfully. We will also send you an email confirmation.')
            ->line('After that, you can start using Villa Bit AI Server.')
            ->salutation($signature);

        return $message;
    }
}
