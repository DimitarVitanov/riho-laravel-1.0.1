<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_impersonation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('target_user_id')->constrained('users')->onDelete('cascade');
            $table->string('target_role')->nullable();
            $table->text('reason')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_impersonation_logs');
    }
};
