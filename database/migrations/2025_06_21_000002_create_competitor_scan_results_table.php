<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_scan_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained('agency_profiles')->onDelete('cascade');
            $table->foreignId('competitor_website_id')->nullable()->constrained('competitor_websites')->onDelete('set null');
            $table->string('scan_type'); // new_properties, seo_pages, blog, price_movement, gbp_reviews, weakness_detection
            $table->string('title');
            $table->text('summary');
            $table->json('details_json')->nullable();
            $table->string('recommended_action')->nullable();
            $table->text('recommended_content')->nullable();
            $table->string('status')->default('new'); // new, reviewed, acted, dismissed
            $table->timestamp('scanned_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_scan_results');
    }
};
