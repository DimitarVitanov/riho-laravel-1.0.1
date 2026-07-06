<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->string('uniqueness_check_method')->default('villabit_ai')->after('copyscape_api_key');
        });
    }

    public function down(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->dropColumn('uniqueness_check_method');
        });
    }
};
