<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_discovery_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained()->cascadeOnDelete();
            $table->string('url', 2048);
            $table->string('title', 1024)->nullable();
            $table->text('snippet')->nullable();
            $table->enum('source_type', [
                'property_portal',
                'news',
                'blog',
                'directory',
                'backlink',
                'press_release',
                'review',
                'forum',
                'partnership',
                'social',
                'other'
            ])->nullable();
            $table->enum('match_status', ['pending', 'match', 'no_match', 'unknown'])->default('pending');
            $table->unsignedTinyInteger('ai_confidence')->nullable();
            $table->text('ai_reason')->nullable();
            $table->timestamp('discovered_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['competitor_id', 'match_status'], 'comp_discovery_match_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_discovery_candidates');
    }
};
