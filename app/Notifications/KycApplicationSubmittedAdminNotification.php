<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KycApplicationSubmittedAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $investorEmail;

    public function __construct(string $investorEmail)
    {
        $this->investorEmail = $investorEmail;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $signature = "Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai";

        return (new MailMessage)
            ->subject('New KYC Application Submitted')
            ->greeting('Hello Villa Bit Capital Admin,')
            ->line('A new KYC application has been submitted and needs to be reviewed.')
            ->line("Email: {$this->investorEmail}")
            ->line('Please review the submitted KYC information and documents.')
            ->salutation($signature);
    }
}
