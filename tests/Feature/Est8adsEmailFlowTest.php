<?php

namespace Tests\Feature;

use App\Models\Est8ads\Profile;
use App\Models\User;
use App\Notifications\Est8ads\PaymentConfirmedNotification;
use App\Notifications\Est8ads\PaymentRequestNotification;
use App\Notifications\Est8ads\VerifyEmailNotification as Est8adsVerifyEmailNotification;
use App\Notifications\Est8ads\WelcomeNotification as Est8adsWelcomeNotification;
use App\Notifications\VerifyEmailNotification as VillabitVerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/**
 * End-to-end verification of the EST8ADS-only transactional email sequence:
 * verification -> welcome -> payment request -> payment confirmed. Every
 * message must go out through the dedicated "est8ads" mailer from
 * real@est8ads.com, never the default Villa Bit sender.
 */
class Est8adsEmailFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_est8ads_email_sequence_fires_from_the_est8ads_sender(): void
    {
        Notification::fake();

        // 1. Register as an individual directly on EST8ADS.
        $this->post('http://est8ads.com/register', [
            'account_type' => 'individual',
            'first_name' => 'Ana',
            'last_name' => 'Kovac',
            'email' => 'ana@example.com',
            'phone' => '+385 91 555 1122',
            'country' => 'Croatia',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
            'terms' => 'on',
        ])->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'ana@example.com')->firstOrFail();
        $this->assertTrue($user->isEst8adsOnly());

        // The verification email is the EST8ADS-branded one, not Villa Bit's,
        // and it is sent through the est8ads mailer from real@est8ads.com.
        Notification::assertSentTo($user, Est8adsVerifyEmailNotification::class,
            fn ($notification) => $this->assertEst8adsSender($notification->toMail($user)));
        Notification::assertNotSentTo($user, VillabitVerifyEmailNotification::class);

        // No welcome/payment mail until the address is actually confirmed.
        Notification::assertNotSentTo($user, Est8adsWelcomeNotification::class);
        Notification::assertNotSentTo($user, PaymentRequestNotification::class);

        // 2. Confirm the email via the signed verification link.
        $verifyUrl = URL::temporarySignedRoute('verification.verify', now()->addHour(), [
            'id' => $user->id,
            'hash' => sha1($user->getEmailForVerification()),
        ]);
        $this->get($verifyUrl)->assertRedirect(route('est8ads.dashboard'));
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        // 3. Welcome + payment-request emails fire on verification, both from
        //    the est8ads sender.
        Notification::assertSentTo($user, Est8adsWelcomeNotification::class,
            fn ($notification) => $this->assertEst8adsSender($notification->toMail($user)));
        Notification::assertSentTo($user, PaymentRequestNotification::class,
            fn ($notification) => $this->assertEst8adsSender($notification->toMail($user)));

        // 4. An admin confirms the PayPal payment through the admin panel, which
        //    sends the "payment confirmed / account active" email.
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'has_est8ads_access' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('secret-password'),
        ]);

        $profile = Profile::where('user_id', $user->id)->firstOrFail();
        $invoice = $profile->invoices()->latest('issued_on')->firstOrFail();

        $this->actingAs($admin)
            ->postJson(route('est8ads.admin.invoices.mark-paid', $invoice))
            ->assertOk();

        Notification::assertSentTo($user, PaymentConfirmedNotification::class,
            fn ($notification) => $this->assertEst8adsSender($notification->toMail($user)));
    }

    /**
     * Asserts a built MailMessage goes out through the dedicated est8ads
     * mailer from the EST8ADS Proton address. Returns true so it can be used
     * as the assertSentTo truth-test closure.
     */
    private function assertEst8adsSender(MailMessage $mail): bool
    {
        $this->assertSame('est8ads', $mail->mailer);
        $this->assertSame(['real@est8ads.com', 'EST8ADS'], $mail->from);

        return true;
    }
}
