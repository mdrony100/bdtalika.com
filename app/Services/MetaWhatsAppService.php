<?php

namespace App\Services;

use App\Models\Listing;
use Illuminate\Support\Facades\Http;

class MetaWhatsAppService
{
    public function sendPaymentPendingReminder(Listing $listing, ?string $rawPhone = null): array
    {
        $phone = BangladeshPhoneService::normalize(
            $rawPhone ?: optional($listing->user)->phone ?: $listing->whatsapp_number
        );

        if (!$phone) {
            return $this->failed('invalid_phone', 'No valid Bangladesh WhatsApp number found.');
        }

        if (!config('services.meta_whatsapp.enabled')) {
            return $this->failed('disabled', 'Meta WhatsApp sending is disabled.');
        }

        $phoneNumberId = config('services.meta_whatsapp.phone_number_id');
        $accessToken = config('services.meta_whatsapp.access_token');

        if (!$phoneNumberId || !$accessToken) {
            return $this->failed('missing_config', 'Meta WhatsApp credentials are not configured.');
        }

        $payload = $this->paymentPendingPayload($listing, $phone);
        $version = config('services.meta_whatsapp.graph_version', 'v20.0');
        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        try {
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->asJson()
                ->timeout((int) config('services.meta_whatsapp.timeout', 20))
                ->post($url, $payload);
        } catch (\Throwable $e) {
            return $this->failed('request_failed', $this->sanitizeError($e->getMessage()));
        }

        $json = $response->json() ?: [];

        if ($response->successful()) {
            return [
                'success' => true,
                'status' => 'sent',
                'recipient_phone' => $phone,
                'meta_message_id' => $json['messages'][0]['id'] ?? null,
                'payload' => $payload,
                'response_status' => $response->status(),
            ];
        }

        return $this->failed(
            'failed',
            $this->sanitizeError($json['error']['message'] ?? $response->body()),
            $phone,
            $response->status()
        );
    }

    public function paymentPendingPayload(Listing $listing, string $phone): array
    {
        return [
            'messaging_product' => 'whatsapp',
            'to' => $phone,
            'type' => 'template',
            'template' => [
                'name' => config('services.meta_whatsapp.template_name', 'payment_pending_reminder'),
                'language' => [
                    'code' => config('services.meta_whatsapp.template_language', 'en'),
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => optional($listing->user)->name ?: 'Customer',
                            ],
                            [
                                'type' => 'text',
                                'text' => $listing->name ?: 'your business listing',
                            ],
                        ],
                    ],
                    [
                        'type' => 'button',
                        'sub_type' => 'url',
                        'index' => '0',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => (string) $listing->id,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function failed(string $status, string $message, ?string $phone = null, ?int $responseStatus = null): array
    {
        return [
            'success' => false,
            'status' => $status,
            'recipient_phone' => $phone,
            'error_message' => $this->sanitizeError($message),
            'response_status' => $responseStatus,
        ];
    }

    private function sanitizeError(string $message): string
    {
        return mb_substr(trim(preg_replace('/\s+/', ' ', $message)), 0, 500);
    }
}
