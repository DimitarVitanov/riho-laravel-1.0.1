<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_identifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['phone', 'email', 'email_domain', 'person_name', 'vat_number', 'oib']);
            $table->string('value');
            $table->string('normalized_value')->nullable();
            $table->string('display_value')->nullable();
            $table->timestamps();

            $table->index(['competitor_id', 'type']);
            $table->index('normalized_value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_identifiers');
    }
};
