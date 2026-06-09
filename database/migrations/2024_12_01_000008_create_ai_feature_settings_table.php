<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_feature_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained('agency_profiles')->onDelete('cascade');
            $table->string('feature_key');
            $table->boolean('is_enabled')->default(false);
            $table->text('custom_prompt_override')->nullable();
            $table->string('ai_model_provider')->nullable();
            $table->string('ai_model_name')->nullable();
            $table->string('frequency')->default('daily');
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->timestamps();

            $table->unique(['agency_profile_id', 'feature_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_feature_settings');
    }
};
