<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\InvestorProfile;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestorSupportTest extends TestCase
{
    use RefreshDatabase;

    protected User $investor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);
        $this->investor = User::factory()->create(['role' => 'investor', 'email_verified_at' => now()]);
        InvestorProfile::create([
            'user_id' => $this->investor->id,
            'investor_type' => 'us_accredited',
            'kyc_status' => 'approved',
        ]);
    }

    public function test_investor_can_view_support_index(): void
    {
        $response = $this->actingAs($this->investor)->get(route('investor.support.index'));
        $response->assertOk();
    }

    public function test_investor_can_view_create_ticket_form(): void
    {
        $response = $this->actingAs($this->investor)->get(route('investor.support.create'));
        $response->assertOk();
    }

    public function test_investor_can_create_support_ticket(): void
    {
        $response = $this->actingAs($this->investor)->post(route('investor.support.store'), [
            'subject' => 'Capital call question',
            'message' => 'I have a question about my capital call schedule.',
        ]);

        $response->assertRedirect(route('investor.support.index'));
        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $this->investor->id,
            'subject' => 'Capital call question',
            'status' => 'open',
        ]);
    }

    public function test_investor_can_view_own_ticket(): void
    {
        $ticket = SupportTicket::create([
            'user_id' => $this->investor->id,
            'subject' => 'Test Ticket',
            'message' => 'Test message',
        ]);

        $response = $this->actingAs($this->investor)->get(route('investor.support.show', $ticket));
        $response->assertOk();
    }

    public function test_investor_cannot_view_other_users_ticket(): void
    {
        $other = User::factory()->create(['role' => 'investor', 'email_verified_at' => now()]);
        $ticket = SupportTicket::create([
            'user_id' => $other->id,
            'subject' => 'Other Ticket',
            'message' => 'Other message',
        ]);

        $response = $this->actingAs($this->investor)->get(route('investor.support.show', $ticket));
        $response->assertStatus(403);
    }

    public function test_investor_can_reply_to_own_ticket(): void
    {
        $ticket = SupportTicket::create([
            'user_id' => $this->investor->id,
            'subject' => 'Test Ticket',
            'message' => 'Original message',
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->investor)->post(route('investor.support.reply', $ticket), [
            'message' => 'My follow-up reply',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('support_ticket_messages', [
            'support_ticket_id' => $ticket->id,
            'user_id' => $this->investor->id,
            'message' => 'My follow-up reply',
        ]);
    }

    public function test_investor_cannot_reply_to_others_ticket(): void
    {
        $other = User::factory()->create(['role' => 'investor', 'email_verified_at' => now()]);
        $ticket = SupportTicket::create([
            'user_id' => $other->id,
            'subject' => 'Other Ticket',
            'message' => 'Other message',
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->investor)->post(route('investor.support.reply', $ticket), [
            'message' => 'Unauthorized reply',
        ]);
        $response->assertStatus(403);
    }

    public function test_non_investor_cannot_access_support(): void
    {
        $agency = User::factory()->create(['role' => 'real_estate_agency', 'email_verified_at' => now()]);

        $response = $this->actingAs($agency)->get(route('investor.support.index'));
        $response->assertStatus(403);
    }
}
