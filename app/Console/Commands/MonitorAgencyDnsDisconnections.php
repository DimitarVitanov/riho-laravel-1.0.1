<?php

namespace App\Console\Commands;

use App\Models\AgencyProfile;
use App\Models\User;
use App\Notifications\AdminDomainDisconnectedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class MonitorAgencyDnsDisconnections extends Command
{
    protected $signature = 'app:monitor-agency-dns-disconnections';

    protected $description = 'Monitor active agencies for DNS disconnections and notify admins';

    public function handle()
    {
        $this->info('Monitoring active agencies for DNS disconnections...');

        // Get all active agencies with verified DNS
        $agencies = AgencyProfile::whereHas('user', function ($q) {
            $q->where('status', 'active')
              ->where('role', 'real_estate_agency')
              ->where('onboarding_step', User::ONBOARDING_COMPLETED);
        })
        ->where('is_dns_verified', true)
        ->whereNull('dns_disconnect_notified_at') // Not already notified
        ->whereNotNull('custom_domain')
        ->where('custom_domain', '!=', '')
        ->whereNotNull('nameserver_1')
        ->get();

        $this->info("Found {$agencies->count()} active agencies to monitor.");

        foreach ($agencies as $profile) {
            $domain = $profile->custom_domain;
            $expectedNs1 = strtolower(trim($profile->nameserver_1));
            $expectedNs2 = strtolower(trim($profile->nameserver_2 ?? ''));

            $this->line("Checking {$domain}...");

            // Get current nameservers for the domain
            $currentNameservers = $this->getDomainNameservers($domain);

            if (empty($currentNameservers)) {
                $this->warn("  Could not resolve nameservers for {$domain} - skipping");
                continue;
            }

            $currentNsLower = array_map('strtolower', $currentNameservers);

            // Check if expected nameservers are still in the current nameservers
            $ns1Match = in_array($expectedNs1, $currentNsLower);
            $ns2Match = empty($expectedNs2) || in_array($expectedNs2, $currentNsLower);

            if (!$ns1Match || !$ns2Match) {
                $this->error("  ⚠️ DNS DISCONNECTED for {$domain}!");
                $this->line("    Expected: {$expectedNs1}, {$expectedNs2}");
                $this->line("    Current: " . implode(', ', $currentNameservers));

                // Update profile - mark as disconnected and set notification flag
                $profile->update([
                    'is_dns_verified' => false,
                    'last_dns_check_at' => now(),
                    'dns_disconnect_notified_at' => now(),
                ]);

                // Move user back to step 5 (nameserver pending) and waitlist
                $user = $profile->user;
                $user->update([
                    'onboarding_step' => User::ONBOARDING_NAMESERVER_PENDING,
                    'onboarding_step_updated_at' => now(),
                    'status' => 'waitlist',
                ]);

                // Notify all admins
                $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
                Notification::send($admins, new AdminDomainDisconnectedNotification($profile));

                $this->info("  → Agency moved to waitlist, admins notified.");

                Log::warning("DNS disconnected for agency", [
                    'user_id' => $user->id,
                    'domain' => $domain,
                    'expected_ns' => [$expectedNs1, $expectedNs2],
                    'current_ns' => $currentNameservers,
                ]);
            } else {
                $this->info("  ✓ DNS still connected");
            }
        }

        $this->info('DNS disconnection monitoring complete.');

        return Command::SUCCESS;
    }

    /**
     * Get nameservers for a domain using DNS lookup
     */
    private function getDomainNameservers(string $domain): array
    {
        // Remove any path or protocol
        $domain = preg_replace('#^https?://#', '', $domain);
        $domain = explode('/', $domain)[0];

        try {
            $records = dns_get_record($domain, DNS_NS);

            if ($records === false || empty($records)) {
                return [];
            }

            return array_map(fn($r) => rtrim($r['target'] ?? '', '.'), $records);
        } catch (\Exception $e) {
            Log::warning("DNS lookup failed for {$domain}: " . $e->getMessage());
            return [];
        }
    }
}
