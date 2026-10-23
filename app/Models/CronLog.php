<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronLog extends Model
{
    protected $fillable = [
        'job',
        'status',
        'message',
        'processed',
        'duration',
        'stats',
        'run_date',
        'first_run_at',
        'last_run_at',
        'run_count',
        'total_processed',
        'total_sent',
        'total_skipped',
        'total_failed',
        'last_status',
    ];

    protected $casts = [
        'stats' => 'array',
        'run_date' => 'date',
        'first_run_at' => 'datetime',
        'last_run_at' => 'datetime',
    ];
}
