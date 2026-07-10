<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained('agency_profiles')->onDelete('cascade');
            $table->string('feature_key'); // e.g. 'local_seo_pages', 'ai_search_ranking'
            $table->date('usage_date');
            $table->integer('count')->default(1);
            $table->timestamps();

            $table->unique(['agency_profile_id', 'feature_key', 'usage_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_usage_logs');
    }
};
