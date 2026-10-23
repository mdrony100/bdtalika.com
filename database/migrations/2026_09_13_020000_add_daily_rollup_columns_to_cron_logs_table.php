<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cron_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('cron_logs', 'run_date')) {
                $table->date('run_date')->nullable()->after('job');
            }

            if (!Schema::hasColumn('cron_logs', 'first_run_at')) {
                $table->timestamp('first_run_at')->nullable()->after('stats');
            }

            if (!Schema::hasColumn('cron_logs', 'last_run_at')) {
                $table->timestamp('last_run_at')->nullable()->after('first_run_at');
            }

            if (!Schema::hasColumn('cron_logs', 'run_count')) {
                $table->unsignedInteger('run_count')->default(1)->after('last_run_at');
            }

            if (!Schema::hasColumn('cron_logs', 'total_processed')) {
                $table->unsignedInteger('total_processed')->default(0)->after('run_count');
            }

            if (!Schema::hasColumn('cron_logs', 'total_sent')) {
                $table->unsignedInteger('total_sent')->default(0)->after('total_processed');
            }

            if (!Schema::hasColumn('cron_logs', 'total_skipped')) {
                $table->unsignedInteger('total_skipped')->default(0)->after('total_sent');
            }

            if (!Schema::hasColumn('cron_logs', 'total_failed')) {
                $table->unsignedInteger('total_failed')->default(0)->after('total_skipped');
            }

            if (!Schema::hasColumn('cron_logs', 'last_status')) {
                $table->string('last_status', 20)->nullable()->after('total_failed');
            }
        });

        Schema::table('cron_logs', function (Blueprint $table) {
            $table->unique(['job', 'run_date'], 'cron_logs_job_run_date_unique');
        });
    }

    public function down(): void
    {
        Schema::table('cron_logs', function (Blueprint $table) {
            $table->dropUnique('cron_logs_job_run_date_unique');
        });

        Schema::table('cron_logs', function (Blueprint $table) {
            foreach ([
                'last_status',
                'total_failed',
                'total_skipped',
                'total_sent',
                'total_processed',
                'run_count',
                'last_run_at',
                'first_run_at',
                'run_date',
            ] as $column) {
                if (Schema::hasColumn('cron_logs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
