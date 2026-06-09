<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\GlobalAiPrompt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAiPromptsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);
        $this->admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
    }

    public function test_admin_can_view_ai_prompts_index(): void
    {
        GlobalAiPrompt::create([
            'feature_key' => 'daily_ai_employee',
            'label' => 'Daily AI Employee',
            'prompt_text' => 'You are Villa Bit AI Daily AI Employee...',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.villabit.ai-prompts.index'));
        $response->assertOk();
        $response->assertViewIs('admin.villabit.ai-prompts.index');
    }

    public function test_admin_can_view_edit_prompt(): void
    {
        $prompt = GlobalAiPrompt::create([
            'feature_key' => 'local_seo_presence_boost',
            'label' => 'Local SEO',
            'prompt_text' => 'You are Villa Bit AI Local SEO assistant...',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.villabit.ai-prompts.edit', $prompt));
        $response->assertOk();
    }

    public function test_admin_can_update_prompt(): void
    {
        $prompt = GlobalAiPrompt::create([
            'feature_key' => 'daily_competitor_scan',
            'label' => 'Competitor Scan',
            'prompt_text' => 'Original prompt text',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.villabit.ai-prompts.update', $prompt), [
            'label' => 'Updated Competitor Scan',
            'prompt_text' => 'Updated prompt text',
            'ai_model_provider' => 'openai',
            'ai_model_name' => 'gpt-4o',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.villabit.ai-prompts.index'));
        $this->assertDatabaseHas('global_ai_prompts', [
            'id' => $prompt->id,
            'label' => 'Updated Competitor Scan',
            'ai_model_name' => 'gpt-4o',
        ]);
    }

    public function test_non_admin_cannot_access_ai_prompts(): void
    {
        $agency = User::factory()->create(['role' => 'real_estate_agency', 'email_verified_at' => now()]);

        $response = $this->actingAs($agency)->get(route('admin.villabit.ai-prompts.index'));
        $response->assertStatus(403);
    }
}
