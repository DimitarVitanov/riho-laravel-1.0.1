<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usage_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained('agency_profiles')->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('local_seo_pages_limit')->default(10);
            $table->integer('local_seo_pages_used')->default(0);
            $table->integer('competitor_scans_limit')->default(30);
            $table->integer('competitor_scans_used')->default(0);
            $table->integer('ai_search_freshness_updates_limit')->default(4);
            $table->integer('ai_search_freshness_updates_used')->default(0);
            $table->integer('authority_review_updates_limit')->default(1);
            $table->integer('authority_review_updates_used')->default(0);
            $table->integer('small_ai_content_actions_limit')->default(30);
            $table->integer('small_ai_content_actions_used')->default(0);
            $table->timestamps();

            $table->unique(['agency_profile_id', 'period_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_limits');
    }
};
