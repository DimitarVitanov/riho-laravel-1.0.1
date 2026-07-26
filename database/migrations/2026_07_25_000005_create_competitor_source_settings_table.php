<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_source_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained()->cascadeOnDelete();
            $table->enum('source_type', [
                'website',
                'sitemaps',
                'properties',
                'google',
                'portals',
                'mentions'
            ]);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->unique(['competitor_id', 'source_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_source_settings');
    }
};
