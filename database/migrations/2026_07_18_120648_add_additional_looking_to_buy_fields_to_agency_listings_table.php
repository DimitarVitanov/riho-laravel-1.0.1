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
        Schema::table('agency_listings', function (Blueprint $table) {
            // Additional Property Chain fields
            $table->json('looking_locations')->nullable()->after('looking_location'); // Multiple locations
            $table->integer('looking_min_bedrooms')->nullable()->after('looking_locations');
            $table->decimal('looking_min_size', 10, 2)->nullable()->after('looking_min_bedrooms'); // m²
            $table->string('looking_timeline')->nullable()->after('looking_min_size'); // ASAP, 3 months, 6 months, 1 year, flexible
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agency_listings', function (Blueprint $table) {
            $table->dropColumn([
                'looking_locations',
                'looking_min_bedrooms',
                'looking_min_size',
                'looking_timeline',
            ]);
        });
    }
};
