<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_user_id')->constrained('users')->onDelete('cascade');
            $table->string('referral_code');
            $table->string('clicked_ip_hash')->nullable();
            $table->string('clicked_user_agent_hash')->nullable();
            $table->string('landing_url')->nullable();
            $table->string('referrer_url')->nullable();
            $table->timestamp('cookie_expires_at')->nullable();
            $table->foreignId('converted_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('converted_account_type')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->string('status')->default('clicked');
            $table->timestamps();

            $table->index('referral_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_referrals');
    }
};
