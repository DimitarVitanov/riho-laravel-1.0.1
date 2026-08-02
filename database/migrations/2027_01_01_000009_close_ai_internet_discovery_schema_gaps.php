<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('est8ads_internet_sources', function (Blueprint $table) {
            $table->string('domain')->nullable()->after('base_url');
            $table->string('access_method')->nullable()->after('type');
            $table->string('credentials_reference')->nullable()->after('configuration');
            $table->boolean('enabled')->default(false)->index()->after('status');
            $table->json('country_codes')->nullable()->after('credentials_reference');
            $table->unsignedInteger('requests_per_minute')->default(10)->after('country_codes');
            $table->string('robots_status')->default('unknown')->index()->after('requests_per_minute');
            $table->string('terms_status')->default('unreviewed')->index()->after('robots_status');
            $table->timestamp('last_success_at')->nullable()->after('last_crawled_at');
            $table->timestamp('last_error_at')->nullable()->after('last_success_at');
        });

        Schema::table('est8ads_discovery_jobs', function (Blueprint $table) {
            $table->foreignId('property_move_id')->nullable()->after('agency_id')->constrained('est8ads_property_moves')->nullOnDelete();
            $table->string('trigger')->default('manual')->index()->after('requested_by_user_id');
            $table->decimal('save_threshold', 5, 2)->default(55)->after('parameters');
            $table->decimal('auto_connect_threshold', 5, 2)->default(88)->after('save_threshold');
            $table->unsignedInteger('provider_count')->default(1)->after('pages_processed');
            $table->unsignedInteger('imported_count')->default(0)->after('listings_found');
            $table->unsignedInteger('connected_count')->default(0)->after('imported_count');
            $table->index(['property_move_id', 'status', 'created_at'], 'est8ads_discovery_move_status_idx');
        });

        Schema::table('est8ads_external_listings', function (Blueprint $table) {
            $table->string('property_fingerprint', 64)->nullable()->index()->after('content_hash');
            $table->text('description_excerpt')->nullable()->after('title');
            $table->string('area')->nullable()->after('city');
            $table->decimal('latitude', 10, 7)->nullable()->after('country_code');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->decimal('size_m2', 12, 2)->nullable()->after('currency');
            $table->decimal('land_m2', 12, 2)->nullable()->after('size_m2');
            $table->unsignedSmallInteger('bedrooms')->nullable()->after('land_m2');
            $table->unsignedSmallInteger('bathrooms')->nullable()->after('bedrooms');
            $table->string('condition')->nullable()->after('bathrooms');
            $table->json('raw_payload')->nullable()->after('attributes');
            $table->timestamp('source_published_at')->nullable()->after('raw_payload');
            $table->timestamp('source_updated_at')->nullable()->after('source_published_at');
            $table->index(['property_fingerprint', 'price'], 'est8ads_external_fingerprint_price_idx');
        });

        Schema::table('est8ads_external_listing_matches', function (Blueprint $table) {
            $table->foreignId('property_move_id')->nullable()->after('property_id')->constrained('est8ads_property_moves')->nullOnDelete();
            $table->foreignId('discovery_job_id')->nullable()->after('property_move_id')->constrained('est8ads_discovery_jobs')->nullOnDelete();
            $table->decimal('deterministic_score', 5, 2)->default(0)->after('confidence_score');
            $table->decimal('semantic_score', 5, 2)->nullable()->after('deterministic_score');
            $table->decimal('final_score', 5, 2)->default(0)->index()->after('semantic_score');
            $table->decimal('data_confidence', 5, 2)->default(0)->after('final_score');
            $table->json('hard_conflicts')->nullable()->after('reasons');
            $table->text('explanation')->nullable()->after('hard_conflicts');
            $table->foreignId('connected_property_id')->nullable()->after('is_manual')->constrained('est8ads_properties')->nullOnDelete();
            $table->index(['property_move_id', 'status', 'final_score'], 'est8ads_external_match_review_idx');
        });

        Schema::table('est8ads_external_listing_snapshots', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->nullable()->after('content_hash');
            $table->string('availability_status')->nullable()->after('price');
            $table->text('raw_content_storage_path')->nullable()->after('payload');
        });
    }

    public function down(): void
    {
        Schema::table('est8ads_external_listing_snapshots', function (Blueprint $table) {
            $table->dropColumn(['price', 'availability_status', 'raw_content_storage_path']);
        });
        Schema::table('est8ads_external_listing_matches', function (Blueprint $table) {
            $table->dropIndex('est8ads_external_match_review_idx');
            $table->dropConstrainedForeignId('connected_property_id');
            $table->dropConstrainedForeignId('discovery_job_id');
            $table->dropConstrainedForeignId('property_move_id');
            $table->dropColumn(['deterministic_score', 'semantic_score', 'final_score', 'data_confidence', 'hard_conflicts', 'explanation']);
        });
        Schema::table('est8ads_external_listings', function (Blueprint $table) {
            $table->dropIndex('est8ads_external_fingerprint_price_idx');
            $table->dropColumn(['property_fingerprint', 'description_excerpt', 'area', 'latitude', 'longitude', 'size_m2', 'land_m2', 'bedrooms', 'bathrooms', 'condition', 'raw_payload', 'source_published_at', 'source_updated_at']);
        });
        Schema::table('est8ads_discovery_jobs', function (Blueprint $table) {
            $table->dropIndex('est8ads_discovery_move_status_idx');
            $table->dropConstrainedForeignId('property_move_id');
            $table->dropColumn(['trigger', 'save_threshold', 'auto_connect_threshold', 'provider_count', 'imported_count', 'connected_count']);
        });
        Schema::table('est8ads_internet_sources', function (Blueprint $table) {
            $table->dropColumn(['domain', 'access_method', 'credentials_reference', 'enabled', 'country_codes', 'requests_per_minute', 'robots_status', 'terms_status', 'last_success_at', 'last_error_at']);
        });
    }
};
