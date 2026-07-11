<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->boolean('sidebar_enabled')->default(true)->after('footer_privacy_url');
            $table->string('sidebar_title')->nullable()->after('sidebar_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->dropColumn(['sidebar_enabled', 'sidebar_title']);
        });
    }
};
