<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_daily_report_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competitor_daily_report_id');
            $table->foreign('competitor_daily_report_id', 'comp_report_items_report_fk')
                  ->references('id')->on('competitor_daily_reports')->cascadeOnDelete();
            $table->enum('item_type', ['what_changed', 'why_it_matters', 'recommended_action']);
            $table->text('content');
            $table->enum('priority', ['high', 'medium', 'low'])->nullable();
            $table->text('reason')->nullable();
            $table->text('evidence_event_ids')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['competitor_daily_report_id', 'item_type'], 'comp_report_items_type_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_daily_report_items');
    }
};
