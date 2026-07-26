<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_urls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competitor_source_id')->nullable()->constrained()->nullOnDelete();
            $table->string('url', 2048);
            $table->string('canonical_url', 2048)->nullable();
            $table->enum('page_type', [
                'homepage',
                'property_listing',
                'property_detail',
                'location_page',
                'blog_post',
                'news',
                'team',
                'services',
                'contact',
                'faq',
                'other'
            ])->nullable();
            $table->enum('status', ['active', 'removed', 'redirected', 'error'])->default('active');
            $table->timestamp('first_detected_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('sitemap_lastmod')->nullable();
            $table->timestamps();

            $table->index(['competitor_id', 'status']);
            $table->index(['competitor_id', 'page_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_urls');
    }
};
