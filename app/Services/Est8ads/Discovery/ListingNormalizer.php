<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\InternetSource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ListingNormalizer
{
    public function __construct(private SafeUrlPolicy $urlPolicy) {}

    public function normalize(InternetSource $source, array $record): array
    {
        $url = $this->canonicalize((string) ($record['canonical_url'] ?? $record['url'] ?? ''));
        $this->urlPolicy->assertAllowed($url, $source->domain ?: parse_url($source->base_url, PHP_URL_HOST));
        $externalId = trim((string) ($record['external_id'] ?? $record['source_listing_id'] ?? ''));
        if ($externalId === '') throw new InvalidArgumentException('Provider record has no source listing ID.');

        $normalized = [
            'external_id' => Str::limit($externalId, 255, ''), 'canonical_url' => $url,
            'status' => in_array(($record['availability_status'] ?? 'active'), ['active', 'recently_verified'], true) ? 'active' : 'inactive',
            'title' => Str::limit(strip_tags((string) ($record['title'] ?? '')), 255, ''),
            'description_excerpt' => Str::limit(strip_tags((string) ($record['description'] ?? $record['description_excerpt'] ?? '')), 1000, ''),
            'property_type' => Str::lower((string) ($record['property_type'] ?? '')),
            'address' => Str::limit(strip_tags((string) ($record['address'] ?? '')), 1000, ''),
            'city' => Str::limit(strip_tags((string) ($record['city'] ?? '')), 255, ''),
            'area' => Str::limit(strip_tags((string) ($record['area'] ?? '')), 255, ''),
            'country_code' => strtoupper(substr((string) ($record['country_code'] ?? $record['country'] ?? ''), 0, 2)),
            'latitude' => $record['latitude'] ?? null, 'longitude' => $record['longitude'] ?? null,
            'price' => $record['price'] ?? null, 'currency' => strtoupper(substr((string) ($record['currency'] ?? 'EUR'), 0, 3)),
            'size_m2' => $record['size_m2'] ?? $record['floor_area'] ?? null, 'land_m2' => $record['land_m2'] ?? null,
            'bedrooms' => $record['bedrooms'] ?? null, 'bathrooms' => $record['bathrooms'] ?? null,
            'condition' => $record['condition'] ?? null, 'media' => [],
            'attributes' => [
                'features' => array_values((array) ($record['features'] ?? [])),
                'offer_type' => Str::lower((string) ($record['offer_type'] ?? $record['listing_type'] ?? '')),
            ],
            'source_published_at' => $record['source_published_at'] ?? null, 'source_updated_at' => $record['source_updated_at'] ?? null,
            'first_seen_at' => now(), 'last_seen_at' => now(),
        ];
        $hashPayload = Arr::except($normalized, ['first_seen_at', 'last_seen_at']);
        $normalized['content_hash'] = hash('sha256', json_encode($hashPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $normalized['property_fingerprint'] = hash('sha256', implode('|', [
            Str::lower(trim($normalized['address'].' '.$normalized['city'])), $normalized['property_type'],
            round((float) $normalized['price'], -3), round((float) $normalized['size_m2']),
        ]));
        $normalized['raw_payload'] = Arr::except($record, ['description', 'media', 'images']);
        return $normalized;
    }

    private function canonicalize(string $url): string
    {
        $parts = parse_url(trim($url));
        if (! is_array($parts) || empty($parts['host'])) return '';
        $path = preg_replace('#/+#', '/', $parts['path'] ?? '/');
        return strtolower((string) ($parts['scheme'] ?? 'https')).'://'.strtolower($parts['host']).rtrim($path, '/');
    }
}
