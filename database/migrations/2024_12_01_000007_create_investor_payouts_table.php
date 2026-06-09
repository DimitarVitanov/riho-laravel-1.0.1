<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investor_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('investor_investment_id')->constrained('investor_investments')->onDelete('cascade');
            $table->foreignId('investment_project_id')->constrained('investment_projects')->onDelete('cascade');
            $table->string('payout_type');
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('USD');
            $table->string('payout_method')->nullable();
            $table->string('payout_status')->default('pending');
            $table->date('scheduled_for')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->text('admin_note')->nullable();
            $table->foreignId('approved_by_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investor_payouts');
    }
};
