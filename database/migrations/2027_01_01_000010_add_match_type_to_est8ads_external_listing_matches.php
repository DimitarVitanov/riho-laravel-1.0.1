<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('est8ads_external_listing_matches', function (Blueprint $table) {
            // exact | tolerance | conflict — lets the UI list exact hits first
            // without recomputing the tolerance bands on every read.
            $table->string('match_type', 20)->default('exact')->after('status')->index();
            $table->json('tolerance')->nullable()->after('hard_conflicts');
        });
    }

    public function down(): void
    {
        Schema::table('est8ads_external_listing_matches', function (Blueprint $table) {
            $table->dropIndex(['match_type']);
            $table->dropColumn(['match_type', 'tolerance']);
        });
    }
};
