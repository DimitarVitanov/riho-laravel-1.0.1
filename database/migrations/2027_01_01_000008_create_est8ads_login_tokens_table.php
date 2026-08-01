<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('est8ads_login_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token_hash', 64)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('expires_at')->index();
            $table->timestamp('used_at')->nullable()->index();
            $table->string('requested_ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('est8ads_login_tokens');
    }
};
