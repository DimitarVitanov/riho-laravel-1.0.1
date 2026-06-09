<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('global_ai_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('feature_key')->unique();
            $table->string('label');
            $table->longText('prompt_text');
            $table->string('ai_model_provider')->default('openai');
            $table->string('ai_model_name')->default('gpt-4');
            $table->json('parameters_json')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('updated_by_user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('global_ai_prompts');
    }
};
