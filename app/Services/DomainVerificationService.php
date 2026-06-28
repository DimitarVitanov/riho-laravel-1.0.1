<?php

namespace App\Services;

use App\Models\AgencyProfile;
use Illuminate\Support\Facades\Log;

class DomainVerificationService
{
    public function verify(AgencyProfile $profile): bool
    {
        $domain = $profile->custom_domain;

        if (!$domain) {
            $profile->update(['last_dns_check_at' => now()]);
            return false;
        }

        $baseDomain = $this->extractBaseDomain($domain);
        $expected = $this->expectedNameservers($profile);
        $current = $this->getNameservers($baseDomain);

        if ($expected === []) {
            Log::info("DNS verification failed: no expected nameservers for {$domain}");
            $profile->update([
                'last_dns_check_at' => now(),
                'dns_verified_at' => null,
            ]);
            return false;
        }

        if ($current === []) {
            Log::info("DNS verification failed: could not resolve nameservers for {$baseDomain}");
            $profile->update([
                'last_dns_check_at' => now(),
                'dns_verified_at' => null,
            ]);
            return false;
        }

        sort($expected);
        sort($current);

        $verified = $current === $expected;

        if (!$verified) {
            Log::info("DNS verification failed: {$baseDomain} nameservers " . implode(', ', $current) . " do not match expected " . implode(', ', $expected));
        }

        $profile->update([
            'last_dns_check_at' => now(),
            'dns_verified_at' => $verified ? now() : null,
        ]);

        return $verified;
    }

    protected function extractBaseDomain(string $domain): string
    {
        $domain = preg_replace('#^https?://#i', '', trim($domain));
        $domain = explode('/', $domain)[0];
        $domain = preg_replace('/:\d+$/', '', $domain);

        return $domain;
    }

    protected function getNameservers(string $domain): array
    {
        if (!filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            return [];
        }

        $records = dns_get_record($domain, DNS_NS);
        $nameservers = [];

        foreach ($records as $record) {
            if (!empty($record['target'])) {
                $nameservers[] = rtrim(strtolower($record['target']), '.');
            }
        }

        return array_values(array_unique($nameservers));
    }

    protected function expectedNameservers(AgencyProfile $profile): array
    {
        $nameservers = [];

        if ($profile->nameserver_1) {
            $nameservers[] = rtrim(strtolower($profile->nameserver_1), '.');
        }

        if ($profile->nameserver_2) {
            $nameservers[] = rtrim(strtolower($profile->nameserver_2), '.');
        }

        return array_values(array_unique($nameservers));
    }
}
