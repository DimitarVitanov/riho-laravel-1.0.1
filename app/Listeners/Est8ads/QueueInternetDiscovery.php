<?php

namespace App\Listeners\Est8ads;

use App\Events\Est8ads\PropertyMoveSubmitted;
use App\Events\Est8ads\PropertyRequestUpdated;
use App\Services\Est8ads\Discovery\DiscoveryManager;
use Throwable;

class QueueInternetDiscovery
{
    public function __construct(private DiscoveryManager $manager) {}

    public function handle(PropertyMoveSubmitted|PropertyRequestUpdated $event): void
    {
        try {
            $this->manager->dispatch($event->propertyMove, $event instanceof PropertyMoveSubmitted ? $event->trigger : 'updated');
        } catch (Throwable $exception) {
            report($exception); // Fail closed when no compliant source/provider exists.
        }
    }
}
