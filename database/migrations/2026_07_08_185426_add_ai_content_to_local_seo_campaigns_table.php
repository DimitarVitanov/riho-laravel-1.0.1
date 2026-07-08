<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('local_seo_campaigns', function (Blueprint $table) {
            $table->json('ai_generated_content')->nullable()->after('page_settings');
            $table->timestamp('content_generated_at')->nullable()->after('ai_generated_content');
            $table->string('content_uniqueness_status')->default('pending')->after('content_generated_at');
            $table->json('uniqueness_result')->nullable()->after('content_uniqueness_status');
        });
    }

    public function down(): void
    {
        Schema::table('local_seo_campaigns', function (Blueprint $table) {
            $table->dropColumn(['ai_generated_content', 'content_generated_at', 'content_uniqueness_status', 'uniqueness_result']);
        });
    }
};
