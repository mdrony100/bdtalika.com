<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BkashPayment;
use App\Models\Listing;
use App\Models\Plan;
use App\Models\Transaction;
use App\Services\AdminNotificationService;
use App\Services\BkashCheckoutService;
use App\Services\TransactionInvoiceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Throwable;

class BkashListingPaymentController extends Controller
{
    public function __construct(
        private BkashCheckoutService $bkash,
        private TransactionInvoiceService $invoiceService
    )
    {
    }

    public function initiate(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        $adId = session()->get('ad_id');
        $listing = Listing::where('id', $adId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$listing) {
            Session::flash('error', 'Invalid listing selected for payment.');
            return redirect()->route('user.listing.index');
        }

        $plan = Plan::where('id', $id)->where('status', 1)->first();

        if (!$plan) {
            Session::flash('error', 'Invalid plan selected.');
            return redirect()->route('user.listing.plan');
        }

        if ((int) $listing->plan_id === (int) $plan->id) {
            Session::flash('error', 'The plan is already active for this listing.');
            session()->forget('ad_id');
            return redirect()->route('user.listing.index');
        }

        if ($plan->plan_type === 'free') {
            Session::flash('error', 'Sorry !! Currently we are not accepting free listing. You have to take paid plan');
            return redirect()->route('user.listing.plan');
        }

        $pending = BkashPayment::where('user_id', Auth::id())
            ->where('listing_id', $listing->id)
            ->where('plan_id', $plan->id)
            ->where('payment_status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(10))
            ->latest()
            ->first();

        if ($pending && !empty($pending->raw_create_response['bkashURL'])) {
            return redirect()->away($pending->raw_create_response['bkashURL']);
        }

        $amount = $this->bkash->payableAmount($plan);
        $invoice = $this->invoiceNumber();
        $payerReference = 'listing-' . $listing->id . '-user-' . Auth::id();

        try {
            $payment = BkashPayment::create([
                'user_id' => Auth::id(),
                'listing_id' => $listing->id,
                'plan_id' => $plan->id,
                'merchant_invoice_number' => $invoice,
                'payer_reference' => $payerReference,
                'amount' => $amount,
                'currency' => config('services.bkash.currency', 'BDT'),
                'payment_status' => 'pending',
            ]);

            $response = $this->bkash->createPayment($payerReference, $invoice, $amount);

            $payment->update([
                'payment_id' => $response['paymentID'] ?? null,
                'transaction_status' => $response['transactionStatus'] ?? null,
                'status_code' => $response['statusCode'] ?? null,
                'status_message' => $response['statusMessage'] ?? null,
                'raw_create_response' => $response,
            ]);

            if (!$this->bkash->validCreateResponse($response)) {
                $payment->update(['payment_status' => 'failed']);
                Session::flash('error', 'Unable to start bKash payment. Please try again.');
                return redirect()->route('user.listing.plan');
            }

            return redirect()->away($response['bkashURL']);
        } catch (Throwable $e) {
            Log::error('bKash create payment failed', [
                'user_id' => Auth::id(),
                'listing_id' => $listing->id,
                'plan_id' => $plan->id,
                'message' => $e->getMessage(),
            ]);

            Session::flash('error', 'Unable to start bKash payment. Please try again.');
            return redirect()->route('user.listing.plan');
        }
    }

    public function callback(Request $request)
    {
        $paymentId = $request->query('paymentID');
        $callbackStatus = strtolower((string) $request->query('status'));

        if (!$paymentId || !$callbackStatus) {
            return $this->callbackRedirect('error', 'Invalid bKash callback received.');
        }

        $payment = BkashPayment::where('payment_id', $paymentId)->first();

        if (!$payment) {
            return $this->callbackRedirect('error', 'Payment record was not found.');
        }

        if ($payment->payment_status === 'completed') {
            return $this->callbackRedirect('message', 'Payment already completed. Your listing is waiting for review.');
        }

        if (in_array($callbackStatus, ['cancel', 'cancelled', 'canceled'], true)) {
            $payment->update([
                'callback_status' => $callbackStatus,
                'payment_status' => 'cancelled',
            ]);

            return $this->callbackRedirect('error', 'bKash payment was cancelled.');
        }

        if (!in_array($callbackStatus, ['success'], true)) {
            $payment->update([
                'callback_status' => $callbackStatus,
                'payment_status' => 'failed',
            ]);

            return $this->callbackRedirect('error', 'bKash payment was not completed.');
        }

        try {
            $executeResponse = $this->bkash->executePayment($paymentId);
        } catch (Throwable $e) {
            Log::warning('bKash execute payment failed, trying query payment', [
                'bkash_payment_id' => $payment->id,
                'payment_id' => $paymentId,
                'message' => $e->getMessage(),
            ]);

            $executeResponse = [];
        }

        $verifiedResponse = $executeResponse;

        if (!$this->bkash->completed($verifiedResponse)) {
            try {
                $verifiedResponse = $this->bkash->queryPayment($paymentId);
            } catch (Throwable $e) {
                $payment->update([
                    'callback_status' => $callbackStatus,
                    'raw_execute_response' => $executeResponse ?: null,
                    'payment_status' => 'pending',
                ]);

                Log::error('bKash query payment failed', [
                    'bkash_payment_id' => $payment->id,
                    'payment_id' => $paymentId,
                    'message' => $e->getMessage(),
                ]);

                return $this->callbackRedirect('error', 'Unable to verify bKash payment. Please contact support.');
            }
        }

        return DB::transaction(function () use ($payment, $callbackStatus, $executeResponse, $verifiedResponse) {
            $lockedPayment = BkashPayment::where('id', $payment->id)->lockForUpdate()->firstOrFail();

            if ($lockedPayment->payment_status === 'completed') {
                return $this->callbackRedirect('message', 'Payment already completed. Your listing is waiting for review.');
            }

            $lockedPayment->update([
                'callback_status' => $callbackStatus,
                'customer_msisdn' => $verifiedResponse['customerMsisdn'] ?? $verifiedResponse['payerAccount'] ?? null,
                'trx_id' => $verifiedResponse['trxID'] ?? null,
                'transaction_status' => $verifiedResponse['transactionStatus'] ?? null,
                'status_code' => $verifiedResponse['statusCode'] ?? null,
                'status_message' => $verifiedResponse['statusMessage'] ?? null,
                'raw_execute_response' => $executeResponse ?: null,
                'raw_query_response' => $executeResponse === $verifiedResponse ? null : $verifiedResponse,
            ]);

            if (!$this->validCompletedPayment($lockedPayment, $verifiedResponse)) {
                $lockedPayment->update(['payment_status' => 'failed']);

                return $this->callbackRedirect('error', 'bKash payment verification failed. Your listing was not activated.');
            }

            $listing = Listing::where('id', $lockedPayment->listing_id)
                ->where('user_id', $lockedPayment->user_id)
                ->lockForUpdate()
                ->first();

            $plan = Plan::where('id', $lockedPayment->plan_id)
                ->where('status', 1)
                ->first();

            if (!$listing || !$plan) {
                $lockedPayment->update(['payment_status' => 'failed']);

                return $this->callbackRedirect('error', 'Unable to activate listing package. Please contact support.');
            }

            $startDate = Carbon::now();
            $durationDays = $this->bkash->planDurationDays($plan);

            $listing->update([
                'plan_id' => $plan->id,
                'plan_start_date' => $startDate,
                'plan_end_date' => $durationDays ? $startDate->copy()->addDays($durationDays - 1) : null,
                'status' => 3,
            ]);

            $transaction = new Transaction();
            $transaction->user_id = $lockedPayment->user_id;
            $transaction->plan_id = $plan->id;
            $transaction->ad_id = $listing->id;
            $transaction->email = optional($lockedPayment->user)->email ?? '';
            $transaction->phone = $lockedPayment->customer_msisdn;
            $transaction->amount = $lockedPayment->amount;
            $transaction->type = $plan->plan_type;
            $transaction->plan_duration = $durationDays ? $durationDays . ' days' : 'lifetime';
            $transaction->plan_name = $plan->title;
            $transaction->txnid = $lockedPayment->trx_id;
            $transaction->status = 1;
            $transaction->save();

            $lockedPayment->update([
                'transaction_id' => $transaction->id,
                'payment_status' => 'completed',
                'paid_at' => now(),
            ]);

            DB::afterCommit(function () use ($transaction) {
                $this->invoiceService->sendPaymentSuccess($transaction);
                app(AdminNotificationService::class)->bkashListingPaymentCompleted($transaction);
            });

            session()->forget('ad_id');

            return $this->callbackRedirect('message', 'Payment completed successfully. Review is required. We will notify you soon.');
        });
    }

    private function validCompletedPayment(BkashPayment $payment, array $response): bool
    {
        return $this->bkash->completed($response)
            && $this->bkash->amountMatches((float) $payment->amount, $response['amount'] ?? null)
            && $this->bkash->currencyMatches($response['currency'] ?? null)
            && ($response['merchantInvoiceNumber'] ?? null) === $payment->merchant_invoice_number
            && ($response['paymentID'] ?? null) === $payment->payment_id;
    }

    private function callbackRedirect(string $type, string $message)
    {
        Session::flash($type, $message);
        return redirect()->route('user.listing.index');
    }

    private function invoiceNumber(): string
    {
        do {
            $invoice = 'BDT-' . now()->format('ymdHis') . '-' . Str::upper(Str::random(8));
        } while (BkashPayment::where('merchant_invoice_number', $invoice)->exists());

        return $invoice;
    }
}
