<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villa_ready_property_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_ready_property_id')->constrained()->onDelete('cascade');
            $table->foreignId('agency_profile_id')->constrained()->onDelete('cascade');
            
            // Visitor tracking
            $table->string('cookie_id')->unique();
            $table->string('visitor_email')->nullable();
            $table->string('visitor_name')->nullable();
            $table->string('visitor_ip')->nullable();
            $table->string('visitor_user_agent')->nullable();
            
            // Timestamps
            $table->timestamp('first_visit_at')->useCurrent();
            $table->timestamp('last_visit_at')->nullable();
            $table->timestamp('cookie_expires_at')->nullable();
            
            // Status: visited, viewed, paid
            $table->string('status')->default('visited');
            
            // Sale info (filled by admin when sale confirmed)
            $table->decimal('sale_amount', 12, 2)->nullable();
            $table->decimal('commission_percent', 5, 2)->default(6.00);
            $table->decimal('commission_amount', 12, 2)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['agency_profile_id', 'status'], 'vr_referrals_agency_status_idx');
            $table->index(['villa_ready_property_id', 'status'], 'vr_referrals_property_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villa_ready_property_referrals');
    }
};
