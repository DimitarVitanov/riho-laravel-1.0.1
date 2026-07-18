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
        Schema::table('users', function (Blueprint $table) {
            // Onboarding step for agencies:
            // 1 = payment_required (waiting for payment)
            // 2 = payment_confirmed (admin confirmed payment, AI server setup begins)
            // 3 = ai_server_setup (server being configured)
            // 4 = domain_connection (agency needs to enter domain)
            // 5 = nameserver_pending (waiting for DNS propagation)
            // 6 = completed (full access)
            $table->unsignedTinyInteger('onboarding_step')->default(1)->after('status');
            $table->timestamp('onboarding_step_updated_at')->nullable()->after('onboarding_step');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['onboarding_step', 'onboarding_step_updated_at']);
        });
    }
};
