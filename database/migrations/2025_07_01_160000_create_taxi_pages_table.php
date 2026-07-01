<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxi_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->default('home');
            $table->string('locale', 10)->default('en');

            // Top strip
            $table->string('taxi_strip_badge')->nullable();
            $table->string('taxi_strip_text')->nullable();

            // Hero section
            $table->string('taxi_hero_title')->nullable();
            $table->text('taxi_hero_copy')->nullable();

            // Ask AI section
            $table->string('taxi_ask_title')->nullable();
            $table->string('taxi_ask_placeholder')->nullable();
            $table->text('taxi_ask_note')->nullable();

            // Why section (paragraphs stored as JSON array)
            $table->json('taxi_why_paragraphs')->nullable();

            // Purpose card
            $table->text('taxi_purpose_text')->nullable();

            // Focus section
            $table->string('taxi_focus_title')->nullable();
            $table->text('taxi_focus_intro')->nullable();
            $table->text('taxi_focus_areas_intro')->nullable();
            $table->json('taxi_focus_areas')->nullable(); // [{number, title}]
            $table->json('taxi_focus_paragraphs')->nullable();

            // Topic 1
            $table->string('taxi_topic1_title')->nullable();
            $table->json('taxi_topic1_paragraphs')->nullable();

            // Topic 2
            $table->string('taxi_topic2_title')->nullable();
            $table->json('taxi_topic2_paragraphs')->nullable();

            // Topic 3
            $table->string('taxi_topic3_title')->nullable();
            $table->json('taxi_topic3_paragraphs')->nullable();
            $table->string('taxi_topic3_question')->nullable();
            $table->json('taxi_topic3_after_paragraphs')->nullable();

            // Topic 4
            $table->string('taxi_topic4_title')->nullable();
            $table->string('taxi_topic4_question')->nullable();
            $table->json('taxi_topic4_paragraphs')->nullable();
            $table->string('taxi_topic4_list_title')->nullable();
            $table->json('taxi_topic4_list_items')->nullable();
            $table->text('taxi_topic4_closing')->nullable();

            // Footer
            $table->text('taxi_footer_description')->nullable();
            $table->string('taxi_footer_subscribe_title')->nullable();
            $table->text('taxi_footer_subscribe_text')->nullable();

            // Meta
            $table->string('taxi_meta_title')->nullable();
            $table->string('taxi_meta_description')->nullable();

            $table->timestamps();

            $table->unique(['slug', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxi_pages');
    }
};
