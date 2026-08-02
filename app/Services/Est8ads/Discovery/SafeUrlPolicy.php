<?php

namespace App\Services\Est8ads\Discovery;

use InvalidArgumentException;

class SafeUrlPolicy
{
    public function assertAllowed(string $url, ?string $expectedDomain = null): void
    {
        $parts = parse_url($url);
        if (! is_array($parts) || ($parts['scheme'] ?? null) !== 'https' || empty($parts['host']) || isset($parts['user']) || isset($parts['pass'])) {
            throw new InvalidArgumentException('Only credential-free HTTPS URLs are allowed.');
        }

        $host = strtolower(rtrim((string) $parts['host'], '.'));
        if ($expectedDomain && $host !== strtolower($expectedDomain) && ! str_ends_with($host, '.'.strtolower($expectedDomain))) {
            throw new InvalidArgumentException('URL host is outside the approved source domain.');
        }

        $ips = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : $this->resolve($host);
        if ($ips === []) {
            throw new InvalidArgumentException('URL host could not be resolved safely.');
        }
        foreach ($ips as $ip) {
            if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new InvalidArgumentException('Private or reserved network destinations are blocked.');
            }
        }
    }

    /** Validate every Location target before a caller follows it. */
    public function assertRedirectChain(array $urls, ?string $expectedDomain = null, int $maximum = 3): void
    {
        if (count($urls) > $maximum + 1) {
            throw new InvalidArgumentException('Too many redirects.');
        }
        foreach ($urls as $url) {
            $this->assertAllowed((string) $url, $expectedDomain);
        }
    }

    private function resolve(string $host): array
    {
        $records = dns_get_record($host, DNS_A | DNS_AAAA);
        return array_values(array_unique(array_filter(array_map(
            fn (array $record) => $record['ip'] ?? $record['ipv6'] ?? null,
            is_array($records) ? $records : []
        ))));
    }
}
