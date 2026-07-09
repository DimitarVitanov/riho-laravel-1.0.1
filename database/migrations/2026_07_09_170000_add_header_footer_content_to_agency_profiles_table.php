<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            // Top bar
            $table->string('header_topbar_text')->nullable()->after('website_custom_css');
            $table->boolean('header_topbar_enabled')->default(false)->after('header_topbar_text');
            // Logo
            $table->string('header_logo_path')->nullable()->after('header_topbar_enabled');
            $table->string('header_logo_url')->nullable()->after('header_logo_path');
            // Header colors
            $table->string('header_bg_color', 7)->nullable()->after('header_logo_url');
            $table->string('header_text_color', 7)->nullable()->after('header_bg_color');
            // CTA button
            $table->boolean('header_cta_enabled')->default(true)->after('header_text_color');
            $table->string('header_cta_text')->nullable()->after('header_cta_enabled');
            $table->string('header_cta_url')->nullable()->after('header_cta_text');
            $table->string('header_cta_bg_color', 7)->nullable()->after('header_cta_url');
            $table->string('header_cta_text_color', 7)->nullable()->after('header_cta_bg_color');
            // Nav menu (JSON: [{label, url}])
            $table->json('header_nav_items')->nullable()->after('header_cta_text_color');
            // Footer colors
            $table->string('footer_bg_color', 7)->nullable()->after('header_nav_items');
            $table->string('footer_text_color', 7)->nullable()->after('footer_bg_color');
            // Footer section 1 - links
            $table->string('footer_col1_title')->nullable()->after('footer_text_color');
            $table->json('footer_col1_links')->nullable()->after('footer_col1_title');
            // Footer section 2 - about text
            $table->string('footer_col2_title')->nullable()->after('footer_col1_links');
            $table->text('footer_col2_text')->nullable()->after('footer_col2_title');
            // Footer bottom
            $table->string('footer_copyright_text')->nullable()->after('footer_col2_text');
            $table->string('footer_terms_url')->nullable()->after('footer_copyright_text');
            $table->string('footer_privacy_url')->nullable()->after('footer_terms_url');
        });
    }

    public function down(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'header_topbar_text', 'header_topbar_enabled',
                'header_logo_path', 'header_logo_url',
                'header_bg_color', 'header_text_color',
                'header_cta_enabled', 'header_cta_text', 'header_cta_url',
                'header_cta_bg_color', 'header_cta_text_color',
                'header_nav_items',
                'footer_bg_color', 'footer_text_color',
                'footer_col1_title', 'footer_col1_links',
                'footer_col2_title', 'footer_col2_text',
                'footer_copyright_text', 'footer_terms_url', 'footer_privacy_url',
            ]);
        });
    }
};
