<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use App\Notifications\WelcomeAfterVerificationNotification;
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

        $event->user->notify(new WelcomeAfterVerificationNotification());
    }
}
