<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manager_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_code')->nullable();
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            $table->string('permission_level')->default('standard');
            $table->boolean('can_manage_agencies')->default(true);
            $table->boolean('can_manage_investors')->default(false);
            $table->boolean('can_review_ai_outputs')->default(true);
            $table->boolean('can_prepare_payouts')->default(false);
            $table->boolean('can_view_financials')->default(false);
            $table->boolean('can_login_as_user')->default(false);
            $table->text('daily_task_notes')->nullable();
            $table->date('active_from')->nullable();
            $table->date('active_until')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manager_profiles');
    }
};
