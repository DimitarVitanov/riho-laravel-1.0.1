<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generated_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained('agency_profiles')->onDelete('cascade');
            $table->string('feature_key');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->longText('content_html')->nullable();
            $table->json('content_json')->nullable();
            $table->string('target_url')->nullable();
            $table->string('content_uniqueness_status')->default('draft');
            $table->timestamp('uniqueness_checked_at')->nullable();
            $table->string('publish_workflow')->default('auto_publish');
            $table->enum('status', ['draft', 'pending_review', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('approved_by_user_id')->nullable();
            $table->timestamps();

            $table->index(['agency_profile_id', 'feature_key']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_pages');
    }
};
