<?php

namespace App\Observers\Est8ads;

use App\Models\Est8ads\Profile;
use App\Services\Est8ads\BillingService;

class ProfileObserver
{
    public function __construct(private BillingService $billing)
    {
    }

    /**
     * Opens the first $12/month invoice as soon as an individual signs up
     * for EST8ADS. Agency-mirrored profiles (type "agency_contact", created
     * by the chain-discovery pipeline) are never billed.
     */
    public function created(Profile $profile): void
    {
        $this->billing->createFirstInvoice($profile);
    }
}
