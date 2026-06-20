<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agency_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained('agency_profiles')->onDelete('cascade');
            $table->string('title');
            $table->string('property_type')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('currency')->default('EUR');
            $table->json('images_json')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['agency_profile_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agency_listings');
    }
};
