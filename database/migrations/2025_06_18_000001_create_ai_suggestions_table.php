<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained()->cascadeOnDelete();
            $table->string('feature_key')->default('daily_ai_employee');
            $table->string('suggestion_type'); // 'blog_post', 'landing_page', 'seo_update', 'faq', etc.
            $table->string('title');
            $table->string('target_keyword')->nullable();
            $table->text('content_html');
            $table->json('content_json')->nullable();
            $table->text('ai_summary')->nullable();
            $table->text('ai_conclusion')->nullable();
            $table->string('status')->default('pending'); // pending, accepted, skipped, removed
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reviewer_notes')->nullable();
            $table->foreignId('converted_to_page_id')->nullable()->constrained('generated_pages')->nullOnDelete();
            $table->string('content_uniqueness_status')->nullable(); // pending, checking, passed, failed
            $table->timestamp('uniqueness_checked_at')->nullable();
            $table->timestamps();

            $table->index(['agency_profile_id', 'status']);
            $table->index(['feature_key', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_suggestions');
    }
};
