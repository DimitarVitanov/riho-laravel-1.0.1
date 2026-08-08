<?php

namespace App\Listeners\Est8ads;

use App\Events\Est8ads\PropertyMoveSubmitted;
use App\Events\Est8ads\PropertyRequestUpdated;
use App\Services\Est8ads\BillingService;
use App\Services\Est8ads\Discovery\DiscoveryManager;
use Throwable;

class QueueInternetDiscovery
{
    public function __construct(private DiscoveryManager $manager, private BillingService $billing) {}

    public function handle(PropertyMoveSubmitted|PropertyRequestUpdated $event): void
    {
        try {
            $move = $event->propertyMove;
            $profile = $move->profile;

            // Don't spend AI credits before the member has paid: a billable
            // account still awaiting its first payment gets no discovery. It is
            // (re)dispatched automatically once the payment is confirmed.
            if ($profile && $this->billing->awaitingFirstPayment($profile)) {
                return;
            }

            $this->manager->dispatch($move, $event instanceof PropertyMoveSubmitted ? $event->trigger : 'updated');
        } catch (Throwable $exception) {
            report($exception); // Fail closed when no compliant source/provider exists.
        }
    }
}
