<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('est8ads_properties', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->nullOnDelete();
            $table->foreignId('property_move_id')->nullable()->constrained('est8ads_property_moves')->nullOnDelete();
            $table->string('reference')->unique();
            $table->string('status')->default('draft')->index();
            $table->string('listing_type')->index();
            $table->string('property_type')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('region')->nullable()->index();
            $table->string('postal_code')->nullable();
            $table->string('country_code', 2)->nullable()->index();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('asking_price', 15, 2)->nullable()->index();
            $table->string('currency', 3)->default('EUR');
            $table->decimal('floor_area', 12, 2)->nullable();
            $table->decimal('land_area', 12, 2)->nullable();
            $table->unsignedSmallInteger('bedrooms')->nullable();
            $table->unsignedSmallInteger('bathrooms')->nullable();
            $table->unsignedSmallInteger('year_built')->nullable();
            $table->string('energy_rating')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['agency_id', 'status', 'created_at']);
            $table->index(['country_code', 'city', 'property_type']);
        });

        Schema::create('est8ads_property_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('est8ads_properties')->cascadeOnDelete();
            $table->string('type')->default('image')->index();
            $table->string('disk')->default('public');
            $table->text('path');
            $table->text('url')->nullable();
            $table->string('title')->nullable();
            $table->text('alt_text')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false)->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['property_id', 'sort_order']);
        });

        Schema::create('est8ads_features', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('group')->nullable()->index();
            $table->string('value_type')->default('boolean');
            $table->json('options')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('est8ads_property_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('est8ads_properties')->cascadeOnDelete();
            $table->foreignId('feature_id')->constrained('est8ads_features')->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->timestamps();
            $table->unique(['property_id', 'feature_id']);
        });

        Schema::create('est8ads_property_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('est8ads_properties')->cascadeOnDelete();
            $table->string('category')->index();
            $table->string('rating')->nullable()->index();
            $table->text('notes')->nullable();
            $table->decimal('estimated_cost', 15, 2)->nullable();
            $table->string('currency', 3)->default('EUR');
            $table->date('inspected_on')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
            $table->unique(['property_id', 'category']);
        });

        Schema::create('est8ads_property_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('est8ads_properties')->cascadeOnDelete();
            $table->foreignId('profile_id')->nullable()->constrained('est8ads_profiles')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('role')->index();
            $table->string('status')->default('active')->index();
            $table->boolean('is_primary')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['property_id', 'role', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('est8ads_property_participants');
        Schema::dropIfExists('est8ads_property_conditions');
        Schema::dropIfExists('est8ads_property_features');
        Schema::dropIfExists('est8ads_features');
        Schema::dropIfExists('est8ads_property_media');
        Schema::dropIfExists('est8ads_properties');
    }
};
