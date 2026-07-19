<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create a separate content table to avoid row size limits
        Schema::create('villa_ready_property_content', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_ready_property_id')->constrained()->onDelete('cascade');
            
            // ========== PUBLICATION & CONTENT LOCK ==========
            $table->boolean('content_locked')->default(true);
            $table->string('content_lock_level', 20)->default('locked');
            
            // ========== HERO SECTION ==========
            $table->string('hero_eyebrow')->nullable();
            $table->json('hero_chips')->nullable();
            $table->string('hero_media_label', 100)->nullable();
            $table->text('hero_360_url')->nullable();
            
            // ========== LOCATION VALUE SECTION ==========
            $table->string('location_value_title')->nullable();
            $table->text('location_value_subtitle')->nullable();
            $table->longText('location_value_content')->nullable();
            $table->text('location_value_highlight')->nullable();
            
            // ========== 4-VILLA CHAIN LOCATION ==========
            $table->string('chain_title')->nullable();
            $table->text('chain_subtitle')->nullable();
            $table->longText('chain_content')->nullable();
            
            // ========== SEA VIEW SECTION ==========
            $table->string('sea_title')->nullable();
            $table->text('sea_subtitle')->nullable();
            $table->longText('sea_description')->nullable();
            
            // ========== MAP VIEW SECTION ==========
            $table->string('map_title')->nullable();
            $table->text('map_subtitle')->nullable();
            $table->longText('map_description')->nullable();
            $table->text('map_embed_url')->nullable();
            $table->string('map_coordinates', 50)->nullable();
            
            // ========== PROJECT COUNTERS ==========
            $table->string('stat_total_area', 50)->nullable();
            $table->string('stat_plots', 20)->nullable();
            $table->string('stat_villas', 20)->nullable();
            $table->string('stat_apartments', 20)->nullable();
            $table->integer('total_area_m2')->nullable();
            $table->integer('plots_count')->nullable();
            
            // ========== ACCESS SECTION ==========
            $table->string('access_title')->nullable();
            $table->text('access_subtitle')->nullable();
            $table->longText('access_intro')->nullable();
            $table->json('access_cards')->nullable();
            
            // ========== PLOTS SECTION ==========
            $table->string('plots_title')->nullable();
            $table->text('plots_subtitle')->nullable();
            $table->json('plot_sizes')->nullable();
            
            // ========== CONCEPTUAL DEVELOPMENT ==========
            $table->string('concept_title')->nullable();
            $table->text('concept_subtitle')->nullable();
            $table->longText('concept_disclaimer')->nullable();
            
            // ========== AERIAL PERSPECTIVE ==========
            $table->string('aerial_title')->nullable();
            $table->text('aerial_subtitle')->nullable();
            
            // ========== PRICING SECTION ==========
            $table->string('pricing_title')->nullable();
            $table->text('pricing_subtitle')->nullable();
            $table->longText('pricing_payment_text')->nullable();
            $table->longText('pricing_discount_text')->nullable();
            $table->integer('apartment_discount')->nullable();
            $table->integer('villa_discount')->nullable();
            $table->longText('custom_villa_option')->nullable();
            $table->text('permitted_structure')->nullable();
            $table->string('basement_use', 100)->nullable();
            $table->string('pricing_currency', 10)->default('EUR');
            $table->json('buildings_data')->nullable();
            $table->decimal('total_project_price', 15, 2)->nullable();
            
            // ========== TAX SECTION ==========
            $table->string('tax_title')->nullable();
            $table->text('tax_subtitle')->nullable();
            $table->longText('tax_intro')->nullable();
            $table->longText('non_eu_note')->nullable();
            $table->integer('vat_rate')->default(25);
            $table->json('tax_groups')->nullable();
            
            // ========== PRIVATE INVESTOR CALL ==========
            $table->string('investor_title')->nullable();
            $table->text('investor_subtitle')->nullable();
            $table->longText('investor_content')->nullable();
            $table->string('investor_button', 100)->nullable();
            $table->text('investor_booking_url')->nullable();
            $table->string('investor_whatsapp', 50)->nullable();
            $table->string('investor_email', 100)->nullable();
            
            // ========== CORE VALUES ==========
            $table->string('core_title')->nullable();
            $table->text('core_subtitle')->nullable();
            $table->json('core_values')->nullable();
            
            // ========== PROJECT SUMMARY ==========
            $table->string('summary_title')->nullable();
            $table->text('summary_subtitle')->nullable();
            $table->longText('summary_text')->nullable();
            
            // ========== HISTORY SECTION ==========
            $table->string('history_title')->nullable();
            $table->text('history_subtitle')->nullable();
            $table->longText('history_content')->nullable();
            
            // ========== FINAL MESSAGE ==========
            $table->longText('final_message')->nullable();
            
            // ========== CONTACT FORM SETTINGS ==========
            $table->string('contact_form_title')->nullable();
            $table->text('contact_form_subtitle')->nullable();
            $table->string('contact_button', 100)->nullable();
            $table->text('contact_action')->nullable();
            $table->string('agency_recipient_email', 100)->nullable();
            $table->string('agency_phone', 50)->nullable();
            $table->string('crm_pipeline', 100)->nullable();
            $table->text('contact_interest_options')->nullable();
            $table->boolean('require_name')->default(true);
            $table->boolean('require_email')->default(true);
            $table->boolean('require_phone')->default(false);
            
            // ========== SIDEBAR ==========
            $table->string('sidebar_price_label', 100)->nullable();
            $table->string('sidebar_price_value', 100)->nullable();
            $table->text('sidebar_price_note')->nullable();
            $table->json('key_facts')->nullable();
            
            // ========== SEO / SCHEMA ==========
            $table->text('canonical_url')->nullable();
            $table->text('og_image')->nullable();
            $table->string('schema_name')->nullable();
            $table->text('schema_address')->nullable();
            $table->string('schema_latitude', 20)->nullable();
            $table->string('schema_longitude', 20)->nullable();
            $table->date('date_published')->nullable();
            $table->date('date_modified')->nullable();
            $table->longText('schema_extra')->nullable();
            
            // ========== MEDIA PATHS ==========
            $table->text('logo_path')->nullable();
            $table->text('decor_lines_path')->nullable();
            $table->text('decor_dot_path')->nullable();
            
            $table->timestamps();
            
            $table->unique('villa_ready_property_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villa_ready_property_content');
    }
};
