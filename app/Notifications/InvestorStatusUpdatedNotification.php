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

        // KYC Approved
        if (($this->changes['kyc_status'] ?? null) === 'approved') {
            return (new MailMessage)
                ->subject('KYC Approved — AML Review Has Started')
                ->greeting("Hello {$notifiable->first_name},")
                ->line('Your KYC application has been approved.')
                ->line('**Next action:**')
                ->line('No action is required from you at this stage. Villa Bit Capital has now started the AML review of your submitted information.')
                ->line('We will contact you only if we need additional information or documents. When the AML review is completed, you will receive the next onboarding update.')
                ->salutation($signature);
        }

        // AML Approved
        if (($this->changes['aml_status'] ?? null) === 'approved') {
            return (new MailMessage)
                ->subject('AML Review Update — Accreditation Step Required')
                ->greeting("Hello {$notifiable->first_name},")
                ->line('Your AML review has been completed.')
                ->line('**Next action:**')
                ->line('Please complete the accreditation step when requested by Villa Bit Capital. We will send you the exact accreditation information, documents, and confirmation requirements that apply to your investor profile.')
                ->line('After accreditation is completed, Villa Bit Capital will review the eligible investment structure for your participation.')
                ->salutation($signature);
        }

        // Accreditation Verified
        if (($this->changes['accreditation_status'] ?? null) === 'verified') {
            return (new MailMessage)
                ->subject('Accreditation Review Complete — Investment Structure Review Started')
                ->greeting("Hello {$notifiable->first_name},")
                ->line('Your accreditation review has been completed.')
                ->line('**Next action:**')
                ->line('No action is required from you at this stage. Villa Bit Capital will now review the eligible investment structure for your participation.')
                ->line('We will contact you with the selected structure and any documents you need to review or sign before payment instructions are issued.')
                ->salutation($signature);
        }

        // Eligible Structure Confirmed / Investment Documents and Payment
        if (isset($this->changes['eligible_structure']) && !empty($this->changes['eligible_structure']) && $this->changes['eligible_structure'] !== 'pending_review') {
            return (new MailMessage)
                ->subject('Investment Documents and Payment Instructions')
                ->greeting("Hello {$notifiable->first_name},")
                ->line('Your investment structure review has been completed, and your investment documents are now ready.')
                ->line('**Next action:**')
                ->line('1. Review the investment documents sent to you.')
                ->line('2. Sign all required agreements.')
                ->line('3. Complete your investment payment by bank wire transfer using the payment instructions provided by Villa Bit Capital.')
                ->line('4. Send us payment confirmation if requested.')
                ->line('Your investment process can begin only after the required documents are signed and your payment has been received and confirmed.')
                ->salutation($signature);
        }

        // Onboarding Phase Approved
        if (($this->changes['onboarding_phase'] ?? null) === 'approved') {
            return (new MailMessage)
                ->subject('Onboarding Approved — Your Investment Is Active')
                ->greeting("Hello {$notifiable->first_name},")
                ->line('Your onboarding process has been fully approved. Your investment account is now active.')
                ->line('You can log in to your investor panel at any time to view your investment details, documents, and distributions.')
                ->salutation($signature);
        }

        // Generic fallback
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
