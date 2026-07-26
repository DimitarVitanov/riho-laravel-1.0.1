<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained()->cascadeOnDelete();
            $table->enum('type', [
                'website',
                'sitemap',
                'rss',
                'google_place',
                'property_portal',
                'social',
                'directory',
                'news',
                'blog',
                'backlink',
                'press_release',
                'review',
                'forum',
                'partnership',
                'other'
            ]);
            $table->string('url')->nullable();
            $table->string('external_id')->nullable();
            $table->enum('status', ['active', 'paused', 'error', 'pending'])->default('pending');
            $table->text('config_json')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->index(['competitor_id', 'type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_sources');
    }
};
