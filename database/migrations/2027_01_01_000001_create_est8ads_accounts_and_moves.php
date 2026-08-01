<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('est8ads_agencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('status')->default('active')->index();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('registration_number')->nullable()->unique();
            $table->string('country_code', 2)->nullable()->index();
            $table->string('timezone')->default('UTC');
            $table->string('default_currency', 3)->default('EUR');
            $table->json('branding')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('est8ads_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->nullOnDelete();
            $table->string('type')->default('individual')->index();
            $table->string('status')->default('active')->index();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('email')->nullable()->index();
            $table->text('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('postal_code')->nullable();
            $table->string('country_code', 2)->nullable()->index();
            $table->string('preferred_language', 10)->default('en');
            $table->string('timezone')->default('UTC');
            $table->string('public_reference', 40)->unique();
            $table->string('intake_token_hash', 64)->nullable()->unique();
            $table->timestamp('consent_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->json('preferences')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['agency_id', 'status']);
        });

        Schema::create('est8ads_agency_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('est8ads_agencies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->default('member')->index();
            $table->string('status')->default('active')->index();
            $table->json('permissions')->nullable();
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();
            $table->unique(['agency_id', 'user_id']);
        });

        Schema::create('est8ads_property_moves', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('profile_id')->constrained('est8ads_profiles')->cascadeOnDelete();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->nullOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('move_type')->index();
            $table->string('status')->default('draft')->index();
            $table->string('title')->nullable();
            $table->date('target_date')->nullable()->index();
            $table->string('current_location')->nullable();
            $table->string('target_location')->nullable()->index();
            $table->decimal('budget_min', 15, 2)->nullable();
            $table->decimal('budget_max', 15, 2)->nullable();
            $table->string('currency', 3)->default('EUR');
            $table->text('notes')->nullable();
            $table->json('requirements')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['agency_id', 'status', 'created_at']);
            $table->index(['profile_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('est8ads_property_moves');
        Schema::dropIfExists('est8ads_agency_memberships');
        Schema::dropIfExists('est8ads_profiles');
        Schema::dropIfExists('est8ads_agencies');
    }
};
