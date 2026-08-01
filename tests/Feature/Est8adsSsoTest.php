<?php

namespace Tests\Feature;

use App\Models\Est8ads\LoginToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Est8adsSsoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_est8ads_with_a_single_use_login_handoff(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
            'has_villabit_access' => true,
            'has_est8ads_access' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('est8ads.sso.start'));
        $response->assertRedirect();
        $url = $response->headers->get('Location');
        $this->assertStringContainsString('/est8ads-app/sso/', $url);
        $this->assertDatabaseCount('est8ads_login_tokens', 1);

        $this->get($url)->assertRedirect(route('est8ads.dev.admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
        $this->assertNotNull(LoginToken::firstOrFail()->used_at);

        $this->get($url)->assertRedirect(route('est8ads.dev.login'));
    }

    public function test_agency_can_generate_the_same_est8ads_handoff(): void
    {
        /** @var User $agency */
        $agency = User::factory()->create([
            'role' => 'real_estate_agency',
            'status' => 'active',
            'email_verified_at' => now(),
            'has_villabit_access' => true,
            'has_est8ads_access' => true,
        ]);

        $this->actingAs($agency)
            ->post(route('est8ads.sso.start'))
            ->assertRedirect();

        $this->assertDatabaseHas('est8ads_login_tokens', ['user_id' => $agency->id]);
    }

    public function test_sso_handoff_expires_and_never_stores_the_raw_token(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
            'has_villabit_access' => true,
            'has_est8ads_access' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('est8ads.sso.start'));
        $url = $response->headers->get('Location');
        $rawToken = basename(parse_url($url, PHP_URL_PATH));
        $record = LoginToken::firstOrFail();

        $this->assertNotSame($rawToken, $record->getRawOriginal('token_hash'));
        $this->assertSame(hash('sha256', $rawToken), $record->getRawOriginal('token_hash'));

        $record->update(['expires_at' => now()->subSecond()]);
        $this->get($url)->assertRedirect(route('est8ads.dev.login'));
        $this->assertNull($record->fresh()->used_at);
    }

    public function test_user_without_est8ads_access_cannot_generate_a_handoff(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'role' => 'real_estate_agency',
            'status' => 'active',
            'email_verified_at' => now(),
            'has_villabit_access' => true,
            'has_est8ads_access' => false,
        ]);

        $this->actingAs($user)
            ->post(route('est8ads.sso.start'))
            ->assertForbidden();

        $this->assertDatabaseCount('est8ads_login_tokens', 0);
    }
}
