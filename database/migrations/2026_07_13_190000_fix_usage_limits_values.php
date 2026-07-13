<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('usage_limits')->update([
            'local_seo_pages_limit' => 15,
            'ai_search_ranking_limit' => 15,
            'authority_review_updates_limit' => 30,
        ]);
    }

    public function down(): void
    {
        // No rollback
    }
};
