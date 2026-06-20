<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('local_seo_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained('agency_profiles')->onDelete('cascade');
            $table->string('target_type'); // city, keyword, subniche
            $table->string('target_value');
            $table->boolean('is_selected')->default(true);
            $table->foreignId('generated_page_id')->nullable()->constrained('generated_pages')->nullOnDelete();
            $table->timestamps();

            $table->index(['agency_profile_id', 'target_type']);
            $table->index(['agency_profile_id', 'is_selected']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('local_seo_targets');
    }
};
