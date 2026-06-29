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
            'investor'           => 'Real Estate Investor',
            'manager'            => 'Manager Account',
            'super_admin', 'admin' => 'Administrator',
            default              => ucfirst($notifiable->role ?? 'User'),
        };

        $subType = match ($notifiable->agency_server_type ?? '') {
            'subdomain_ai_server'      => 'Subdomain Villa Bit AI Server',
            'domain_folder_ai_server'  => 'Domain Folder Villa Bit AI Server',
            default                    => null,
        };

        $price = $notifiable->agency_server_price ? '$' . number_format($notifiable->agency_server_price, 2) . ' per month' : null;

        $paypalLink = match ($notifiable->agency_server_type ?? '') {
            'domain_folder_ai_server' => 'https://app.villabit.ai/folder.php',
            'subdomain_ai_server'     => 'https://app.villabit.ai/subdomain.php',
            default                   => null,
        };

        $signature = "Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai";

        if ($notifiable->role === 'investor') {
            $message = (new MailMessage)
                ->subject('Complete KYC to Activate Your Investor Account')
                ->greeting("Hello {$notifiable->first_name},")
                ->line('Your email address has been confirmed successfully.')
                ->line("Your account type is: {$userType}")
                ->line('Your account is currently on hold because KYC verification and payment are required before activation.')
                ->line('To activate your account, first complete the KYC process. After your KYC is approved, complete your investment payment securely by bank wire transfer. Once both steps are completed, your account will be activated and your investment process will begin.')
                ->line('Note: If you want to become a reseller only, your account does not need to be fully activated. Your affiliate link and all reseller features are fully active.')
                ->line("You can log in to your panel here: {$loginLink}")
                ->line('For Real Estate Investor accounts, Villa Bit AI Server gives access to investor-related areas connected with real estate development opportunities, multi-layered investment profit possibilities, preferred-return information, and project participation details where applicable.')
                ->salutation($signature);

            return $message;
        }

        $message = (new MailMessage)
            ->subject('Email Confirmed — Activate Your Villa Bit AI Server Account by Making a Payment')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('Your email address has been confirmed successfully.')
            ->line("Your account type is: {$userType}");

        if ($subType) {
            $message->line("Your subaccount type is: {$subType}");
        }

        if ($price) {
            $message->line("Your selected monthly price is: {$price}");
        }

        if ($paypalLink) {
            $message
                ->line('To activate your account and add it to the Villa Bit AI Server, please click the button below and complete payment securely through PayPal:')
                ->action('ACTIVATE YOUR ACCOUNT AND PAY WITH PAYPAL', $paypalLink);
        } else {
            $message->line('To activate your account and add it to the Villa Bit AI Server, please select a plan and complete payment securely through PayPal.');
        }

        $message
            ->line('Once payment is completed, your account will be activated and added to the AI Server setup process.')
            ->line("You can log in to your panel here: {$loginLink}")
            ->line('For Real Estate Agency accounts, Villa Bit AI Server helps agencies increase sales, build a stronger local presence, create useful second-level content, analyze competitors, improve AI-search readiness, and strengthen online authority.')
            ->salutation($signature);

        return $message;
    }
}
