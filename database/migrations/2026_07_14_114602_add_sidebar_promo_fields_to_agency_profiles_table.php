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
            $table->boolean('sidebar_promo_enabled')->default(true)->after('sidebar_show_last_updated');
            $table->string('sidebar_promo_image')->nullable()->after('sidebar_promo_enabled');
            $table->string('sidebar_promo_title')->nullable()->after('sidebar_promo_image');
            $table->text('sidebar_promo_text')->nullable()->after('sidebar_promo_title');
            $table->string('sidebar_promo_url')->nullable()->after('sidebar_promo_text');
            $table->string('sidebar_promo_button_text')->nullable()->after('sidebar_promo_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'sidebar_promo_enabled',
                'sidebar_promo_image',
                'sidebar_promo_title',
                'sidebar_promo_text',
                'sidebar_promo_url',
                'sidebar_promo_button_text',
            ]);
        });
    }
};
