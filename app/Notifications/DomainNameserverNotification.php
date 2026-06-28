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

        $message = (new MailMessage)
            ->subject("Action Required: Change Nameservers for {$this->domain}")
            ->greeting("Hello {$name},")
            ->line("Your domain {$this->domain} has now been added to the Villa Bit AI Server Cloudflare DNS system.")
            ->line('You are now at Step 3 of the connection process.')
            ->line("Please log in to the company where you registered {$this->domain}, open the domain nameserver settings, and replace your current nameservers with these new Cloudflare nameservers:")
            ->line('')
            ->line($this->nameserver1 ?: '')
            ->line($this->nameserver2 ?: '')
            ->line('')
            ->line('Please save the changes after replacing the old nameservers.')
            ->line('You only need to change the nameservers. Do not move your website hosting or change your existing hosting server settings.')
            ->line('After you save the nameservers, DNS propagation can take up to 24 hours across the internet. During this period, Villa Bit AI Server will verify your connection automatically.')
            ->line('When the DNS connection is fully propagated, you will see a green confirmation icon in your Villa Bit AI Server panel confirming that your domain has been connected successfully.')
            ->salutation("Kind regards,\n\nVILLA BIT AI Server Team\nAI Server For Real Estate Agencies\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\nhttps://villabit.ai");

        return $message;
    }
}
