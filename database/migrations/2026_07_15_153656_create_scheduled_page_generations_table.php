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
        Schema::create('scheduled_page_generations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_profile_id');
            $table->unsignedBigInteger('local_seo_campaign_id');
            $table->string('place_name');
            $table->string('place_type')->nullable();
            $table->string('place_distance')->nullable();
            $table->date('scheduled_for');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->unsignedBigInteger('generated_page_id')->nullable();
            $table->timestamps();
            
            $table->foreign('agency_profile_id')->references('id')->on('agency_profiles')->onDelete('cascade');
            $table->foreign('local_seo_campaign_id')->references('id')->on('local_seo_campaigns')->onDelete('cascade');
            $table->foreign('generated_page_id')->references('id')->on('generated_pages')->onDelete('set null');
            $table->index(['scheduled_for', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_page_generations');
    }
};
