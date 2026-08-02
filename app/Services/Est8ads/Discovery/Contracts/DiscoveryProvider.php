<?php

namespace App\Services\Est8ads\Discovery\Contracts;

use App\Models\Est8ads\InternetSource;

interface DiscoveryProvider
{
    /** @return iterable<array<string, mixed>> */
    public function search(InternetSource $source, array $profile): iterable;
}
