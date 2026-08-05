<?php

namespace App\Listeners;

use App\Models\Est8ads\Profile;
use App\Notifications\Est8ads\PaymentRequestNotification;
use App\Notifications\Est8ads\WelcomeNotification as Est8adsWelcomeNotification;
use App\Notifications\WelcomeAfterVerificationNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Cache;

class SendWelcomeEmailAfterVerification
{
    public function handle(Verified $event): void
    {
        $key = 'welcome_email_sent_' . $event->user->id;

        if (Cache::has($key)) {
            return;
        }

        Cache::put($key, true, now()->addHours(24));

        if ($event->user->isEst8adsOnly()) {
            $event->user->notify(new Est8adsWelcomeNotification());

            $profile = Profile::where('user_id', $event->user->id)->first();

            if ($profile && $profile->type === 'individual') {
                $invoice = $profile->invoices()->latest('issued_on')->first();
                $event->user->notify(new PaymentRequestNotification($invoice));
            }

            return;
        }

        $event->user->notify(new WelcomeAfterVerificationNotification());
    }
}
