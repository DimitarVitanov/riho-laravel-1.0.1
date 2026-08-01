<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('est8ads_chains', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->nullOnDelete();
            $table->string('name');
            $table->string('status')->default('building')->index();
            $table->unsignedInteger('node_count')->default(0);
            $table->decimal('confidence_score', 5, 4)->nullable()->index();
            $table->json('summary')->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['agency_id', 'status', 'updated_at']);
        });

        Schema::create('est8ads_chain_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chain_id')->constrained('est8ads_chains')->cascadeOnDelete();
            $table->foreignId('property_move_id')->nullable()->constrained('est8ads_property_moves')->nullOnDelete();
            $table->foreignId('property_id')->nullable()->constrained('est8ads_properties')->nullOnDelete();
            $table->string('node_type')->index();
            $table->string('status')->default('candidate')->index();
            $table->unsignedInteger('position')->default(0);
            $table->json('constraints')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['chain_id', 'position']);
        });

        Schema::create('est8ads_chain_edges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chain_id')->constrained('est8ads_chains')->cascadeOnDelete();
            $table->foreignId('from_node_id')->constrained('est8ads_chain_nodes')->cascadeOnDelete();
            $table->foreignId('to_node_id')->constrained('est8ads_chain_nodes')->cascadeOnDelete();
            $table->string('edge_type')->default('depends_on')->index();
            $table->string('status')->default('proposed')->index();
            $table->decimal('score', 5, 4)->nullable();
            $table->json('reasons')->nullable();
            $table->timestamps();
            $table->unique(['chain_id', 'from_node_id', 'to_node_id']);
        });

        Schema::create('est8ads_chain_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chain_id')->constrained('est8ads_chains')->cascadeOnDelete();
            $table->foreignId('property_move_id')->constrained('est8ads_property_moves')->cascadeOnDelete();
            $table->foreignId('property_id')->nullable()->constrained('est8ads_properties')->nullOnDelete();
            $table->string('status')->default('pending')->index();
            $table->decimal('score', 5, 4)->index();
            $table->json('score_breakdown')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();
            $table->unique(['chain_id', 'property_move_id', 'property_id'], 'est8ads_chain_candidate_unique');
        });

        Schema::create('est8ads_match_results', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('property_move_id')->constrained('est8ads_property_moves')->cascadeOnDelete();
            $table->foreignId('property_id')->nullable()->constrained('est8ads_properties')->nullOnDelete();
            $table->foreignId('chain_id')->nullable()->constrained('est8ads_chains')->nullOnDelete();
            $table->string('match_type')->default('direct')->index();
            $table->string('status')->default('proposed')->index();
            $table->decimal('score', 5, 4)->index();
            $table->json('score_breakdown')->nullable();
            $table->json('explanation')->nullable();
            $table->string('algorithm_version')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['property_move_id', 'status', 'score']);
            $table->index(['chain_id', 'status']);
        });

        Schema::create('est8ads_match_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_result_id')->constrained('est8ads_match_results')->cascadeOnDelete();
            $table->foreignId('profile_id')->nullable()->constrained('est8ads_profiles')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('party_role')->index();
            $table->string('decision')->index();
            $table->text('comment')->nullable();
            $table->timestamp('decided_at');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['match_result_id', 'party_role', 'profile_id'], 'est8ads_match_confirmation_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('est8ads_match_confirmations');
        Schema::dropIfExists('est8ads_match_results');
        Schema::dropIfExists('est8ads_chain_candidates');
        Schema::dropIfExists('est8ads_chain_edges');
        Schema::dropIfExists('est8ads_chain_nodes');
        Schema::dropIfExists('est8ads_chains');
    }
};
