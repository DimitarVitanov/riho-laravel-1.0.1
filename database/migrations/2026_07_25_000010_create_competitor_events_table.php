<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competitor_source_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('event_type', [
                'new_property',
                'property_removed',
                'possibly_removed',
                'price_increase',
                'price_decrease',
                'description_changed',
                'images_added',
                'images_removed',
                'new_url',
                'url_removed',
                'title_changed',
                'meta_description_changed',
                'h1_changed',
                'content_changed',
                'schema_changed',
                'cta_changed',
                'new_seo_page',
                'new_blog_post',
                'new_review',
                'rating_changed',
                'new_mention',
                'new_backlink'
            ]);
            $table->enum('entity_type', ['property', 'url', 'review', 'mention', 'other'])->default('other');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->timestamp('detected_at');
            $table->timestamp('verified_at')->nullable();
            $table->text('old_value_json')->nullable();
            $table->text('new_value_json')->nullable();
            $table->text('fact_json')->nullable();
            $table->string('evidence_url', 2048)->nullable();
            $table->unsignedTinyInteger('confidence')->default(100);
            $table->unsignedTinyInteger('importance_score')->default(50);
            $table->text('ai_interpretation')->nullable();
            $table->text('ai_opportunity')->nullable();
            $table->text('ai_action')->nullable();
            $table->unsignedTinyInteger('ai_confidence')->nullable();
            $table->text('ai_evidence_event_ids')->nullable();
            $table->unsignedTinyInteger('ai_priority')->nullable();
            $table->enum('opportunity_status', ['open', 'actioned', 'dismissed', 'expired'])->nullable();
            $table->timestamps();

            $table->index(['competitor_id', 'event_type', 'detected_at'], 'comp_events_type_detected_idx');
            $table->index(['competitor_id', 'detected_at'], 'comp_events_detected_idx');
            $table->index(['entity_type', 'entity_id'], 'comp_events_entity_idx');
            $table->index('opportunity_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_events');
    }
};
