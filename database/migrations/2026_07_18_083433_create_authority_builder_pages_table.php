<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('authority_builder_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained()->onDelete('cascade');
            
            // Source page reference (Local SEO Campaign or AI Search Page)
            $table->string('source_type'); // 'local_seo' or 'ai_search'
            $table->unsignedBigInteger('source_id'); // ID of the source page/campaign
            $table->string('source_title'); // Title of the source page
            
            // Page details
            $table->string('title');
            $table->string('slug');
            $table->string('location')->nullable(); // City/area
            $table->string('country')->nullable();
            
            // Content - JSON structure for all 31 analysis boxes
            $table->json('content_sections')->nullable();
            $table->longText('full_html')->nullable(); // Final rendered HTML
            
            // Scheduling
            $table->date('scheduled_for'); // Date when it should be generated
            $table->string('status')->default('pending'); // pending, generating, generated, published, failed
            
            // Generation tracking
            $table->timestamp('generation_started_at')->nullable();
            $table->timestamp('generation_completed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->text('error_message')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            $table->timestamps();
            
            $table->index(['agency_profile_id', 'status']);
            $table->index(['scheduled_for', 'status']);
            $table->unique(['source_type', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authority_builder_pages');
    }
};
