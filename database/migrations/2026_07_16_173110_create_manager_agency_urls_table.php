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
        Schema::create('manager_agency_urls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->string('url'); // The agency URL/domain the manager is responsible for
            $table->foreignId('agency_profile_id')->nullable()->constrained('agency_profiles')->onDelete('set null'); // Linked when agency signs up
            $table->string('status')->default('pending'); // pending, matched, inactive
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['manager_id', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_agency_urls');
    }
};
