<?php

namespace App\Providers;

use App\Events\Est8ads\PropertyMoveSubmitted;
use App\Events\Est8ads\PropertyRequestUpdated;
use App\Listeners\Est8ads\QueueInternetDiscovery;
use App\Listeners\SendWelcomeEmailAfterVerification;
use App\Models\AgencyProfile;
use App\Models\Est8ads\Message;
use App\Models\Est8ads\Profile;
use App\Models\Est8ads\Property;
use App\Models\Est8ads\PropertyMove;
use App\Observers\AgencyProfileObserver;
use App\Observers\Est8ads\MessageObserver;
use App\Observers\Est8ads\ProfileObserver;
use App\Observers\Est8ads\PropertyMoveObserver;
use App\Observers\Est8ads\PropertyObserver;
use App\Services\Est8ads\Discovery\ProviderRegistry;
use App\Services\Est8ads\Discovery\Providers\AiWebSearchProvider;
use App\Services\Est8ads\Discovery\Providers\SitemapScraperProvider;
use App\Services\Est8ads\Discovery\Providers\WebSearchProvider;
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
        // EST8ADS internet discovery adapters. The key is the `adapter` value on
        // the internet source configuration; unknown keys fall back to the
        // UnconfiguredProvider, which refuses to run.
        $this->app->singleton(ProviderRegistry::class, fn ($app) => new ProviderRegistry([
            'web_search' => $app->make(WebSearchProvider::class),
            'ai_web_search' => $app->make(AiWebSearchProvider::class),
            'sitemap_scraper' => $app->make(SitemapScraperProvider::class),
        ]));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        AgencyProfile::observe(AgencyProfileObserver::class);
        PropertyMove::observe(PropertyMoveObserver::class);
        Property::observe(PropertyObserver::class);
        Message::observe(MessageObserver::class);
        Profile::observe(ProfileObserver::class);
        Event::listen(Verified::class, SendWelcomeEmailAfterVerification::class);
        Event::listen(PropertyMoveSubmitted::class, QueueInternetDiscovery::class);
        Event::listen(PropertyRequestUpdated::class, QueueInternetDiscovery::class);
    }
}
