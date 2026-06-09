<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investor_investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('investor_profile_id')->constrained('investor_profiles')->onDelete('cascade');
            $table->foreignId('investment_project_id')->constrained('investment_projects')->onDelete('cascade');
            $table->string('structure_used')->nullable();
            $table->decimal('committed_amount', 15, 2)->default(0);
            $table->decimal('funded_amount', 15, 2)->default(0);
            $table->decimal('unfunded_commitment_amount', 15, 2)->default(0);
            $table->decimal('preferred_return_percent', 5, 2)->default(0);
            $table->decimal('preferred_return_accrued_amount', 15, 2)->default(0);
            $table->decimal('rental_profit_accrued_amount', 15, 2)->default(0);
            $table->decimal('project_exit_profit_accrued_amount', 15, 2)->default(0);
            $table->decimal('total_earnings_accrued', 15, 2)->default(0);
            $table->decimal('total_payouts_paid', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->string('investment_status')->default('pending');
            $table->string('signed_documents_status')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('exited_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investor_investments');
    }
};
