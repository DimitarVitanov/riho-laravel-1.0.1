<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investor_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('investor_type', ['us_accredited', 'non_us_eligible', 'institutional', 'professional', 'other'])->nullable();
            $table->string('citizenship_country')->nullable();
            $table->string('residence_country')->nullable();
            $table->enum('eligible_structure', ['usa_llc', 'uk_llp', 'pending_review'])->nullable();
            $table->enum('accreditation_status', ['not_started', 'pending', 'verified', 'rejected', 'expired'])->default('not_started');
            $table->enum('kyc_status', ['not_started', 'pending', 'approved', 'rejected'])->default('not_started');
            $table->enum('aml_status', ['not_started', 'pending', 'approved', 'rejected'])->default('not_started');
            $table->decimal('max_commitment_amount', 15, 2)->nullable();
            $table->string('preferred_currency', 10)->default('USD');
            $table->enum('payout_method', ['bank_wire', 'stripe', 'paypal', 'crypto', 'other'])->nullable();
            $table->text('payout_details_encrypted')->nullable();
            $table->string('tax_form_status')->nullable();
            $table->text('investor_notes')->nullable();
            $table->timestamp('risk_acknowledgement_at')->nullable();
            $table->timestamp('terms_accepted_at')->nullable();
            $table->unsignedBigInteger('assigned_manager_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_manager_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investor_profiles');
    }
};
