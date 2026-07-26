<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_url_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_url_id')->constrained()->cascadeOnDelete();
            $table->string('title', 1024)->nullable();
            $table->string('title_hash', 64)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_description_hash', 64)->nullable();
            $table->string('h1', 1024)->nullable();
            $table->string('h1_hash', 64)->nullable();
            $table->string('content_hash', 64)->nullable();
            $table->text('schema_json')->nullable();
            $table->string('schema_hash', 64)->nullable();
            $table->text('cta_text')->nullable();
            $table->string('cta_hash', 64)->nullable();
            $table->unsignedInteger('word_count')->nullable();
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->timestamp('captured_at');
            $table->timestamps();

            $table->index(['competitor_url_id', 'captured_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_url_snapshots');
    }
};
