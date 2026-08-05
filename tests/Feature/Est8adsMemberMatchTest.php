<?php

namespace Tests\Feature;

use App\Events\Est8ads\PropertyMoveSubmitted;
use App\Events\Est8ads\PropertyRequestUpdated;
use App\Models\Est8ads\Profile;
use App\Models\Est8ads\Property;
use App\Models\Est8ads\PropertyMove;
use App\Models\User;
use App\Services\Est8ads\BillingService;
use App\Services\Est8ads\Discovery\MemberMatchFinder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Internal "match against other EST8ADS members" discovery: a buyer's wanted
 * criteria are matched against the shared listing pool (est8ads intake plus
 * mirrored Villa Bit listings) within a +/- 15% tolerance, excluding the
 * buyer's own listings.
 */
class Est8adsMemberMatchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // These tests exercise the internal matcher directly, not the web
        // discovery pipeline that a submitted move would otherwise queue.
        Event::fake([PropertyMoveSubmitted::class, PropertyRequestUpdated::class]);
    }

    public function test_matches_another_members_listing_within_15_percent(): void
    {
        $seller = $this->profile('Adria Homes');
        $this->sellProperty($seller, ['title' => 'Sea-view apartment', 'city' => 'Split', 'type' => 'Apartment', 'price' => 320000, 'size' => 78]);

        $buyer = $this->profile('Ana Kovac');
        $move = $this->buyMove($buyer, ['city' => 'Split', 'type' => 'Apartment', 'budget' => 300000, 'size' => 75]);

        $matches = app(MemberMatchFinder::class)->forMove($move);

        $this->assertCount(1, $matches);
        $this->assertSame('Sea-view apartment', $matches[0]['title']);
        $this->assertSame('Adria Homes', $matches[0]['owner']);
        $this->assertGreaterThan(0, $matches[0]['score']);
    }

    public function test_excludes_a_listing_more_than_15_percent_over_budget(): void
    {
        $seller = $this->profile('Adria Homes');
        // 400k vs a 300k budget is +33% — outside the 15% band.
        $this->sellProperty($seller, ['title' => 'Overpriced villa', 'city' => 'Split', 'type' => 'Apartment', 'price' => 400000, 'size' => 78]);

        $buyer = $this->profile('Ana Kovac');
        $move = $this->buyMove($buyer, ['city' => 'Split', 'type' => 'Apartment', 'budget' => 300000, 'size' => 75]);

        $this->assertCount(0, app(MemberMatchFinder::class)->forMove($move));
    }

    public function test_never_matches_a_member_against_their_own_listing(): void
    {
        $me = $this->profile('Ana Kovac');
        $this->sellProperty($me, ['title' => 'My own apartment', 'city' => 'Split', 'type' => 'Apartment', 'price' => 300000, 'size' => 75]);

        $move = $this->buyMove($me, ['city' => 'Split', 'type' => 'Apartment', 'budget' => 300000, 'size' => 75]);

        $this->assertCount(0, app(MemberMatchFinder::class)->forMove($move));
    }

    public function test_does_not_match_listings_in_a_different_city(): void
    {
        $seller = $this->profile('Adria Homes');
        $this->sellProperty($seller, ['title' => 'Zagreb flat', 'city' => 'Zagreb', 'type' => 'Apartment', 'price' => 300000, 'size' => 75]);

        $buyer = $this->profile('Ana Kovac');
        $move = $this->buyMove($buyer, ['city' => 'Split', 'type' => 'Apartment', 'budget' => 300000, 'size' => 75]);

        $this->assertCount(0, app(MemberMatchFinder::class)->forMove($move));
    }

    public function test_member_matches_are_surfaced_on_the_user_dashboard(): void
    {
        $seller = $this->profile('Adria Homes');
        $this->sellProperty($seller, ['title' => 'Sea-view apartment', 'city' => 'Split', 'type' => 'Apartment', 'price' => 315000, 'size' => 76]);

        // A paid buyer who reaches the full dashboard.
        $user = User::factory()->create([
            'role' => 'investor',
            'account_type' => 'investor',
            'has_est8ads_access' => true,
            'email_verified_at' => now(),
        ]);
        $buyer = Profile::create([
            'user_id' => $user->id,
            'type' => 'individual',
            'status' => 'active',
            'email' => $user->email,
            'public_reference' => 'EST-TEST-' . Str::random(6),
        ]);
        app(BillingService::class)->markPaid($buyer->invoices()->latest('issued_on')->firstOrFail());
        $this->buyMove($buyer, ['city' => 'Split', 'type' => 'Apartment', 'budget' => 300000, 'size' => 75]);

        $this->actingAs($user)
            ->get('http://est8ads.com/dashboard')
            ->assertOk()
            ->assertSee('Matches from other EST8ADS members')
            ->assertSee('Sea-view apartment');
    }

    private function profile(string $name): Profile
    {
        return Profile::create([
            'type' => 'individual',
            'status' => 'active',
            'company_name' => $name,
            'email' => Str::slug($name) . '@example.com',
            'public_reference' => 'EST-' . strtoupper(Str::random(10)),
        ]);
    }

    private function sellProperty(Profile $profile, array $attrs): Property
    {
        $move = PropertyMove::create([
            'uuid' => (string) Str::uuid(),
            'profile_id' => $profile->id,
            'move_type' => 'sell',
            'status' => 'active',
            'submitted_at' => now(),
        ]);

        return Property::create([
            'uuid' => (string) Str::uuid(),
            'property_move_id' => $move->id,
            'reference' => 'SELL-' . strtoupper(Str::random(12)),
            'status' => 'active',
            'listing_type' => 'sell',
            'property_type' => $attrs['type'],
            'title' => $attrs['title'],
            'city' => $attrs['city'],
            'asking_price' => $attrs['price'],
            'currency' => 'EUR',
            'floor_area' => $attrs['size'] ?? null,
        ]);
    }

    private function buyMove(Profile $profile, array $attrs): PropertyMove
    {
        $move = PropertyMove::create([
            'uuid' => (string) Str::uuid(),
            'profile_id' => $profile->id,
            'move_type' => 'buy',
            'status' => 'active',
            'budget_max' => $attrs['budget'],
            'submitted_at' => now(),
        ]);

        Property::create([
            'uuid' => (string) Str::uuid(),
            'property_move_id' => $move->id,
            'reference' => 'BUY-' . strtoupper(Str::random(12)),
            'status' => 'active',
            'listing_type' => 'wanted',
            'property_type' => $attrs['type'],
            'title' => 'Wanted ' . $attrs['type'] . ' in ' . $attrs['city'],
            'city' => $attrs['city'],
            'asking_price' => $attrs['budget'],
            'currency' => 'EUR',
            'floor_area' => $attrs['size'] ?? null,
        ]);

        return $move;
    }
}
