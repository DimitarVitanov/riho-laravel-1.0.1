<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->string('copyscape_username')->nullable()->after('sitemap_url');
            $table->string('copyscape_api_key')->nullable()->after('copyscape_username');
        });
    }

    public function down(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->dropColumn(['copyscape_username', 'copyscape_api_key']);
        });
    }
};
