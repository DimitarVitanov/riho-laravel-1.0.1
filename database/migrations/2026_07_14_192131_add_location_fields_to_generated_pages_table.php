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
        // All columns, FK and index already exist from partial migration run
        // Nothing to do
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generated_pages', function (Blueprint $table) {
            $table->dropForeign(['local_seo_campaign_id']);
            $table->dropIndex('gp_campaign_neighborhood_idx');
            $table->dropColumn([
                'local_seo_campaign_id', 'name', 'target_city', 'target_neighborhood',
                'country', 'latitude', 'longitude', 'property_type', 'page_type'
            ]);
        });
    }
};
