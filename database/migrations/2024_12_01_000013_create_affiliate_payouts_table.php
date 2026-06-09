<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_user_id')->constrained('users')->onDelete('cascade');
            $table->string('payout_batch_month');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->string('payout_method')->nullable();
            $table->string('payout_status')->default('requested');
            $table->boolean('minimum_payout_reached')->default(false);
            $table->date('scheduled_for')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_payouts');
    }
};
