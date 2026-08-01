<?php

namespace App\Observers;

use App\Models\AgencyProfile;
use App\Services\Est8ads\AgencyProvisioner;

class AgencyProfileObserver
{
    public function created(AgencyProfile $agencyProfile): void
    {
        app(AgencyProvisioner::class)->provision($agencyProfile);
    }

    public function updated(AgencyProfile $agencyProfile): void
    {
        if ($agencyProfile->wasChanged([
            'agency_name',
            'official_website_url',
            'country',
            'contact_email',
            'contact_phone',
        ])) {
            app(AgencyProvisioner::class)->provision($agencyProfile);
        }
    }
}
