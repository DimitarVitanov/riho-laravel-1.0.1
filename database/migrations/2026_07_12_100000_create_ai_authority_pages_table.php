<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_authority_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('target_city');
            $table->string('target_neighborhood')->nullable();
            $table->string('country')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('property_type')->nullable();
            $table->string('page_type')->default('property');
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->json('ai_generated_content')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('schema_markup')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();

            $table->index(['agency_profile_id', 'status']);
            $table->index('target_city');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_authority_pages');
    }
};
