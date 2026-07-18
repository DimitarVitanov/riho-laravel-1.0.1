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
            // Property Chain - Looking to Buy fields (optional)
            $table->boolean('looking_to_buy')->default(false)->after('year_built');
            $table->string('looking_property_type')->nullable()->after('looking_to_buy');
            $table->string('looking_location')->nullable()->after('looking_property_type');
            $table->decimal('looking_budget_min', 15, 2)->nullable()->after('looking_location');
            $table->decimal('looking_budget_max', 15, 2)->nullable()->after('looking_budget_min');
            $table->string('looking_currency', 3)->default('EUR')->after('looking_budget_max');
            $table->text('looking_notes')->nullable()->after('looking_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agency_listings', function (Blueprint $table) {
            $table->dropColumn([
                'looking_to_buy',
                'looking_property_type',
                'looking_location',
                'looking_budget_min',
                'looking_budget_max',
                'looking_currency',
                'looking_notes',
            ]);
        });
    }
};
