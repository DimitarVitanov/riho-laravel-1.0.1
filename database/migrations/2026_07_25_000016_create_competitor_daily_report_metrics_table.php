<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_daily_report_metrics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competitor_daily_report_id');
            $table->foreign('competitor_daily_report_id', 'comp_report_metrics_report_fk')
                  ->references('id')->on('competitor_daily_reports')->cascadeOnDelete();
            $table->unsignedInteger('new_properties')->default(0);
            $table->unsignedInteger('removed_properties')->default(0);
            $table->unsignedInteger('price_increases')->default(0);
            $table->unsignedInteger('price_decreases')->default(0);
            $table->unsignedInteger('new_seo_pages')->default(0);
            $table->unsignedInteger('new_blog_posts')->default(0);
            $table->unsignedInteger('new_reviews')->default(0);
            $table->unsignedInteger('new_mentions')->default(0);
            $table->unsignedInteger('total_changes')->default(0);
            $table->timestamps();

            $table->unique('competitor_daily_report_id', 'comp_report_metrics_report_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_daily_report_metrics');
    }
};
