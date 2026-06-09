<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use App\Notifications\WelcomeAfterVerificationNotification;

class SendWelcomeEmailAfterVerification
{
    public function handle(Verified $event): void
    {
        $event->user->notify(new WelcomeAfterVerificationNotification());
    }
}
