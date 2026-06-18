<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usage_limits', function (Blueprint $table) {
            $table->integer('competitor_scans_limit')->default(10)->change();
            $table->integer('small_ai_content_actions_limit')->default(10)->change();
        });
    }

    public function down(): void
    {
        Schema::table('usage_limits', function (Blueprint $table) {
            $table->integer('competitor_scans_limit')->default(30)->change();
            $table->integer('small_ai_content_actions_limit')->default(30)->change();
        });
    }
};
