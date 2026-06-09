<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expiryTime = config('auth.passwords.users.expire', 60) . ' minutes';

        $userType = match ($notifiable->role) {
            'real_estate_agency' => 'Real Estate Agency',
            'investor' => 'Real Estate Investor',
            'manager' => 'Manager Account',
            'super_admin', 'admin' => 'Administrator',
            default => ucfirst($notifiable->role ?? 'User'),
        };

        return (new MailMessage)
            ->subject('Reset Your Villa Bit AI Server Password')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('We received a request to reset the password for your Villa Bit AI Server account.')
            ->line("Your registered account type is: **{$userType}**")
            ->line('To create a new password, please click the button below:')
            ->action('RESET PASSWORD', $resetUrl)
            ->line("For security reasons, this password reset link will expire after {$expiryTime}.")
            ->line('If you did not request a password reset, you can safely ignore this email. Your current password will remain unchanged.')
            ->salutation("Kind regards,\nVilla Bit AI Server Team\nhttps://villabit.ai");
    }
}
