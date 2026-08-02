<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\ExternalListing;
use App\Models\Est8ads\InternetSource;

class ListingDeduplicator
{
    public function find(InternetSource $source, array $listing): ?ExternalListing
    {
        return ExternalListing::withTrashed()->where(function ($query) use ($source, $listing) {
            $query->where(fn ($q) => $q->where('internet_source_id', $source->id)->where('external_id', $listing['external_id']))
                ->orWhere('canonical_url', $listing['canonical_url'])
                ->orWhere('content_hash', $listing['content_hash'])
                ->orWhere(fn ($q) => $q->where('property_fingerprint', $listing['property_fingerprint'])
                    ->when($listing['price'], fn ($priceQuery) => $priceQuery->whereBetween('price', [(float) $listing['price'] * .98, (float) $listing['price'] * 1.02])));
        })->first();
    }
}
