<?php

namespace Tests\Feature;

use App\Http\Middleware\BanUser;
use App\Http\Middleware\Otp;
use App\Models\BkashPayment;
use App\Models\Listing;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\BkashCheckoutService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class BkashListingPaymentTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            Otp::class,
            BanUser::class,
            ThrottleRequests::class,
        ]);

        config([
            'services.bkash.currency' => 'BDT',
            'services.bkash.mode' => '0011',
            'services.bkash.intent' => 'sale',
            'services.bkash.callback_url' => 'https://bdtalika.com/user/bkash/callback',
        ]);
    }

    public function test_basic_yearly_amount_is_calculated_from_monthly_display_price(): void
    {
        $plan = $this->plan(7, 'Basic Monthly', 'monthly', 200, 30);
        $service = app(BkashCheckoutService::class);

        $this->assertSame(10.0, $service->displayAmount($plan));
        $this->assertSame(120.0, $service->payableAmount($plan));
        $this->assertSame(365, $service->planDurationDays($plan));
    }

    public function test_premium_yearly_amount_is_calculated_from_monthly_display_price(): void
    {
        $plan = $this->plan(2, 'Premium Monthly', 'monthly', 20, 30);
        $service = app(BkashCheckoutService::class);

        $this->assertSame(20.0, $service->displayAmount($plan));
        $this->assertSame(240.0, $service->payableAmount($plan));
        $this->assertSame(365, $service->planDurationDays($plan));
    }

    public function test_lifetime_amount_is_not_yearly_billed(): void
    {
        $plan = $this->plan(3, 'Lifetime', 'lifetime', 999, null);
        $service = app(BkashCheckoutService::class);

        $this->assertSame(999.0, $service->displayAmount($plan));
        $this->assertSame(999.0, $service->payableAmount($plan));
        $this->assertNull($service->planDurationDays($plan));
    }

    public function test_payment_amount_cannot_be_changed_from_browser(): void
    {
        $user = $this->user();
        $listing = $this->listing($user);
        $this->plan(2, 'Premium Monthly', 'monthly', 20, 30);

        $this->mockSuccessfulCreatePayment(240.0);

        $this->actingAs($user)
            ->withSession(['ad_id' => $listing->id])
            ->get(route('user.listing.plan.checkout', ['id' => 2, 'amount' => 1]))
            ->assertRedirect('https://sandbox.bkash.test/checkout');

        $this->assertDatabaseHas('bkash_payments', [
            'listing_id' => $listing->id,
            'plan_id' => 2,
            'amount' => '240.00',
            'payment_status' => 'pending',
        ]);
    }

    public function test_user_cannot_start_payment_for_another_users_listing(): void
    {
        $payer = $this->user('payer');
        $owner = $this->user('owner');
        $listing = $this->listing($owner);
        $this->plan(2, 'Premium Monthly', 'monthly', 20, 30);

        $this->partialMock(BkashCheckoutService::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('createPayment');
        });

        $this->actingAs($payer)
            ->withSession(['ad_id' => $listing->id])
            ->get(route('user.listing.plan.checkout', 2))
            ->assertRedirect(route('user.listing.index'));

        $this->assertDatabaseMissing('bkash_payments', [
            'listing_id' => $listing->id,
            'plan_id' => 2,
        ]);
    }

    public function test_create_payment_stores_pending_record_and_redirects_to_bkash_url(): void
    {
        $user = $this->user();
        $listing = $this->listing($user);
        $this->plan(7, 'Basic Monthly', 'monthly', 200, 30);

        $this->mockSuccessfulCreatePayment(120.0);

        $this->actingAs($user)
            ->withSession(['ad_id' => $listing->id])
            ->get(route('user.listing.plan.checkout', 7))
            ->assertRedirect('https://sandbox.bkash.test/checkout');

        $this->assertDatabaseHas('bkash_payments', [
            'user_id' => $user->id,
            'listing_id' => $listing->id,
            'plan_id' => 7,
            'payment_id' => 'PAY123',
            'amount' => '120.00',
            'payment_status' => 'pending',
        ]);
    }

    public function test_success_callback_executes_payment_and_activates_yearly_package(): void
    {
        Carbon::setTestNow('2026-08-04 12:00:00');

        $user = $this->user();
        $listing = $this->listing($user);
        $plan = $this->plan(2, 'Premium Monthly', 'monthly', 20, 30);
        $payment = $this->pendingPayment($user, $listing, $plan, 240.0);

        $this->mockExecutePayment($payment, 240.0);

        $this->get(route('user.bkash.callback', [
            'paymentID' => $payment->payment_id,
            'status' => 'success',
        ]))->assertRedirect(route('user.listing.index'));

        $this->assertDatabaseHas('bkash_payments', [
            'id' => $payment->id,
            'payment_status' => 'completed',
            'trx_id' => 'TRX123',
        ]);
        $this->assertDatabaseHas('listings', [
            'id' => $listing->id,
            'plan_id' => 2,
            'status' => 3,
            'plan_start_date' => '2026-08-04',
            'plan_end_date' => '2027-08-03',
        ]);
        $this->assertDatabaseHas('transactions', [
            'ad_id' => $listing->id,
            'plan_id' => 2,
            'amount' => 240,
            'txnid' => 'TRX123',
            'plan_duration' => '365 days',
        ]);

        Carbon::setTestNow();
    }

    public function test_cancelled_payment_does_not_activate_package(): void
    {
        $user = $this->user();
        $listing = $this->listing($user);
        $plan = $this->plan(2, 'Premium Monthly', 'monthly', 20, 30);
        $payment = $this->pendingPayment($user, $listing, $plan, 240.0);

        $this->get(route('user.bkash.callback', [
            'paymentID' => $payment->payment_id,
            'status' => 'cancel',
        ]))->assertRedirect(route('user.listing.index'));

        $this->assertDatabaseHas('bkash_payments', [
            'id' => $payment->id,
            'payment_status' => 'cancelled',
        ]);
        $this->assertDatabaseHas('listings', [
            'id' => $listing->id,
            'plan_id' => null,
        ]);
    }

    public function test_amount_mismatch_does_not_activate_package(): void
    {
        $user = $this->user();
        $listing = $this->listing($user);
        $plan = $this->plan(2, 'Premium Monthly', 'monthly', 20, 30);
        $payment = $this->pendingPayment($user, $listing, $plan, 240.0);

        $this->mockExecutePayment($payment, 1.0);

        $this->get(route('user.bkash.callback', [
            'paymentID' => $payment->payment_id,
            'status' => 'success',
        ]))->assertRedirect(route('user.listing.index'));

        $this->assertDatabaseHas('bkash_payments', [
            'id' => $payment->id,
            'payment_status' => 'failed',
        ]);
        $this->assertDatabaseHas('listings', [
            'id' => $listing->id,
            'plan_id' => null,
        ]);
        $this->assertSame(0, Transaction::where('ad_id', $listing->id)->count());
    }

    public function test_duplicate_callback_does_not_activate_twice(): void
    {
        $user = $this->user();
        $listing = $this->listing($user);
        $plan = $this->plan(3, 'Lifetime', 'lifetime', 999, null);
        $payment = $this->pendingPayment($user, $listing, $plan, 999.0);

        $this->mockExecutePayment($payment, 999.0);

        $callback = [
            'paymentID' => $payment->payment_id,
            'status' => 'success',
        ];

        $this->get(route('user.bkash.callback', $callback))
            ->assertRedirect(route('user.listing.index'));
        $this->get(route('user.bkash.callback', $callback))
            ->assertRedirect(route('user.listing.index'));

        $this->assertSame(1, Transaction::where('ad_id', $listing->id)->where('txnid', 'TRX123')->count());
        $this->assertDatabaseHas('listings', [
            'id' => $listing->id,
            'plan_id' => 3,
            'plan_end_date' => null,
        ]);
    }

    private function mockSuccessfulCreatePayment(float $expectedAmount): void
    {
        $this->partialMock(BkashCheckoutService::class, function (MockInterface $mock) use ($expectedAmount) {
            $mock->shouldReceive('createPayment')
                ->once()
                ->withArgs(function ($payerReference, $invoiceNumber, $amount) use ($expectedAmount) {
                    return str_starts_with($payerReference, 'listing-')
                        && str_starts_with($invoiceNumber, 'BDT-')
                        && abs((float) $amount - $expectedAmount) < 0.01;
                })
                ->andReturn([
                    'statusCode' => '0000',
                    'paymentID' => 'PAY123',
                    'bkashURL' => 'https://sandbox.bkash.test/checkout',
                    'transactionStatus' => 'Initiated',
                    'merchantInvoiceNumber' => 'ignored-by-controller',
                ]);
        });
    }

    private function mockExecutePayment(BkashPayment $payment, float $actualAmount): void
    {
        $this->partialMock(BkashCheckoutService::class, function (MockInterface $mock) use ($payment, $actualAmount) {
            $mock->shouldReceive('executePayment')
                ->once()
                ->with($payment->payment_id)
                ->andReturn([
                    'statusCode' => '0000',
                    'statusMessage' => 'Successful',
                    'paymentID' => $payment->payment_id,
                    'trxID' => 'TRX123',
                    'transactionStatus' => 'Completed',
                    'amount' => number_format($actualAmount, 2, '.', ''),
                    'currency' => 'BDT',
                    'merchantInvoiceNumber' => $payment->merchant_invoice_number,
                    'customerMsisdn' => '01700000000',
                ]);

            $mock->shouldNotReceive('queryPayment');
        });
    }

    private function user(string $label = 'user'): User
    {
        return User::create([
            'name' => ucfirst($label),
            'email' => $label . '-' . Str::uuid() . '@example.test',
            'password' => bcrypt('password'),
            'status' => 1,
            'email_verified' => 'Yes',
        ]);
    }

    private function listing(User $user): Listing
    {
        return Listing::create([
            'user_id' => $user->id,
            'name' => 'Test Listing ' . Str::random(6),
            'slug' => 'test-listing-' . Str::random(10),
            'status' => 0,
        ]);
    }

    private function plan(int $id, string $title, string $type, float $price, ?int $duration): Plan
    {
        return Plan::updateOrCreate(
            ['id' => $id],
            [
                'title' => $title,
                'plan_type' => $type,
                'price' => $price,
                'post_duration' => $duration,
                'status' => 1,
            ]
        );
    }

    private function pendingPayment(User $user, Listing $listing, Plan $plan, float $amount): BkashPayment
    {
        return BkashPayment::create([
            'user_id' => $user->id,
            'listing_id' => $listing->id,
            'plan_id' => $plan->id,
            'merchant_invoice_number' => 'BDT-' . Str::upper(Str::random(10)),
            'payment_id' => 'PAY' . Str::upper(Str::random(10)),
            'payer_reference' => 'listing-' . $listing->id . '-user-' . $user->id,
            'amount' => $amount,
            'currency' => 'BDT',
            'payment_status' => 'pending',
        ]);
    }
}
