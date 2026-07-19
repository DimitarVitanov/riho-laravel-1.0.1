<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Track which agencies have this property published on their site
        Schema::create('villa_ready_agency_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_ready_property_id')->constrained()->onDelete('cascade');
            $table->foreignId('agency_profile_id')->constrained()->onDelete('cascade');
            $table->string('affiliate_code')->unique(); // e.g., AGENCY-DEMO-001
            $table->string('page_slug'); // e.g., /properties/villa-ready-milna
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            
            $table->unique(['villa_ready_property_id', 'agency_profile_id'], 'vr_pub_property_agency_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villa_ready_agency_publications');
    }
};
