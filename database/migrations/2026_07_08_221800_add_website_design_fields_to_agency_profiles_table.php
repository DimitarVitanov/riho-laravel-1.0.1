<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->string('website_primary_color', 7)->nullable()->after('ai_content_language');
            $table->string('website_secondary_color', 7)->nullable()->after('website_primary_color');
            $table->string('website_accent_color', 7)->nullable()->after('website_secondary_color');
            $table->string('website_header_style', 20)->default('standard')->after('website_accent_color');
            $table->string('website_footer_style', 20)->default('standard')->after('website_header_style');
            $table->boolean('website_show_logo_in_header')->default(true)->after('website_footer_style');
            $table->boolean('website_show_contact_in_header')->default(false)->after('website_show_logo_in_header');
            $table->boolean('website_show_social_in_footer')->default(true)->after('website_show_contact_in_header');
            $table->text('website_custom_css')->nullable()->after('website_show_social_in_footer');
        });
    }

    public function down(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'website_primary_color',
                'website_secondary_color',
                'website_accent_color',
                'website_header_style',
                'website_footer_style',
                'website_show_logo_in_header',
                'website_show_contact_in_header',
                'website_show_social_in_footer',
                'website_custom_css',
            ]);
        });
    }
};
