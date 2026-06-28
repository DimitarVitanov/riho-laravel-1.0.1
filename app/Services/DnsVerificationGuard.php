<?php

namespace App\Services;

use App\Models\AgencyProfile;
use App\Models\User;
use App\Notifications\AdminDomainDisconnectedNotification;
use Illuminate\Support\Facades\Notification;

class DnsVerificationGuard
{
    public static function check(AgencyProfile $profile): bool
    {
        if (!$profile->custom_domain || !$profile->server_ip) {
            return false;
        }

        return $profile->is_dns_verified;
    }

    public static function ensureVerified(AgencyProfile $profile): bool
    {
        if (self::check($profile)) {
            return true;
        }

        self::notifyAdmins($profile);

        return false;
    }

    protected static function notifyAdmins(AgencyProfile $profile): void
    {
        $adminEmails = User::where('role', 'admin')->pluck('email')->filter();
        if ($adminEmails->isEmpty()) {
            return;
        }

        Notification::route('mail', $adminEmails->toArray())
            ->notify(new AdminDomainDisconnectedNotification($profile));
    }
}
