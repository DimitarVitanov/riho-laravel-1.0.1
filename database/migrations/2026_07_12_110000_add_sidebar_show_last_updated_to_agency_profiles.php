<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->boolean('sidebar_show_last_updated')->default(true)->after('sidebar_title');
        });
    }

    public function down(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->dropColumn('sidebar_show_last_updated');
        });
    }
};
