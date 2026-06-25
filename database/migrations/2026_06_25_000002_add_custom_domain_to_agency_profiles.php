<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->string('custom_domain')->nullable()->after('official_website_url');
        });
    }

    public function down(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->dropColumn('custom_domain');
        });
    }
};
