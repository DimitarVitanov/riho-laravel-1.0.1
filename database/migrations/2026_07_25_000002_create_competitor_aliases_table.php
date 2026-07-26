<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_aliases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained()->cascadeOnDelete();
            $table->string('alias');
            $table->string('evidence')->nullable();
            $table->unsignedTinyInteger('confidence')->default(100);
            $table->boolean('is_user_confirmed')->default(false);
            $table->timestamps();

            $table->index('competitor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_aliases');
    }
};
