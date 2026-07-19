<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villa_ready_property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_ready_property_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('image_type')->default('gallery'); // main, gallery, drone, floor_plan, 360
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villa_ready_property_images');
    }
};
