<?php

namespace App\Providers;

use App\Listeners\SendWelcomeEmailAfterVerification;
use App\Models\AgencyProfile;
use App\Observers\AgencyProfileObserver;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        AgencyProfile::observe(AgencyProfileObserver::class);
        Event::listen(Verified::class, SendWelcomeEmailAfterVerification::class);
    }
}
