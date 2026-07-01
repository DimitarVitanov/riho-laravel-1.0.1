<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ViewOnlyManagerAddedNotification extends Notification
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $loginLink = url('/login');
        $profile = $notifiable->managerProfile;

        $permissions = [];
        if ($profile) {
            if ($profile->can_manage_agencies) $permissions[] = '✓ Can Manage Agencies';
            if ($profile->can_manage_investors) $permissions[] = '✓ Can Manage Investors';
            if ($profile->can_review_ai_outputs) $permissions[] = '✓ Can Review AI Outputs';
            if ($profile->can_prepare_payouts) $permissions[] = '✓ Can Prepare Payouts';
            if ($profile->can_view_financials) $permissions[] = '✓ Can View Financials';
            if ($profile->can_login_as_user) $permissions[] = '✓ Can Login As User';
            if ($profile->can_view_agency_readonly) $permissions[] = '✓ Can View Agency Panel (Read-Only)';
        }

        $permissionsText = !empty($permissions)
            ? "Your granted permissions:\n\n" . implode("\n\n", $permissions)
            : 'No specific permissions have been assigned yet.';

        return (new MailMessage)
            ->subject('You Have Been Added as a View-Only Manager')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('You have been added as a **View-Only Manager** inside the Villa Bit AI Server panel.')
            ->line('Your access level is: **View-Only Manager Account**')
            ->line($permissionsText)
            ->line('This means you can view and browse the Real Estate Agency panel, but you **cannot** submit, edit, or change any data.')
            ->line('You can log in to your panel using the link below:')
            ->action('Login to Panel', $loginLink)
            ->line('Please keep your login details secure and do not share your access with anyone.')
            ->salutation("Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai");
    }
}
