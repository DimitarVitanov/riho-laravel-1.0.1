<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_property_snapshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competitor_property_id');
            $table->foreign('competitor_property_id', 'comp_prop_snap_prop_fk')
                  ->references('id')->on('competitor_properties')->cascadeOnDelete();
            $table->string('title', 1024)->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('currency', 3)->default('EUR');
            $table->decimal('price_per_m2', 12, 2)->nullable();
            $table->string('location_text')->nullable();
            $table->enum('property_type', [
                'apartment',
                'house',
                'villa',
                'land',
                'commercial',
                'hotel',
                'other'
            ])->nullable();
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();
            $table->decimal('surface_m2', 10, 2)->nullable();
            $table->decimal('plot_m2', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->text('images_json')->nullable();
            $table->string('agent_name')->nullable();
            $table->enum('extraction_method', ['json_ld', 'dom_selector', 'heuristic', 'ai_fallback'])->nullable();
            $table->text('field_confidence_json')->nullable();
            $table->timestamp('captured_at');
            $table->timestamps();

            $table->index(['competitor_property_id', 'captured_at'], 'comp_prop_snap_prop_captured_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_property_snapshots');
    }
};
