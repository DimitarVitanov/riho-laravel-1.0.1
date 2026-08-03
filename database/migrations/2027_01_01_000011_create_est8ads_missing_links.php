<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('est8ads_missing_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_move_id')->constrained('est8ads_property_moves')->cascadeOnDelete();
            $table->foreignId('external_listing_match_id')->nullable()->constrained('est8ads_external_listing_matches')->nullOnDelete();
            // Always 'conflict' today: a matching candidate property was found
            // but a hard rule blocked the connection. Kept as its own column
            // so future reasons (e.g. a missing chain participant) can be
            // added without a schema change.
            $table->string('reason_type', 20)->index();
            $table->string('title');
            $table->string('location')->nullable();
            $table->string('impact')->nullable();
            $table->decimal('unlock_value', 15, 2)->nullable();
            $table->string('unlock_value_currency', 3)->default('EUR');
            $table->string('priority', 10)->default('Medium')->index();
            $table->unsignedTinyInteger('priority_rank')->default(2);
            $table->json('blocking_conflicts')->nullable();
            $table->text('explanation')->nullable();
            $table->string('status', 20)->default('open')->index();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->unique(['property_move_id', 'reason_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('est8ads_missing_links');
    }
};
