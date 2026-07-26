<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained()->cascadeOnDelete();
            $table->date('report_date');
            $table->text('executive_summary')->nullable();
            $table->text('report_json')->nullable();
            $table->string('prompt_version')->nullable();
            $table->string('ai_model')->nullable();
            $table->text('source_event_ids')->nullable();
            $table->timestamps();

            $table->unique(['agency_profile_id', 'report_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_daily_reports');
    }
};
