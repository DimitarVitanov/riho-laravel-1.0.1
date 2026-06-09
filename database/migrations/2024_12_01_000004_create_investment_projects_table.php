<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investment_projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->string('project_code')->unique();
            $table->string('project_location')->nullable();
            $table->string('project_country')->nullable();
            $table->string('project_type')->nullable();
            $table->string('project_status')->default('draft');
            $table->decimal('minimum_investment_amount', 15, 2)->default(0);
            $table->decimal('target_raise_amount', 15, 2)->default(0);
            $table->decimal('max_raise_amount', 15, 2)->default(0);
            $table->decimal('preferred_return_percent', 5, 2)->default(0);
            $table->string('preferred_return_type')->default('cumulative');
            $table->decimal('rental_profit_share_percent', 5, 2)->default(0);
            $table->decimal('project_exit_profit_share_percent', 5, 2)->default(0);
            $table->integer('estimated_duration_months')->nullable();
            $table->text('summary')->nullable();
            $table->longText('full_description')->nullable();
            $table->text('risk_notes')->nullable();
            $table->string('structure_us_llc_name')->nullable();
            $table->string('structure_uk_llp_name')->nullable();
            $table->string('document_folder_path')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_projects');
    }
};
