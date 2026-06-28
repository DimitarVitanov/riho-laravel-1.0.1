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
        $resolvedIp = gethostbyname($domain);

        if ($resolvedIp === $domain) {
            Log::info("DNS verification failed: could not resolve {$domain}");
            return false;
        }

        if ($resolvedIp !== $serverIp) {
            Log::info("DNS verification failed: {$domain} resolves to {$resolvedIp}, expected {$serverIp}");
            return false;
        }

        return $this->httpCheck("https://{$domain}/__villa_bit_verify.php")
            || $this->httpCheck("https://{$domain}/");
    }

    protected function verifyFolder(string $domain, string $serverIp): bool
    {
        $baseDomain = explode('/', $domain, 2)[0];
        $resolvedIp = gethostbyname($baseDomain);

        if ($resolvedIp === $baseDomain) {
            Log::info("DNS verification failed: could not resolve {$baseDomain}");
            return false;
        }

        return $this->httpCheck("https://{$domain}/__villa_bit_verify.php")
            || $this->httpCheck("https://{$domain}/");
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
