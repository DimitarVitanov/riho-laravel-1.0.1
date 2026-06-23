<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider');                          // openai, gemini, anthropic
            $table->string('feature_key')->nullable();          // which feature triggered the call
            $table->unsignedBigInteger('agency_profile_id')->nullable();
            $table->integer('tokens_input')->default(0);
            $table->integer('tokens_output')->default(0);
            $table->integer('api_calls_count')->default(1);
            $table->decimal('cost_estimate_usd', 10, 6)->default(0);
            $table->string('model_name')->nullable();
            $table->string('status')->default('success');       // success, error
            $table->timestamps();

            $table->index(['provider', 'created_at']);
            $table->index(['agency_profile_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_api_logs');
    }
};
