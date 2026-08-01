<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('est8ads_agencies', function (Blueprint $table) {
            $table->foreignId('agency_profile_id')
                ->nullable()
                ->after('id')
                ->unique()
                ->constrained('agency_profiles')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('est8ads_agencies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('agency_profile_id');
        });
    }
};
