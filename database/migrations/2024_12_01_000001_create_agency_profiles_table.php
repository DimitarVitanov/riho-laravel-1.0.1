<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agency_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('agency_name')->nullable();
            $table->string('official_website_url')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('main_service_area')->nullable();
            $table->string('target_city')->nullable();
            $table->integer('target_radius_km')->nullable();
            $table->text('main_property_types')->nullable();
            $table->text('buyer_types')->nullable();
            $table->text('seller_services')->nullable();
            $table->text('rental_management_services')->nullable();
            $table->text('investment_services')->nullable();
            $table->boolean('foreign_buyer_support')->default(false);
            $table->boolean('property_management_support')->default(false);
            $table->string('google_business_profile_url')->nullable();
            $table->string('sitemap_url')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('agency_logo_path')->nullable();
            $table->string('brand_primary_color')->nullable();
            $table->string('brand_secondary_color')->nullable();
            $table->unsignedBigInteger('subscription_plan_id')->nullable();
            $table->string('subscription_status')->nullable();
            $table->unsignedBigInteger('assigned_manager_id')->nullable();
            $table->enum('ai_status', ['active', 'paused', 'suspended'])->default('paused');
            $table->string('ai_content_language')->default('English');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_manager_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agency_profiles');
    }
};
