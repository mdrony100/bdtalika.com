<?php

namespace Tests\Unit;

use App\Services\BangladeshPhoneService;
use PHPUnit\Framework\TestCase;

class BangladeshPhoneServiceTest extends TestCase
{
    /**
     * @dataProvider validBangladeshNumbers
     */
    public function test_it_normalizes_bangladesh_mobile_numbers(string $input): void
    {
        $this->assertSame('8801712345678', BangladeshPhoneService::normalize($input));
    }

    public function test_it_rejects_invalid_bangladesh_mobile_numbers(): void
    {
        $this->assertNull(BangladeshPhoneService::normalize('01212345678'));
        $this->assertNull(BangladeshPhoneService::normalize('8801212345678'));
        $this->assertFalse(BangladeshPhoneService::isValid('01712345678'));
    }

    public static function validBangladeshNumbers(): array
    {
        return [
            ['01712345678'],
            ['+8801712345678'],
            ['8801712345678'],
            ['017 1234-5678'],
        ];
    }
}
