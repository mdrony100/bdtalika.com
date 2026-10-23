<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Ensure report_business stores when a report was submitted,
// so the admin Report Directories page can show the date.

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('report_business')) {
            return;
        }

        if (!Schema::hasColumn('report_business', 'created_at')) {
            Schema::table('report_business', function (Blueprint $table) {
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('report_business') && Schema::hasColumn('report_business', 'created_at')) {
            Schema::table('report_business', function (Blueprint $table) {
                $table->dropColumn('created_at');
            });
        }
    }
};
