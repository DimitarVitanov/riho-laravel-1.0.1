<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agency_listings', function (Blueprint $table) {
            $table->string('external_url')->nullable()->after('status');
            $table->integer('size')->nullable()->after('external_url');
            $table->integer('bedrooms')->nullable()->after('size');
            $table->integer('bathrooms')->nullable()->after('bedrooms');
            $table->json('features')->nullable()->after('bathrooms');
            $table->string('primary_image')->nullable()->after('features');
        });
    }

    public function down(): void
    {
        Schema::table('agency_listings', function (Blueprint $table) {
            $table->dropColumn(['external_url', 'size', 'bedrooms', 'bathrooms', 'features', 'primary_image']);
        });
    }
};
