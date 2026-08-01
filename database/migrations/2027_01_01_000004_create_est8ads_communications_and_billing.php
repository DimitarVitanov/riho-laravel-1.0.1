<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('est8ads_conversations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->nullOnDelete();
            $table->foreignId('property_move_id')->nullable()->constrained('est8ads_property_moves')->nullOnDelete();
            $table->foreignId('match_result_id')->nullable()->constrained('est8ads_match_results')->nullOnDelete();
            $table->string('type')->default('direct')->index();
            $table->string('status')->default('open')->index();
            $table->string('subject')->nullable();
            $table->timestamp('last_message_at')->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['agency_id', 'status', 'last_message_at']);
        });

        Schema::create('est8ads_conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('est8ads_conversations')->cascadeOnDelete();
            $table->foreignId('profile_id')->nullable()->constrained('est8ads_profiles')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('role')->default('member')->index();
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('last_read_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->boolean('is_muted')->default(false);
            $table->timestamps();
            $table->index(['conversation_id', 'profile_id'], 'est8ads_conv_part_profile_idx');
            $table->index(['conversation_id', 'user_id'], 'est8ads_conv_part_user_idx');
        });

        Schema::create('est8ads_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('conversation_id')->constrained('est8ads_conversations')->cascadeOnDelete();
            $table->foreignId('sender_profile_id')->nullable()->constrained('est8ads_profiles')->nullOnDelete();
            $table->foreignId('sender_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reply_to_id')->nullable()->constrained('est8ads_messages')->nullOnDelete();
            $table->string('type')->default('text')->index();
            $table->text('body')->nullable();
            $table->json('attachments')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('sent_at')->index();
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['conversation_id', 'sent_at']);
        });

        Schema::create('est8ads_request_activations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('property_move_id')->constrained('est8ads_property_moves')->cascadeOnDelete();
            $table->foreignId('profile_id')->constrained('est8ads_profiles')->cascadeOnDelete();
            $table->string('status')->default('pending')->index();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['property_move_id', 'profile_id']);
        });

        Schema::create('est8ads_invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('agency_id')->nullable()->constrained('est8ads_agencies')->nullOnDelete();
            $table->foreignId('profile_id')->nullable()->constrained('est8ads_profiles')->nullOnDelete();
            $table->foreignId('request_activation_id')->nullable()->constrained('est8ads_request_activations')->nullOnDelete();
            $table->string('number')->unique();
            $table->string('status')->default('draft')->index();
            $table->string('currency', 3)->default('EUR');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('amount_due', 15, 2)->default(0);
            $table->json('line_items');
            $table->json('billing_details')->nullable();
            $table->date('issued_on')->nullable()->index();
            $table->date('due_on')->nullable()->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['profile_id', 'status', 'due_on']);
        });

        Schema::create('est8ads_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('invoice_id')->nullable()->constrained('est8ads_invoices')->nullOnDelete();
            $table->foreignId('profile_id')->nullable()->constrained('est8ads_profiles')->nullOnDelete();
            $table->string('provider')->nullable()->index();
            $table->string('provider_reference')->nullable();
            $table->string('idempotency_key')->nullable()->unique();
            $table->string('method')->nullable()->index();
            $table->string('status')->default('pending')->index();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('EUR');
            $table->decimal('refunded_amount', 15, 2)->default(0);
            $table->text('provider_payload')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('processed_at')->nullable()->index();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['provider', 'provider_reference']);
            $table->index(['invoice_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('est8ads_payments');
        Schema::dropIfExists('est8ads_invoices');
        Schema::dropIfExists('est8ads_request_activations');
        Schema::dropIfExists('est8ads_messages');
        Schema::dropIfExists('est8ads_conversation_participants');
        Schema::dropIfExists('est8ads_conversations');
    }
};
