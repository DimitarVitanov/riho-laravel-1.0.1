<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agency_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->string('agency_name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('license')->nullable();
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->integer('reviews_count')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_agents');
    }
};
