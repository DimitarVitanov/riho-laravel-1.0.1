<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvestorStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $changes;

    public function __construct(array $changes)
    {
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
            ->subject('Your Investor Status Has Been Updated')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('We would like to inform you that your investor account status has been updated.');

        foreach ($this->changes as $field => $value) {
            if ($value && isset($labels[$field])) {
                $displayValue = ucwords(str_replace('_', ' ', $value));
                $message->line("**{$labels[$field]}**: {$displayValue}");
            }
        }

        $message->line('If you have any questions, please contact us through your support panel.')
            ->salutation($signature);

        return $message;
    }
}
