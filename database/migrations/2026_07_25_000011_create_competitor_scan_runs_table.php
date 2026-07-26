<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_scan_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained()->cascadeOnDelete();
            $table->enum('level', ['discovery', 'deep_scan', 'ai_analysis']);
            $table->enum('status', ['pending', 'running', 'success', 'partial', 'failed'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('urls_scanned')->default(0);
            $table->unsignedInteger('changes_detected')->default(0);
            $table->unsignedInteger('errors_count')->default(0);
            $table->text('error_log')->nullable();
            $table->timestamps();

            $table->index(['competitor_id', 'level', 'status']);
            $table->index(['competitor_id', 'finished_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_scan_runs');
    }
};
