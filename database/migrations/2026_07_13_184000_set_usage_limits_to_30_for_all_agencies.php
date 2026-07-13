<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('usage_limits')->update([
            'local_seo_pages_limit' => 30,
            'authority_review_updates_limit' => 30,
        ]);
    }

    public function down(): void
    {
        // No reliable rollback because original values are unknown.
    }
};
