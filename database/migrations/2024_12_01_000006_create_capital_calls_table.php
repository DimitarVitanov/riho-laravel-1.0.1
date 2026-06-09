<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capital_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_project_id')->constrained('investment_projects')->onDelete('cascade');
            $table->foreignId('investor_investment_id')->constrained('investor_investments')->onDelete('cascade');
            $table->foreignId('investor_user_id')->constrained('users')->onDelete('cascade');
            $table->integer('call_number')->default(1);
            $table->decimal('requested_amount', 15, 2);
            $table->date('due_date');
            $table->text('reason')->nullable();
            $table->string('construction_phase')->nullable();
            $table->string('status')->default('draft');
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capital_calls');
    }
};
