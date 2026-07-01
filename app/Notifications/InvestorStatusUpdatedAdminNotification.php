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
    protected string $investorName;
    protected array $changes;

    public function __construct(string $investorEmail, array $changes, string $investorName = '')
    {
        $this->investorEmail = $investorEmail;
        $this->investorName = $investorName;
        $this->changes = $changes;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $signature = "Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai";

        $username = $this->investorName ?: $this->investorEmail;

        // KYC Approved
        if (($this->changes['kyc_status'] ?? null) === 'approved') {
            return (new MailMessage)
                ->subject('KYC Approved — Start AML Review')
                ->greeting('Hello Villa Bit Capital Admin,')
                ->line('KYC has been approved for the investor below.')
                ->line("Username: {$username}")
                ->line("Email: {$this->investorEmail}")
                ->line('KYC Status: Approved')
                ->line('**Next admin action:**')
                ->line('Start or complete the AML review. Update the AML Status in the investor panel when the review is complete.')
                ->salutation($signature);
        }

        // AML Completed
        if (($this->changes['aml_status'] ?? null) === 'approved') {
            $amlDisplay = ucwords(str_replace('_', ' ', $this->changes['aml_status']));
            return (new MailMessage)
                ->subject('AML Review Completed — Accreditation Review Required')
                ->greeting('Hello Villa Bit Capital Admin,')
                ->line('The AML review has been completed for the investor below.')
                ->line("Username: {$username}")
                ->line("Email: {$this->investorEmail}")
                ->line("AML Status: {$amlDisplay}")
                ->line('**Next admin action:**')
                ->line('Update the AML Status. If approved, begin the accreditation review and send the investor any required accreditation documents or instructions.')
                ->salutation($signature);
        }

        // Accreditation Completed
        if (($this->changes['accreditation_status'] ?? null) === 'verified') {
            $accredDisplay = ucwords(str_replace('_', ' ', $this->changes['accreditation_status']));
            return (new MailMessage)
                ->subject('Accreditation Review Completed — Determine Eligible Structure')
                ->greeting('Hello Villa Bit Capital Admin,')
                ->line('The accreditation review has been completed for the investor below.')
                ->line("Username: {$username}")
                ->line("Email: {$this->investorEmail}")
                ->line("Accreditation Status: {$accredDisplay}")
                ->line('**Next admin action:**')
                ->line('Determine and update the Eligible Structure. Then move the investor to the next onboarding phase and send the relevant user email.')
                ->salutation($signature);
        }

        // Eligible Structure Confirmed
        if (isset($this->changes['eligible_structure']) && !empty($this->changes['eligible_structure']) && $this->changes['eligible_structure'] !== 'pending_review') {
            $structure = $this->changes['eligible_structure'] === 'usa_llc' ? 'USA LLC' : 'UK LLP';
            return (new MailMessage)
                ->subject('Eligible Structure Confirmed — Send Investment Documents')
                ->greeting('Hello Villa Bit Capital Admin,')
                ->line('The eligible investment structure has been confirmed for the investor below.')
                ->line("Username: {$username}")
                ->line("Email: {$this->investorEmail}")
                ->line("Eligible Structure: {$structure}")
                ->line('**Next admin action:**')
                ->line('Update the Eligible Structure and Onboarding Phase. Send the correct investment documents for review and signature.')
                ->salutation($signature);
        }

        // Onboarding Phase - Documents Review (Investment Documents and Payment step)
        if (($this->changes['onboarding_phase'] ?? null) === 'documents_review') {
            $structure = 'Pending';
            if (isset($this->changes['eligible_structure']) && $this->changes['eligible_structure'] !== 'pending_review') {
                $structure = $this->changes['eligible_structure'] === 'usa_llc' ? 'USA LLC' : 'UK LLP';
            }
            $phase = ucwords(str_replace('_', ' ', $this->changes['onboarding_phase']));
            return (new MailMessage)
                ->subject('Investment Documents and Payment Step Required')
                ->greeting('Hello Villa Bit Capital Admin,')
                ->line('The investor below is ready for the investment documents and payment step.')
                ->line("Username: {$username}")
                ->line("Email: {$this->investorEmail}")
                ->line("Eligible Structure: {$structure}")
                ->line("Onboarding Phase: {$phase}")
                ->line('**Next admin action:**')
                ->line('Send the required investment documents and bank wire payment instructions. After signed documents and payment are confirmed, update the investor status and grant the appropriate panel access.')
                ->salutation($signature);
        }

        // Onboarding Phase Approved
        if (($this->changes['onboarding_phase'] ?? null) === 'approved') {
            return (new MailMessage)
                ->subject('Onboarding Approved — Investment Active')
                ->greeting('Hello Villa Bit Capital Admin,')
                ->line('Onboarding has been fully approved for the investor below.')
                ->line("Username: {$username}")
                ->line("Email: {$this->investorEmail}")
                ->line('Onboarding Phase: Approved')
                ->line('The investor account is now fully active.')
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
            ->subject('Investor Status Updated')
            ->greeting('Hello Villa Bit Capital Admin,')
            ->line("An investor's status has been updated.")
            ->line("Username: {$username}")
            ->line("Email: {$this->investorEmail}");

        foreach ($this->changes as $field => $value) {
            if ($value && isset($labels[$field])) {
                $displayValue = ucwords(str_replace('_', ' ', $value));
                $message->line("**{$labels[$field]}**: {$displayValue}");
            }
        }

        $message->salutation($signature);

        return $message;
    }
}
