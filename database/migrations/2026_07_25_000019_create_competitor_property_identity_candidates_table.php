<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_property_identity_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_a_id')->constrained('competitor_properties')->cascadeOnDelete();
            $table->foreignId('property_b_id')->constrained('competitor_properties')->cascadeOnDelete();
            $table->enum('match_status', ['pending', 'same', 'different', 'unknown'])->default('pending');
            $table->unsignedTinyInteger('ai_confidence')->nullable();
            $table->text('ai_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['property_a_id', 'property_b_id'], 'comp_prop_identity_pair_unique');
            $table->index('match_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_property_identity_candidates');
    }
};
