<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agency_listing_local_seo_campaign', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_listing_id')->constrained()->onDelete('cascade');
            $table->foreignId('local_seo_campaign_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['agency_listing_id', 'local_seo_campaign_id'], 'listing_campaign_unique');
        });

        // Migrate existing data from local_seo_campaign_id column
        DB::statement('
            INSERT INTO agency_listing_local_seo_campaign (agency_listing_id, local_seo_campaign_id, created_at, updated_at)
            SELECT id, local_seo_campaign_id, NOW(), NOW()
            FROM agency_listings
            WHERE local_seo_campaign_id IS NOT NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_listing_local_seo_campaign');
    }
};
