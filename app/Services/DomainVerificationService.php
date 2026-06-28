<?php

namespace App\Services;

use App\Models\AgencyProfile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DomainVerificationService
{
    public function verify(AgencyProfile $profile): bool
    {
        $domain = $profile->custom_domain;
        $serverIp = $profile->server_ip;
        $user = $profile->user;

        if (!$domain || !$serverIp || !$user) {
            $profile->update(['last_dns_check_at' => now()]);
            return false;
        }

        $verified = match ($user->agency_server_type) {
            'subdomain_ai_server' => $this->verifySubdomain($domain, $serverIp),
            'domain_folder_ai_server' => $this->verifyFolder($domain, $serverIp),
            default => $this->verifyDefault($domain, $serverIp),
        };

        $profile->update([
            'last_dns_check_at' => now(),
            'dns_verified_at' => $verified ? now() : null,
        ]);

        return $verified;
    }

    protected function verifySubdomain(string $domain, string $serverIp): bool
    {
        return $this->resolveAndCheck($domain, $serverIp)
            && $this->httpCheck("https://{$domain}/");
    }

    protected function verifyFolder(string $domain, string $serverIp): bool
    {
        $baseDomain = explode('/', $domain, 2)[0];

        return $this->resolveAndCheck($baseDomain, $serverIp)
            && $this->httpCheck("https://{$domain}/");
    }

    protected function resolveAndCheck(string $host, string $serverIp): bool
    {
        $resolvedIp = gethostbyname($host);

        if ($resolvedIp === $host) {
            Log::info("DNS verification failed: could not resolve {$host}");
            return false;
        }

        if ($resolvedIp !== $serverIp) {
            Log::info("DNS verification failed: {$host} resolves to {$resolvedIp}, expected {$serverIp}");
            return false;
        }

        return true;
    }

    protected function verifyDefault(string $domain, string $serverIp): bool
    {
        $resolvedIp = gethostbyname($domain);

        return $resolvedIp !== $domain && $resolvedIp === $serverIp;
    }

    protected function httpCheck(string $url): bool
    {
        try {
            $response = Http::timeout(10)
                ->withOptions(['verify' => false])
                ->get($url);

            return $response->successful();
        } catch (\Exception $e) {
            Log::info("HTTP verification failed for {$url}: {$e->getMessage()}");
            return false;
        }
    }
}
