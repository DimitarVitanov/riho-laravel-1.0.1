<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->string('sftp_username')->nullable()->after('server_ip');
            $table->string('sftp_password')->nullable()->after('sftp_username');
            $table->string('sftp_path')->nullable()->after('sftp_password');
            $table->integer('sftp_port')->default(22)->after('sftp_path');
        });
    }

    public function down(): void
    {
        Schema::table('agency_profiles', function (Blueprint $table) {
            $table->dropColumn(['sftp_username', 'sftp_password', 'sftp_path', 'sftp_port']);
        });
    }
};
