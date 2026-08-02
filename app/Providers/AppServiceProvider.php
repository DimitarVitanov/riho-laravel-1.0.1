<?php

namespace App\Providers;

use App\Events\Est8ads\PropertyMoveSubmitted;
use App\Events\Est8ads\PropertyRequestUpdated;
use App\Listeners\Est8ads\QueueInternetDiscovery;
use App\Listeners\SendWelcomeEmailAfterVerification;
use App\Models\AgencyProfile;
use App\Models\Est8ads\Property;
use App\Models\Est8ads\PropertyMove;
use App\Observers\AgencyProfileObserver;
use App\Observers\Est8ads\PropertyMoveObserver;
use App\Observers\Est8ads\PropertyObserver;
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
        PropertyMove::observe(PropertyMoveObserver::class);
        Property::observe(PropertyObserver::class);
        Event::listen(Verified::class, SendWelcomeEmailAfterVerification::class);
        Event::listen(PropertyMoveSubmitted::class, QueueInternetDiscovery::class);
        Event::listen(PropertyRequestUpdated::class, QueueInternetDiscovery::class);
    }
}
