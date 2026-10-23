<?php

namespace App\Services;

use App\Models\CronLog;
use Illuminate\Support\Facades\Log;

class CronLogService
{
    /**
     * Record a single cron job execution.
     *
     * @param  string      $job       Cron job identifier (e.g. fetch-google-reviews)
     * @param  string      $status    success | failed
     * @param  string|null $message   Human readable summary
     * @param  int         $processed Number of records processed
     * @param  float|null  $duration  Execution time in seconds
     * @param  array       $stats     Extra details to store as JSON
     */
    public function record(
        string $job,
        string $status = 'success',
        ?string $message = null,
        int $processed = 0,
        ?float $duration = null,
        array $stats = []
    ): void {
        try {
            CronLog::create([
                'job' => $job,
                'status' => $status,
                'message' => $message,
                'processed' => $processed,
                'duration' => $duration !== null ? round($duration, 2) : null,
                'stats' => !empty($stats) ? $stats : null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Cron log write failed', [
                'job' => $job,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
