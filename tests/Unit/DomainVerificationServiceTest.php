<?php

namespace Tests\Unit;

use App\Models\AgencyProfile;
use App\Models\User;
use App\Services\DomainVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainVerificationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_verifies_when_nameservers_match(): void
    {
        $service = new TestableDomainVerificationService(['ns1.example.com', 'ns2.example.com']);
        $profile = $this->createProfile('ai.booking-demo.dev', 'ns1.example.com', 'ns2.example.com');

        $this->assertTrue($service->verify($profile));
        $this->assertNotNull($profile->fresh()->dns_verified_at);
    }

    public function test_fails_when_nameservers_do_not_match(): void
    {
        $service = new TestableDomainVerificationService(['ns1.other.com']);
        $profile = $this->createProfile('ai.booking-demo.dev', 'ns1.example.com', 'ns2.example.com');

        $this->assertFalse($service->verify($profile));
        $this->assertNull($profile->fresh()->dns_verified_at);
    }

    public function test_fails_when_no_expected_nameservers(): void
    {
        $service = new TestableDomainVerificationService(['ns1.example.com']);
        $profile = $this->createProfile('ai.booking-demo.dev', null, null);

        $this->assertFalse($service->verify($profile));
        $this->assertNull($profile->fresh()->dns_verified_at);
    }

    public function test_fails_when_no_current_nameservers(): void
    {
        $service = new TestableDomainVerificationService([]);
        $profile = $this->createProfile('ai.booking-demo.dev', 'ns1.example.com', 'ns2.example.com');

        $this->assertFalse($service->verify($profile));
        $this->assertNull($profile->fresh()->dns_verified_at);
    }

    public function test_uses_base_domain_for_subdomain(): void
    {
        $service = new TestableDomainVerificationService(['ns1.example.com']);
        $profile = $this->createProfile('ai.booking-demo.dev', 'ns1.example.com', null);

        $service->verify($profile);
        $this->assertSame('booking-demo.dev', $service->lastCheckedDomain);
    }

    public function test_uses_base_domain_for_folder(): void
    {
        $service = new TestableDomainVerificationService(['ns1.example.com']);
        $profile = $this->createProfile('f5web.com/ai/', 'ns1.example.com', null);

        $service->verify($profile);
        $this->assertSame('f5web.com', $service->lastCheckedDomain);
    }

    public function test_ignores_trailing_dot_case_and_duplicates(): void
    {
        $service = new TestableDomainVerificationService(['ns1.example.com.', 'NS2.EXAMPLE.COM']);
        $profile = $this->createProfile('f5web.com/ai/', 'ns1.example.com', 'ns2.example.com');

        $this->assertTrue($service->verify($profile));
    }

    private function createProfile(string $domain, ?string $ns1, ?string $ns2): AgencyProfile
    {
        $user = User::factory()->create([
            'role' => 'real_estate_agency',
            'agency_server_type' => 'subdomain_ai_server',
        ]);

        return AgencyProfile::create([
            'user_id' => $user->id,
            'agency_name' => 'Test Agency',
            'custom_domain' => $domain,
            'nameserver_1' => $ns1,
            'nameserver_2' => $ns2,
        ]);
    }
}

class TestableDomainVerificationService extends DomainVerificationService
{
    public ?string $lastCheckedDomain = null;

    public function __construct(private array $nameservers)
    {
        //
    }

    protected function getNameservers(string $domain): array
    {
        $this->lastCheckedDomain = $domain;

        return array_values(array_unique(array_map(
            fn (string $ns) => rtrim(strtolower($ns), '.'),
            $this->nameservers
        )));
    }
}
