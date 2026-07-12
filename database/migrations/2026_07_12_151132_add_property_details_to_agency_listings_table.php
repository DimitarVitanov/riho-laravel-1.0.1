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
            $table->decimal('plot_size', 10, 2)->nullable()->after('size');
            $table->boolean('is_turnkey')->default(false)->after('features');
            $table->string('property_condition')->nullable()->after('is_turnkey');
            $table->integer('year_built')->nullable()->after('property_condition');
            $table->decimal('living_area', 10, 2)->nullable()->after('size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agency_listings', function (Blueprint $table) {
            $table->dropColumn(['plot_size', 'is_turnkey', 'property_condition', 'year_built', 'living_area']);
        });
    }
};
