<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villa_ready_properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_id')->unique(); // e.g., VRC-MILNA-001
            $table->string('status')->default('draft'); // draft, published, reserved, sold
            
            // Main text fields
            $table->string('title');
            $table->string('short_title')->nullable();
            $table->string('location');
            $table->string('address')->nullable();
            $table->string('price_display')->nullable(); // e.g., "€5,900 / m² net"
            $table->string('property_type')->nullable();
            
            // Descriptions
            $table->text('intro')->nullable();
            $table->text('description')->nullable();
            $table->text('location_description')->nullable();
            $table->text('disclaimer')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('slug')->unique();
            
            // Project details
            $table->integer('buildings_count')->nullable();
            $table->string('structure')->nullable(); // e.g., "Basement + Ground Floor + 1st Floor + Attic"
            $table->decimal('price_per_m2', 10, 2)->nullable();
            $table->string('ground_floor_range')->nullable();
            $table->string('first_floor_range')->nullable();
            $table->string('attic_range')->nullable();
            
            // Purchase info
            $table->text('payment_structure')->nullable();
            $table->text('vat_info')->nullable();
            $table->text('use_options')->nullable();
            $table->text('management_service')->nullable();
            
            // Affiliate settings
            $table->decimal('commission_percent', 5, 2)->default(6.00);
            $table->integer('cookie_duration_days')->default(180);
            $table->string('source_url')->nullable();
            $table->boolean('agency_can_edit')->default(false);
            
            // Featured image (main image path)
            $table->string('featured_image')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villa_ready_properties');
    }
};
