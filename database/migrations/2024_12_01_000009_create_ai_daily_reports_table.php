<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained('agency_profiles')->onDelete('cascade');
            $table->string('feature_key');
            $table->date('report_date');
            $table->text('ai_actions_summary')->nullable();
            $table->json('completed_tasks_json')->nullable();
            $table->json('recommended_next_actions_json')->nullable();
            $table->json('generated_content_ids_json')->nullable();
            $table->json('detected_issues_json')->nullable();
            $table->json('usage_snapshot_json')->nullable();
            $table->string('ai_model_used')->nullable();
            $table->integer('token_input_count')->default(0);
            $table->integer('token_output_count')->default(0);
            $table->decimal('api_cost_estimate', 10, 4)->default(0);
            $table->string('status')->default('completed');
            $table->string('content_uniqueness_status')->default('draft');
            $table->timestamp('uniqueness_checked_at')->nullable();
            $table->timestamps();

            $table->index(['agency_profile_id', 'report_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_daily_reports');
    }
};
