<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'whatsapp_opt_in')) {
                $table->boolean('whatsapp_opt_in')->default(false)->after('phone');
            }

            if (!Schema::hasColumn('users', 'whatsapp_opt_in_at')) {
                $table->timestamp('whatsapp_opt_in_at')->nullable()->after('whatsapp_opt_in');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'whatsapp_opt_in_at')) {
                $table->dropColumn('whatsapp_opt_in_at');
            }

            if (Schema::hasColumn('users', 'whatsapp_opt_in')) {
                $table->dropColumn('whatsapp_opt_in');
            }
        });
    }
};
