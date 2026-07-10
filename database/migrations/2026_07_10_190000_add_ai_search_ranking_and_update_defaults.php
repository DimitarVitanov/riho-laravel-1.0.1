<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usage_limits', function (Blueprint $table) {
            if (!Schema::hasColumn('usage_limits', 'ai_search_ranking_limit')) {
                $table->integer('ai_search_ranking_limit')->default(15)->after('period_end');
            }
            if (!Schema::hasColumn('usage_limits', 'ai_search_ranking_used')) {
                $table->integer('ai_search_ranking_used')->default(0)->after('ai_search_ranking_limit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('usage_limits', function (Blueprint $table) {
            $table->dropColumn(['ai_search_ranking_limit', 'ai_search_ranking_used']);
        });
    }
};
