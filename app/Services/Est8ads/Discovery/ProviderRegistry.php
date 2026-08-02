<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\InternetSource;
use App\Services\Est8ads\Discovery\Contracts\DiscoveryProvider;
use App\Services\Est8ads\Discovery\Providers\UnconfiguredProvider;
use InvalidArgumentException;

class ProviderRegistry
{
    /** @param array<string, DiscoveryProvider> $providers */
    public function __construct(private array $providers = []) {}

    public function for(InternetSource $source): DiscoveryProvider
    {
        if (! $source->isApprovedForDiscovery()) {
            throw new InvalidArgumentException('Source is disabled, legally unapproved, robots-blocked, or uses an unsupported access method.');
        }

        $adapter = (string) data_get($source->configuration, 'adapter', '');

        return $this->providers[$adapter] ?? app(UnconfiguredProvider::class);
    }
}
