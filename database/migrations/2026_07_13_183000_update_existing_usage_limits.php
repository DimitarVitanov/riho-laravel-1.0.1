<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Raise authority review limit to 30 for all agencies currently on the old default of 10.
        DB::table('usage_limits')
            ->where('authority_review_updates_limit', 10)
            ->update(['authority_review_updates_limit' => 30]);

        // Recount local_seo_pages_used to include already published Local SEO + AI Search Ranking pages.
        // This is a one-time sync so existing pages are reflected in the current period usage.
        $limits = DB::table('usage_limits')->get();
        foreach ($limits as $limit) {
            $localSeoPages = DB::table('generated_pages')
                ->where('agency_profile_id', $limit->agency_profile_id)
                ->where('feature_key', 'local_seo_presence_boost')
                ->where('status', 'published')
                ->count();

            $aiSearchPages = DB::table('ai_authority_pages')
                ->where('agency_profile_id', $limit->agency_profile_id)
                ->where('status', 'published')
                ->count();

            $used = $localSeoPages + $aiSearchPages;

            DB::table('usage_limits')
                ->where('id', $limit->id)
                ->update(['local_seo_pages_used' => min($used, $limit->local_seo_pages_limit)]);
        }
    }

    public function down(): void
    {
        DB::table('usage_limits')
            ->where('authority_review_updates_limit', 30)
            ->update(['authority_review_updates_limit' => 10]);
    }
};
