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
        Schema::table('local_seo_campaigns', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_campaign_id')->nullable()->after('agency_profile_id');
            $table->boolean('is_sub_campaign')->default(false)->after('status');
            
            $table->foreign('parent_campaign_id')->references('id')->on('local_seo_campaigns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('local_seo_campaigns', function (Blueprint $table) {
            $table->dropForeign(['parent_campaign_id']);
            $table->dropColumn(['parent_campaign_id', 'is_sub_campaign']);
        });
    }
};
