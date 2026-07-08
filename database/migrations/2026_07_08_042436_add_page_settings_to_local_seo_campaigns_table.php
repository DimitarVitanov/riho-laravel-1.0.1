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
        Schema::table('local_seo_campaigns', function (Blueprint $table) {
            $table->json('page_settings')->nullable()->after('page_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('local_seo_campaigns', function (Blueprint $table) {
            $table->dropColumn('page_settings');
        });
    }
};
