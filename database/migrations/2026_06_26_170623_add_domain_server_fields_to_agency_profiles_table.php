<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->string('server_name')->nullable()->after('custom_domain');
            $table->string('server_ip')->nullable()->after('server_name');
            $table->string('nameserver_1')->nullable()->after('server_ip');
            $table->string('nameserver_2')->nullable()->after('nameserver_1');
            $table->timestamp('dns_verified_at')->nullable()->after('nameserver_2');
            $table->timestamp('last_dns_check_at')->nullable()->after('dns_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->dropColumn(['server_name', 'server_ip', 'nameserver_1', 'nameserver_2', 'dns_verified_at', 'last_dns_check_at']);
        });
    }
};
