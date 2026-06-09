<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained('agency_profiles')->onDelete('cascade');
            $table->string('source')->default('invisible_lead_magnet');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('investor_type')->nullable();
            $table->decimal('interest_amount', 15, 2)->nullable();
            $table->text('message')->nullable();
            $table->string('landing_page_url')->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'lost'])->default('new');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('assigned_to_user_id')->nullable();
            $table->timestamps();

            $table->index(['agency_profile_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
