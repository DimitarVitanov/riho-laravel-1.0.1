<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxi_country_reports', function (Blueprint $table) {
            $table->id();
            $table->string('country');
            $table->string('country_slug');
            $table->string('locale', 10)->default('en');
            $table->string('iso2', 2)->nullable();
            $table->string('region')->nullable();
            $table->string('report_number')->nullable();

            $table->string('title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url')->nullable();

            $table->longText('html_full');
            $table->string('source_file')->nullable();
            $table->string('content_hash', 64)->nullable();

            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('refresh_interval_days')->default(30);
            $table->timestamp('last_generated_at')->nullable();
            $table->timestamp('next_refresh_at')->nullable();
            $table->string('last_refresh_status')->nullable();
            $table->text('last_refresh_note')->nullable();
            $table->unsignedInteger('sections_updated')->default(0);
            $table->string('ai_provider')->nullable();

            $table->foreignId('source_report_id')->nullable()->constrained('taxi_country_reports')->nullOnDelete();

            $table->timestamps();

            $table->unique(['country_slug', 'locale']);
            $table->index(['locale', 'is_published']);
            $table->index('next_refresh_at');
        });

        Schema::create('taxi_report_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();          // e.g. SEC-014
            $table->string('label');                  // e.g. Mortgage market
            $table->string('placement')->nullable();  // HTML placement note
            $table->string('section_id')->nullable(); // matching DOM id/anchor when known
            $table->longText('prompt_text');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('taxi_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxi_settings');
        Schema::dropIfExists('taxi_report_prompts');
        Schema::dropIfExists('taxi_country_reports');
    }
};
