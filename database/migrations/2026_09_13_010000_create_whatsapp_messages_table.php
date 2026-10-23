<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index();
            $table->foreignId('admin_id')->nullable()->index();
            $table->foreignId('listing_id')->nullable()->index();
            $table->string('idempotency_key', 191)->nullable()->unique();
            $table->string('recipient_phone', 30)->nullable();
            $table->string('template_name', 100)->index();
            $table->string('purpose', 100)->index();
            $table->string('source', 30)->default('automatic')->index();
            $table->string('status', 30)->default('pending')->index();
            $table->string('meta_message_id', 255)->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamps();

            $table->index(['listing_id', 'purpose', 'source', 'status'], 'wa_msg_listing_purpose_source_status_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
