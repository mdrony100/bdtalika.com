<?php

namespace Tests\Feature;

use App\Models\BkashPayment;
use App\Models\CronLog;
use App\Models\Listing;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WhatsAppMessage;
use App\Services\MetaWhatsAppService;
use App\Services\WhatsAppAbandonedPaymentReminderService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class WhatsAppAbandonedPaymentReminderTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-09-15 12:00:00');

        config([
            'services.meta_whatsapp.enabled' => true,
            'services.meta_whatsapp.access_token' => 'test-token',
            'services.meta_whatsapp.phone_number_id' => '123456789',
            'services.meta_whatsapp.graph_version' => 'v20.0',
            'services.meta_whatsapp.template_name' => 'payment_pending_reminder',
            'services.meta_whatsapp.template_language' => 'en',
            'services.meta_whatsapp.cron_token' => 'secret',
            'services.meta_whatsapp.abandoned_start_at' => '2026-09-15 00:00:00',
            'services.meta_whatsapp.abandoned_min_age_minutes' => 30,
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_meta_template_payload_contains_body_and_dynamic_url_parameters(): void
    {
        $listing = $this->listing('2026-09-15 11:00:00');
        $payload = app(MetaWhatsAppService::class)->paymentPendingPayload($listing, '8801712345678');

        $this->assertSame('payment_pending_reminder', $payload['template']['name']);
        $this->assertSame($listing->user->name, $payload['template']['components'][0]['parameters'][0]['text']);
        $this->assertSame($listing->name, $payload['template']['components'][0]['parameters'][1]['text']);
        $this->assertSame((string) $listing->id, $payload['template']['components'][1]['parameters'][0]['text']);
    }

    public function test_cutoff_age_unpaid_paid_free_consent_and_already_sent_rules(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'messages' => [
                    ['id' => 'wamid.test'],
                ],
            ], 200),
        ]);

        $eligible = $this->listing('2026-09-15 11:00:00');
        $this->listing('2026-09-14 23:59:59');
        $this->listing('2026-09-15 11:45:00');
        $this->paidListing('2026-09-15 11:00:00');
        $this->freeListing('2026-09-15 11:00:00');
        $this->listing('2026-09-15 11:00:00', ['whatsapp_opt_in' => false]);
        $alreadySent = $this->listing('2026-09-15 11:00:00');
        WhatsAppMessage::create([
            'user_id' => $alreadySent->user_id,
            'listing_id' => $alreadySent->id,
            'recipient_phone' => '8801712345678',
            'template_name' => 'payment_pending_reminder',
            'purpose' => WhatsAppAbandonedPaymentReminderService::PURPOSE,
            'source' => WhatsAppAbandonedPaymentReminderService::SOURCE,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $result = app(WhatsAppAbandonedPaymentReminderService::class)->run();

        $this->assertSame(1, $result['processed']);
        $this->assertSame(1, $result['sent']);
        $this->assertDatabaseHas('whatsapp_messages', [
            'listing_id' => $eligible->id,
            'status' => 'sent',
            'meta_message_id' => 'wamid.test',
        ]);

        Http::assertSentCount(1);
    }

    public function test_api_failure_logs_failed_message_and_allows_retry(): void
    {
        $listing = $this->listing('2026-09-15 11:00:00');

        Http::fake([
            'graph.facebook.com/*' => Http::sequence()
                ->push([
                    'error' => ['message' => 'Temporary Meta error'],
                ], 500)
                ->push([
                    'messages' => [
                        ['id' => 'wamid.retry'],
                    ],
                ], 200),
        ]);

        $first = app(WhatsAppAbandonedPaymentReminderService::class)->run();

        $this->assertSame(1, $first['failed']);
        $this->assertDatabaseHas('whatsapp_messages', [
            'listing_id' => $listing->id,
            'status' => 'failed',
            'error_message' => 'Temporary Meta error',
        ]);

        $service = app(WhatsAppAbandonedPaymentReminderService::class);
        $this->assertTrue($service->eligibleQuery()->where('id', $listing->id)->exists());

        $this->assertSame('sent', $service->processListing($listing->fresh(['user', 'plan'])));
        $this->assertSame(1, WhatsAppMessage::where('listing_id', $listing->id)->count());
        $this->assertDatabaseHas('whatsapp_messages', [
            'listing_id' => $listing->id,
            'status' => 'sent',
            'meta_message_id' => 'wamid.retry',
        ]);
    }

    public function test_repeated_cron_does_not_duplicate_send_and_keeps_one_daily_history_row(): void
    {
        $this->listing('2026-09-15 11:00:00');

        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'messages' => [
                    ['id' => 'wamid.once'],
                ],
            ], 200),
        ]);

        app(WhatsAppAbandonedPaymentReminderService::class)->run();
        app(WhatsAppAbandonedPaymentReminderService::class)->run();

        $this->assertSame(1, WhatsAppMessage::where('status', 'sent')->count());
        $this->assertSame(1, CronLog::where('job', WhatsAppAbandonedPaymentReminderService::JOB)->whereDate('run_date', '2026-09-15')->count());

        $log = CronLog::where('job', WhatsAppAbandonedPaymentReminderService::JOB)->whereDate('run_date', '2026-09-15')->first();
        $this->assertSame(2, (int) $log->run_count);
        $this->assertSame(1, (int) $log->total_sent);

        Http::assertSentCount(1);
    }

    public function test_manual_success_satisfies_automatic_abandoned_reminder(): void
    {
        $listing = $this->listing('2026-09-15 11:00:00');

        WhatsAppMessage::create([
            'user_id' => $listing->user_id,
            'admin_id' => 1,
            'listing_id' => $listing->id,
            'idempotency_key' => 'manual-test-' . $listing->id,
            'recipient_phone' => '8801712345678',
            'template_name' => 'payment_pending_reminder',
            'purpose' => WhatsAppAbandonedPaymentReminderService::PURPOSE,
            'source' => 'manual',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'messages' => [
                    ['id' => 'wamid.should-not-send'],
                ],
            ], 200),
        ]);

        $result = app(WhatsAppAbandonedPaymentReminderService::class)->run();

        $this->assertSame(0, $result['processed']);
        $this->assertSame(1, WhatsAppMessage::where('listing_id', $listing->id)->count());
        Http::assertSentCount(0);
    }

    public function test_cron_endpoint_requires_token(): void
    {
        $this->get(route('cron.whatsapp.abandoned.payment'))->assertForbidden();
    }

    private function listing(string $createdAt, array $userOverrides = [], array $listingOverrides = []): Listing
    {
        $user = User::create(array_merge([
            'name' => 'Reminder User',
            'email' => 'reminder-' . Str::uuid() . '@example.test',
            'password' => bcrypt('password'),
            'status' => 1,
            'email_verified' => 'Yes',
            'phone' => '8801712345678',
            'whatsapp_opt_in' => true,
            'whatsapp_opt_in_at' => now(),
        ], $userOverrides));

        $listing = Listing::create(array_merge([
            'user_id' => $user->id,
            'name' => 'Reminder Listing ' . Str::random(6),
            'slug' => 'reminder-listing-' . Str::random(10),
            'status' => 0,
        ], $listingOverrides));

        $listing->forceFill([
            'created_at' => Carbon::parse($createdAt),
            'updated_at' => Carbon::parse($createdAt),
        ])->save();

        return $listing->fresh(['user', 'plan']);
    }

    private function paidListing(string $createdAt): Listing
    {
        $plan = $this->plan('monthly');
        $listing = $this->listing($createdAt, [], [
            'plan_id' => $plan->id,
            'status' => 3,
        ]);

        BkashPayment::create([
            'user_id' => $listing->user_id,
            'listing_id' => $listing->id,
            'plan_id' => $plan->id,
            'merchant_invoice_number' => 'BDT-' . Str::upper(Str::random(10)),
            'payment_id' => 'PAY' . Str::upper(Str::random(10)),
            'payer_reference' => 'listing-' . $listing->id . '-user-' . $listing->user_id,
            'amount' => 120,
            'currency' => 'BDT',
            'payment_status' => 'completed',
            'paid_at' => now(),
        ]);

        $transaction = new Transaction();
        $transaction->user_id = $listing->user_id;
        $transaction->plan_id = $plan->id;
        $transaction->ad_id = $listing->id;
        $transaction->email = $listing->user->email;
        $transaction->amount = 120;
        $transaction->type = 'monthly';
        $transaction->txnid = 'TRX' . Str::upper(Str::random(8));
        $transaction->status = 1;
        $transaction->save();

        return $listing;
    }

    private function freeListing(string $createdAt): Listing
    {
        return $this->listing($createdAt, [], [
            'plan_id' => $this->plan('free')->id,
            'status' => 0,
        ]);
    }

    private function plan(string $type): Plan
    {
        return Plan::create([
            'title' => ucfirst($type) . ' Plan ' . Str::random(6),
            'plan_type' => $type,
            'price' => $type === 'free' ? 0 : 10,
            'post_duration' => $type === 'free' ? null : 30,
            'status' => 1,
            'is_private' => 0,
        ]);
    }
}
