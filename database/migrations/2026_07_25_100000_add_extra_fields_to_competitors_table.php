<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competitors', function (Blueprint $table) {
            $table->string('country')->nullable()->after('primary_market');
            $table->string('google_maps_url', 2048)->nullable()->after('google_place_id');
            $table->boolean('include_in_daily_report')->default(true)->after('is_active');
            $table->boolean('include_in_comparison')->default(true)->after('include_in_daily_report');
            $table->string('priority')->default('normal')->after('include_in_comparison');
            $table->string('scan_profile')->default('full')->after('priority');
            $table->json('monitoring_sources')->nullable()->after('scan_profile');
        });
    }

    public function down(): void
    {
        Schema::table('competitors', function (Blueprint $table) {
            $table->dropColumn([
                'country',
                'google_maps_url',
                'include_in_daily_report',
                'include_in_comparison',
                'priority',
                'scan_profile',
                'monitoring_sources',
            ]);
        });
    }
};
