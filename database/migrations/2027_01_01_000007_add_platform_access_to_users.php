<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('has_villabit_access')->default(true)->after('status')->index();
            $table->boolean('has_est8ads_access')->default(false)->after('has_villabit_access')->index();
        });

        DB::table('users')
            ->whereIn('role', ['super_admin', 'admin', 'real_estate_agency'])
            ->update(['has_est8ads_access' => true]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['has_villabit_access', 'has_est8ads_access']);
        });
    }
};
