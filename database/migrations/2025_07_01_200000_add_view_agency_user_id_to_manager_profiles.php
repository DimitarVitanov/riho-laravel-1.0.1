<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manager_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('view_agency_user_id')->nullable()->after('can_view_agency_readonly');
            $table->foreign('view_agency_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('manager_profiles', function (Blueprint $table) {
            $table->dropForeign(['view_agency_user_id']);
            $table->dropColumn('view_agency_user_id');
        });
    }
};
