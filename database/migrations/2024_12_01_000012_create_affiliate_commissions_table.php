<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referred_user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('source_payment_id')->nullable();
            $table->string('source_payment_type')->nullable();
            $table->decimal('gross_payment_amount', 15, 2)->default(0);
            $table->decimal('commission_percent', 5, 2)->default(10.00);
            $table->decimal('commission_amount', 15, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->string('commission_status')->default('pending');
            $table->timestamp('earned_at')->nullable();
            $table->timestamp('payable_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->unsignedBigInteger('payout_batch_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_commissions');
    }
};
