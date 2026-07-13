<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Force update ALL usage_limits records regardless of current values
        DB::table('usage_limits')->update([
            'local_seo_pages_limit' => 30,
            'authority_review_updates_limit' => 30,
            'ai_search_freshness_updates_limit' => 30,
        ]);
    }

    public function down(): void
    {
        // No rollback
    }
};
