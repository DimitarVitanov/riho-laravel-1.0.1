<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('est8ads_contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->nullOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('new')->index();
            $table->string('source')->default('website')->index();
            $table->string('name');
            $table->string('email')->index();
            $table->text('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->text('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('consent_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['agency_id', 'status', 'created_at']);
        });

        Schema::create('est8ads_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event')->index();
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('request_id')->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['agency_id', 'created_at']);
        });

        Schema::create('est8ads_analytics_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->nullOnDelete();
            $table->foreignId('profile_id')->nullable()->constrained('est8ads_profiles')->nullOnDelete();
            $table->string('event')->index();
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('session_id')->nullable()->index();
            $table->json('properties')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
            $table->index(['subject_type', 'subject_id']);
            $table->index(['agency_id', 'event', 'occurred_at']);
        });

        Schema::create('est8ads_exports', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->nullOnDelete();
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type')->index();
            $table->string('format')->default('csv');
            $table->string('status')->default('queued')->index();
            $table->json('filters')->nullable();
            $table->string('disk')->nullable();
            $table->text('path')->nullable();
            $table->unsignedBigInteger('row_count')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
            $table->index(['agency_id', 'status', 'created_at']);
        });

        Schema::create('est8ads_internet_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->nullOnDelete();
            $table->string('name');
            $table->string('type')->index();
            $table->text('base_url');
            $table->string('status')->default('active')->index();
            $table->json('configuration')->nullable();
            $table->json('crawl_rules')->nullable();
            $table->timestamp('last_crawled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['agency_id', 'name']);
        });

        Schema::create('est8ads_discovery_jobs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('internet_source_id')->constrained('est8ads_internet_sources')->cascadeOnDelete();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->nullOnDelete();
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('queued')->index();
            $table->json('parameters')->nullable();
            $table->unsignedInteger('pages_processed')->default(0);
            $table->unsignedInteger('listings_found')->default(0);
            $table->unsignedInteger('errors_count')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
            $table->index(['internet_source_id', 'status', 'created_at'], 'est8ads_discovery_source_status_created_idx');
        });

        Schema::create('est8ads_external_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internet_source_id')->constrained('est8ads_internet_sources')->cascadeOnDelete();
            $table->foreignId('discovery_job_id')->nullable()->constrained('est8ads_discovery_jobs')->nullOnDelete();
            $table->string('external_id');
            $table->text('canonical_url');
            $table->string('status')->default('active')->index();
            $table->string('title')->nullable();
            $table->string('property_type')->nullable()->index();
            $table->text('address')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('country_code', 2)->nullable();
            $table->decimal('price', 15, 2)->nullable()->index();
            $table->string('currency', 3)->nullable();
            $table->json('media')->nullable();
            $table->json('attributes')->nullable();
            $table->string('content_hash', 64)->nullable()->index();
            $table->timestamp('first_seen_at')->useCurrent()->index();
            $table->timestamp('last_seen_at')->useCurrent()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['internet_source_id', 'external_id']);
        });

        Schema::create('est8ads_external_listing_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('external_listing_id')->constrained('est8ads_external_listings')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained('est8ads_properties')->cascadeOnDelete();
            $table->string('status')->default('candidate')->index();
            $table->decimal('confidence_score', 5, 4)->index();
            $table->json('reasons')->nullable();
            $table->boolean('is_manual')->default(false);
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->unique(['external_listing_id', 'property_id'], 'est8ads_external_listing_property_unique');
        });

        Schema::create('est8ads_external_listing_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('external_listing_id')->constrained('est8ads_external_listings')->cascadeOnDelete();
            $table->string('content_hash', 64)->index();
            $table->json('payload');
            $table->timestamp('captured_at')->index();
            $table->timestamps();
            $table->unique(['external_listing_id', 'content_hash'], 'est8ads_listing_snapshot_hash_unique');
        });

        Schema::create('est8ads_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->cascadeOnDelete();
            $table->string('scope')->default('global')->index();
            $table->unsignedBigInteger('scope_id')->default(0);
            $table->string('group')->default('general')->index();
            $table->string('key');
            $table->string('type')->default('string');
            $table->json('value')->nullable();
            $table->text('encrypted_value')->nullable();
            $table->boolean('is_public')->default(false)->index();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['scope', 'scope_id', 'group', 'key'], 'est8ads_setting_scope_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('est8ads_settings');
        Schema::dropIfExists('est8ads_external_listing_snapshots');
        Schema::dropIfExists('est8ads_external_listing_matches');
        Schema::dropIfExists('est8ads_external_listings');
        Schema::dropIfExists('est8ads_discovery_jobs');
        Schema::dropIfExists('est8ads_internet_sources');
        Schema::dropIfExists('est8ads_exports');
        Schema::dropIfExists('est8ads_analytics_events');
        Schema::dropIfExists('est8ads_audit_logs');
        Schema::dropIfExists('est8ads_contact_inquiries');
    }
};
