<?php

namespace App\Services\Est8ads\Discovery\Providers;

use App\Models\Est8ads\InternetSource;
use App\Services\Est8ads\Discovery\Contracts\DiscoveryProvider;
use RuntimeException;

class UnconfiguredProvider implements DiscoveryProvider
{
    public function search(InternetSource $source, array $profile): iterable
    {
        throw new RuntimeException("No approved provider adapter is configured for source {$source->id}.");
    }
}
