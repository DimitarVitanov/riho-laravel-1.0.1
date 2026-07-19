<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villa_ready_property_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_ready_property_id')->constrained()->onDelete('cascade');
            $table->integer('building_number');
            $table->string('floor'); // Ground Floor, First Floor, Attic
            $table->string('unit_code'); // A1, B1, C1
            $table->decimal('size_m2', 8, 2);
            $table->decimal('net_price', 12, 2);
            $table->string('status')->default('available'); // available, reserved, sold
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villa_ready_property_units');
    }
};
