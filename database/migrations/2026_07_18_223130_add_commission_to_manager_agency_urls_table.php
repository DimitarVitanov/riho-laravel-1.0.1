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
        Schema::table('manager_agency_urls', function (Blueprint $table) {
            $table->decimal('commission_percent', 5, 2)->default(10.00)->after('status');
            $table->decimal('commission_amount', 10, 2)->nullable()->after('commission_percent');
            $table->string('commission_status')->default('pending')->after('commission_amount'); // pending, paid
            $table->timestamp('commission_paid_at')->nullable()->after('commission_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manager_agency_urls', function (Blueprint $table) {
            $table->dropColumn(['commission_percent', 'commission_amount', 'commission_status', 'commission_paid_at']);
        });
    }
};
