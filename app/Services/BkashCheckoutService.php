<?php

namespace App\Services;

use App\Models\Plan;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BkashCheckoutService
{
    public function displayAmount(Plan $plan): float
    {
        if ((int) $plan->id === 7) {
            return 10.00;
        }

        return (float) $plan->price;
    }

    public function payableAmount(Plan $plan): float
    {
        if ($plan->plan_type === 'monthly') {
            return $this->displayAmount($plan) * 12;
        }

        return (float) $plan->price;
    }

    public function billingLabel(Plan $plan): string
    {
        if ($plan->plan_type === 'monthly') {
            return 'Billed yearly: ' . number_format($this->payableAmount($plan), 0) . ' BDT';
        }

        if ($plan->plan_type === 'lifetime') {
            return 'One-time payment';
        }

        return '';
    }

    public function planDurationDays(Plan $plan): ?int
    {
        if ($plan->plan_type === 'monthly') {
            return 365;
        }

        if ($plan->plan_type === 'lifetime' || $plan->plan_type === 'free') {
            return null;
        }

        return $plan->post_duration ? (int) $plan->post_duration : null;
    }

    public function createPayment(string $payerReference, string $invoiceNumber, float $amount): array
    {
        $response = $this->client()
            ->withHeaders([
                'Authorization' => $this->token(),
                'X-APP-Key' => config('services.bkash.app_key'),
            ])
            ->post($this->url('/tokenized/checkout/create'), [
                'mode' => config('services.bkash.mode', '0011'),
                'payerReference' => $payerReference,
                'callbackURL' => config('services.bkash.callback_url'),
                'amount' => $this->formatAmount($amount),
                'currency' => config('services.bkash.currency', 'BDT'),
                'intent' => config('services.bkash.intent', 'sale'),
                'merchantInvoiceNumber' => $invoiceNumber,
            ])
            ->throw();

        return $response->json() ?: [];
    }

    public function executePayment(string $paymentId): array
    {
        $response = $this->client()
            ->withHeaders([
                'Authorization' => $this->token(),
                'X-APP-Key' => config('services.bkash.app_key'),
            ])
            ->post($this->url('/tokenized/checkout/execute'), [
                'paymentID' => $paymentId,
            ])
            ->throw();

        return $response->json() ?: [];
    }

    public function queryPayment(string $paymentId): array
    {
        $response = $this->client()
            ->withHeaders([
                'Authorization' => $this->token(),
                'X-APP-Key' => config('services.bkash.app_key'),
            ])
            ->post($this->url('/tokenized/checkout/payment/status'), [
                'paymentID' => $paymentId,
            ])
            ->throw();

        return $response->json() ?: [];
    }

    public function completed(array $response): bool
    {
        return ($response['statusCode'] ?? null) === '0000'
            && ($response['transactionStatus'] ?? null) === 'Completed';
    }

    public function validCreateResponse(array $response): bool
    {
        return ($response['statusCode'] ?? null) === '0000'
            && !empty($response['paymentID'])
            && !empty($response['bkashURL']);
    }

    public function amountMatches(float $expected, mixed $actual): bool
    {
        return abs((float) $expected - (float) $actual) < 0.01;
    }

    public function currencyMatches(mixed $currency): bool
    {
        return strtoupper((string) $currency) === config('services.bkash.currency', 'BDT');
    }

    private function token(): string
    {
        return Cache::remember('bkash_checkout_id_token', now()->addMinutes(50), function () {
            $response = $this->client()
                ->withHeaders([
                    'username' => config('services.bkash.username'),
                    'password' => config('services.bkash.password'),
                ])
                ->post($this->url('/tokenized/checkout/token/grant'), [
                    'app_key' => config('services.bkash.app_key'),
                    'app_secret' => config('services.bkash.app_secret'),
                ])
                ->throw();

            $data = $response->json() ?: [];

            if (empty($data['id_token'])) {
                throw new RequestException($response);
            }

            return $data['id_token'];
        });
    }

    private function client()
    {
        return Http::timeout((int) config('services.bkash.timeout', 30))
            ->acceptJson()
            ->asJson();
    }

    private function url(string $path): string
    {
        return rtrim(config('services.bkash.base_url'), '/') . $path;
    }

    private function formatAmount(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }
}
