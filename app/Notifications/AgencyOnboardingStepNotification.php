<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgencyOnboardingStepNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $step)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $notifiable->first_name ?: $notifiable->name;
        $loginUrl = url('/login');
        $signature = "Kind regards,\n\nVILLA BIT AI Server Team\\\nAI Server For Real Estate Agencies\\\n┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄ ┄\\\nVilla Bit AI Really Works Better, More, And Cheaper Than A Human!\\\nhttps://villabit.ai";

        return match ($this->step) {
            User::ONBOARDING_PAYMENT_CONFIRMED => $this->paymentConfirmedEmail($name, $loginUrl, $signature),
            User::ONBOARDING_AI_SERVER_SETUP => $this->aiServerSetupEmail($name, $loginUrl, $signature),
            User::ONBOARDING_DOMAIN_CONNECTION => $this->domainConnectionEmail($name, $loginUrl, $signature),
            User::ONBOARDING_NAMESERVER_PENDING => $this->nameserverPendingEmail($name, $loginUrl, $signature),
            User::ONBOARDING_COMPLETED => $this->completedEmail($name, $loginUrl, $signature),
            default => $this->defaultEmail($name, $loginUrl, $signature),
        };
    }

    private function paymentConfirmedEmail(string $name, string $loginUrl, string $signature): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Confirmed – Your Villa Bit AI Server Setup Has Begun')
            ->greeting("Hello {$name},")
            ->line('We have successfully received your payment. Thank you!')
            ->line('Your Villa Bit AI Server account setup has now begun. Our team is preparing your AI server environment.')
            ->line('**What happens next:**')
            ->line('1. Our team will configure your dedicated AI server (usually within 24-48 hours)')
            ->line('2. You will receive an email when your server is ready')
            ->line('3. You will then enter your domain name to connect to Villa Bit AI')
            ->line('You can check your progress at any time by logging into your account.')
            ->action('Check Your Progress', $loginUrl)
            ->salutation($signature);
    }

    private function aiServerSetupEmail(string $name, string $loginUrl, string $signature): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Villa Bit AI Server Is Being Configured')
            ->greeting("Hello {$name},")
            ->line('Great news! Your Villa Bit AI Server is now being configured by our team.')
            ->line('This process typically takes 24-48 hours. You will receive an email notification as soon as your server is ready.')
            ->line('**Current Status:** AI Server Setup in Progress')
            ->line('Once setup is complete, you will be able to enter your domain name and connect it to your Villa Bit AI Server.')
            ->action('Check Your Progress', $loginUrl)
            ->salutation($signature);
    }

    private function domainConnectionEmail(string $name, string $loginUrl, string $signature): MailMessage
    {
        return (new MailMessage)
            ->subject('Action Required: Enter Your Domain Name')
            ->greeting("Hello {$name},")
            ->line('Your Villa Bit AI Server is ready! 🎉')
            ->line('**Action Required:** Please log in to your account and enter your domain name to connect it to your Villa Bit AI Server.')
            ->line('**How to connect your domain:**')
            ->line('1. Log in to your Villa Bit AI account')
            ->line('2. Go to Settings → Domain')
            ->line('3. Enter your domain name (e.g., yourdomain.com/villabit)')
            ->line('4. Save your settings')
            ->line('After you enter your domain, we will provide you with Cloudflare nameservers to complete the connection.')
            ->action('Enter Your Domain Now', $loginUrl)
            ->salutation($signature);
    }

    private function nameserverPendingEmail(string $name, string $loginUrl, string $signature): MailMessage
    {
        return (new MailMessage)
            ->subject('Action Required: Update Your Domain Nameservers')
            ->greeting("Hello {$name},")
            ->line('Your domain has been added to the Villa Bit AI Server system.')
            ->line('**Final Step:** Please update your domain nameservers to complete the connection.')
            ->line('Log in to your account to see the exact Cloudflare nameservers you need to use. Then, go to your domain registrar and update the nameservers.')
            ->line('**Important:** DNS propagation can take up to 24 hours. Once complete, your Villa Bit AI Server will be fully active.')
            ->action('View Your Nameservers', $loginUrl)
            ->salutation($signature);
    }

    private function completedEmail(string $name, string $loginUrl, string $signature): MailMessage
    {
        return (new MailMessage)
            ->subject('🎉 Your Villa Bit AI Server Is Now LIVE!')
            ->greeting("Hello {$name},")
            ->line('Congratulations! Your Villa Bit AI Server is now fully active and ready to use.')
            ->line('All features in your account are now available. Your AI-powered real estate tools are ready to help grow your business.')
            ->line('**What you can do now:**')
            ->line('• Create AI-powered local SEO pages')
            ->line('• Generate authority builder content')
            ->line('• Run competitor analysis')
            ->line('• And much more!')
            ->action('Start Using Villa Bit AI', $loginUrl)
            ->line('Thank you for choosing Villa Bit AI. We are excited to help your real estate agency succeed!')
            ->salutation($signature);
    }

    private function defaultEmail(string $name, string $loginUrl, string $signature): MailMessage
    {
        return (new MailMessage)
            ->subject('Villa Bit AI Server – Account Update')
            ->greeting("Hello {$name},")
            ->line('Your Villa Bit AI Server account has been updated.')
            ->line('Please log in to check your current status and next steps.')
            ->action('Log In to Your Account', $loginUrl)
            ->salutation($signature);
    }
}
