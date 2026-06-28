<?php

namespace App\Console\Commands;

use App\Models\AgencyProfile;
use App\Models\User;
use App\Notifications\AdminDomainDisconnectedNotification;
use App\Services\DomainVerificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class VerifyAgencyDomains extends Command
{
    protected $signature = 'app:verify-agency-domains';
    protected $description = 'Verify DNS connection for all agency domains';

    public function handle(DomainVerificationService $service): int
    {
        $profiles = AgencyProfile::whereNotNull('custom_domain')
            ->whereNotNull('server_ip')
            ->with('user')
            ->get();

        $verified = 0;
        $failed = 0;

        foreach ($profiles as $profile) {
            $wasVerified = $profile->is_dns_verified;
            $isVerified = $service->verify($profile);

            if ($isVerified) {
                $verified++;
                $this->info("{$profile->custom_domain}: verified");
            } else {
                $failed++;
                $this->info("{$profile->custom_domain}: failed");

                if ($wasVerified) {
                    $this->notifyAdmins($profile);
                }
            }
        }

        $this->info("Done. Verified: {$verified}, Failed: {$failed}");

        return self::SUCCESS;
    }

    protected function notifyAdmins(AgencyProfile $profile): void
    {
        $adminEmails = User::where('role', 'admin')->pluck('email')->filter();
        if ($adminEmails->isEmpty()) {
            return;
        }

        Notification::route('mail', $adminEmails->toArray())
            ->notify(new AdminDomainDisconnectedNotification($profile));
    }
}
