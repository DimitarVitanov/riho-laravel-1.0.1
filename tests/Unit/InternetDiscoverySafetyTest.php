<?php

namespace Tests\Unit;

use App\Models\Est8ads\ExternalListing;
use App\Services\Est8ads\Discovery\DeterministicMatchScorer;
use App\Services\Est8ads\Discovery\SafeUrlPolicy;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class InternetDiscoverySafetyTest extends TestCase
{
    public function test_ssrf_policy_rejects_private_and_non_https_urls(): void
    {
        $policy = new SafeUrlPolicy();

        foreach (['http://example.com/listing', 'https://127.0.0.1/listing', 'https://169.254.169.254/latest/meta-data'] as $url) {
            try {
                $policy->assertAllowed($url);
                $this->fail("Unsafe URL was accepted: {$url}");
            } catch (InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_deterministic_hard_conflicts_prevent_high_score(): void
    {
        $listing = new ExternalListing([
            'status' => 'active', 'property_type' => 'apartment', 'country_code' => 'HR',
            'city' => 'Split', 'price' => 500000, 'size_m2' => 50, 'bedrooms' => 1,
            'bathrooms' => 1, 'attributes' => ['features' => []],
        ]);
        $result = (new DeterministicMatchScorer())->score([
            'property_types' => ['house'], 'countries' => ['HR'], 'cities' => ['Zagreb'],
            'price' => ['max' => 250000], 'minimum_size_m2' => 100, 'minimum_bedrooms' => 3,
            'minimum_bathrooms' => 2, 'must_have_features' => ['pool'], 'flexibility_percent' => 10,
        ], $listing);

        $this->assertLessThan(88, $result['score']);
        $this->assertNotEmpty($result['hard_conflicts']);
        $this->assertContains('budget', $result['hard_conflicts']);
        $this->assertContains('property_type', $result['hard_conflicts']);
    }
}
