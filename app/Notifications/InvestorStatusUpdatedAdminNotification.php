<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvestorStatusUpdatedAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $investorEmail;
    protected array $changes;

    public function __construct(string $investorEmail, array $changes)
    {
        $this->investorEmail = $investorEmail;
        $this->changes = $changes;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $signature = "Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai";

        $labels = [
            'kyc_status'           => 'KYC Status',
            'aml_status'           => 'AML Status',
            'accreditation_status' => 'Accreditation Status',
            'eligible_structure'   => 'Eligible Structure',
            'onboarding_phase'     => 'Onboarding Phase',
        ];

        $message = (new MailMessage)
            ->subject('Investor Status Updated')
            ->greeting('Hello Villa Bit Capital Admin,')
            ->line("An investor's status has been updated.")
            ->line("Email: {$this->investorEmail}");

        foreach ($this->changes as $field => $value) {
            if ($value && isset($labels[$field])) {
                $displayValue = ucwords(str_replace('_', ' ', $value));
                $message->line("**{$labels[$field]}**: {$displayValue}");
            }
        }

        $message->line('This is a confirmation that the status change has been applied.')
            ->salutation($signature);

        return $message;
    }
}
