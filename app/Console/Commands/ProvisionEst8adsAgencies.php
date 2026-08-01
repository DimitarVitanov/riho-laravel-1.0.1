<?php

namespace App\Console\Commands;

use App\Models\AgencyProfile;
use App\Services\Est8ads\AgencyProvisioner;
use Illuminate\Console\Command;

class ProvisionEst8adsAgencies extends Command
{
    protected $signature = 'est8ads:provision-agencies';

    protected $description = 'Provision EST8ADS access and agency profiles for every Villa Bit agency';

    public function handle(AgencyProvisioner $provisioner): int
    {
        $count = 0;

        AgencyProfile::with('user')->whereHas('user')->chunkById(100, function ($profiles) use ($provisioner, &$count) {
            foreach ($profiles as $profile) {
                $provisioner->provision($profile);
                $count++;
            }
        });

        $this->info("Provisioned {$count} Villa Bit agencies for EST8ADS.");

        return self::SUCCESS;
    }
}
