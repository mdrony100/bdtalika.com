<?php

namespace App\Services;

use App\Models\BkashPayment;
use App\Models\CronLog;
use App\Models\Listing;
use App\Models\Transaction;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WhatsAppAbandonedPaymentReminderService
{
    public const JOB = 'whatsapp_abandoned_payment_reminder';
    public const PURPOSE = 'abandoned_payment_reminder';
    public const SOURCE = 'automatic';

    public function __construct(private MetaWhatsAppService $whatsApp)
    {
    }

    public function run(int $limit = 25): array
    {
        $startedAt = microtime(true);
        $stats = [
            'success' => true,
            'processed' => 0,
            'sent' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];

        if (!config('services.meta_whatsapp.enabled')) {
            $this->recordDailyCron($stats, microtime(true) - $startedAt);

            return $stats;
        }

        $listings = $this->eligibleQuery()
            ->orderBy('created_at')
            ->limit($limit)
            ->get();

        foreach ($listings as $listing) {
            $stats['processed']++;

            $result = $this->processListing($listing);

            if ($result === 'sent') {
                $stats['sent']++;
            } elseif ($result === 'failed') {
                $stats['failed']++;
            } else {
                $stats['skipped']++;
            }
        }

        $stats['success'] = $stats['failed'] === 0;
        $this->recordDailyCron($stats, microtime(true) - $startedAt);

        return $stats;
    }

    public function eligibleQuery()
    {
        $now = now();
        $startAt = $this->startAt();
        $oldestAllowed = $now->copy()->subMinutes($this->minimumAgeMinutes());

        return Listing::query()
            ->with(['user', 'plan'])
            ->where('created_at', '>=', $startAt)
            ->where('created_at', '<=', $oldestAllowed)
            ->where('status', 0)
            ->whereDoesntHave('plan', function ($query) {
                $query->where('plan_type', 'free');
            })
            ->whereDoesntHave('latestCompletedBkashPayment')
            ->whereDoesntHave('latestTransaction')
            ->whereDoesntHave('whatsAppMessages', function ($query) {
                $query->where('purpose', self::PURPOSE)
                    ->where('status', 'sent');
            })
            ->whereHas('user', function ($query) {
                $query->where('whatsapp_opt_in', 1)
                    ->whereNotNull('phone')
                    ->where('phone', '<>', '');
            });
    }

    public function processListing(Listing $listing): string
    {
        $listing->loadMissing(['user', 'plan']);

        if (!$this->isStillEligible($listing)) {
            return 'skipped';
        }

        return DB::transaction(function () use ($listing) {
            $alreadySent = WhatsAppMessage::where('listing_id', $listing->id)
                ->where('purpose', self::PURPOSE)
                ->where('status', 'sent')
                ->lockForUpdate()
                ->exists();

            if ($alreadySent) {
                return 'skipped';
            }

            $phone = BangladeshPhoneService::normalize(optional($listing->user)->phone)
                ?: BangladeshPhoneService::normalize($listing->whatsapp_number);

            if (!$phone) {
                return 'skipped';
            }

            $message = WhatsAppMessage::where('listing_id', $listing->id)
                ->where('purpose', self::PURPOSE)
                ->where('source', self::SOURCE)
                ->where('idempotency_key', $this->automaticIdempotencyKey($listing->id))
                ->lockForUpdate()
                ->first();

            if ($message && $message->status === 'sent') {
                return 'skipped';
            }

            if (!$message) {
                $message = new WhatsAppMessage([
                    'listing_id' => $listing->id,
                    'purpose' => self::PURPOSE,
                    'source' => self::SOURCE,
                    'idempotency_key' => $this->automaticIdempotencyKey($listing->id),
                ]);
            }

            $message->fill([
                'user_id' => $listing->user_id,
                'recipient_phone' => $phone,
                'template_name' => config('services.meta_whatsapp.template_name', 'payment_pending_reminder'),
                'status' => 'pending',
                'meta_message_id' => null,
                'error_message' => null,
                'sent_at' => null,
            ])->save();

            $result = $this->whatsApp->sendPaymentPendingReminder($listing, $phone);

            if ($result['success'] ?? false) {
                $message->update([
                    'status' => 'sent',
                    'meta_message_id' => $result['meta_message_id'] ?? null,
                    'sent_at' => now(),
                ]);

                return 'sent';
            }

            $message->update([
                'status' => 'failed',
                'error_message' => $result['error_message'] ?? 'Meta WhatsApp request failed.',
            ]);

            return 'failed';
        });
    }

    public function isStillEligible(Listing $listing): bool
    {
        $phone = BangladeshPhoneService::normalize(optional($listing->user)->phone)
            ?: BangladeshPhoneService::normalize($listing->whatsapp_number);

        if (!$phone || !optional($listing->user)->whatsapp_opt_in) {
            return false;
        }

        if ($listing->created_at < $this->startAt()) {
            return false;
        }

        if ($listing->created_at > now()->subMinutes($this->minimumAgeMinutes())) {
            return false;
        }

        if ((int) $listing->status !== 0) {
            return false;
        }

        if ($listing->plan && $listing->plan->plan_type === 'free') {
            return false;
        }

        if ($listing->latestCompletedBkashPayment || $listing->latestTransaction) {
            return false;
        }

        return !BkashPayment::where('listing_id', $listing->id)->where('payment_status', 'completed')->exists()
            && !Transaction::where('ad_id', $listing->id)->where('status', 1)->exists();
    }

    public function startAt(): Carbon
    {
        return Carbon::parse(
            config('services.meta_whatsapp.abandoned_start_at', '2026-09-15 00:00:00'),
            config('app.timezone')
        );
    }

    public function minimumAgeMinutes(): int
    {
        return (int) config('services.meta_whatsapp.abandoned_min_age_minutes', 30);
    }

    private function recordDailyCron(array $stats, float $duration): void
    {
        $today = now()->toDateString();

        DB::transaction(function () use ($stats, $duration, $today) {
            $log = CronLog::where('job', self::JOB)
                ->where('run_date', $today)
                ->lockForUpdate()
                ->first();

            $status = $stats['success'] ? 'success' : 'failed';
            $message = "WhatsApp abandoned payment cron completed. Sent: {$stats['sent']}, Skipped: {$stats['skipped']}, Failed: {$stats['failed']}.";

            if (!$log) {
                CronLog::create([
                    'job' => self::JOB,
                    'run_date' => $today,
                    'status' => $status,
                    'message' => $message,
                    'processed' => $stats['processed'],
                    'duration' => round($duration, 2),
                    'stats' => $stats,
                    'first_run_at' => now(),
                    'last_run_at' => now(),
                    'run_count' => 1,
                    'total_processed' => $stats['processed'],
                    'total_sent' => $stats['sent'],
                    'total_skipped' => $stats['skipped'],
                    'total_failed' => $stats['failed'],
                    'last_status' => $status,
                ]);

                return;
            }

            $log->update([
                'status' => $status,
                'message' => $message,
                'processed' => ((int) $log->processed) + $stats['processed'],
                'duration' => round($duration, 2),
                'stats' => $stats,
                'first_run_at' => $log->first_run_at ?: now(),
                'last_run_at' => now(),
                'run_count' => ((int) $log->run_count) + 1,
                'total_processed' => ((int) $log->total_processed) + $stats['processed'],
                'total_sent' => ((int) $log->total_sent) + $stats['sent'],
                'total_skipped' => ((int) $log->total_skipped) + $stats['skipped'],
                'total_failed' => ((int) $log->total_failed) + $stats['failed'],
                'last_status' => $status,
            ]);
        });
    }

    private function automaticIdempotencyKey(int $listingId): string
    {
        return self::PURPOSE . ':automatic:' . $listingId;
    }
}
