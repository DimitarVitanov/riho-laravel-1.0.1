<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_strategy_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained()->cascadeOnDelete();
            $table->enum('summary_type', ['property_strategy', 'seo_strategy', 'market_focus', 'pricing_strategy']);
            $table->text('observation');
            $table->unsignedTinyInteger('confidence')->default(50);
            $table->text('evidence_event_ids')->nullable();
            $table->date('period_start');
            $table->date('period_end');
            $table->string('prompt_version')->nullable();
            $table->string('ai_model')->nullable();
            $table->timestamps();

            $table->index(['competitor_id', 'summary_type', 'period_end'], 'comp_strategy_type_period_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_strategy_summaries');
    }
};
