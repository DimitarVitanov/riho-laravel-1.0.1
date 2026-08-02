<?php

namespace App\Services\CompetitorIntelligence;

use App\Models\CompetitorProperty;
use App\Models\CompetitorPropertySnapshot;
use App\Models\CompetitorUrl;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PropertyExtractorService
{
    protected int $timeout = 30;
    protected string $userAgent = 'Mozilla/5.0 (compatible; VillaBitBot/1.0; +https://villabit.ai/bot)';

    public function extractProperty(CompetitorUrl $url): ?array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'User-Agent' => $this->userAgent,
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->get($url->url);

            if (!$response->successful()) {
                return null;
            }

            return $this->extractFromHtml($response->body(), $url->url);

        } catch (\Exception $e) {
            Log::error("Property extraction failed for {$url->url}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Run the extraction cascade (JSON-LD, DOM selectors, heuristics, URL slug)
     * against an arbitrary page. Shared with EST8ADS internet discovery.
     */
    public function extractFromHtml(string $html, string $pageUrl): ?array
    {
        $jsonLdData = $this->extractFromJsonLd($html);
        if ($jsonLdData && $this->isValidPropertyData($jsonLdData)) {
            $jsonLdData['extraction_method'] = 'json_ld';
            return $jsonLdData;
        }

        $domData = $this->extractFromDom($html, $pageUrl);
        if ($domData && $this->isValidPropertyData($domData)) {
            $domData['extraction_method'] = 'dom_selector';
            return $domData;
        }

        $heuristicData = $this->extractHeuristic($html);
        if ($heuristicData) {
            $heuristicData['extraction_method'] = 'heuristic';
            return $heuristicData;
        }

        // Try URL-based extraction for SPAs (React sites like RE/MAX)
        $urlData = $this->extractFromUrl($pageUrl);
        if ($urlData) {
            $urlData['extraction_method'] = 'heuristic';
            return $urlData;
        }

        return null;
    }

    protected function extractFromJsonLd(string $html): ?array
    {
        preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si', $html, $matches);

        foreach ($matches[1] as $jsonString) {
            try {
                $data = json_decode($jsonString, true);
                if (!$data) continue;

                if (isset($data['@graph'])) {
                    foreach ($data['@graph'] as $item) {
                        $extracted = $this->parseSchemaProperty($item);
                        if ($extracted) return $extracted;
                    }
                } else {
                    $extracted = $this->parseSchemaProperty($data);
                    if ($extracted) return $extracted;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }

    protected function parseSchemaProperty(array $data): ?array
    {
        $type = $data['@type'] ?? null;

        $propertyTypes = [
            'RealEstateListing',
            'Product',
            'Accommodation',
            'House',
            'Apartment',
            'LodgingBusiness',
            'Residence',
        ];

        if (!$type || !in_array($type, $propertyTypes)) {
            return null;
        }

        $result = [
            'title' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'images' => [],
        ];

        if (isset($data['offers'])) {
            $offer = is_array($data['offers']) && isset($data['offers'][0]) 
                ? $data['offers'][0] 
                : $data['offers'];
            
            $result['price'] = $this->extractPrice($offer['price'] ?? null);
            $result['currency'] = $offer['priceCurrency'] ?? 'EUR';
        }

        if (isset($data['address'])) {
            $address = $data['address'];
            if (is_string($address)) {
                $result['location_text'] = $address;
            } else {
                $parts = array_filter([
                    $address['streetAddress'] ?? null,
                    $address['addressLocality'] ?? null,
                    $address['addressRegion'] ?? null,
                ]);
                $result['location_text'] = implode(', ', $parts);
            }
        }

        if (isset($data['floorSize'])) {
            $result['surface_m2'] = $this->extractNumber($data['floorSize']['value'] ?? $data['floorSize']);
        }

        if (isset($data['numberOfRooms'])) {
            $result['bedrooms'] = (int)$data['numberOfRooms'];
        }

        if (isset($data['numberOfBathroomsTotal'])) {
            $result['bathrooms'] = (int)$data['numberOfBathroomsTotal'];
        }

        if (isset($data['image'])) {
            $images = is_array($data['image']) ? $data['image'] : [$data['image']];
            foreach ($images as $img) {
                $result['images'][] = is_string($img) ? $img : ($img['url'] ?? $img['contentUrl'] ?? null);
            }
            $result['images'] = array_filter($result['images']);
        }

        return $result;
    }

    protected function extractFromDom(string $html, string $url): ?array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOERROR);
        $xpath = new \DOMXPath($dom);

        $result = [
            'title' => $this->extractDomTitle($xpath),
            'price' => $this->extractDomPrice($xpath),
            'currency' => 'EUR',
            'location_text' => $this->extractDomLocation($xpath),
            'surface_m2' => $this->extractDomSurface($xpath),
            'bedrooms' => $this->extractDomBedrooms($xpath),
            'bathrooms' => $this->extractDomBathrooms($xpath),
            'description' => $this->extractDomDescription($xpath),
            'images' => $this->extractDomImages($xpath, $url),
        ];

        return $result;
    }

    protected function extractDomTitle(\DOMXPath $xpath): ?string
    {
        $selectors = [
            '//h1[contains(@class, "property")]',
            '//h1[contains(@class, "title")]',
            '//h1[contains(@class, "listing")]',
            '//h1',
            '//*[contains(@class, "property-title")]',
        ];

        foreach ($selectors as $selector) {
            $nodes = $xpath->query($selector);
            if ($nodes->length > 0) {
                return trim($nodes->item(0)->textContent);
            }
        }

        return null;
    }

    protected function extractDomPrice(\DOMXPath $xpath): ?float
    {
        $selectors = [
            '//*[contains(@class, "price")]',
            '//*[contains(@class, "cijena")]',
            '//*[contains(@itemprop, "price")]',
            '//*[@data-price]',
        ];

        foreach ($selectors as $selector) {
            $nodes = $xpath->query($selector);
            if ($nodes->length > 0) {
                $text = $nodes->item(0)->textContent;
                $price = $this->extractPrice($text);
                if ($price) return $price;
            }
        }

        return null;
    }

    protected function extractDomLocation(\DOMXPath $xpath): ?string
    {
        $selectors = [
            '//*[contains(@class, "location")]',
            '//*[contains(@class, "lokacija")]',
            '//*[contains(@class, "address")]',
            '//*[contains(@itemprop, "address")]',
        ];

        foreach ($selectors as $selector) {
            $nodes = $xpath->query($selector);
            if ($nodes->length > 0) {
                return trim($nodes->item(0)->textContent);
            }
        }

        return null;
    }

    protected function extractDomSurface(\DOMXPath $xpath): ?float
    {
        $nodes = $xpath->query('//*[contains(text(), "m²") or contains(text(), "m2") or contains(text(), "sqm")]');

        foreach ($nodes as $node) {
            $text = $node->textContent;
            if (preg_match('/(\d+(?:[.,]\d+)?)\s*(?:m²|m2|sqm)/i', $text, $matches)) {
                return $this->extractNumber($matches[1]);
            }
        }

        return null;
    }

    protected function extractDomBedrooms(\DOMXPath $xpath): ?int
    {
        $patterns = [
            '/(\d+)\s*(?:bedroom|soba|spavaća)/i',
            '/(\d+)\s*(?:bed|krevet)/i',
        ];

        $nodes = $xpath->query('//*');
        foreach ($nodes as $node) {
            $text = $node->textContent;
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $text, $matches)) {
                    return (int)$matches[1];
                }
            }
        }

        return null;
    }

    protected function extractDomBathrooms(\DOMXPath $xpath): ?int
    {
        $patterns = [
            '/(\d+)\s*(?:bathroom|kupaonica)/i',
            '/(\d+)\s*(?:bath|wc)/i',
        ];

        $nodes = $xpath->query('//*');
        foreach ($nodes as $node) {
            $text = $node->textContent;
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $text, $matches)) {
                    return (int)$matches[1];
                }
            }
        }

        return null;
    }

    protected function extractDomDescription(\DOMXPath $xpath): ?string
    {
        $selectors = [
            '//*[contains(@class, "description")]',
            '//*[contains(@class, "opis")]',
            '//*[contains(@itemprop, "description")]',
        ];

        foreach ($selectors as $selector) {
            $nodes = $xpath->query($selector);
            if ($nodes->length > 0) {
                return trim($nodes->item(0)->textContent);
            }
        }

        return null;
    }

    protected function extractDomImages(\DOMXPath $xpath, string $baseUrl): array
    {
        $images = [];
        $selectors = [
            '//img[contains(@class, "property")]/@src',
            '//img[contains(@class, "gallery")]/@src',
            '//*[contains(@class, "gallery")]//img/@src',
            '//*[contains(@class, "slider")]//img/@src',
        ];

        foreach ($selectors as $selector) {
            $nodes = $xpath->query($selector);
            foreach ($nodes as $node) {
                $src = $node->textContent;
                if ($src) {
                    $images[] = $this->resolveUrl($src, $baseUrl);
                }
            }
        }

        return array_unique(array_filter($images));
    }

    protected function extractHeuristic(string $html): ?array
    {
        $result = [];

        if (preg_match('/€\s*([\d.,]+)/i', $html, $matches)) {
            $result['price'] = $this->extractPrice($matches[1]);
            $result['currency'] = 'EUR';
        }

        if (preg_match('/([\d.,]+)\s*m²/i', $html, $matches)) {
            $result['surface_m2'] = $this->extractNumber($matches[1]);
        }

        return !empty($result) ? $result : null;
    }

    protected function extractPrice($value): ?float
    {
        if (!$value) return null;

        if (is_numeric($value)) {
            return (float)$value;
        }

        $cleaned = preg_replace('/[^\d.,]/', '', $value);
        $cleaned = str_replace(',', '.', $cleaned);

        if (substr_count($cleaned, '.') > 1) {
            $cleaned = str_replace('.', '', substr($cleaned, 0, strrpos($cleaned, '.'))) 
                     . substr($cleaned, strrpos($cleaned, '.'));
        }

        return $cleaned ? (float)$cleaned : null;
    }

    protected function extractNumber($value): ?float
    {
        if (!$value) return null;

        $cleaned = preg_replace('/[^\d.,]/', '', $value);
        $cleaned = str_replace(',', '.', $cleaned);

        return $cleaned ? (float)$cleaned : null;
    }

    protected function resolveUrl(string $url, string $baseUrl): string
    {
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        $parsed = parse_url($baseUrl);
        $base = $parsed['scheme'] . '://' . $parsed['host'];

        if (str_starts_with($url, '//')) {
            return $parsed['scheme'] . ':' . $url;
        }

        if (str_starts_with($url, '/')) {
            return $base . $url;
        }

        return $base . '/' . $url;
    }

    protected function isValidPropertyData(array $data): bool
    {
        // Valid if has title, price, or external_id (from URL extraction)
        return !empty($data['title']) || !empty($data['price']) || !empty($data['external_id']);
    }

    public function createOrUpdateProperty(CompetitorUrl $url, array $data): CompetitorProperty
    {
        $property = CompetitorProperty::firstOrCreate(
            [
                'competitor_id' => $url->competitor_id,
                'competitor_url_id' => $url->id,
            ],
            [
                'canonical_url' => $url->url,
                'current_status' => 'active',
                'first_detected_at' => now(),
            ]
        );

        $property->update(['last_seen_at' => now()]);

        $this->createSnapshot($property, $data);

        return $property;
    }

    protected function createSnapshot(CompetitorProperty $property, array $data): CompetitorPropertySnapshot
    {
        return CompetitorPropertySnapshot::create([
            'competitor_property_id' => $property->id,
            'title' => $data['title'] ?? null,
            'price' => $data['price'] ?? null,
            'currency' => $data['currency'] ?? 'EUR',
            'price_per_m2' => $this->calculatePricePerM2($data),
            'location_text' => $data['location_text'] ?? null,
            'property_type' => $data['property_type'] ?? null,
            'bedrooms' => $data['bedrooms'] ?? null,
            'bathrooms' => $data['bathrooms'] ?? null,
            'surface_m2' => $data['surface_m2'] ?? null,
            'plot_m2' => $data['plot_m2'] ?? null,
            'description' => $data['description'] ?? null,
            'images_json' => $data['images'] ?? null,
            'agent_name' => $data['agent_name'] ?? null,
            'extraction_method' => $data['extraction_method'] ?? null,
            'captured_at' => now(),
        ]);
    }

    protected function calculatePricePerM2(array $data): ?float
    {
        if (empty($data['price']) || empty($data['surface_m2'])) {
            return null;
        }

        return round($data['price'] / $data['surface_m2'], 2);
    }

    /**
     * Extract property data from URL patterns (for SPAs like RE/MAX)
     * URL patterns like: /listinzi/stan-apartman/za-prodaju/split/123456
     */
    protected function extractFromUrl(string $url): ?array
    {
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? '';
        $host = $parsed['host'] ?? '';

        // RE/MAX pattern: /hr-hr/listinzi/{type}/za-prodaju/{location}/{id}
        // or /en/listings/{type}/for-sale/{location}/{id}
        if (str_contains($host, 'remax')) {
            return $this->extractRemaxUrl($path);
        }

        // Generic listing URL patterns
        if (preg_match('/\/(nekretnin[ae]|listing|property|oglas)\/([^\/]+)/i', $path, $matches)) {
            $externalId = $this->extractIdFromPath($path);
            return [
                'external_id' => $externalId,
                'property_type' => $this->normalizePropertyType($matches[2] ?? null),
                'source_url' => $url,
            ];
        }

        return null;
    }

    protected function extractRemaxUrl(string $path): ?array
    {
        // Pattern: /hr-hr/listinzi/stan-apartman/za-prodaju/umag/300441009-1167
        // Pattern: /en/listings/apartment/for-sale/umag/300441009-1167
        $segments = array_filter(explode('/', $path));
        $segments = array_values($segments);

        if (count($segments) < 5) {
            return null;
        }

        // Check if this is a listing URL
        if (!in_array($segments[1] ?? '', ['listinzi', 'listings'])) {
            return null;
        }

        $propertyType = $segments[2] ?? null;
        $transactionType = $segments[3] ?? null;
        $location = $segments[4] ?? null;
        $externalId = $segments[5] ?? null;

        // Map Croatian/English property types
        $typeMap = [
            'stan-apartman' => 'apartment',
            'apartment' => 'apartment',
            'stan' => 'apartment',
            'kuca' => 'house',
            'house' => 'house',
            'vila' => 'villa',
            'villa' => 'villa',
            'zemljiste' => 'land',
            'land' => 'land',
            'poslovni-prostor' => 'commercial',
            'commercial' => 'commercial',
            'garaza' => 'garage',
            'garage' => 'garage',
        ];

        $transactionMap = [
            'za-prodaju' => 'sale',
            'for-sale' => 'sale',
            'za-najam' => 'rent',
            'for-rent' => 'rent',
        ];

        return [
            'external_id' => $externalId,
            'property_type' => $typeMap[$propertyType] ?? $propertyType,
            'transaction_type' => $transactionMap[$transactionType] ?? $transactionType,
            'location' => ucfirst(str_replace('-', ' ', $location ?? '')),
            'source_url' => $path,
        ];
    }

    protected function extractIdFromPath(string $path): ?string
    {
        // Try to find numeric ID at end of path
        if (preg_match('/[\/-](\d{5,})(?:[^\d]|$)/', $path, $matches)) {
            return $matches[1];
        }
        // Try slug-based ID
        $segments = explode('/', trim($path, '/'));
        return end($segments) ?: null;
    }

    protected function normalizePropertyType(?string $type): ?string
    {
        if (!$type) return null;

        $type = strtolower(trim($type));

        $map = [
            'stan' => 'apartment',
            'apartman' => 'apartment',
            'stan-apartman' => 'apartment',
            'apartment' => 'apartment',
            'kuca' => 'house',
            'kuća' => 'house',
            'house' => 'house',
            'vila' => 'villa',
            'villa' => 'villa',
            'zemljiste' => 'land',
            'zemljište' => 'land',
            'land' => 'land',
            'poslovni' => 'commercial',
            'poslovni-prostor' => 'commercial',
            'commercial' => 'commercial',
            'garaza' => 'garage',
            'garaža' => 'garage',
            'garage' => 'garage',
            'parking' => 'parking',
        ];

        return $map[$type] ?? $type;
    }
}
