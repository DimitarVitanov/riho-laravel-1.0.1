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
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->boolean('ai_search_promo_enabled')->default(false)->after('sidebar_promo_button_text');
            $table->string('ai_search_promo_title')->nullable()->after('ai_search_promo_enabled');
            $table->text('ai_search_promo_text')->nullable()->after('ai_search_promo_title');
            $table->string('ai_search_promo_url')->nullable()->after('ai_search_promo_text');
            $table->string('ai_search_promo_image')->nullable()->after('ai_search_promo_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'ai_search_promo_enabled',
                'ai_search_promo_title',
                'ai_search_promo_text',
                'ai_search_promo_url',
                'ai_search_promo_image',
            ]);
        });
    }
};
