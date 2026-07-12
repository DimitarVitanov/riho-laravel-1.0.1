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
        Schema::table('agency_agents', function (Blueprint $table) {
            $table->foreignId('agency_listing_id')->nullable()->after('agency_profile_id')->constrained('agency_listings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agency_agents', function (Blueprint $table) {
            $table->dropForeign(['agency_listing_id']);
            $table->dropColumn('agency_listing_id');
        });
    }
};
