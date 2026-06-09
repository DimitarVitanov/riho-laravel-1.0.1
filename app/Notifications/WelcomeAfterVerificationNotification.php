<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeAfterVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $loginLink = url('/login');

        $userType = match ($notifiable->role) {
            'real_estate_agency' => 'Real Estate Agency',
            'investor' => 'Real Estate Investor',
            'manager' => 'Manager Account',
            'super_admin', 'admin' => 'Administrator',
            default => ucfirst($notifiable->role ?? 'User'),
        };

        $message = (new MailMessage)
            ->subject('Welcome to Villa Bit AI Server')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('Your email address has been confirmed successfully.')
            ->line('Your account is currently on the waiting list because our AI server resources are already allocated to existing clients, and full panel access will be granted on a first-registered, first-served basis as additional capacity becomes available. You do not need to take any further action at this stage. Demand for Villa Bit AI Server is currently higher than the available AI server resources, which is a sign of growing interest, and our team is actively working to expand capacity and activate new accounts every week.')
            ->line("Your account type is: **{$userType}**")
            ->line('You can now log in to your panel and continue with your account setup.')
            ->action('LOGIN TO YOUR PANEL', $loginLink);

        $message->line('For Real Estate Agency accounts, Villa Bit AI Server is focused on helping agencies increase sales, build a stronger local presence, create useful second-level content, analyze competitors, improve AI-search readiness, and strengthen online authority.');
        $message->line('For Real Estate Investor accounts, Villa Bit AI Server gives access to investor-related areas connected with real estate development opportunities, multi-layered investment profit possibilities, preferred-return information, and project participation details where applicable.');

        $message->line('You can log in and start using your Villa Bit AI Server.')
            ->salutation("Kind regards,\nVilla Bit AI Server Team\nhttps://villabit.ai");

        return $message;
    }
}
