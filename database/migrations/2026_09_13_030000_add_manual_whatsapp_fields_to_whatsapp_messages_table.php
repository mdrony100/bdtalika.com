<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('whatsapp_messages', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->index()->after('user_id');
            }

            if (!Schema::hasColumn('whatsapp_messages', 'idempotency_key')) {
                $table->string('idempotency_key', 191)->nullable()->unique()->after('listing_id');
            }
        });

        try {
            DB::statement('ALTER TABLE `whatsapp_messages` DROP INDEX `wa_msg_listing_purpose_source_unique`');
        } catch (\Throwable $e) {
            // Index may not exist on fresh databases using the updated create migration.
        }
    }

    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE `whatsapp_messages` DROP INDEX `wa_msg_listing_purpose_source_status_index`');
        } catch (\Throwable $e) {
            // Index may not exist if rollback is run after a partial manual SQL change.
        }

        Schema::table('whatsapp_messages', function (Blueprint $table) {
            if (Schema::hasColumn('whatsapp_messages', 'idempotency_key')) {
                $table->dropUnique(['idempotency_key']);
                $table->dropColumn('idempotency_key');
            }

            if (Schema::hasColumn('whatsapp_messages', 'admin_id')) {
                $table->dropColumn('admin_id');
            }
        });
    }
};
