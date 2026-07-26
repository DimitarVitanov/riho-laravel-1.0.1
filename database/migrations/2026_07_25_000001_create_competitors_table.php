<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('primary_market')->nullable();
            $table->string('website_url');
            $table->string('normalized_domain');
            $table->string('google_place_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_scan_at')->nullable();
            $table->timestamps();

            $table->index(['agency_profile_id', 'is_active']);
            $table->unique(['agency_profile_id', 'normalized_domain']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitors');
    }
};
