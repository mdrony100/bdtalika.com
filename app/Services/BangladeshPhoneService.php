<?php

namespace App\Services;

class BangladeshPhoneService
{
    public static function normalize(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '880')) {
            $normalized = $digits;
        } elseif (str_starts_with($digits, '88')) {
            $normalized = '880' . substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $normalized = '88' . $digits;
        } else {
            $normalized = '880' . $digits;
        }

        return self::isValid($normalized) ? $normalized : null;
    }

    public static function isValid(?string $phone): bool
    {
        return (bool) preg_match('/^8801[3-9]\d{8}$/', (string) $phone);
    }
}
