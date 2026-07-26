<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competitor_source_id')->nullable()->constrained()->nullOnDelete();
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
            ]);
            $table->timestamp('first_detected_at');
            $table->timestamp('last_seen_at')->nullable();
            $table->enum('status', ['active', 'removed', 'unknown'])->default('active');
            $table->timestamps();

            $table->index(['competitor_id', 'source_type'], 'comp_mentions_source_idx');
            $table->index(['competitor_id', 'first_detected_at'], 'comp_mentions_detected_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_mentions');
    }
};
