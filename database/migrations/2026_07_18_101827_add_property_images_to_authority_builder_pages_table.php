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
        Schema::table('authority_builder_pages', function (Blueprint $table) {
            $table->json('property_images')->nullable()->after('content_sections');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authority_builder_pages', function (Blueprint $table) {
            $table->dropColumn('property_images');
        });
    }
};
