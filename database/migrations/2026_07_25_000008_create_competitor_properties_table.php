<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competitor_url_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_reference')->nullable();
            $table->string('canonical_url', 2048)->nullable();
            $table->enum('current_status', [
                'active',
                'possibly_removed',
                'removed',
                'unlisted',
                'sold',
                'url_changed',
                'unknown'
            ])->default('active');
            $table->timestamp('first_detected_at');
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('removed_at')->nullable();
            $table->timestamps();

            $table->index(['competitor_id', 'current_status'], 'comp_props_status_idx');
            $table->index(['competitor_id', 'first_detected_at'], 'comp_props_detected_idx');
            $table->index('external_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_properties');
    }
};
