<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agency_listings', function (Blueprint $table) {
            $table->string('primary_city')->nullable()->after('location');
            $table->string('country')->nullable()->after('primary_city');
            $table->decimal('latitude', 10, 7)->nullable()->after('country');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('agency_listings', function (Blueprint $table) {
            $table->dropColumn(['primary_city', 'country', 'latitude', 'longitude']);
        });
    }
};
