<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('local_seo_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_profile_id')->constrained('agency_profiles')->onDelete('cascade');
            $table->string('name');
            $table->string('primary_city')->nullable();
            $table->string('country')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('coverage_area')->nullable();
            $table->string('coverage_unit', 10)->default('km');
            $table->json('target_places')->nullable();
            $table->text('positioning_note')->nullable();
            $table->string('page_slug')->nullable();
            $table->enum('status', ['draft', 'published', 'unpublished'])->default('draft');
            $table->foreignId('generated_page_id')->nullable()->constrained('generated_pages')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['agency_profile_id', 'status']);
        });

        Schema::table('agency_listings', function (Blueprint $table) {
            $table->foreignId('local_seo_campaign_id')->nullable()->after('agency_profile_id')
                ->constrained('local_seo_campaigns')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('agency_listings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('local_seo_campaign_id');
        });

        Schema::dropIfExists('local_seo_campaigns');
    }
};
