<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competitor_events', function (Blueprint $table) {
            $table->string('created_page_feature')->nullable()->after('opportunity_status');
            $table->unsignedBigInteger('created_page_id')->nullable()->after('created_page_feature');
        });

        Schema::table('ai_authority_pages', function (Blueprint $table) {
            $table->text('generation_brief')->nullable()->after('page_type');
        });
    }

    public function down(): void
    {
        Schema::table('competitor_events', function (Blueprint $table) {
            $table->dropColumn(['created_page_feature', 'created_page_id']);
        });

        Schema::table('ai_authority_pages', function (Blueprint $table) {
            $table->dropColumn('generation_brief');
        });
    }
};
